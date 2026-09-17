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
                (int) $sousActivite->activite_id === (int) $activite->id,
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
    |
    | Si une sous-activité est fournie :
    |   → uniquement ses tâches.
    |
    | Sinon :
    |   → uniquement les tâches directement rattachées à l'activité.
    |
    */

        $baseQuery = OnfpTache::query()
            ->where('activite_id', $activite->id)
            ->when(
                $sousActivite,
                function ($query) use ($sousActivite) {
                    $query->where(
                        'sous_activite_id',
                        $sousActivite->id
                    );
                },
                function ($query) {
                    $query->whereNull('sous_activite_id');
                }
            );

        /*
    |--------------------------------------------------------------------------
    | Statistiques générales
    |--------------------------------------------------------------------------
    |
    | Les statistiques sont calculées sur l'ensemble des tâches,
    | et non uniquement sur les 15 tâches de la page courante.
    |
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
            ->whereNotIn('statut', [
                'terminee',
                'annulee',
            ])
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
    |--------------------------------------------------------------------------
    | Conserver les paramètres de recherche/filtres
    |--------------------------------------------------------------------------
    */

        $taches->appends(
            request()->query()
        );

        /*
    |--------------------------------------------------------------------------
    | Libellés des statuts
    |--------------------------------------------------------------------------
    */

        $statusLabels = [
            'a_faire'   => 'À faire',
            'en_cours'  => 'En cours',
            'suspendue' => 'Suspendue',
            'terminee'  => 'Terminée',
            'annulee'   => 'Annulée',
        ];

        /*
    |--------------------------------------------------------------------------
    | Classes CSS des statuts
    |--------------------------------------------------------------------------
    */

        $statusClasses = [
            'a_faire'   => 'bg-secondary-subtle text-secondary',
            'en_cours'  => 'bg-primary-subtle text-primary',
            'suspendue' => 'bg-warning-subtle text-warning',
            'terminee'  => 'bg-success-subtle text-success',
            'annulee'   => 'bg-danger-subtle text-danger',
        ];

        /*
    |--------------------------------------------------------------------------
    | Libellés des priorités
    |--------------------------------------------------------------------------
    */

        $priorityLabels = [
            'basse'   => 'Basse',
            'normale' => 'Normale',
            'haute'   => 'Haute',
            'urgente' => 'Urgente',
        ];

        /*
    |--------------------------------------------------------------------------
    | Classes CSS des priorités
    |--------------------------------------------------------------------------
    */

        $priorityClasses = [
            'basse'   => 'text-secondary',
            'normale' => 'text-primary',
            'haute'   => 'text-warning',
            'urgente' => 'text-danger',
        ];

        /*
    |--------------------------------------------------------------------------
    | Préparation des tâches pour la vue
    |--------------------------------------------------------------------------
    |
    | Le Blade ne réalise plus de calcul métier.
    |
    */

        $taches->getCollection()->transform(
            function ($tache) use (
                $statusLabels,
                $statusClasses,
                $priorityLabels,
                $priorityClasses,
                $routeParams
            ) {

                /*
            |--------------------------------------------------------------
            | Statut
            |--------------------------------------------------------------
            */

                $tache->status_label =
                    $statusLabels[$tache->statut]
                    ?? ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $tache->statut ?? ''
                        )
                    );

                $tache->status_class =
                    $statusClasses[$tache->statut]
                    ?? 'bg-light text-dark';


                /*
            |--------------------------------------------------------------
            | Priorité
            |--------------------------------------------------------------
            */

                $tache->priority_label =
                    $priorityLabels[$tache->priorite]
                    ?? ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $tache->priorite ?? ''
                        )
                    );

                $tache->priority_class =
                    $priorityClasses[$tache->priorite]
                    ?? 'text-secondary';


                /*
            |--------------------------------------------------------------
            | Progression
            |--------------------------------------------------------------
            |
            | Toujours une valeur comprise entre 0 et 100.
            |
            */

                $progression = is_numeric(
                    $tache->progression
                )
                    ? (int) $tache->progression
                    : 0;

                $tache->progression_value = max(
                    0,
                    min(100, $progression)
                );


                /*
            |--------------------------------------------------------------
            | Retard
            |--------------------------------------------------------------
            */

                $tache->en_retard =
                    $tache->date_echeance !== null
                    && $tache->date_echeance->isPast()
                    && !in_array(
                        $tache->statut,
                        [
                            'terminee',
                            'annulee',
                        ],
                        true
                    );


                /*
            |--------------------------------------------------------------
            | Paramètres des routes
            |--------------------------------------------------------------
            */

                $tache->route_params = array_merge(
                    $routeParams,
                    [
                        'tache' => $tache,
                    ]
                );

                return $tache;
            }
        );

        /*
    |--------------------------------------------------------------------------
    | Retour de la vue
    |--------------------------------------------------------------------------
    */

        return view(
            'onfp.activites.taches.index',
            [
                'activite'     => $activite,
                'sousActivite' => $sousActivite,

                'taches'       => $taches,

                'totalTaches'  => $totalTaches,
                'aFaire'       => $aFaire,
                'enCours'      => $enCours,
                'terminees'    => $terminees,
                'enRetard'     => $enRetard,

                'routePrefix'  => $routePrefix,
                'routeParams'  => $routeParams,
            ]
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
     *
     * IMPORTANT : l'ordre des paramètres (activite, sousActivite, tache) doit
     * impérativement correspondre à l'ordre des segments dans l'URL des deux
     * routes qui partagent ce contrôleur :
     *   - activites/{activite}/taches/{tache}
     *   - activites/{activite}/sous-activites/{sousActivite}/taches/{tache}
     * Laravel résout les paramètres du contrôleur par position (pas par nom)
     * lors de l'appel final : ne PAS réordonner cette signature, et ne PAS
     * retirer $sousActivite de la signature même si on ne s'en sert pas
     * directement, sous peine de décaler tous les paramètres suivants.
     */
    /**
     * Afficher une tâche.
     */
    public function show(
        OnfpActivite $activite,
        Request $request
    ) {
        $sousActiviteParam = $request->route('sousActivite');
        $tacheParam = $request->route('tache');

        /*
    |--------------------------------------------------------------------------
    | Récupération de la sous-activité
    |--------------------------------------------------------------------------
    */

        $sousActivite = null;

        if ($sousActiviteParam) {
            $sousActivite = OnfpSousActivite::where(
                $sousActiviteParam instanceof OnfpSousActivite
                    ? 'id'
                    : (new OnfpSousActivite)->getRouteKeyName(),
                $sousActiviteParam instanceof OnfpSousActivite
                    ? $sousActiviteParam->id
                    : $sousActiviteParam
            )->firstOrFail();

            abort_unless(
                (int) $sousActivite->activite_id === (int) $activite->id,
                404
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Récupération de la tâche
    |--------------------------------------------------------------------------
    */

        $tache = $tacheParam instanceof OnfpTache
            ? $tacheParam
            : (new OnfpTache)->resolveRouteBinding($tacheParam);

        abort_unless($tache, 404);

        /*
    |--------------------------------------------------------------------------
    | Vérification activité
    |--------------------------------------------------------------------------
    */

        abort_unless(
            (int) $tache->activite_id === (int) $activite->id,
            404
        );

        /*
    |--------------------------------------------------------------------------
    | Vérification sous-activité
    |--------------------------------------------------------------------------
    */

        if ($sousActivite) {
            abort_unless(
                (int) $tache->sous_activite_id === (int) $sousActivite->id,
                404
            );
        } else {
            abort_unless(
                is_null($tache->sous_activite_id),
                404
            );
        }

        return view(
            'onfp.activites.taches.show',
            compact(
                'activite',
                'sousActivite',
                'tache'
            )
        );
    }

    /**
     * Formulaire de modification d'une tâche.
     * Même contrainte d'ordre de paramètres que show() ci-dessus.
     */
    /**
     * Formulaire de modification d'une tâche.
     */
    public function edit(
        OnfpActivite $activite,
        Request $request
    ) {
        $sousActiviteParam = $request->route('sousActivite');
        $tacheParam = $request->route('tache');

        /*
    |--------------------------------------------------------------------------
    | Sous-activité
    |--------------------------------------------------------------------------
    */

        $sousActivite = null;

        if ($sousActiviteParam) {

            $sousActivite = $sousActiviteParam instanceof OnfpSousActivite
                ? $sousActiviteParam
                : (new OnfpSousActivite)->resolveRouteBinding($sousActiviteParam);

            abort_unless($sousActivite, 404);

            abort_unless(
                (int) $sousActivite->activite_id === (int) $activite->id,
                404
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Tâche
    |--------------------------------------------------------------------------
    */

        $tache = $tacheParam instanceof OnfpTache
            ? $tacheParam
            : (new OnfpTache)->resolveRouteBinding($tacheParam);

        abort_unless($tache, 404);

        /*
    |--------------------------------------------------------------------------
    | Vérification activité
    |--------------------------------------------------------------------------
    */

        abort_unless(
            (int) $tache->activite_id === (int) $activite->id,
            404
        );

        /*
    |--------------------------------------------------------------------------
    | Vérification sous-activité
    |--------------------------------------------------------------------------
    */

        if ($sousActivite) {

            abort_unless(
                (int) $tache->sous_activite_id === (int) $sousActivite->id,
                404
            );
        } else {

            abort_unless(
                is_null($tache->sous_activite_id),
                404
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Employés
    |--------------------------------------------------------------------------
    */

        $employees = Employee::with('user')
            ->whereHas('user')
            ->get()
            ->sortBy(function ($employee) {

                return strtolower(
                    ($employee->user->name ?? '') . ' ' .
                        ($employee->user->firstname ?? '')
                );
            });

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

        return view(
            'onfp.activites.taches.edit',
            compact(
                'activite',
                'sousActivite',
                'tache',
                'employees',
                'routePrefix',
                'routeParams'
            )
        );
    }


    /**
     * Mettre à jour une tâche.
     */
    public function update(
        Request $request,
        OnfpActivite $activite,
        OnfpTache $tache,
        ?OnfpSousActivite $sousActivite = null
    ) {
        /*
    |--------------------------------------------------------------------------
    | Vérification de cohérence
    |--------------------------------------------------------------------------
    */

        // La tâche doit appartenir à l'activité
        abort_unless(
            (int) $tache->activite_id === (int) $activite->id,
            404
        );

        /*
    |--------------------------------------------------------------------------
    | Vérification sous-activité
    |--------------------------------------------------------------------------
    */

        if ($sousActivite) {

            // La sous-activité doit appartenir à l'activité
            abort_unless(
                (int) $sousActivite->activite_id === (int) $activite->id,
                404
            );

            // La tâche doit appartenir à cette sous-activité
            abort_unless(
                (int) $tache->sous_activite_id === (int) $sousActivite->id,
                404
            );
        } else {

            // Si aucune sous-activité n'est fournie,
            // la tâche doit être directement liée à l'activité.
            abort_unless(
                is_null($tache->sous_activite_id),
                404
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

        $validated = $request->validate([
            /* 'libelle' => [
                'required',
                'string',
                'max:255',
            ], */

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

        /*
    |--------------------------------------------------------------------------
    | Cohérence des dates
    |--------------------------------------------------------------------------
    */

        if (
            !empty($validated['date_enclenchement']) &&
            !empty($validated['date_execution']) &&
            $validated['date_execution'] < $validated['date_enclenchement']
        ) {
            return back()
                ->withErrors([
                    'date_execution' =>
                    'La date d’exécution doit être postérieure ou égale à la date d’enclenchement.',
                ])
                ->withInput();
        }

        /*
    |--------------------------------------------------------------------------
    | Mise à jour automatique de la progression
    |--------------------------------------------------------------------------
    */

        if ($validated['statut'] === 'terminee') {
            $validated['progression'] = 100;
        }

        /*
    |--------------------------------------------------------------------------
    | Cohérence activité / sous-activité
    |--------------------------------------------------------------------------
    */

        $validated['activite_id'] = $activite->id;
        $validated['sous_activite_id'] = $sousActivite?->id;

        /*
    |--------------------------------------------------------------------------
    | Mise à jour
    |--------------------------------------------------------------------------
    */

        $tache->update($validated);

        /*
    |--------------------------------------------------------------------------
    | Redirection
    |--------------------------------------------------------------------------
    */

        if ($sousActivite) {
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

        return redirect()
            ->route(
                'onfp.activites.taches.index',
                [
                    'activite' => $activite,
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
