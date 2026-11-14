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
use App\Models\User;
use App\Services\Support\SupportAffectationService;
use App\Services\Support\SupportInterventionService;

class SupportTicketController extends Controller
{

    public function dashboard()
    {
        $user = auth()->user();

        abort_unless($user->can('support.dashboard.view'), 403);

        // Périmètre des tickets visibles par l'utilisateur.
        $query = SupportTicket::query();

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

        $stats = [
            'total' => (clone $query)->count(),

            'nouveaux' => (clone $query)->whereHas(
                'statut',
                fn ($q) => $q->where('code', 'NOUVEAU')
            )->count(),

            'en_cours' => (clone $query)->whereHas(
                'statut',
                fn ($q) => $q->where('code', 'EN_COURS')
            )->count(),

            'en_attente' => (clone $query)->whereHas(
                'statut',
                fn ($q) => $q->where('code', 'EN_ATTENTE')
            )->count(),

            'resolus' => (clone $query)->whereHas(
                'statut',
                fn ($q) => $q->where('code', 'RESOLU')
            )->count(),
        ];

        $ticketsRecents = (clone $query)->with([
            'categorie',
            'priorite',
            'statut',
            'demandeur',
            'assigne',
        ])->orderByDesc('date_soumission')
        ->limit(10)
        ->get();

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
            'interventions.user',
            /* 'commentaires.user', */
            'commentaires' => function ($query) use ($user) {
                $query->with('user');

                if (!$user->can('support.commentaires.view')) {
                    $query->where('visible_demandeur', true);
                }
            },
            'piecesJointes.user',
            'affectations.ancienTechnicien',
            'affectations.nouveauTechnicien',
            'affectations.auteurAffectation',
        ]);

        $techniciens = collect();

        if ($user->can('support.tickets.assign')) {
            $techniciens = User::role('technicien-support')
                ->orderBy('firstname')
                ->orderBy('name')
                ->get();
        }

        return view('support.tickets.show', compact(
            'ticket',
            'techniciens'
        ));
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

        public function affecter(
        Request $request,
        SupportTicket $ticket,
        SupportAffectationService $service
    ) {
        abort_unless(
            auth()->user()->can('support.tickets.assign'),
            403
        );

        $validated = $request->validate([
            'technicien_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'motif' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $technicien = User::role('technicien-support')
            ->findOrFail($validated['technicien_id']);

        $service->affecter(
            $ticket,
            $technicien,
            auth()->user(),
            $validated['motif'] ?? null
        );

        return redirect()
            ->route('support.tickets.show', $ticket)
            ->with('success', 'Le ticket a été affecté avec succès.');
    }


    public function ajouterCommentaire(
        Request $request,
        SupportTicket $ticket
    ) {
        $user = auth()->user();

        abort_unless(
            $user->can('support.commentaires.create'),
            403
        );

        // Vérifier que l'utilisateur a le droit de consulter ce ticket.
        $autorise = $user->can('support.tickets.view-all')
            || (
                $user->can('support.tickets.view-assigned')
                && (
                    (int) $ticket->user_demandeur_id === (int) $user->id
                    || (int) $ticket->user_assigne_id === (int) $user->id
                )
            )
            || (
                $user->can('support.tickets.view-own')
                && (int) $ticket->user_demandeur_id === (int) $user->id
            );

        abort_unless($autorise, 403);

        $validated = $request->validate([
            'contenu' => ['required', 'string', 'min:2', 'max:5000'],
            'visible_demandeur' => ['nullable', 'boolean'],
        ]);

        // Un utilisateur hors équipe Support ne peut publier
        // que des commentaires visibles par le demandeur.
        $visibleDemandeur = !$user->can('support.commentaires.view')
            || $request->boolean('visible_demandeur');

        DB::transaction(function () use (
            $ticket,
            $user,
            $validated,
            $visibleDemandeur
        ) {
            $commentaire = $ticket->commentaires()->create([
                'user_id' => $user->id,
                'contenu' => $validated['contenu'],
                'visible_demandeur' => $visibleDemandeur,
            ]);

            SupportTicketHistorique::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'action' => 'COMMENTAIRE',
                'champ' => null,
                'ancienne_valeur' => null,
                'nouvelle_valeur' => (string) $commentaire->id,
                'description' => $visibleDemandeur
                    ? 'Ajout d’un commentaire visible par le demandeur.'
                    : 'Ajout d’une note interne au support.',
            ]);
        });

        return redirect()
            ->route('support.tickets.show', $ticket)
            ->with('success', 'Votre commentaire a été enregistré.');
    }

   /*  public function enregistrerIntervention(
    Request $request,
    SupportTicket $ticket,
    SupportInterventionService $service
    ) {
        $user = auth()->user();

        $autorise =
            $user->can('support.tickets.view-all')
            || (
                $user->can('support.tickets.view-assigned')
                && (
                    (int) $ticket->user_demandeur_id === (int) $user->id
                    || (int) $ticket->user_assigne_id === (int) $user->id
                )
            )
            || (
                $user->can('support.tickets.view-own')
                && (int) $ticket->user_demandeur_id === (int) $user->id
            );

        abort_unless($autorise, 403);

        $donnees = $request->validate([
            'date_intervention' => ['required', 'date'],
            'diagnostic' => ['required', 'string', 'max:5000'],
            'travaux_realises' => ['required', 'string', 'max:10000'],
            'duree_minutes' => ['required', 'integer', 'min:1', 'max:100000'],
            'resultat' => [
                'required',
                'in:RESOLU,NON_RESOLU,EN_ATTENTE,A_SUIVRE',
            ],
            'observations' => ['nullable', 'string', 'max:5000'],
        ]);

        $service->enregistrer($ticket, auth()->user(), $donnees);

        return redirect()
            ->route('support.tickets.show', $ticket)
            ->with('success', 'L’intervention a été enregistrée avec succès.');
    } */

     public function enregistrerIntervention(
        Request $request,
        SupportTicket $ticket,
        SupportInterventionService $service
    ) {
        $user = auth()->user();

        $autorise =
            $user->can('support.tickets.view-all')
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

        $donnees = $request->validate([
            'date_intervention' => ['required', 'date'],
            'diagnostic' => ['required', 'string', 'max:5000'],
            'travaux_realises' => ['required', 'string', 'max:10000'],
            'duree_minutes' => ['required', 'integer', 'min:1', 'max:100000'],
            'resultat' => [
                'required',
                'in:RESOLU,NON_RESOLU,EN_ATTENTE,A_SUIVRE',
            ],
            'observations' => ['nullable', 'string', 'max:5000'],
        ]);

        $service->enregistrer($ticket, $user, $donnees);

        return redirect()
            ->route('support.tickets.show', $ticket)
            ->with('success', 'Intervention enregistrée avec succès.');
    }
}
