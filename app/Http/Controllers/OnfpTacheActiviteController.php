<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpSousActivite;
use App\Models\OnfpTache;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;

class OnfpTacheActiviteController extends Controller
{
    /**
     * Liste des tâches d'une sous-activité, ou des tâches
     * directement rattachées à l'activité si aucune sous-activité.
     */

    public function index(
        OnfpActivite $activite,
        ?OnfpSousActivite $sousActivite = null
    ) {
        /*
    |--------------------------------------------------------------------------
    | Vérification de la sous-activité
    |--------------------------------------------------------------------------
    */

        if ($sousActivite) {
            abort_unless(
                $sousActivite->activite_id == $activite->id,
                404
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Contexte des routes
    |--------------------------------------------------------------------------
    */

        $isNested = $sousActivite !== null;

        $routePrefix = $isNested
            ? 'onfp.activites.sous-activites.taches'
            : 'onfp.activites.taches';

        $routeParams = $isNested
            ? [
                'activite' => $activite,
                'sousActivite' => $sousActivite,
            ]
            : [
                'activite' => $activite,
            ];

        /*
    |--------------------------------------------------------------------------
    | Requête de base
    |--------------------------------------------------------------------------
    */

        $baseQuery = OnfpTache::where(
            'activite_id',
            $activite->id
        )
            ->when(
                $sousActivite,
                fn($query) => $query->where(
                    'sous_activite_id',
                    $sousActivite->id
                ),
                fn($query) => $query->whereNull('sous_activite_id')
            );

        /*
    |--------------------------------------------------------------------------
    | Statistiques générales
    |
    | Calculées sur TOUTES les tâches et non uniquement sur la page courante.
    |--------------------------------------------------------------------------
    */

        $totalTaches = (clone $baseQuery)->count();

        $aFaire = (clone $baseQuery)
            ->where('statut', 'a_faire')
            ->count();

        $enCours = (clone $baseQuery)
            ->where('statut', 'en_cours')
            ->count();

        $terminees = (clone $baseQuery)
            ->where('statut', 'terminee')
            ->count();

        $enRetard = (clone $baseQuery)
            ->whereNotNull('date_echeance')
            ->where('date_echeance', '<', now())
            ->whereNotIn('statut', ['terminee', 'annulee'])
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $taches = (clone $baseQuery)
            ->orderBy('ordre')
            ->orderBy('created_at')
            ->paginate(15);

        /*
    | Conserver les paramètres éventuels de l'URL
    |--------------------------------------------------------------------------
    */

        $taches->appends(request()->query());

        /*
    |--------------------------------------------------------------------------
    | Préparation des données d'affichage
    |--------------------------------------------------------------------------
    */

        $statusLabels = [
            'a_faire'   => 'À faire',
            'en_cours'  => 'En cours',
            'suspendue' => 'Suspendue',
            'terminee'  => 'Terminée',
            'annulee'   => 'Annulée',
        ];

        $statusClasses = [
            'a_faire'   => 'bg-secondary-subtle text-secondary',
            'en_cours'  => 'bg-primary-subtle text-primary',
            'suspendue' => 'bg-warning-subtle text-warning',
            'terminee'  => 'bg-success-subtle text-success',
            'annulee'   => 'bg-danger-subtle text-danger',
        ];

        $priorityLabels = [
            'basse'   => 'Basse',
            'normale' => 'Normale',
            'haute'   => 'Haute',
            'urgente' => 'Urgente',
        ];

        $priorityClasses = [
            'basse'   => 'text-secondary',
            'normale' => 'text-primary',
            'haute'   => 'text-warning',
            'urgente' => 'text-danger',
        ];

        /*
    |--------------------------------------------------------------------------
    | Enrichissement des tâches
    |--------------------------------------------------------------------------
    */

        $taches->getCollection()->transform(function ($tache) use (
            $statusLabels,
            $statusClasses,
            $priorityLabels,
            $priorityClasses,
            $routeParams
        ) {

            // Statut
            $tache->status_label =
                $statusLabels[$tache->statut]
                ?? ucfirst($tache->statut);

            $tache->status_class =
                $statusClasses[$tache->statut]
                ?? 'bg-light text-dark';

            // Priorité
            $tache->priority_label =
                $priorityLabels[$tache->priorite]
                ?? ucfirst($tache->priorite);

            $tache->priority_class =
                $priorityClasses[$tache->priorite]
                ?? 'text-secondary';

            // Progression
            $tache->progression_value = max(
                0,
                min(100, (int) ($tache->progression ?? 0))
            );

            // Retard
            $tache->en_retard =
                $tache->date_echeance
                && $tache->date_echeance->isPast()
                && !in_array(
                    $tache->statut,
                    ['terminee', 'annulee'],
                    true
                );

            // Paramètres des routes
            $tache->route_params = array_merge(
                $routeParams,
                [
                    'tache' => $tache,
                ]
            );

            return $tache;
        });

        /*
    |--------------------------------------------------------------------------
    | Vue
    |--------------------------------------------------------------------------
    */

        return view(
            'onfp.activites.taches.index',
            compact(
                'activite',
                'sousActivite',
                'enCours',
                'terminees',
                'aFaire',
                'enRetard',
                'routePrefix',
                'routeParams',
                'totalTaches',
                'taches'
            )
        );
    }

    /**
     * Formulaire de création d'une tâche.
     */
    public function create(
        OnfpActivite $activite,
        ?OnfpSousActivite $sousActivite = null
    ) {
        if ($sousActivite) {
            abort_unless(
                $sousActivite->activite_id == $activite->id,
                404
            );
        }

        $employees = Employee::with('user')
            ->whereHas('user')
            ->get()
            ->sortBy(function ($employee) {
                return strtolower(
                    ($employee->user->name ?? '') . ' ' .
                        ($employee->user->firstname ?? '')
                );
            });

        return view('onfp.activites.taches.create', compact(
            'activite',
            'sousActivite',
            'employees'
        ));
    }

    /**
     * Enregistrer une nouvelle tâche.
     */
    public function store(
        Request $request,
        OnfpActivite $activite,
        ?OnfpSousActivite $sousActivite = null
    ) {
        // Vérifier que la sous-activité appartient bien à l'activité (uniquement si fournie)
        if ($sousActivite) {
            abort_unless(
                $sousActivite->activite_id == $activite->id,
                404
            );
        }

        $validated = $request->validate([
            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'required',
                'string',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'nullable',
                'string',
                'in:basse,normale,haute,urgente',
            ],

            'date_debut' => [
                'nullable',
                'date',
            ],

            'date_echeance' => [
                'nullable',
                'date',
            ],

            'date_realisation' => [
                'nullable',
                'date',
            ],

            'progression' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'ordre' => [
                'nullable',
                'integer',
                'min:0',
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
                'exists:employees,id',
            ],

            'suiveurs' => [
                'nullable',
                'array',
            ],

            'suiveurs.*' => [
                'exists:employees,id',
            ],
        ]);

        $validated['activite_id'] = $activite->id;
        $validated['sous_activite_id'] = $sousActivite?->id;

        // Valeurs par défaut
        $validated['statut'] = $validated['statut'] ?? 'a_faire';
        $validated['priorite'] = $validated['priorite'] ?? 'normale';
        $validated['progression'] = $validated['progression'] ?? 0;

        if (!isset($validated['ordre'])) {
            $validated['ordre'] = (
                OnfpTache::where(
                    'sous_activite_id',
                    $sousActivite?->id
                )
                ->where('activite_id', $activite->id)
                ->max('ordre') ?? 0
            ) + 1;
        }

        $responsables = $validated['responsables'] ?? [];
        $suiveurs = $validated['suiveurs'] ?? [];
        unset($validated['responsables'], $validated['suiveurs']);

        $tache = OnfpTache::create($validated);

        // Adapter selon la structure réelle de vos relations
        // (table pivot dédiée, ou relation directe employee_id)
        if (!empty($responsables)) {
            $tache->responsables()->createMany(
                collect($responsables)->map(fn($employeeId) => [
                    'employee_id' => $employeeId,
                ])->all()
            );
        }

        if (!empty($suiveurs)) {
            $tache->suiveurs()->createMany(
                collect($suiveurs)->map(fn($employeeId) => [
                    'employee_id' => $employeeId,
                ])->all()
            );
        }

        // Redirection adaptée selon le contexte
        $redirectRoute = $sousActivite
            ? route('onfp.activites.sous-activites.taches.index', [
                'activite' => $activite,
                'sousActivite' => $sousActivite,
            ])
            : route('onfp.activites.show', $activite);

        return redirect()
            ->to($redirectRoute)
            ->with(
                'success',
                'La tâche a été créée avec succès.'
            );
    }

    /**
     * Afficher une tâche.
     */
    public function show(
        OnfpActivite $activite,
        OnfpTache $tache,
        ?OnfpSousActivite $sousActivite = null
    ) {
        // Vérifications de cohérence
        if ($sousActivite) {
            abort_unless(
                $sousActivite->activite_id == $activite->id,
                404
            );
        }

        abort_unless(
            $tache->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite?->id,
            404
        );

        return view('onfp.activites.taches.show', compact(
            'activite',
            'sousActivite',
            'tache'
        ));
    }

    /**
     * Formulaire de modification d'une tâche.
     */
    public function edit(
        OnfpActivite $activite,
        OnfpTache $tache,
        ?OnfpSousActivite $sousActivite = null
    ) {
        // Vérifications de cohérence
        if ($sousActivite) {
            abort_unless(
                $sousActivite->activite_id == $activite->id,
                404
            );
        }

        abort_unless(
            $tache->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite?->id,
            404
        );

        $employees = Employee::with('user')
            ->whereHas('user')
            ->get()
            ->sortBy(fn($e) => strtolower(($e->user->name ?? '') . ' ' . ($e->user->firstname ?? '')));

        return view('onfp.activites.taches.edit', compact(
            'activite',
            'sousActivite',
            'employees',
            'tache'
        ));
    }

    /**
     * Mettre à jour une tâche.
     */
    public function update(
        Request $request,
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        // Vérifications de cohérence
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite->id,
            404
        );

        $validated = $request->validate([
            'libelle' => [
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
                'string',
                'in:a_faire,en_cours,suspendue,terminee,annulee',
            ],

            'priorite' => [
                'nullable',
                'string',
                'in:basse,normale,haute,urgente',
            ],

            'date_enclenchement' => [
                'nullable',
                'date',
            ],

            'date_execution' => [
                'nullable',
                'date',
            ],

            'date_fin' => [
                'nullable',
                'date',
                'after_or_equal:date_execution',
            ],

            'progression' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'ordre' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'observations' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['activite_id'] = $activite->id;
        $validated['sous_activite_id'] = $sousActivite->id;

        // Si la tâche est terminée, la progression passe automatiquement à 100 %
        if (
            isset($validated['statut']) &&
            $validated['statut'] === 'terminee'
        ) {
            $validated['progression'] = 100;
        }

        $tache->update($validated);

        return redirect()
            ->route(
                'onfp.activites.sous-activites.taches.index',
                [
                    'activite' => $activite,
                    'sousActivite' => $sousActivite,
                ]
            )
            ->with(
                'success',
                'La tâche a été mise à jour avec succès.'
            );
    }

    /**
     * Supprimer une tâche.
     */
    public function destroy(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        // Vérifications de cohérence
        abort_unless(
            $sousActivite->activite_id == $activite->id,
            404
        );

        abort_unless(
            $tache->sous_activite_id == $sousActivite->id,
            404
        );

        $tache->delete();

        return redirect()
            ->route(
                'onfp.activites.sous-activites.taches.index',
                [
                    'activite' => $activite,
                    'sousActivite' => $sousActivite,
                ]
            )
            ->with(
                'success',
                'La tâche a été supprimée avec succès.'
            );
    }
}
