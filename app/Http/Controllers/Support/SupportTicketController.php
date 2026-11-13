<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\SupportCategory;
use App\Models\Support\SupportType;
use App\Models\Support\SupportPriorite;
use App\Models\Support\SupportStatut;
use App\Models\Support\SupportTicket;
use App\Models\Support\SupportTicketHistorique;
use App\Services\Support\SupportReferenceService;
use App\Services\Support\SupportPriseEnChargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportTicketController extends Controller
{
    public function dashboard()
    {
        abort_unless(auth()->user()->can('support.dashboard.view'), 403);

        $stats = [
            'total' => SupportTicket::count(),
            'nouveaux' => SupportTicket::whereHas(
                'statut',
                fn ($q) => $q->where('code', 'NOUVEAU')
            )->count(),
            'en_cours' => SupportTicket::whereHas(
                'statut',
                fn ($q) => $q->where('code', 'EN_COURS')
            )->count(),
            'en_attente' => SupportTicket::whereHas(
                'statut',
                fn ($q) => $q->where('code', 'EN_ATTENTE')
            )->count(),
            'resolus' => SupportTicket::whereHas(
                'statut',
                fn ($q) => $q->where('code', 'RESOLU')
            )->count(),
        ];

        $ticketsRecents = SupportTicket::with([
            'categorie',
            'priorite',
            'statut',
            'demandeur',
            'assigne',
        ])->recents()->limit(10)->get();

        return view('support.dashboard', compact(
            'stats',
            'ticketsRecents'
        ));
    }

    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('support.access'), 403);

        $user = auth()->user();

        $query = SupportTicket::with([
            'categorie',
            'type',
            'priorite',
            'statut',
            'demandeur',
            'assigne',
        ])->recents();

        if (!$user->can('support.tickets.view-all')) {
            if ($user->can('support.tickets.view-assigned')) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_demandeur_id', $user->id)
                      ->orWhere('user_assigne_id', $user->id);
                });
            } else {
                $query->where('user_demandeur_id', $user->id);
            }
        }

        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        if ($request->filled('statut')) {
            $query->where('support_statut_id', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('support_priorite_id', $request->priorite);
        }

        $tickets = $query->paginate(15)->withQueryString();

        $statuts = SupportStatut::actifs()->ordonnes()->get();
        $priorites = SupportPriorite::actives()->ordonnees()->get();

        return view('support.tickets.index', compact(
            'tickets',
            'statuts',
            'priorites'
        ));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('support.tickets.create'), 403);

        $categories = SupportCategory::query()
            ->where('actif', true)
            ->orderBy('libelle')
            ->get();

        $types = SupportType::query()
            ->where('actif', true)
            ->orderBy('libelle')
            ->get();

        $priorites = SupportPriorite::actives()->ordonnees()->get();

        return view('support.tickets.create', compact(
            'categories',
            'types',
            'priorites'
        ));
    }

    public function store(
        Request $request,
        SupportReferenceService $referenceService
    ) {
        abort_unless(auth()->user()->can('support.tickets.create'), 403);

        $validated = $request->validate([
            'objet' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'min:10'],
            'support_category_id' => ['required', 'exists:support_categories,id'],
            'support_type_id' => ['required', 'exists:support_types,id'],
            'support_priorite_id' => ['required', 'exists:support_priorites,id'],
        ]);

        $typeValide = SupportType::query()
            ->whereKey($validated['support_type_id'])
            ->where('support_category_id', $validated['support_category_id'])
            ->exists();

        if (!$typeValide) {
            throw ValidationException::withMessages([
                'support_type_id' => 'Le type sélectionné ne correspond pas à la catégorie.',
            ]);
        }

        $statut = SupportStatut::where('code', 'NOUVEAU')
            ->where('actif', true)
            ->firstOrFail();

        $ticket = DB::transaction(function () use (
            $validated,
            $referenceService,
            $statut
        ) {
            $ticket = SupportTicket::create([
                ...$validated,
                'reference' => $referenceService->generer(),
                'support_statut_id' => $statut->id,
                'user_demandeur_id' => auth()->id(),
                'date_soumission' => now(),
            ]);

            SupportTicketHistorique::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'CREATION',
                'champ' => null,
                'ancienne_valeur' => null,
                'nouvelle_valeur' => $ticket->reference,
                'description' => 'Création du ticket ' . $ticket->reference,
            ]);

            return $ticket;
        });

        return redirect()
            ->route('support.tickets.show', $ticket)
            ->with('success', 'Votre ticket a été enregistré avec succès.');
    }

    public function show(SupportTicket $ticket)
    {
        $user = auth()->user();

        $autorise = $user->can('support.tickets.view-all')
            || (
                $user->can('support.tickets.view-assigned')
                && (
                    (int) $ticket->user_assigne_id === (int) $user->id
                    || (int) $ticket->user_demandeur_id === (int) $user->id
                )
            )
            || (
                $user->can('support.tickets.view-own')
                && (int) $ticket->user_demandeur_id === (int) $user->id
            );

        abort_unless($autorise, 403);

        $ticket->load([
            'categorie',
            'type',
            'priorite',
            'statut',
            'demandeur',
            'assigne',
            'historiques.user',
            'commentaires.user',
            'piecesJointes.user',
            'affectations.ancienTechnicien',
            'affectations.nouveauTechnicien',
            'affectations.auteurAffectation',
        ]);

        return view('support.tickets.show', compact('ticket'));
    }

    public function prendreEnCharge(
        SupportTicket $ticket,
        SupportPriseEnChargeService $service
    ) {
        $service->prendreEnCharge($ticket, auth()->user());

        return back()->with(
            'success',
            'Le ticket a été pris en charge avec succès.'
        );
    }
}
