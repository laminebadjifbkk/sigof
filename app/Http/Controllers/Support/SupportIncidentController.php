<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\SupportIncident;
use App\Models\Support\SupportIncidentType;
use App\Models\Support\SupportIncidentStatut;
use App\Models\Support\SupportPriorite;
use App\Services\Support\SupportIncidentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Support\SupportTicket;
use App\Models\Support\SupportIncidentHistorique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportIncidentController extends Controller
{
    /**
     * Liste des incidents.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user->can('support.incidents.view'), 403);

        $query = SupportIncident::query()
            ->with([
                'type',
                'priorite',
                'statut',
                'declarant',
                'responsable',
            ]);

        if (!$user->can('support.incidents.manage')) {
            $query->where(function ($q) use ($user) {
                $q->where('user_declarant_id', $user->id)
                    ->orWhere('user_responsable_id', $user->id);
            });
        }

        if ($request->filled('statut')) {
            $query->whereHas('statut', function ($q) use ($request) {
                $q->where('code', $request->statut);
            });
        }

        if ($request->filled('priorite')) {
            $query->where('support_priorite_id', $request->priorite);
        }

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;

            $query->where(function ($q) use ($recherche) {
                $q->where('reference', 'like', "%{$recherche}%")
                    ->orWhere('titre', 'like', "%{$recherche}%")
                    ->orWhere('description', 'like', "%{$recherche}%");
            });
        }

        $incidents = $query
            ->orderByDesc('date_detection')
            ->paginate(15)
            ->withQueryString();

            $statuts = SupportIncidentStatut::actifs()
            ->ordonnes()
            ->get();

            $priorites = SupportPriorite::actives()
    ->ordonnees()
    ->get();

        return view('support.incidents.index', compact(
            'incidents',
            'statuts',
            'priorites'
        ));
    }

    /**
     * Formulaire de déclaration.
     */
    public function create(Request $request)
    {
        abort_unless(
            $request->user()->can('support.incidents.create'),
            403
        );

        $types = SupportIncidentType::actifs()
            ->ordonnes()
            ->get();

        $priorites = SupportPriorite::actives()
    ->ordonnees()
    ->get();

        return view('support.incidents.create', compact(
            'types',
            'priorites'
        ));
    }

    /**
     * Enregistrement d'un incident.
     */
    public function store(
        Request $request,
        SupportIncidentService $incidentService
    ) {
        abort_unless(
            $request->user()->can('support.incidents.create'),
            403
        );

        $validated = $request->validate([
            'titre' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
            ],
            'support_incident_type_id' => [
                'required',
                'integer',
                Rule::exists('support_incident_types', 'id')
                    ->where('actif', true)
                    ->whereNull('deleted_at'),
            ],
            'support_priorite_id' => [
                'required',
                'integer',
                Rule::exists('support_priorites', 'id')
                    ->where('actif', true)
                    ->whereNull('deleted_at'),
            ],
            'date_detection' => [
                'required',
                'date',
                'before_or_equal:now',
            ],
        ]);

        $incident = $incidentService->creer(
            $validated,
            $request->user()
        );

        return redirect()
            ->route('support.incidents.show', $incident)
            ->with(
                'success',
                'L’incident ' . $incident->reference
                . ' a été déclaré avec succès.'
            );
    }

    /**
     * Consultation du détail d'un incident.
     */
    /* public function show(
        Request $request,
        SupportIncident $incident
    ) {
        $user = $request->user();

        $autorise = $user->can('support.incidents.manage')
            || (
                $user->can('support.incidents.view')
                && (
                    (int) $incident->user_declarant_id === (int) $user->id
                    || (int) $incident->user_responsable_id === (int) $user->id
                )
            );

        abort_unless($autorise, 403);

        $incident->load([
            'type',
            'priorite',
            'statut',
            'declarant',
            'responsable',
            'historiques.user',
            'tickets',
        ]);

        return view('support.incidents.show', compact('incident'));
    } */


/**
 * Consultation du détail d'un incident.
 */
public function show(
    Request $request,
    SupportIncident $incident
) {
    $user = $request->user();

    $estDeclarant = (int) $incident->user_declarant_id === (int) $user->id;
    $estResponsable = (int) $incident->user_responsable_id === (int) $user->id;

    $autorise = $user->can('support.incidents.manage')
        || (
            $user->can('support.incidents.view')
            && ($estDeclarant || $estResponsable)
        );

    abort_unless($autorise, 403);

    $peutModifierIncident = $user->can('support.incidents.manage')
        || (
            $user->can('support.incidents.update')
            && ($estDeclarant || $estResponsable)
        );

    $incident->load([
        'type',
        'priorite',
        'statut',
        'declarant',
        'responsable',
        'historiques.user',
        'tickets.statut',
    ]);

    // Tickets visibles par l'utilisateur et non encore associés.
    $ticketsDisponibles = $this->ticketsVisiblesPour($user)
        ->with('statut')
        ->whereDoesntHave('incidents', function ($query) use ($incident) {
            $query->where('support_incidents.id', $incident->id);
        })
        ->when($request->filled('recherche_tickets'), function ($query) use ($request) {
            $recherche = $request->recherche_tickets;

            $query->where(function ($q) use ($recherche) {
                $q->where('reference', 'like', "%{$recherche}%")
                    ->orWhere('objet', 'like', "%{$recherche}%");
            });
        })
        ->orderByDesc('date_soumission')
        ->paginate(10, ['*'], 'tickets_page')
        ->withQueryString();

    return view('support.incidents.show', compact(
        'incident',
        'ticketsDisponibles',
        'peutModifierIncident'
    ));
}

