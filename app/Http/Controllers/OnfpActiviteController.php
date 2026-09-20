<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\Employee;
use App\Models\OnfpTiers;
use App\Models\OnfpActivite;
use App\Models\OnfpActiviteResponsable;
use App\Models\OnfpActiviteSuiveur;
use App\Models\OnfpActiviteType;
use App\Models\OnfpActiviteTiers;
use App\Models\OnfpActiviteTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnfpActiviteController extends Controller
{
    /**
     * Statuts possibles d'une activité (valeur => libellé / couleur badge).
     */
    private const STATUTS = [
        'a_faire'   => ['label' => 'À faire',   'badge' => 'secondary'],
        'en_cours'  => ['label' => 'En cours',  'badge' => 'primary'],
        'suspendue' => ['label' => 'Suspendue', 'badge' => 'warning'],
        'terminee'  => ['label' => 'Terminée',  'badge' => 'success'],
        'annulee'   => ['label' => 'Annulée',   'badge' => 'dark'],
    ];

    /**
     * Niveaux de priorité.
     */
    private const PRIORITES = [
        'basse'   => 'Basse',
        'normale' => 'Normale',
        'haute'   => 'Haute',
        'urgente' => 'Urgente',
    ];

    /**
     * États de santé d'une activité.
     */
    private const ETATS_SANTE = [
        'normal'       => ['label' => 'Normal',       'badge' => 'success'],
        'a_surveiller' => ['label' => 'À surveiller', 'badge' => 'warning'],
        'risque'       => ['label' => 'Risque',       'badge' => 'onfp-orange'],
        'critique'     => ['label' => 'Critique',     'badge' => 'danger'],
    ];

    /**
     * Colonnes de tri autorisées.
     */
    private const SORTABLE_COLUMNS = [
        'reference',
        'titre',
        'date_enclenchement',
        'date_fin_prevue',
        'progression',
        'statut',
        'priorite',
    ];

    /**
     * Tailles de page autorisées.
     */
    private const PER_PAGE_OPTIONS = [5, 10, 15, 25, 50, 100];
    /**
     * Liste des activités.
     */
    public function index(Request $request)
    {
        /*
    |--------------------------------------------------------------------
    | Statistiques générales (une seule requête au lieu de 7)
    |--------------------------------------------------------------------
    */

        $stats = OnfpActivite::query()->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN statut = 'a_faire'   THEN 1 ELSE 0 END) as a_faire,
            SUM(CASE WHEN statut = 'en_cours'  THEN 1 ELSE 0 END) as en_cours,
            SUM(CASE WHEN statut = 'terminee'  THEN 1 ELSE 0 END) as terminee,
            SUM(CASE WHEN statut = 'suspendue' THEN 1 ELSE 0 END) as suspendue,
            SUM(CASE WHEN statut = 'annulee'   THEN 1 ELSE 0 END) as annulee,
            SUM(CASE WHEN etat_sante IN ('risque','critique') THEN 1 ELSE 0 END) as a_risque,
            COALESCE(AVG(progression), 0) as progression_moyenne
        ")->first();

        $totalActivites      = (int) $stats->total;
        $activitesAFaire     = (int) $stats->a_faire;
        $activitesEnCours    = (int) $stats->en_cours;
        $activitesTerminees  = (int) $stats->terminee;
        $activitesSuspendues = (int) $stats->suspendue;
        $activitesAnnulees   = (int) $stats->annulee;
        $activitesRisque     = (int) $stats->a_risque;
        $progressionMoyenne  = (int) round($stats->progression_moyenne);

        /*
    |--------------------------------------------------------------------
    | Requête principale
    |--------------------------------------------------------------------
    */

        $query = OnfpActivite::query()
            ->with([
                'direction:id,name,sigle',
                'type:id,libelle',
                'responsables.employee.user',
                'tags',
            ])
            ->withCount(['sousActivites', 'taches']);

        // Recherche (référence, titre, description, ou responsable)
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('responsables.employee.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filtres
        $query
            ->when($request->filled('direction_id'), fn($q) => $q->where('direction_id', $request->direction_id))
            ->when($request->filled('type_id'), fn($q) => $q->where('type_id', $request->type_id))
            ->when($request->filled('statut'), fn($q) => $q->where('statut', $request->statut))
            ->when($request->filled('priorite'), fn($q) => $q->where('priorite', $request->priorite))
            ->when($request->filled('etat_sante'), fn($q) => $q->where('etat_sante', $request->etat_sante))
            ->when($request->filled('tag'), function ($q) use ($request) {
                $q->whereHas('tags', function ($sub) use ($request) {
                    $sub->where('onfp_activite_tags.slug', $request->string('tag'));
                });
            });

        // Tri
        $sort = $request->get('sort', 'date_fin_prevue');

        if (!in_array($sort, self::SORTABLE_COLUMNS, true)) {
            $sort = 'date_fin_prevue';
        }

        $direction = $request->get('direction', 'asc') === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sort, $direction);

        $activites = $query->get();

        /*
    |--------------------------------------------------------------------
    | Données des filtres
    |--------------------------------------------------------------------
    */

        $directions = Direction::query()->orderBy('name')->get();

        $types = OnfpActiviteType::query()
            ->where('actif', true)
            ->orderBy('ordre')
            ->orderBy('libelle')
            ->get();

        $tagsDisponibles = OnfpActiviteTag::where('actif', true)->orderBy('nom')->get();

        // Construit un lien de tri qui préserve les filtres/pagination actifs
        // et inverse la direction si on clique deux fois sur la même colonne.
        $sortLink = function (string $column) use ($sort, $direction) {
            $newDirection = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';

            return request()->fullUrlWithQuery([
                'sort' => $column,
                'direction' => $newDirection,
                'page' => 1,
            ]);
        };

        $sortIcon = function (string $column) use ($sort, $direction) {
            if ($sort !== $column) {
                return 'bi-arrow-down-up text-muted opacity-50';
            }

            return $direction === 'asc' ? 'bi-sort-up' : 'bi-sort-down';
        };

        $hasActiveFilters = collect([
            'search',
            'direction_id',
            'type_id',
            'statut',
            'priorite',
            'etat_sante',
            'tag',
        ])->contains(fn($key) => request()->filled($key));

        return view('onfp.activites.index', [
            'activites'  => $activites,
            'directions' => $directions,
            'types'      => $types,
            'tagsDisponibles' => $tagsDisponibles,

            //Autres
            'hasActiveFilters'      => $hasActiveFilters,
            'sortIcon'      => $sortIcon,
            'sortLink'      => $sortLink,

            // Statistiques
            'totalActivites'      => $totalActivites,
            'activitesAFaire'     => $activitesAFaire,
            'activitesEnCours'    => $activitesEnCours,
            'activitesTerminees'  => $activitesTerminees,
            'activitesSuspendues' => $activitesSuspendues,
            'activitesAnnulees'   => $activitesAnnulees,
            'activitesRisque'     => $activitesRisque,
            'progressionMoyenne'  => $progressionMoyenne,

            // Référentiels partagés avec la vue (évite de recalculer les
            // libellés/couleurs à chaque ligne du tableau)
            'statuts'     => self::STATUTS,
            'priorites'   => self::PRIORITES,
            'etatsSante'  => self::ETATS_SANTE,

            // Compteur par statut, pour le bloc "État des activités"
            'statutCounts' => [
                'a_faire'   => $activitesAFaire,
                'en_cours'  => $activitesEnCours,
                'suspendue' => $activitesSuspendues,
                'terminee'  => $activitesTerminees,
                'annulee'   => $activitesAnnulees,
            ],

            // État courant du tri / pagination pour les liens de la vue
            'sort'          => $sort,
            'sortDirection' => $direction,
        ]);
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('onfp.activites.create', [
            'directions' => Direction::query()->orderBy('name')->get(),
            'types'      => OnfpActiviteType::query()
                ->where('actif', true)
                ->orderBy('ordre')
                ->orderBy('libelle')
                ->get(),
            'employees' => Employee::query()->with('direction')->orderBy('matricule')->get(),
            'tiers'     => OnfpTiers::query()->actifs()->orderBy('nom')->get(),
            'tags'     =>  OnfpActiviteTag::where('actif', true)->orderBy('nom')->get(),
            'statuts'    => self::STATUTS,
            'priorites'  => self::PRIORITES,
            'etatsSante' => self::ETATS_SANTE,
        ]);
    }

    /**
     * Enregistrement d'une activité.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if (!$this->responsablePrincipalEstValide($validated)) {
            return back()
                ->withInput()
                ->withErrors([
                    'responsable_principal' =>
                    "Le responsable principal doit également figurer parmi les responsables.",
                ]);
        }

        /* $activite = DB::transaction(function () use ($validated) {

            $activite = OnfpActivite::create($this->donneesActivite($validated) + [
                'reference'  => $this->genererReference(),
                'created_by' => optional(auth()->user()->employee)->id,
            ]);

            $this->synchroniserResponsables($activite, $validated);
            $this->synchroniserSuiveurs($activite, $validated);

            $activite->historiques()->create([
                'employee_id'          => optional(auth()->user()->employee)->id,
                'action'               => 'creation',
                'nouveau_statut'       => $activite->statut,
                'nouvelle_progression' => $activite->progression,
                'description'          => "Création de l'activité.",
            ]);

            return $activite;
        }); */

        $activite = DB::transaction(function () use ($validated) {

            $activite = OnfpActivite::create($this->donneesActivite($validated) + [
                'reference'  => $this->genererReference(),
                'created_by' => optional(auth()->user()->employee)->id,
            ]);

            $this->synchroniserResponsables($activite, $validated);
            $this->synchroniserSuiveurs($activite, $validated);

            return $activite;
        });

        // Dans store(), après la création de l'activité :
        if ($request->filled('tags')) {
            $activite->tags()->sync($request->input('tags'));
        }

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
            /* 'responsables.tiers', */
            'suiveurs.employee.direction',
            /* 'suiveurs.tiers', */

            'sousActivites.taches',

            'taches.responsables.employee',
            'taches.suiveurs.employee',

            'tiers.tiers',
            'indicateurs',
            'documents.employee',
            'commentaires.employee',
            'historiques.employee.user',
            'historiques.employee.fonction',
            'notifications.employee',
            'tags',
        ]);
        $historiques = $activite->historiques->sortByDesc('created_at')->values();
        $parPage = 1;
        return view('onfp.activites.show', [
            'activite'   => $activite,
            'historiques'   => $historiques,
            'parPage'   => $parPage,
            'statuts'    => self::STATUTS,
            'priorites'  => self::PRIORITES,
            'etatsSante' => self::ETATS_SANTE,
        ]);
    }

    /**
     * Formulaire de modification.
     */
    public function edit(OnfpActivite $activite)
    {
        $activite->load(['responsables', 'suiveurs']);

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

        $tierIds = OnfpActiviteTiers::where('activite_id', $activite->id)
            ->pluck('tier_id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        return view('onfp.activites.edit', [
            'activite'   => $activite,
            'directions' => Direction::query()->orderBy('name')->get(),
            'types'      => OnfpActiviteType::query()
                ->where('actif', true)
                ->orderBy('ordre')
                ->orderBy('libelle')
                ->get(),
            'employees' => Employee::query()->with('direction')->orderBy('matricule')->get(),
            'tiers'     => OnfpTiers::query()->actifs()->orderBy('nom')->get(),
            'tags'       => OnfpActiviteTag::where('actif', true)->orderBy('nom')->get(),

            'responsableIds'        => $responsableIds,
            'responsablePrincipal'  => $responsablePrincipal,
            'suiveurIds'            => $suiveurIds,
            'tierIds'               => $tierIds,

            'statuts'    => self::STATUTS,
            'priorites'  => self::PRIORITES,
            'etatsSante' => self::ETATS_SANTE,
        ]);
    }

    /**
     * Mise à jour.
     */
    /* public function update(Request $request, OnfpActivite $activite)
    {
        $validated = $request->validate($this->rules());

        if (!$this->responsablePrincipalEstValide($validated)) {
            return back()
                ->withInput()
                ->withErrors([
                    'responsable_principal' =>
                    "Le responsable principal doit également figurer parmi les responsables.",
                ]);
        }

        DB::transaction(function () use ($validated, $activite) {

            $ancienStatut        = $activite->statut;
            $ancienneProgression = $activite->progression;
            $anciennePriorite    = $activite->priorite;

            $activite->update($this->donneesActivite($validated) + [
                'updated_by' => optional(auth()->user()->employee)->id,
            ]);

            $this->synchroniserResponsables($activite, $validated);
            $this->synchroniserSuiveurs($activite, $validated);
            $this->synchroniserTiers($activite, $validated);

            $changements = [];

            if ($ancienStatut !== $activite->statut) {
                $changements[] = "Statut : {$ancienStatut} → {$activite->statut}";
            }

            if ((int) $ancienneProgression !== (int) $activite->progression) {
                $changements[] = "Progression : {$ancienneProgression}% → {$activite->progression}%";
            }

            if ($anciennePriorite !== $activite->priorite) {
                $changements[] = "Priorité : {$anciennePriorite} → {$activite->priorite}";
            }

            if (!empty($changements)) {
                $activite->historiques()->create([
                    'employee_id'           => optional(auth()->user()->employee)->id,
                    'action'                => 'modification',
                    'ancien_statut'         => $ancienStatut,
                    'nouveau_statut'        => $activite->statut,
                    'ancienne_progression'  => $ancienneProgression,
                    'nouvelle_progression'  => $activite->progression,
                    'description'           => implode(' | ', $changements),
                ]);
            }
        });

        return redirect()
            ->route('onfp.activites.show', $activite)
            ->with('success', 'Activité mise à jour avec succès.');
    } */

    /**
     * Mise à jour.
     *
     * L'historique est désormais généré automatiquement par
     * App\Observers\OnfpActiviteObserver::updated() — plus besoin de détecter
     * les changements manuellement ici.
     */
    public function update(Request $request, OnfpActivite $activite)
    {
        $validated = $request->validate($this->rules());

        if (!$this->responsablePrincipalEstValide($validated)) {
            return back()
                ->withInput()
                ->withErrors([
                    'responsable_principal' =>
                    "Le responsable principal doit également figurer parmi les responsables.",
                ]);
        }

        DB::transaction(function () use ($validated, $activite) {

            $activite->update($this->donneesActivite($validated) + [
                'updated_by' => optional(auth()->user()->employee)->id,
            ]);

            $this->synchroniserResponsables($activite, $validated);
            $this->synchroniserSuiveurs($activite, $validated);
            $this->synchroniserTiers($activite, $validated);
        });

        $activite->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('onfp.activites.show', $activite)
            ->with('success', 'Activité mise à jour avec succès.');
    }


    /**
     * Synchronise les tiers intervenants sélectionnés dans le formulaire
     * avec la table onfp_activite_tiers.
     *
     * - Ajoute les tiers nouvellement sélectionnés (role/observation à null,
     *   à préciser ensuite depuis la page dédiée onfp.activites.tiers).
     * - Retire les associations dont le tiers a été décoché.
     * - Ne touche PAS au role/observation des tiers qui restent sélectionnés,
     *   pour ne pas écraser ce qui a été renseigné depuis la page dédiée.
     */
    private function synchroniserTiers(OnfpActivite $activite, array $validated): void
    {
        $tierIdsSelectionnes = collect($validated['tiers_intervenants'] ?? [])
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $tierIdsActuels = OnfpActiviteTiers::where('activite_id', $activite->id)
            ->pluck('tier_id')
            ->map(fn($id) => (int) $id);

        $aAjouter = $tierIdsSelectionnes->diff($tierIdsActuels);
        $aRetirer = $tierIdsActuels->diff($tierIdsSelectionnes);

        if ($aRetirer->isNotEmpty()) {
            OnfpActiviteTiers::where('activite_id', $activite->id)
                ->whereIn('tier_id', $aRetirer)
                ->delete();
        }

        foreach ($aAjouter as $tierId) {
            OnfpActiviteTiers::create([
                'activite_id' => $activite->id,
                'tier_id' => $tierId,
                'role'        => OnfpActiviteTiers::ROLE_PAR_DEFAUT,
            ]);
        }
    }

    /**
     * Suppression logique.
     */
    public function destroy(OnfpActivite $activite)
    {
        DB::transaction(function () use ($activite) {

            $activite->historiques()->create([
                'employee_id'          => optional(auth()->user()->employee)->id,
                'action'               => 'suppression',
                'ancien_statut'        => $activite->statut,
                'ancienne_progression' => $activite->progression,
                'description'          => "Suppression de l'activité.",
            ]);

            $activite->delete();
        });

        return redirect()
            ->route('onfp.activites.index')
            ->with('success', 'Activité supprimée avec succès.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers privés
    |--------------------------------------------------------------------------
    */

    /**
     * Règles de validation communes à store() et update().
     */
    private function rules(): array
    {
        return [
            'direction_id' => ['nullable', 'integer', 'exists:directions,id'],
            'type_id'      => ['nullable', 'integer', 'exists:onfp_activite_types,id'],
            'titre'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'statut'       => ['required', 'in:' . implode(',', array_keys(self::STATUTS))],
            'priorite'     => ['required', 'in:' . implode(',', array_keys(self::PRIORITES))],
            'progression'  => ['required', 'integer', 'min:0', 'max:100'],

            'date_enclenchement'    => ['nullable', 'date'],
            'date_execution_prevue' => ['nullable', 'date'],
            'date_fin_prevue'       => ['nullable', 'date', 'after_or_equal:date_execution_prevue'],
            'date_execution_reelle' => ['nullable', 'date'],
            'date_fin_reelle'       => ['nullable', 'date', 'after_or_equal:date_execution_reelle'],

            'etat_sante'  => ['required', 'in:' . implode(',', array_keys(self::ETATS_SANTE))],
            'observation' => ['nullable', 'string'],

            'responsables'           => ['nullable', 'array'],
            'responsables.*'         => ['integer', 'exists:employees,id'],
            'responsable_principal'  => ['nullable', 'integer', 'exists:employees,id'],

            'suiveurs'   => ['nullable', 'array'],
            'suiveurs.*' => ['integer', 'exists:employees,id'],

            'tiers_intervenants' => ['nullable', 'array'],
            'tiers_intervenants.*' => ['exists:onfp_tiers,id'],

            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:onfp_activite_tags,id'],
        ];
    }

    /**
     * Vérifie que le responsable principal, s'il est renseigné, figure
     * bien dans la liste des responsables.
     */
    private function responsablePrincipalEstValide(array $validated): bool
    {
        $principal    = $validated['responsable_principal'] ?? null;
        $responsables = $validated['responsables'] ?? [];

        if ($principal === null) {
            return true;
        }

        return in_array((int) $principal, array_map('intval', $responsables), true);
    }

    /**
     * Génère la référence unique d'une activité (ACT-AAAA-00001).
     */
    private function genererReference(): string
    {
        $annee  = now()->format('Y');
        $lastId = OnfpActivite::withTrashed()->max('id') ?? 0;

        return 'ACT-' . $annee . '-' . str_pad((string) ($lastId + 1), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Champs "métier" partagés entre la création et la mise à jour.
     */
    private function donneesActivite(array $validated): array
    {
        return [
            'direction_id'           => $validated['direction_id'] ?? null,
            'type_id'                => $validated['type_id'] ?? null,
            'titre'                  => $validated['titre'],
            'description'            => $validated['description'] ?? null,
            'statut'                 => $validated['statut'],
            'priorite'               => $validated['priorite'],
            'progression'            => $validated['progression'],
            /* 'date_enclenchement'     => $validated['date_enclenchement'] ?? null,
            'date_execution_prevue'  => $validated['date_execution_prevue'] ?? null,
            'date_fin_prevue'        => $validated['date_fin_prevue'] ?? null,
            'date_execution_reelle'  => $validated['date_execution_reelle'] ?? null,
            'date_fin_reelle'        => $validated['date_fin_reelle'] ?? null, */
            'date_enclenchement'     => $this->dateOuNull($validated['date_enclenchement'] ?? null),
            'date_execution_prevue'  => $this->dateOuNull($validated['date_execution_prevue'] ?? null),
            'date_fin_prevue'        => $this->dateOuNull($validated['date_fin_prevue'] ?? null),
            'date_execution_reelle'  => $this->dateOuNull($validated['date_execution_reelle'] ?? null),
            'date_fin_reelle'        => $this->dateOuNull($validated['date_fin_reelle'] ?? null),
            'etat_sante'             => $validated['etat_sante'],
            'observation'            => $validated['observation'] ?? null,
        ];
    }

    private function dateOuNull(?string $valeur): ?string
    {
        return filled($valeur) ? $valeur : null;
    }
    /**
     * Remplace la liste des responsables d'une activité.
     */
    private function synchroniserResponsables(OnfpActivite $activite, array $validated): void
    {
        OnfpActiviteResponsable::where('activite_id', $activite->id)->delete();

        foreach ($validated['responsables'] ?? [] as $employeeId) {
            $estPrincipal = $employeeId == ($validated['responsable_principal'] ?? null);

            OnfpActiviteResponsable::create([
                'activite_id'  => $activite->id,
                'employee_id'  => $employeeId,
                'role'         => $estPrincipal ? 'principal' : 'responsable',
                'is_principal' => $estPrincipal,
            ]);
        }
    }

    /**
     * Remplace la liste des suiveurs d'une activité.
     */
    private function synchroniserSuiveurs(OnfpActivite $activite, array $validated): void
    {
        OnfpActiviteSuiveur::where('activite_id', $activite->id)->delete();

        foreach ($validated['suiveurs'] ?? [] as $employeeId) {
            OnfpActiviteSuiveur::create([
                'activite_id' => $activite->id,
                'employee_id' => $employeeId,
            ]);
        }
    }
}
