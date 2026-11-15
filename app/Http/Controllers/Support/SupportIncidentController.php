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
    public function show(
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
    }
}