/**
 * Retourne les tickets accessibles à l'utilisateur connecté.
 */
private function ticketsVisiblesPour($user)
{
    $query = SupportTicket::query();

    if ($user->can('support.tickets.view-all')) {
        return $query;
    }

    if ($user->can('support.tickets.view-assigned')) {
        return $query->where(function ($q) use ($user) {
            $q->where('user_demandeur_id', $user->id)
                ->orWhere('user_assigne_id', $user->id);
        });
    }

    if ($user->can('support.tickets.view-own')) {
        return $query->where('user_demandeur_id', $user->id);
    }

    return $query->whereRaw('1 = 0');
}

/**
 * Vérifie le droit de modifier les associations d'un incident.
 */
private function autoriserModificationIncident($user, SupportIncident $incident): void
{
    $autorise = $user->can('support.incidents.manage')
        || (
            $user->can('support.incidents.update')
            && (
                (int) $incident->user_declarant_id === (int) $user->id
                || (int) $incident->user_responsable_id === (int) $user->id
            )
        );

    abort_unless($autorise, 403);
}

/**
 * Associer plusieurs tickets à un incident.
 */
public function associerTickets(
    Request $request,
    SupportIncident $incident
) {
    $user = $request->user();

    $this->autoriserModificationIncident($user, $incident);

    $validated = $request->validate([
        'ticket_ids' => ['required', 'array', 'min:1'],
        'ticket_ids.*' => [
            'required',
            'integer',
            'distinct',
            'exists:support_tickets,id',
        ],
    ]);

    $ticketIds = $validated['ticket_ids'];

    // Vérifier que tous les tickets sont visibles par l'utilisateur.
    $ticketsAutorises = $this->ticketsVisiblesPour($user)
        ->whereIn('id', $ticketIds)
        ->get();

    if ($ticketsAutorises->count() !== count($ticketIds)) {
        throw ValidationException::withMessages([
            'ticket_ids' => 'Certains tickets sélectionnés ne sont pas accessibles.',
        ]);
    }

    DB::transaction(function () use ($incident, $ticketsAutorises, $user) {

        foreach ($ticketsAutorises as $ticket) {

            // Éviter les associations en double.
            $dejaAssocie = $incident->tickets()
                ->where('support_tickets.id', $ticket->id)
                ->exists();

            if ($dejaAssocie) {
                continue;
            }

            $incident->tickets()->attach($ticket->id, [
                'user_create_id' => $user->id,
            ]);

            SupportIncidentHistorique::create([
                'support_incident_id' => $incident->id,
                'user_id' => $user->id,
                'action' => 'TICKET_ASSOCIE',
                'champ' => 'tickets',
                'ancienne_valeur' => null,
                'nouvelle_valeur' => $ticket->reference,
                'description' => 'Association du ticket '
                    . $ticket->reference
                    . ' à l’incident '
                    . $incident->reference . '.',
            ]);
        }
    });

    return redirect()
        ->route('support.incidents.show', $incident)
        ->with('success', 'Les tickets sélectionnés ont été associés à l’incident.');
}

/**
 * Retirer l'association entre un ticket et un incident.
 */
public function retirerTicket(
    Request $request,
    SupportIncident $incident,
    SupportTicket $ticket
) {
    $user = $request->user();

    $this->autoriserModificationIncident($user, $incident);

    // Vérifier que le ticket est accessible à l'utilisateur.
    $ticketAccessible = $this->ticketsVisiblesPour($user)
        ->whereKey($ticket->id)
        ->exists();

    abort_unless($ticketAccessible, 403);

    $estAssocie = $incident->tickets()
        ->where('support_tickets.id', $ticket->id)
        ->exists();

    abort_unless($estAssocie, 404);

    DB::transaction(function () use ($incident, $ticket, $user) {

        SupportIncidentHistorique::create([
            'support_incident_id' => $incident->id,
            'user_id' => $user->id,
            'action' => 'TICKET_RETIRE',
            'champ' => 'tickets',
            'ancienne_valeur' => $ticket->reference,
            'nouvelle_valeur' => null,
            'description' => 'Retrait du ticket '
                . $ticket->reference
                . ' de l’incident '
                . $incident->reference . '.',
        ]);

        $incident->tickets()->detach($ticket->id);
    });

    return redirect()
        ->route('support.incidents.show', $incident)
        ->with('success', 'L’association du ticket a été retirée. Le ticket est conservé.');
}

}
