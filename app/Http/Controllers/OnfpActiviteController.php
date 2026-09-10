<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\Employee;
use App\Models\OnfpActivite;
use App\Models\OnfpActiviteResponsable;
use App\Models\OnfpActiviteSuiveur;
use App\Models\OnfpActiviteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OnfpActiviteController extends Controller
{
    /**
     * Liste des activités.
     */
    public function index(Request $request)
    {
        $query = OnfpActivite::query()
            ->with([
                'direction',
                'type',
                'responsables.employee',
            ])
            ->withCount([
                'sousActivites',
                'taches',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filtres
        |--------------------------------------------------------------------------
        */

        if ($request->filled('direction_id')) {
            $query->where(
                'direction_id',
                $request->direction_id
            );
        }

        if ($request->filled('type_id')) {
            $query->where(
                'type_id',
                $request->type_id
            );
        }

        if ($request->filled('statut')) {
            $query->where(
                'statut',
                $request->statut
            );
        }

        if ($request->filled('priorite')) {
            $query->where(
                'priorite',
                $request->priorite
            );
        }

        if ($request->filled('etat_sante')) {
            $query->where(
                'etat_sante',
                $request->etat_sante
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tri
        |--------------------------------------------------------------------------
        */

        $sort = $request->get('sort', 'date_fin_prevue');
        $direction = $request->get('direction', 'asc');

        $allowedSorts = [
            'reference',
            'titre',
            'date_enclenchement',
            'date_fin_prevue',
            'progression',
            'statut',
            'priorite',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'date_fin_prevue';
        }

        $direction = $direction === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        $activites = $query->paginate(15)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Données des filtres
        |--------------------------------------------------------------------------
        */

        $directions = Direction::query()
            ->orderBy('name')
            ->get();

        $types = OnfpActiviteType::query()
            ->where('actif', true)
            ->orderBy('ordre')
            ->orderBy('libelle')
            ->get();

        return view('onfp.activites.index', compact(
            'activites',
            'directions',
            'types'
        ));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $directions = Direction::query()
            ->orderBy('name')
            ->get();

        $types = OnfpActiviteType::query()
            ->where('actif', true)
            ->orderBy('ordre')
            ->orderBy('libelle')
            ->get();

        $employees = Employee::query()
            ->with('direction')
            ->orderBy('matricule')
            ->get();

        return view('onfp.activites.create', compact(
            'directions',
            'types',
            'employees'
        ));
    }

    /**
     * Enregistrement d'une activité.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'direction_id' => [
                'nullable',
                'integer',
                'exists:directions,id',
            ],

            'type_id' => [
                'nullable',
                'integer',
                'exists:onfp_activite_types,id',
            ],

            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'required',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'required',
                'in:basse,normale,haute,urgente',
            ],

            'progression' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'date_enclenchement' => [
                'nullable',
                'date',
            ],

            'date_execution_prevue' => [
                'nullable',
                'date',
            ],

            'date_fin_prevue' => [
                'nullable',
                'date',
                'after_or_equal:date_execution_prevue',
            ],

            'etat_sante' => [
                'required',
                'in:normal,a_surveiller,risque,critique',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

            'responsables' => [
                'nullable',
                'array',
            ],

            'responsables.*' => [
                'integer',
                'exists:employees,id',
            ],

            'responsable_principal' => [
                'nullable',
                'integer',
                'exists:employees,id',
            ],

            'suiveurs' => [
                'nullable',
                'array',
            ],

            'suiveurs.*' => [
                'integer',
                'exists:employees,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérification du responsable principal
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['responsable_principal']) &&
            !in_array(
                (int) $validated['responsable_principal'],
                array_map('intval', $validated['responsables'] ?? [])
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'responsable_principal' =>
                    'Le responsable principal doit également figurer parmi les responsables.',
                ]);
        }

        DB::transaction(function () use ($validated, &$activite) {

            /*
            |--------------------------------------------------------------------------
            | Génération de la référence
            |--------------------------------------------------------------------------
            */

            $annee = now()->format('Y');

            $lastId = OnfpActivite::withTrashed()->max('id') ?? 0;

            $reference = 'ACT-' . $annee . '-' .
                str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

            /*
            |--------------------------------------------------------------------------
            | Création
            |--------------------------------------------------------------------------
            */

            $activite = OnfpActivite::create([
                'reference'              => $reference,
                'direction_id'           => $validated['direction_id'] ?? null,
                'type_id'                => $validated['type_id'] ?? null,
                'titre'                  => $validated['titre'],
                'description'            => $validated['description'] ?? null,
                'statut'                 => $validated['statut'],
                'priorite'               => $validated['priorite'],
                'progression'            => $validated['progression'],
                'date_enclenchement'     => $validated['date_enclenchement'] ?? null,
                'date_execution_prevue'  => $validated['date_execution_prevue'] ?? null,
                'date_fin_prevue'        => $validated['date_fin_prevue'] ?? null,
                'etat_sante'             => $validated['etat_sante'],
                'observation'            => $validated['observation'] ?? null,
                'created_by'             => auth()->user()->employee_id ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Responsables
            |--------------------------------------------------------------------------
            */

            foreach ($validated['responsables'] ?? [] as $employeeId) {

                OnfpActiviteResponsable::create([
                    'activite_id'  => $activite->id,
                    'employee_id'  => $employeeId,
                    'role'         => $employeeId == ($validated['responsable_principal'] ?? null)
                        ? 'principal'
                        : 'responsable',
                    'is_principal' => $employeeId == ($validated['responsable_principal'] ?? null),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Suiveurs
            |--------------------------------------------------------------------------
            */

            foreach ($validated['suiveurs'] ?? [] as $employeeId) {

                OnfpActiviteSuiveur::create([
                    'activite_id' => $activite->id,
                    'employee_id' => $employeeId,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Historique initial
            |--------------------------------------------------------------------------
            */

            $activite->historiques()->create([
                'employee_id' => auth()->user()->employee_id ?? null,
                'action'      => 'creation',
                'nouveau_statut' => $activite->statut,
                'nouvelle_progression' => $activite->progression,
                'description' => 'Création de l’activité.',
            ]);
        });

        return redirect()
            ->route('onfp.activites.show', $activite)
            ->with('success', 'Activité créée avec succès.');
    }

    /**
     * Affichage détaillé d'une activité.
     */
    public function show(OnfpActivite $activite)
    {
        $activite->load([
            'direction',
            'type',

            'responsables.employee.direction',
            'suiveurs.employee.direction',

            'sousActivites.taches',

            'taches.responsables.employee',
            'taches.suiveurs.employee',

            'tiers.tiers',
            'indicateurs',
            'documents.employee',
            'commentaires.employee',
            'historiques.employee',
            'notifications.employee',
            'tags',
        ]);

        return view(
            'onfp.activites.show',
            compact('activite')
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(OnfpActivite $activite)
    {
        $activite->load([
            'responsables',
            'suiveurs',
        ]);

        $directions = Direction::query()
            ->orderBy('name')
            ->get();

        $types = OnfpActiviteType::query()
            ->where('actif', true)
            ->orderBy('ordre')
            ->orderBy('libelle')
            ->get();

        $employees = Employee::query()
            ->with('direction')
            ->orderBy('matricule')
            ->get();

        $responsableIds = $activite->responsables
            ->pluck('employee_id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        $responsablePrincipal = $activite->responsables
            ->where('is_principal', true)
            ->first()?->employee_id;

        $suiveurIds = $activite->suiveurs
            ->pluck('employee_id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        return view('onfp.activites.edit', compact(
            'activite',
            'directions',
            'types',
            'employees',
            'responsableIds',
            'responsablePrincipal',
            'suiveurIds'
        ));
    }

    /**
     * Mise à jour.
     */
    public function update(
        Request $request,
        OnfpActivite $activite
    ) {
        $validated = $request->validate([
            'direction_id' => [
                'nullable',
                'integer',
                'exists:directions,id',
            ],

            'type_id' => [
                'nullable',
                'integer',
                'exists:onfp_activite_types,id',
            ],

            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'required',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'required',
                'in:basse,normale,haute,urgente',
            ],

            'progression' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'date_enclenchement' => [
                'nullable',
                'date',
            ],

            'date_execution_prevue' => [
                'nullable',
                'date',
            ],

            'date_fin_prevue' => [
                'nullable',
                'date',
                'after_or_equal:date_execution_prevue',
            ],

            'date_execution_reelle' => [
                'nullable',
                'date',
            ],

            'date_fin_reelle' => [
                'nullable',
                'date',
                'after_or_equal:date_execution_reelle',
            ],

            'etat_sante' => [
                'required',
                'in:normal,a_surveiller,risque,critique',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

            'responsables' => [
                'nullable',
                'array',
            ],

            'responsables.*' => [
                'integer',
                'exists:employees,id',
            ],

            'responsable_principal' => [
                'nullable',
                'integer',
                'exists:employees,id',
            ],

            'suiveurs' => [
                'nullable',
                'array',
            ],

            'suiveurs.*' => [
                'integer',
                'exists:employees,id',
            ],
        ]);

        if (
            !empty($validated['responsable_principal']) &&
            !in_array(
                (int) $validated['responsable_principal'],
                array_map('intval', $validated['responsables'] ?? [])
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'responsable_principal' =>
                    'Le responsable principal doit également figurer parmi les responsables.',
                ]);
        }

        DB::transaction(function () use (
            $validated,
            $activite
        ) {

            $ancienStatut = $activite->statut;
            $ancienneProgression = $activite->progression;

            $activite->update([
                'direction_id'           => $validated['direction_id'] ?? null,
                'type_id'                => $validated['type_id'] ?? null,
                'titre'                  => $validated['titre'],
                'description'            => $validated['description'] ?? null,
                'statut'                 => $validated['statut'],
                'priorite'               => $validated['priorite'],
                'progression'            => $validated['progression'],
                'date_enclenchement'     => $validated['date_enclenchement'] ?? null,
                'date_execution_prevue'  => $validated['date_execution_prevue'] ?? null,
                'date_fin_prevue'        => $validated['date_fin_prevue'] ?? null,
                'date_execution_reelle'  => $validated['date_execution_reelle'] ?? null,
                'date_fin_reelle'        => $validated['date_fin_reelle'] ?? null,
                'etat_sante'             => $validated['etat_sante'],
                'observation'            => $validated['observation'] ?? null,
                'updated_by'              => auth()->user()->employee_id ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Responsables
            |--------------------------------------------------------------------------
            */

            OnfpActiviteResponsable::where(
                'activite_id',
                $activite->id
            )->delete();

            foreach ($validated['responsables'] ?? [] as $employeeId) {

                OnfpActiviteResponsable::create([
                    'activite_id'  => $activite->id,
                    'employee_id'  => $employeeId,
                    'role'         => $employeeId == ($validated['responsable_principal'] ?? null)
                        ? 'principal'
                        : 'responsable',
                    'is_principal' => $employeeId == ($validated['responsable_principal'] ?? null),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Suiveurs
            |--------------------------------------------------------------------------
            */

            OnfpActiviteSuiveur::where(
                'activite_id',
                $activite->id
            )->delete();

            foreach ($validated['suiveurs'] ?? [] as $employeeId) {

                OnfpActiviteSuiveur::create([
                    'activite_id' => $activite->id,
                    'employee_id' => $employeeId,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Historique
            |--------------------------------------------------------------------------
            */

            $changements = [];

            if ($ancienStatut !== $activite->statut) {
                $changements[] = "Statut : {$ancienStatut} → {$activite->statut}";
            }

            if ((int) $ancienneProgression !== (int) $activite->progression) {
                $changements[] = "Progression : {$ancienneProgression}% → {$activite->progression}%";
            }

            if (!empty($changements)) {
                $activite->historiques()->create([
                    'employee_id' => auth()->user()->employee_id ?? null,
                    'action' => 'modification',
                    'ancien_statut' => $ancienStatut,
                    'nouveau_statut' => $activite->statut,
                    'ancienne_progression' => $ancienneProgression,
                    'nouvelle_progression' => $activite->progression,
                    'description' => implode(' | ', $changements),
                ]);
            }
        });

        return redirect()
            ->route('onfp.activites.show', $activite)
            ->with('success', 'Activité mise à jour avec succès.');
    }

    /**
     * Suppression logique.
     */
    public function destroy(OnfpActivite $activite)
    {
        DB::transaction(function () use ($activite) {

            $activite->historiques()->create([
                'employee_id' => auth()->user()->employee_id ?? null,
                'action' => 'suppression',
                'ancien_statut' => $activite->statut,
                'ancienne_progression' => $activite->progression,
                'description' => 'Suppression de l’activité.',
            ]);

            $activite->delete();
        });

        return redirect()
            ->route('onfp.activites.index')
            ->with('success', 'Activité supprimée avec succès.');
    }
}
