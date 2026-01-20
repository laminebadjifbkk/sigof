<?php

namespace App\Http\Controllers;

use App\Exports\ExportIndividuellesStatut;
use App\Exports\ExportIndividuellesStatutQuery;
use App\Mail\ValidationDemandeIndividuelleNotification;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\File;
use App\Models\Individuelle;
use App\Models\Module;
use App\Models\Projet;
use App\Models\Region;
use App\Models\User;
use App\Models\Validationindividuelle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use ZipArchive;

class IndividuelleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Demandeur|Employe|DIOF|ADIOF|Ingenieur|AntKD|AntKL|AntSL|AntKG|AntMT|AntDL|AntZG|AntTH|CAR|DEC|DG']);
        $this->middleware("permission:individuelle-view", ["only" => ["index"]]);
    }

    public function create()
    {

        // Optimisation des requêtes pour les départements et modules
        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::select('id', 'name')->orderBy('created_at', 'DESC')->get();

        return view(
            "individuelles.create",
            compact(
                'departements',
                'modules',
            )
        );
    }

    /* public function index()
    {
        // Comptage total des individus (sans charger toutes les entrées en mémoire)
        $individuelles      = Individuelle::count();
        $totalIndividuelles = number_format($individuelles, 0, ',', ' ');

        // Récupération des 500 dernières demandes
        $individuelles = Individuelle::latest()->limit(500)->get();

        // Optimisation des requêtes pour les départements et modules
        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::select('id', 'name')->orderBy('created_at', 'DESC')->get();

        return view(
            "individuelles.index",
            compact(
                'individuelles',
                'departements',
                'totalIndividuelles',
                'modules',
            )
        );
    } */

    public function index(Request $request)
    {
        // Total global
        $total = Individuelle::count();
        $totalIndividuelles = number_format($total, 0, ',', ' ');

        // =========================
        // TABLE (max 500 lignes)
        // =========================
        $query = Individuelle::query();

        if ($statut = $request->query('statut')) {
            $query->where('statut', $statut);
        }

        $individuelles = $query
            ->latest()
            ->limit(500)
            ->get();

        // =========================
        // CARDS (SQL ONLY)
        // =========================
        /* $groupesRaw = Individuelle::select('statut')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('statut')
            ->get(); */

        $groupes = Individuelle::select(DB::raw('YEAR(date_depot) as annee'))
            ->selectRaw('COUNT(*) as total')
            ->groupBy('annee')
            ->get();

        // Reformatage pour la vue
        /* $groupes = [];
        $statutPourcentages = [];

        foreach ($groupesRaw as $row) {
            $key = $row->statut ?? 'inconnu';

            $groupes[$key] = $row->total;

            $statutPourcentages[$key] = [
                'count'   => $row->total,
                'percent' => $total
                    ? round($row->total * 100 / $total, 1)
                    : 0
            ];
        } */

        // Données annexes
        $departements = Departement::select('id', 'nom')->orderBy('nom')->get();
        $modules = Module::select('id', 'name')->latest()->get();

        return view('individuelles.index', compact(
            'individuelles',
            'departements',
            'modules',
            'totalIndividuelles',
            'groupes',
            /* 'statutPourcentages',
            'statut' */
        ));
    }

    /*  public function parAnnee(Request $request, $annee)
    {
        $query = Individuelle::whereYear('date_depot', $annee);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $individuelles = $query->latest()->limit(500)->get();

        $total = $query->count();
        $totalIndividuelles = number_format($total, 0, ',', ' ');

        // Cartes par statut pour cette année
        $groupesRaw = Individuelle::select('statut')
            ->selectRaw('COUNT(*) as total')
            ->whereYear('date_depot', $annee)
            ->groupBy('statut')
            ->get();

        $statutPourcentages = [];
        foreach ($groupesRaw as $row) {
            $key = $row->statut ?? 'inconnu';
            $statutPourcentages[$key] = [
                'count' => $row->total,
                'percent' => $total ? round($row->total * 100 / $total, 1) : 0
            ];
        }

        // Tableau des années
        $groupes = Individuelle::select(DB::raw('YEAR(date_depot) as annee'))
            ->selectRaw('COUNT(*) as total')
            ->groupBy('annee')
            ->get();

        // Données annexes
        $departements = Departement::select('id', 'nom')->orderBy('nom')->get();
        $modules = Module::select('id', 'name')->latest()->get();

        return view('individuelles.index_annee', compact(
            'individuelles',
            'departements',
            'modules',
            'statutPourcentages',
            'groupes',
            'annee',
            'totalIndividuelles'
        ));
    } */
    public function parAnnee(Request $request, $annee)
    {
        // =======================================
        // Construction de la requête de base
        // =======================================
        $query = Individuelle::whereYear('date_depot', $annee);

        // Filtre par statut si fourni
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par région si fourni
        if ($request->filled('region')) {
            $query->where('regions_id', $request->region);
        }

        // =======================================
        // Individuelles détaillées (max 500)
        // =======================================
        $individuelles = $query->latest()->limit(500)->get();

        // Total pour l'année après filtres
        $total = $query->count();
        $totalIndividuelles = number_format($total, 0, ',', ' ');

        // =======================================
        // Cartes par région pour cette année
        // =======================================
        $groupes = Individuelle::with('region')
            ->select('regions_id')
            ->selectRaw('COUNT(*) as total')
            ->whereYear('date_depot', $annee)
            ->groupBy('regions_id')
            ->get();

        // Format pour Blade : nom de région → count + pourcentage
        $regionPourcentages = [];
        foreach ($groupes as $row) {
            $regionNom = $row->region->nom ?? 'Inconnu';
            $regionPourcentages[$regionNom] = [
                'count' => $row->total,
                'percent' => $total ? round($row->total * 100 / $total, 1) : 0
            ];
        }

        // =======================================
        // Données annexes
        // =======================================
        $departements = Departement::select('id', 'nom')->orderBy('nom')->get();

        // =======================================
        // Retour vers la vue
        // =======================================
        return view('individuelles.index_annee', compact(
            'individuelles',
            'departements',
            'regionPourcentages', // cartes par région
            'groupes',           // tableau des années
            'annee',
            'totalIndividuelles'
        ));
    }

    public function parAnneeRegion(Request $request, $annee, $region)
    {
        // Région depuis le nom
        $region = Region::where('nom', $region)->firstOrFail();

        // Statut optionnel
        $statutFiltre = $request->query('statut');

        // =======================================
        // Base query (année + région)
        // =======================================
        $baseQuery = Individuelle::whereYear('date_depot', $annee)
            ->where('regions_id', $region->id);

        // =======================================
        // Total GLOBAL (sans filtre statut)
        // =======================================
        $total = $baseQuery->count();
        $totalIndividuelles = number_format($total, 0, ',', ' ');

        // =======================================
        // Totaux par statut (pour les cartes)
        // =======================================
        $groupesRegionStatut = Individuelle::select('statut')
            ->selectRaw('COUNT(*) as total')
            ->whereYear('date_depot', $annee)
            ->where('regions_id', $region->id)
            ->groupBy('statut')
            ->get();

        // Pourcentages
        $statutPourcentages = [];
        foreach ($groupesRegionStatut as $row) {
            $statut = $row->statut ?? 'Inconnu';
            $statutPourcentages[$statut] = [
                'count'   => $row->total,
                'percent' => $total ? round($row->total * 100 / $total, 1) : 0,
            ];
        }

        // =======================================
        // Liste des individuelles (FILTRÉE si statut présent)
        // =======================================
        $individuelles = collect();

        Individuelle::whereYear('date_depot', $annee)
            ->where('regions_id', $region->id)
            ->when($statutFiltre, fn($q) => $q->where('statut', $statutFiltre))
            ->orderByDesc('id')
            ->chunk(1000, function ($rows) use (&$individuelles) {
                $individuelles = $individuelles->merge($rows);
            });

        // =======================================
        // Données annexes
        // =======================================
        $regionNom = $region->nom;
        $departements = Departement::select('id', 'nom')->orderBy('nom')->get();

        return view('individuelles.index_annee_region', compact(
            'annee',
            'region',
            'regionNom',
            'totalIndividuelles',
            'statutPourcentages',
            'groupesRegionStatut',
            'individuelles',
            'departements',
            'statutFiltre'
        ));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'module'                 => ['required', 'string', 'max:250'],
            'telephone_secondaire'   => ['required', 'string', 'size:12'],
            'adresse'                => ['required', 'string', 'max:250'],
            'departement'            => ['required', 'string', 'max:250'],
            'module'                 => ['required', 'string', 'max:250'],
            'niveau_etude'           => ['required', 'string', 'max:250'],
            'diplome_academique'     => ['required', 'string', 'max:250'],
            'diplome_professionnel'  => ['required', 'string', 'max:250'],
            'projet_poste_formation' => ['required', 'string', 'max:250'],
            'projetprofessionnel'    => ['required', 'string', 'max:500'],
            'qualification'          => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::findOrFail($request->iduser);

        $individuelle_total = Individuelle::where('users_id', $user->id)
            ->where('projets_id', null)
            ->count();

        if ($individuelle_total >= 3) {
            Alert::warning('Désolé !', 'Vous avez atteint la limite autorisée de demandes.');
            return redirect()->back();
        } else {

            $anneeEnCours = date('Y');
            $an           = date('y');

            $dateString = Carbon::now()->format('d/m/Y');                 // Convertir en chaîne formatée
            $date_depot = Carbon::createFromFormat('d/m/Y', $dateString); // Parser la chaîne correctement

            // Récupérer le dernier numéro existant
            $numero_individuelle = Individuelle::join('users', 'users.id', 'individuelles.users_id')
                ->where('date_depot', 'LIKE', "{$anneeEnCours}%")
                ->orderBy('individuelles.id', 'desc')
                ->value('numero');

            if ($numero_individuelle) {
                $numero_individuelle = ++$numero_individuelle; // Incrémenter le dernier numéro
            } else {
                $numero_individuelle = 'I' . $anneeEnCours . "00001"; // Nouveau numéro
            }

            // Vérifier l'unicité du numéro
            while (Individuelle::where('numero', $numero_individuelle)->exists()) {
                // Si le numéro existe déjà, incrémenter encore plus (ajouter 1 à la partie numérique)
                $numero_individuelle = 'I' . str_pad((int) substr($numero_individuelle, 1) + 1, 6, '0', STR_PAD_LEFT);
            }

            // Normalisation avec des zéros à gauche (6 chiffres minimum après le préfixe)
            $numero_individuelle = strtoupper($numero_individuelle);

            $departement = Departement::where('nom', $request->input("departement"))->first();

            $regionid = $departement->region->id;

            $module_name = $request->input("module");
            /* $module_find = DB::table('modules')->where('name', $module_name)->first(); */
            $module = DB::table('modules')
                ->where('name', $request->input("module"))
                ->whereNull('deleted_at') // ou ->where('is_deleted', false)
                ->first();
            $demandeur_ind = Individuelle::where('users_id', $user->id)->whereHas('module', function ($query) use ($module_name) {
                $query->where('name', $module_name);
            })->first();

            if ($demandeur_ind) {
                Alert::warning('Attention !', 'Le module ' . $demandeur_ind->module->name . ' a déjà été sélectionné.');
                return redirect()->back();
            }

            /* // Si le module n'existe pas, on le crée
            if (! $module) {
                $module = new Module(['name' => $module_name]);
                $module->save();
            } else {
                $module = $module;
            } */

            // If module doesn't exist, create it
            if (! $module) {
                // Vérifier si le module existe mais est supprimé
                $module = Module::withTrashed()->where('name', $request->input("module"))->first();
                // Si le module existe mais est supprimé
                if ($module) {
                    // Restaurer le module supprimé
                    $module->restore();
                } else {
                    Alert::warning('Module introuvable', 'Le module "' . $request->input("module") . '" ne figure pas dans notre base de données.');
                    return redirect()->back();
                    $module = new Module([
                        'name' => $request->input("module"),
                    ]);
                    $module->save(); // Save the new module
                }
            }

            $individuelle = new Individuelle([
                'date_depot'                       => $date_depot,
                'numero'                           => $numero_individuelle,
                'adresse'                          => $request->input('adresse'),
                'telephone'                        => $request->input('telephone_secondaire'),
                'niveau_etude'                     => $request->input('niveau_etude'),
                'diplome_academique'               => $request->input('diplome_academique'),
                'autre_diplome_academique'         => $request->input('autre_diplome_academique'),
                'option_diplome_academique'        => $request->input('option_diplome_academique'),
                'etablissement_academique'         => $request->input('etablissement_academique'),
                'diplome_professionnel'            => $request->input('diplome_professionnel'),
                'autre_diplome_professionnel'      => $request->input('autre_diplome_professionnel'),
                'specialite_diplome_professionnel' => $request->input('specialite_diplome_professionnel'),
                'etablissement_professionnel'      => $request->input('etablissement_professionnel'),
                'projet_poste_formation'           => $request->input('projet_poste_formation'),
                'projetprofessionnel'              => $request->input('projetprofessionnel'),
                'qualification'                    => $request->input('qualification'),
                'experience'                       => $request->input('experience'),
                "departements_id"                  => $departement->id,
                "regions_id"                       => $regionid,
                "modules_id"                       => $module->id,
                'statut'                           => 'Nouvelle',
                'users_id'                         => $user->id,
            ]);

            $individuelle->save();
        }

        $individuelle->save();

        Alert::success("Succès !", "L'enregistrement a été effectué avec succès.");

        return Redirect::back();
    }
    public function individuellesStore(Request $request)
    {
        $this->validate($request, [
            'telephone_secondaire'   => ['required', 'string', 'size:12'],
            'adresse'                => ['required', 'string', 'max:250'],
            'departement'            => ['required', 'string', 'max:250'],
            'module'                 => ['required', 'string', 'max:250'],
            'niveau_etude'           => ['required', 'string', 'max:250'],
            'diplome_academique'     => ['required', 'string', 'max:250'],
            'diplome_professionnel'  => ['required', 'string', 'max:250'],
            'projet_poste_formation' => ['required', 'string', 'max:250'],
            'qualification'          => ['nullable', 'string', 'max:500'],
        ]);

        $user   = Auth::user();
        $projet = Projet::findOrFail($request?->idprojet);

        $individuelle = Individuelle::where('users_id', $user->id)
            ->where('projets_id', $request?->idprojet)
            ->count();

        if ($projet->statut == 'fermer') {
            Alert::warning('Désolé !', 'La période de dépôt est terminée.');
            return redirect()->back();
        } elseif ($individuelle >= 1) {
            Alert::warning('Désolé !', 'Vous avez atteint la limite autorisée de demandes.');
            return redirect()->back();
        } else {

            $date_depot = date('Y-m-d');

            $anneeEnCours = date('Y');
            $an           = date('y');

            // Récupérer le dernier numéro existant
            $numero_individuelle = Individuelle::join('users', 'users.id', 'individuelles.users_id')
                ->where('date_depot', 'LIKE', "{$anneeEnCours}%")
                ->orderBy('individuelles.id', 'desc')
                ->value('numero');

            if ($numero_individuelle) {
                $numero_individuelle = ++$numero_individuelle; // Incrémenter le dernier numéro
            } else {
                $numero_individuelle = 'I' . $anneeEnCours . "00001"; // Nouveau numéro
            }

            // Vérifier l'unicité du numéro
            while (Individuelle::where('numero', $numero_individuelle)->exists()) {
                // Si le numéro existe déjà, incrémenter encore plus (ajouter 1 à la partie numérique)
                $numero_individuelle = 'I' . str_pad((int) substr($numero_individuelle, 1) + 1, 6, '0', STR_PAD_LEFT);
            }

            // Normalisation avec des zéros à gauche (6 chiffres minimum après le préfixe)
            $numero_individuelle = strtoupper($numero_individuelle);

            // Récupération des données de localisation en une seule condition
            $departement_input = $request->input("departement");
            $localite_type     = $projet?->type_localite;

            // Récupérer les IDs des localités selon le type
            if (! empty($departement_input)) {
                if ($localite_type == 'Commune') {
                    $commune          = Commune::where('nom', $departement_input)->first();
                    $communeid        = $commune?->id;
                    $arrondissement   = $commune?->arrondissement;
                    $arrondissementid = $arrondissement?->id;
                    $departement      = $arrondissement?->departement;
                    $departementid    = $departement?->id;
                    $regionid         = $departement?->region?->id;
                } elseif ($localite_type == 'Arrondissement') {
                    $arrondissement   = Arrondissement::where('nom', $departement_input)->first();
                    $arrondissementid = $arrondissement?->id;
                    $departement      = $arrondissement?->departement;
                    $departementid    = $departement?->id;
                    $regionid         = $departement?->region?->id;
                } elseif ($localite_type == 'Departement' || $localite_type == 'Region') {
                    $departement      = Departement::where('nom', $departement_input)->first();
                    $departementid    = $departement?->id;
                    $regionid         = $departement?->region?->id;
                    $communeid        = null;
                    $arrondissementid = null;
                }
            } else {
                $departement      = Departement::where('nom', $departement_input)->first();
                $regionid         = $departement?->region?->id;
                $communeid        = null;
                $arrondissementid = null;
            }

            // Recherche du module
            $module_find   = DB::table('modules')->where('name', $request->input("module"))->first();
            $demandeur_ind = Individuelle::where('users_id', $user->id)->get();

            // Vérifier si le module est déjà sélectionné
            if ($module_find) {
                foreach ($demandeur_ind as $value) {
                    if ($value->module->name == $module_find->name) {
                        Alert::warning('Attention !', 'Le module ' . $value->module->name . ' a déjà été sélectionné.');
                        return redirect()->back();
                    }
                }
            } else {

                Alert::warning('Module introuvable', 'Le module "' . $request->input("module") . '" ne figure pas dans notre base de données.');
                return redirect()->back();
                $module = new Module(['name' => $request->input('module')]);
                $module->save();
                $module_find = $module; // Réassigner pour les prochaines étapes
            }

            // Création ou mise à jour de l'instance Individuelle
            $individuelle = new Individuelle([
                'date_depot'                       => $date_depot,
                'numero'                           => $numero_individuelle,
                'adresse'                          => $request->input('adresse'),
                'telephone'                        => $request->input('telephone_secondaire'),
                'niveau_etude'                     => $request->input('niveau_etude'),
                'diplome_academique'               => $request->input('diplome_academique'),
                'autre_diplome_academique'         => $request->input('autre_diplome_academique'),
                'option_diplome_academique'        => $request->input('option_diplome_academique'),
                'etablissement_academique'         => $request->input('etablissement_academique'),
                'diplome_professionnel'            => $request->input('diplome_professionnel'),
                'autre_diplome_professionnel'      => $request->input('autre_diplome_professionnel'),
                'specialite_diplome_professionnel' => $request->input('specialite_diplome_professionnel'),
                'etablissement_professionnel'      => $request->input('etablissement_professionnel'),
                'projet_poste_formation'           => $request->input('projet_poste_formation'),
                'projetprofessionnel'              => $request->input('projetprofessionnel'),
                'qualification'                    => $request->input('qualification'),
                'experience'                       => $request->input('experience'),
                "departements_id"                  => $departementid,
                "regions_id"                       => $regionid,
                "communes_id"                      => $communeid,
                "arrondissements_id"               => $arrondissementid,
                "modules_id"                       => $module_find->id,
                "projets_id"                       => $request?->idprojet,
                'autre_module'                     => $request->input('module'),
                'statut'                           => 'Nouvelle',
                'users_id'                         => $user->id,
            ]);

            $individuelle->save();
        }

        $individuelle->save();

        Alert::success("Succès !", "Enregistrement effectué avec succès.");

        return Redirect::back();
    }

    public function addIndividuelle(Request $request)
    {
        /* $this->validate($request, [
            'civilite'                  => ['required', 'string'],
            'date_depot'                => ['required', 'date', 'date_format:Y-m-d\TH:i'],
            'cin'                       => [
                'required',
                'string',
                'min:16',
                'max:17',
                Rule::unique('users')->whereNull('deleted_at'), // Ignore les utilisateurs supprimés
            ],
            'email'                     => [
                'required',
                'string',
                'email',
                'max:250',
                Rule::unique('users')->whereNull('deleted_at'), // Ignore les utilisateurs supprimés
            ],
            'firstname'                 => ['required', 'string', 'max:50'],
            'lastname'                  => ['required', 'string', 'max:25'],
            'telephone'                 => ['required', 'string', 'size:12'],
            'telephone_secondaire'      => ['nullable', 'string', 'size:12'],
            'date_naissance'            => ['required', 'date_format:d/m/Y'],
            'lieu_naissance'            => ['required', 'string'],
            'adresse'                   => ['required', 'string', 'max:250'],
            'departement'               => ['required', 'string', 'max:250'],
            'module'                    => ['required', 'string', 'max:250'],
            'situation_professionnelle' => ['nullable', 'string', 'max:250'],
            'situation_familiale'       => ['nullable', 'string', 'max:250'],
            'niveau_etude'              => ['required', 'string', 'max:250'],
            'diplome_academique'        => ['required', 'string', 'max:250'],
            'diplome_professionnel'     => ['required', 'string', 'max:250'],
            'projet_poste_formation'    => ['required', 'string', 'max:250'],
            'qualification'             => ['nullable', 'string', 'max:200'],
        ]); */

        $validator = Validator::make($request->all(), [
            'civilite'                  => ['required', 'string'],
            'date_depot'                => ['required', 'date', 'date_format:Y-m-d\TH:i'],
            'type_piece'                => ['required', 'string', 'in:cni,extrait,passeport'],
            'cin'                       => ['required', 'string', Rule::unique('users')->whereNull('deleted_at')],
            'email'                     => ['required', 'email', 'max:250', Rule::unique('users')->whereNull('deleted_at')],
            'firstname'                 => ['required', 'string', 'max:50'],
            'lastname'                  => ['required', 'string', 'max:25'],
            'telephone'                 => ['required', 'string', 'size:12'],
            'telephone_secondaire'      => ['nullable', 'string', 'size:12'],
            'date_naissance'            => ['required', 'date_format:d/m/Y'],
            'lieu_naissance'            => ['required', 'string'],
            'adresse'                   => ['required', 'string', 'max:250'],
            'departement'               => ['required', 'string', 'max:250'],
            'module'                    => ['required', 'string', 'max:250'],
            'situation_professionnelle' => ['nullable', 'string', 'max:250'],
            'situation_familiale'       => ['nullable', 'string', 'max:250'],
            'niveau_etude'              => ['required', 'string', 'max:250'],
            'diplome_academique'        => ['required', 'string', 'max:250'],
            'diplome_professionnel'     => ['required', 'string', 'max:250'],
            'projet_poste_formation'    => ['required', 'string', 'max:250'],
            'qualification'             => ['nullable', 'string', 'max:200'],
        ]);

        // Validation conditionnelle
        $validator->sometimes('cin', ['digits:5'], function ($input) {
            return $input->type_piece === 'extrait';
        });

        $validator->sometimes('cin', ['digits:9'], function ($input) {
            return $input->type_piece === 'passeport';
        });

        $validator->sometimes('cin', ['digits_between:13,14'], function ($input) {
            return $input->type_piece === 'cni';
        });

        // Validation finale
        $data = $validator->validate();

        // Formatage CIN uniquement si nécessaire
        if ($data['type_piece'] === 'cni') {
            $data['cin'] = $this->formatCin($data['cin']);
        }

        // Utilisation des données validées
        $cin = $data['cin'] ?? null;
        $date_input = $data['date_depot'] ?? null;

        if ($date_input) {
            $date = Carbon::parse($date_input);

            // Vérifier si l'heure est absente (si la date est envoyée seule)
            if ($date->hour == 0 && $date->minute == 0 && $date->second == 0) {
                $date->setTime(now()->hour, now()->minute, now()->second); // Prend l'heure actuelle
            }

            $date_depot = $date->format('Y-m-d H:i:s');
        } else {
            $date_depot = null;
        }

        $anneeEnCours = date('Y');
        $an           = date('y');

        // Récupérer le dernier numéro existant
        $numero_individuelle = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->where('date_depot', 'LIKE', "{$anneeEnCours}%")
            ->orderBy('individuelles.id', 'desc')
            ->value('numero');

        if ($numero_individuelle) {
            $numero_individuelle = ++$numero_individuelle; // Incrémenter le dernier numéro
        } else {
            $numero_individuelle = 'I' . $anneeEnCours . "00001"; // Nouveau numéro
        }

        // Vérifier l'unicité du numéro
        while (Individuelle::where('numero', $numero_individuelle)->exists()) {
            // Si le numéro existe déjà, incrémenter encore plus (ajouter 1 à la partie numérique)
            $numero_individuelle = 'I' . str_pad((int) substr($numero_individuelle, 1) + 1, 6, '0', STR_PAD_LEFT);
        }

        // Normalisation avec des zéros à gauche (6 chiffres minimum après le préfixe)
        $numero_individuelle = strtoupper($numero_individuelle);

        // Récupérer le département et la région
        $departement = Departement::where('nom', $request->input("departement"))->first();
        $regionid    = $departement->region->id;

        /* // Récupérer le module ou en créer un nouveau
        $module = DB::table('modules')->where('name', $request->input("module"))->first();

        if (! $module) {

            $module = new Module(['name' => $request->input('module')]);

            $module->save();
        } */

        /* $module = DB::table('modules')
            ->where('name', $request->input("module"))
            ->whereNull('deleted_at') // ou ->where('is_deleted', false)
            ->first();

        // If module doesn't exist, create it
        if (! $module) {
            // Vérifier si le module existe mais est supprimé
            $module = Module::withTrashed()->where('name', $request->input("module"))->first();
            // Si le module existe mais est supprimé
            if ($module) {
                // Restaurer le module supprimé
                $module->restore();
            } else {

                Alert::warning('Module introuvable', 'Le module "' . $request->input("module") . '" ne figure pas dans notre base de données.');
                return redirect()->back();
                $module = new Module([
                    'name' => $request->input("module"),
                ]);
                $module->save(); // Save the new module
            }
        } */

        $moduleName = $request->input("module");

        $module = Module::withTrashed()->firstOrCreate(
            ['name' => $moduleName],
            ['deleted_at' => null] // s’il était supprimé, il sera restauré
        );

        if ($module->trashed()) {
            $module->restore(); // restaurer si supprimé
        }


        // Formatage de la date de naissance
        $date_naissance = Carbon::createFromFormat('d/m/Y', $request->input('date_naissance'));

        // Création de l'utilisateur
        $user = User::create([
            'civilite'                  => $request->input('civilite'),
            'cin'                       => $cin,
            'firstname'                 => format_proper_name($request->input('firstname')),
            'name'                      => remove_accents_uppercase($request->input('lastname')),
            'date_naissance'            => $date_naissance,
            'lieu_naissance'            => remove_accents_uppercase($request->input('lieu_naissance')),
            'email'                     => $request->input('email'),
            'telephone'                 => $request->input('telephone'),
            'telephone_secondaire'      => $request->input('telephone_secondaire'),
            'situation_familiale'       => $request->input('situation_familiale'),
            'situation_professionnelle' => $request->input('situation_professionnelle'),
            'adresse'                   => $request->input('adresse'),
            'password'                  => Hash::make($request->email),
        ]);

        // Mise à jour du nom d'utilisateur et assignation du rôle
        $user->update(['username' => $request->input('lastname') . '' . $user->id]);
        $user->assignRole('Demandeur');

        // Création de l'individuelle
        $individuelleData = [
            'date_depot'                       => $date_depot,
            'numero'                           => $numero_individuelle,
            'telephone'                        => $request->input('telephone_secondaire'),
            'niveau_etude'                     => $request->input('niveau_etude'),
            'diplome_academique'               => $request->input('diplome_academique'),
            'autre_diplome_academique'         => $request->input('autre_diplome_academique'),
            'option_diplome_academique'        => $request->input('option_diplome_academique'),
            'etablissement_academique'         => $request->input('etablissement_academique'),
            'diplome_professionnel'            => $request->input('diplome_professionnel'),
            'autre_diplome_professionnel'      => $request->input('autre_diplome_professionnel'),
            'specialite_diplome_professionnel' => $request->input('specialite_diplome_professionnel'),
            'etablissement_professionnel'      => $request->input('etablissement_professionnel'),
            'projet_poste_formation'           => $request->input('projet_poste_formation'),
            'projetprofessionnel'              => $request->input('projetprofessionnel'),
            'qualification'                    => $request->input('qualification'),
            'experience'                       => $request->input('experience'),
            'adresse'                          => $request->input('adresse'),
            'departements_id'                  => $departement->id,
            'regions_id'                       => $regionid,
            'modules_id'                       => $module->id,
            'statut'                           => 'Nouvelle',
            'users_id'                         => $user->id,
        ];

        $individuelle = new Individuelle($individuelleData);
        $individuelle->save();

        // Gestion des fichiers associés à l'utilisateur
        File::where('users_id', null)->distinct()->get()->each(function ($file) use ($user) {
            File::create([
                'legende'  => $file->legende,
                'sigle'    => $file->sigle,
                'users_id' => $user->id,
            ]);
        });

        Alert::success("Succès !", "Enregistrement effectué avec succès.");

        return redirect()->back();
    }

    private function formatCin(string $cin): string
    {
        // Supprimer tous les espaces
        $cin = preg_replace('/\s+/', '', $cin);

        if (strlen($cin) === 13) {
            // 1 099 2002 00085
            return substr($cin, 0, 1) . ' '
                . substr($cin, 1, 3) . ' '
                . substr($cin, 4, 4) . ' '
                . substr($cin, 8, 5);
        }

        if (strlen($cin) === 14) {
            // même logique mais 6 derniers chiffres collés
            return substr($cin, 0, 1) . ' '
                . substr($cin, 1, 3) . ' '
                . substr($cin, 4, 4) . ' '
                . substr($cin, 8, 6);
        }

        return $cin; // sécurité
    }

    public function edit(Individuelle $individuelle)
    {
        // Récupérer l'individuelle et les données nécessaires
        /* $individuelle = Individuelle::findOrFail($id); */
        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::latest()->get(); // Même pour les modules
        $projets      = Projet::latest()->get(); // Même pour les projets

        // Vérification des rôles de l'utilisateur de manière simplifiée
        $roleNames       = Auth::user()->roles->pluck('name')->toArray();
        $restrictedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'ADIOF', 'Ingenieur', 'DEC'];

        // Si l'utilisateur a un rôle restreint, on autorise l'action
        if (! empty(array_diff($roleNames, $restrictedRoles))) {
            $this->authorize('update', $individuelle);
        }

        // Vérification des conditions de modification
        /* if ($individuelle->projet && $individuelle->projet->statut !== 'ouvert') {
            Alert::warning('Avertissement !', 'La modification a échoué.');
            return redirect()->back();
        } elseif ($individuelle->statut !== 'Nouvelle' && in_array('Demandeur', $roleNames)) {
            Alert::warning('Attention ! ', 'Action impossible, demande déjà traitée.');
            return redirect()->back();
        } */

        if (! empty(array_diff($roleNames, $restrictedRoles))) {
            $this->authorize('update', $individuelle);
            // Vérification des conditions de modification
            if ($individuelle->projet && $individuelle->projet->statut !== 'ouvert') {
                Alert::warning('Avertissement !', 'Action impossible, la modification a échoué.');
                return redirect()->back();
            } else
            if ($individuelle->statut !== 'Nouvelle' && in_array('Demandeur', $roleNames)) {
                Alert::warning('Attention ! ', 'Action impossible, demande déjà traitée.');
                return redirect()->back();
            }
        }

        // Retourner la vue si toutes les conditions sont remplies
        return view('individuelles.update', compact('individuelle', 'departements', 'modules', 'projets'));
    }

    public function update(Request $request, Individuelle $individuelle)
    {
        /* $individuelle = Individuelle::findOrFail($id); */
        $user_id = $individuelle?->users_id;

        // Role authorization check
        $this->authorizeRoles(Auth::user()->roles, $individuelle);

        // Validate the request
        $this->validateRequest($request);

        // Convertir la date de dépôt depuis la requête
        $date_depot = $this->parseDate($request->input('date_depot'));

        // Vérifier si la date_depot existe déjà dans la base de données
        if ($individuelle->date_depot) {
            // Si la date de dépôt déjà enregistrée est égale à celle de la requête
            if ($individuelle->date_depot->isSameDay($date_depot)) {
                // Si c'est le même jour, ne rien faire (ne pas modifier la date_depot)
                $date_depot = $individuelle->date_depot;
            } else {
                // Si c'est un autre jour, mettre à jour la date_depot
                $date_depot = $this->parseDate($request->input('date_depot'));
            }
        } else {
            // Si la date_depot n'est pas définie, la définir à celle de la requête
            $date_depot = $this->parseDate($request->input('date_depot'));
        }

        // Get project details
        $projet   = Projet::where('sigle', $request->input("projet"))->first();
        $projetid = $projet?->id;

        // Determine location details based on 'departement' and 'type_localite'
        list($communeid, $arrondissementid, $departementid, $regionid) = $this->getLocationIds($request, $projet);

        $module_find = DB::table('modules')
            ->where('name', $request->input("module"))
            ->whereNull('deleted_at') // ou ->where('is_deleted', false)
            ->first();

        // If module doesn't exist, create it
        if (! $module_find) {
            // Vérifier si le module existe mais est supprimé
            $module = Module::withTrashed()->where('name', $request->input("module"))->first();
            // Si le module existe mais est supprimé
            if ($module) {
                // Restaurer le module supprimé
                $module->restore();
            } else {

                Alert::warning('Module introuvable', 'Le module "' . $request->input("module") . '" ne figure pas dans notre base de données.');
                return redirect()->back();
                $module = new Module([
                    'name' => $request->input("module"),
                ]);
                $module->save(); // Save the new module
            }
            $module_find = $module; // Now $module_find will contain the newly created module
        }

        // Get all individual requests from the authenticated user
        $demandeur_ind = Individuelle::where('users_id', Auth::user()->id)
            ->where('id', '!=', $individuelle->id) // Ignore the current record
            ->get();

        // Check if the module is already assigned
        if ($this->isModuleAlreadyAssigned($demandeur_ind, $module_find)) {
            Alert::warning('Désolé !', 'Le module ' . $module_find->name . ' a déjà été choisi');
            return redirect()->back();
        }

        // Update or create module
        $module = $module_find ?? Module::create(['name' => $request->input('module')]);

        // Update Individuelle
        $this->updateIndividuelle($individuelle, $request, $date_depot, $departementid, $regionid, $communeid, $arrondissementid, $projetid, $module->id, $user_id);

        Alert::success('Succès !', 'La demande a été modifiée avec succès.');
        return Redirect::back();
    }

    private function authorizeRoles($roles, $individuelle)
    {
        foreach ($roles as $role) {
            if (! empty($role->name) && ! in_array($role->name, ['super-admin', 'Employe', 'admin', 'DIOF', 'ADIOF', 'Ingenieur', 'DEC'])) {
                $this->authorize('update', $individuelle);
            }
        }
    }

    private function validateRequest($request)
    {
        $this->validate($request, [
            'date_depot'             => ['nullable', 'date_format:d/m/Y'],
            'telephone_secondaire'   => ['required', 'string', 'size:12'],
            'adresse'                => ['required', 'string', 'max:250'],
            'localite'               => ['required', 'string', 'max:250'],
            'module'                 => ['required', 'string', 'max:250'],
            'niveau_etude'           => ['required', 'string', 'max:250'],
            'diplome_academique'     => ['required', 'string', 'max:250'],
            'diplome_professionnel'  => ['required', 'string', 'max:250'],
            'projet_poste_formation' => ['required', 'string', 'max:250'],
            'projetprofessionnel'    => ['required', 'string', 'max:500'],
            'qualification'          => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function parseDate($dateString)
    {
        return ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;
    }

    private function getLocationIds($request, $projet)
    {
        $communeid = $arrondissementid = $departementid = $regionid = null;

        if (! empty($request->input("localite"))) {
            $departement = Departement::where('nom', $request->input("localite"))->first();
            if ($projet?->type_localite == 'Commune') {
                $commune          = Commune::where('nom', $request->input("localite"))->first();
                $communeid        = $commune?->id;
                $arrondissementid = $commune?->arrondissement?->id;
                $departementid    = $commune?->arrondissement?->departement?->id;
                $regionid         = $commune?->arrondissement?->departement?->region?->id;
            } elseif ($projet?->type_localite == 'Arrondissement') {
                $arrondissement   = Arrondissement::where('nom', $request->input("localite"))->first();
                $arrondissementid = $arrondissement?->id;
                $departementid    = $arrondissement?->departement?->id;
                $regionid         = $arrondissement?->departement?->region?->id;
            } else {
                $departementid = $departement?->id;
                $regionid      = $departement?->region?->id;
            }
        }

        return [$communeid, $arrondissementid, $departementid, $regionid];
    }

    private function isModuleAlreadyAssigned($demandeur_ind, $module_find)
    {
        foreach ($demandeur_ind as $value) {
            if ($value->module->name == $module_find->name) {
                return true;
            }
        }
        return false;
    }

    private function updateIndividuelle($individuelle, $request, $date_depot, $departementid, $regionid, $communeid, $arrondissementid, $projetid, $module_id, $user_id)
    {
        $individuelle->update([
            'date_depot'                       => $date_depot,
            'niveau_etude'                     => $request->input('niveau_etude'),
            'telephone'                        => $request->input('telephone_secondaire'),
            'diplome_academique'               => $request->input('diplome_academique'),
            'autre_diplome_academique'         => $request->input('autre_diplome_academique'),
            'option_diplome_academique'        => $request->input('option_diplome_academique'),
            'etablissement_academique'         => $request->input('etablissement_academique'),
            'diplome_professionnel'            => $request->input('diplome_professionnel'),
            'autre_diplome_professionnel'      => $request->input('autre_diplome_professionnel'),
            'specialite_diplome_professionnel' => $request->input('specialite_diplome_professionnel'),
            'etablissement_professionnel'      => $request->input('etablissement_professionnel'),
            'projet_poste_formation'           => $request->input('projet_poste_formation'),
            'projetprofessionnel'              => $request->input('projetprofessionnel'),
            'qualification'                    => $request->input('qualification'),
            /* 'numero'                           => $request->input('numero'), */
            'adresse'                          => $request->input('adresse'),
            'experience'                       => $request->input('experience'),
            "departements_id"                  => $departementid,
            "regions_id"                       => $regionid,
            "communes_id"                      => $communeid,
            "arrondissements_id"               => $arrondissementid,
            "projets_id"                       => $projetid,
            "modules_id"                       => $module_id,
            'users_id'                         => $user_id,
        ]);
    }

    public function show(Individuelle $individuelle)
    {
        /* $individuelle = Individuelle::findOrFail($id); */

        $userRoles = Auth::user()->roles->pluck('name')->toArray();

        $excludedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'ADIOF', 'Ingenieur', 'DEC', 'AntKD', 'AntKL', 'AntSL', 'AntKG', 'AntMT', 'AntDL', 'AntZG', 'AntTH', 'CAR', 'DG'];

        if (! empty(array_diff($userRoles, $excludedRoles))) {
            $this->authorize('show', $individuelle);
        }

        $files = File::where('users_id', $individuelle->user->id)
            ->whereNotNull('file') // Utilisation de whereNotNull pour plus de clarté
            ->distinct()
            ->get();

        /* $user_files = File::where('users_id', $individuelle->user->id)
            ->whereNull('file')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC'])
            ->distinct()
            ->get(); */

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC', 'Titre', 'Contrat', 'Convention', 'Organigramme', 'Quitus', 'Carte', 'Casier', 'Assurance', 'Lettre', 'Bail', 'RIB', 'Domicile', 'Justificatif', 'Non-fonctionnaire'])
            ->orderBy('sigle', 'asc')
            ->get()
            ->unique('sigle') // Évite les doublons sur le champ "sigle"
            ->values();       // Réindexe proprement la collection (0, 1, 2, ...)

        return view("individuelles.show", compact("individuelle", "files", "user_files"));
    }

    public function rejeterIndividuelle(Request $request)
    {
        $request->validate([
            'motif' => 'required',
            'string',
        ]);

        $individuelle         = Individuelle::findOrFail($request->input('id'));
        $individuelle->numero = $request->input('motif');
        $individuelle->save();

        Alert::success('Opération réussie!', 'La région a été modifiée avec succès.');
        return redirect()->route('modal');
    }

    public function destroy(Individuelle $individuelle)
    {
        /* $individuelle  = Individuelle::findOrFail($id); */
        $userRoles     = Auth::user()->roles->pluck('name')->toArray();
        $excludedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'ADIOF', 'Ingenieur', 'DEC'];

        // Vérifier si l'utilisateur n'a pas un rôle exclu avant d'autoriser la suppression
        if (! empty(array_diff($userRoles, $excludedRoles))) {
            $this->authorize('delete', $individuelle);
        }

        // Vérifier si la demande est déjà traitée
        if ($individuelle->statut !== 'Nouvelle' && ! in_array('super-admin', $userRoles)) {
            Alert::warning('Attention !', 'Action impossible, cette demande déjà traitée.');
            return redirect()->back();
        }

        // Mise à jour et suppression de la demande
        $individuelle->update(['numero' => $individuelle->numero . '/' . $individuelle->id]);
        $individuelle->delete();

        Alert::success('Succès !', 'La demande a été supprimée avec succès.');
        return redirect()->back();
    }

    public function validationsRejetMessage(Request $request)
    {
        /* $individuelle = Individuelle::findOrFail($request?->input('id')); */
        $individuelle = Individuelle::with(['validationindividuelles' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($request->input('id'));

        return view("individuelles.validationsrejetmessage", compact('individuelle'));
    }

    public function demandesIndividuelle(Request $request)
    {
        // Récupérer les départements et modules une seule fois
        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy('created_at', 'desc')->get();

        // Récupérer l'utilisateur actuel
        $user = Auth::user();

        // Récupérer les individuelles pour cet utilisateur, filtrées en une seule requête
        $individuelles = Individuelle::where('users_id', $user->id)
            ->whereNotNull('numero')  // Remplacé par whereNotNull pour plus de clarté
            ->whereNull('projets_id') // Simplification
            ->orderBy('created_at', 'asc')
            ->get();

        // Récupérer les fichiers associés à l'utilisateur
        $files = File::where('users_id', $user->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();

        /* $user_files = File::where('users_id', $user?->id)
            ->whereNull('file')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC', 'Permis'])
            ->distinct()
            ->get(); */

        /* $user_files = File::whereNull('file')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC', 'Titre', 'Contrat', 'Convention', 'Organigramme', 'Quitus', 'Carte', 'Casier', 'Assurance', 'Lettre'])
            ->where(function ($query) use ($user) {
                $query->where('users_id', $user?->id)
                    ->orWhereNull('users_id');
            })
            ->orderBy('sigle')
            ->distinct()
            ->get(); */

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC', 'Titre', 'Contrat', 'Convention', 'Organigramme', 'Quitus', 'Carte', 'Casier', 'Assurance', 'Lettre', 'Bail', 'RIB', 'Domicile', 'Justificatif', 'Non-fonctionnaire'])
            ->orderBy('sigle', 'asc')
            ->get()
            ->unique('sigle') // Évite les doublons sur le champ "sigle"
            ->values();       // Réindexe proprement la collection (0, 1, 2, ...)

        // Calcul du nombre d'individuelles
        $individuelle_total = $individuelles->count();

        // Passer directement à la vue sans duplication de code
        $viewData = compact(
            'individuelle_total',
            'departements',
            'individuelles',
            'files',
            'user_files',
            'user',
            'modules'
        );

        // Renvoi de la vue en fonction du nombre d'individuelles
        $view = $individuelle_total == 0
            ? 'individuelles.show-individuelle-aucune'
            : 'individuelles.show-individuelle';

        return view($view, $viewData);
    }

    public function demandesProjet(Request $request)
    {
        $userIndividuellesAvecProjet = Auth::user()->individuelles->whereNotNull('projets_id')->sortByDesc('created_at'); // Trie du plus récent au plus ancien
        $count                       = Auth::user()->individuelles->whereNotNull('projets_id')->count();

        // Passer directement à la vue sans duplication de code
        $viewData = compact(
            'userIndividuellesAvecProjet',
            'count'
        );

        // Renvoi de la vue en fonction du nombre d'individuelles
        $view = 'individuelles.demandesprojets';

        return view($view, $viewData);
    }

    public function rapports(Request $request)
    {
        $title = 'rapports demandes individuelles';
        return view('individuelles.rapports', compact(
            'title'
        ));
    }

    public function generateRapport(Request $request)
    {

        $this->validate($request, [
            'from_date' => 'required|date',
            'to_date'   => 'required|date',
        ]);

        $now = Carbon::now()->format('H:i:s');

        $from_date = date_format(date_create($request->from_date), 'd/m/Y');

        $to_date = date_format(date_create($request->to_date), 'd/m/Y');

        $individuelles = Individuelle::whereBetween(DB::raw('DATE(created_at)'), [$request->from_date, $request->to_date])->get();

        $count = $individuelles->count();

        if ($from_date == $to_date) {
            if (isset($count) && $count < "1") {
                $title = 'aucune demande individuelle reçue le ' . $from_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' demande individuelle reçue le ' . $from_date;
            } else {
                $title = $count . ' demandes individuelles reçues le ' . $from_date;
            }
        } else {
            if (isset($count) && $count < "1") {
                $title = 'aucune demande individuelle reçue entre le ' . $from_date . ' et le ' . $to_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' demande individuelle reçue entre le ' . $from_date . ' et le ' . $to_date;
            } else {
                $title = $count . ' demandes individuelles reçues entre le ' . $from_date . ' et le ' . $to_date;
            }
        }

        return view('individuelles.rapports', compact(
            'individuelles',
            'from_date',
            'to_date',
            'title'
        ));
    }

    public function generateReport(Request $request)
    {
        $this->validate($request, [
            'cin'       => 'nullable|string',
            'name'      => 'nullable|string',
            'firstname' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email'     => 'nullable|email',
        ]);

        if ($request?->cin == null && $request->firstname == null && $request->telephone == null && $request->name == null && $request->email == null) {
            Alert::warning('Attention', 'Veuillez renseigner au moins un champ pour effectuer une recherche.');
            return redirect()->back();
        }

        $individuelles = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('users.firstname', 'LIKE', "%{$request?->firstname}%")
            ->where('users.name', 'LIKE', "%{$request?->name}%")
            ->where('users.cin', 'LIKE', "%{$request?->cin}%")
            ->where('users.telephone', 'LIKE', "%{$request?->telephone}%")
            ->where('users.email', 'LIKE', "%{$request?->email}%")
            ->distinct()
            ->get();

        /* $count = $individuelles?->count(); */

        /* if (isset($count) && $count < "1") {
            $title = 'aucune demande trouvée';
        } elseif (isset($count) && $count == "1") {
            $title = $count . ' demande trouvée';
        } else {
            $title = $count . ' demandes trouvées';
        } */

        $totalIndividuelles = number_format($individuelles?->count(), 0, ',', ' ');

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        /* $modules = Module::orderBy("created_at", "desc")->get(); */
        return view('individuelles.index', compact(
            'individuelles',
            'departements',
            'totalIndividuelles',
            /* 'title' */
        ));
    }

    public function showIndividuelleProjet(Request $request)
    {
        $projet = Projet::findOrFail($request->idprojet);
        $user   = Auth::user();

        $individuelle_total = Individuelle::where('users_id', $user->id)
            ->where('projets_id', $request->idprojet)
            ->count();

        if ($individuelle_total >= 1) {
            Alert::warning('Désolé !', 'Vous avez atteint la limite autorisée de demandes.');
            return redirect()->back();
        }

        $module_name = $request->input("module");
        $module      = DB::table('modules')
            ->where('name', $request->input("module"))
            ->whereNull('deleted_at') // ou ->where('is_deleted', false)
            ->first();
        $demandeur_ind = Individuelle::where('users_id', $user->id)->whereHas('module', function ($query) use ($module_name) {
            $query->where('name', $module_name);
        })->first();

        if ($demandeur_ind) {
            Alert::warning('Attention !', 'Le module ' . $demandeur_ind->module->name . ' a déjà été sélectionné.');
            return redirect()->back();
        }

        $this->validate($request, [
            'telephone_secondaire'   => ['required', 'string', 'size:12'],
            'adresse'                => ['required', 'string', 'max:250'],
            'departement'            => ['required', 'string', 'max:250'],
            /* 'module'                 => ['required', 'string', 'max:250'], */
            'niveau_etude'           => ['required', 'string', 'max:250'],
            'diplome_academique'     => ['required', 'string', 'max:250'],
            'diplome_professionnel'  => ['required', 'string', 'max:250'],
            'projet_poste_formation' => ['required', 'string'],
            'projetprofessionnel'    => ['required', 'string', 'max:500'],
            'qualification'          => ['nullable', 'string', 'max:500'],
        ]);

        $individuelle_total = Individuelle::where('users_id', $user->id)->where('projets_id', $projet->id)
            ->count();

        if ($individuelle_total >= 3) {
            Alert::warning('Désolé !', 'Vous avez atteint la limite autorisée de demandes.');
            return redirect()->back();
        } else {

            $date_depot = date('Y-m-d');

            $anneeEnCours = date('Y');
            $an           = date('y');

            // Récupérer le dernier numéro existant
            $numero_individuelle = Individuelle::join('users', 'users.id', 'individuelles.users_id')
                ->where('date_depot', 'LIKE', "{$anneeEnCours}%")
                ->orderBy('individuelles.id', 'desc')
                ->value('numero');

            if ($numero_individuelle) {
                $numero_individuelle = ++$numero_individuelle; // Incrémenter le dernier numéro
            } else {
                $numero_individuelle = 'I' . $anneeEnCours . "00001"; // Nouveau numéro
            }

            // Vérifier l'unicité du numéro
            while (Individuelle::where('numero', $numero_individuelle)->exists()) {
                // Si le numéro existe déjà, incrémenter encore plus (ajouter 1 à la partie numérique)
                $numero_individuelle = 'I' . str_pad((int) substr($numero_individuelle, 1) + 1, 6, '0', STR_PAD_LEFT);
            }

            // Normalisation avec des zéros à gauche (6 chiffres minimum après le préfixe)
            $numero_individuelle = strtoupper($numero_individuelle);

            if (! empty($request->input("departement")) && $projet?->type_localite == 'Commune') {
                $commune          = Commune::where('nom', $request->input("departement"))->first();
                $communeid        = $commune?->id;
                $arrondissement   = $commune?->arrondissement;
                $arrondissementid = $commune?->arrondissement?->id;
                $departement      = $commune?->arrondissement?->departement;
                $departementid    = $commune?->arrondissement?->departement?->id;
                $regionid         = $commune?->arrondissement?->departement?->region?->id;
            } elseif (! empty($request->input("departement")) && $projet?->type_localite == 'Arrondissement') {
                $communeid        = null;
                $arrondissement   = Arrondissement::where('nom', $request->input("departement"))->first();
                $arrondissementid = $arrondissement?->id;
                $departement      = $arrondissement?->departement;
                $departementid    = $arrondissement?->departement?->id;
                $regionid         = $arrondissement?->departement?->region?->id;
            } elseif (! empty($request->input("departement")) && $projet?->type_localite == 'Departement') {
                $communeid        = null;
                $arrondissementid = null;
                $departement      = Departement::where('nom', $request->input("departement"))->first();
                $departementid    = $departement?->id;
                $regionid         = $departement?->region?->id;
            } elseif (! empty($request->input("departement")) && $projet?->type_localite == 'Region') {
                $communeid        = null;
                $arrondissementid = null;
                $departement      = Departement::where('nom', $request->input("departement"))->first();
                $departementid    = $departement?->id;
                $regionid         = $departement?->region?->id;
            } else {
                $departement = Departement::where('nom', $request->input("departement"))->first();
                $regionid    = $departement?->region?->id;
            }

            // Chercher si le module existe déjà dans la base de données
            $module_find = DB::table('modules')->where('name', $request->input('module'))->first();

            // Créer un tableau des données communes pour l'Individuelle
            $individuelleData = [
                'date_depot'                       => $date_depot,
                'numero'                           => $numero_individuelle,
                'adresse'                          => $request->input('adresse'),
                'telephone'                        => $request->input('telephone_secondaire'),
                'niveau_etude'                     => $request->input('niveau_etude'),
                'diplome_academique'               => $request->input('diplome_academique'),
                'autre_diplome_academique'         => $request->input('autre_diplome_academique'),
                'option_diplome_academique'        => $request->input('option_diplome_academique'),
                'etablissement_academique'         => $request->input('etablissement_academique'),
                'diplome_professionnel'            => $request->input('diplome_professionnel'),
                'autre_diplome_professionnel'      => $request->input('autre_diplome_professionnel'),
                'specialite_diplome_professionnel' => $request->input('specialite_diplome_professionnel'),
                'etablissement_professionnel'      => $request->input('etablissement_professionnel'),
                'projet_poste_formation'           => $request->input('projet_poste_formation'),
                'projetprofessionnel'              => $request->input('projetprofessionnel'),
                'qualification'                    => $request->input('qualification'),
                'experience'                       => $request->input('experience'),
                'autre_module'                     => $request->input('module'),
                'communes_id'                      => $communeid,
                'arrondissements_id'               => $arrondissementid,
                'departements_id'                  => $departementid,
                'regions_id'                       => $regionid,
                'statut'                           => 'Nouvelle',
                'users_id'                         => $user->id,
                'projets_id'                       => $projet->id,
            ];

            // Vérifier si le module existe et l'utiliser
            if (isset($module_find)) {
                // Créer l'Individuelle avec le module trouvé
                $individuelleData['modules_id'] = $module_find->id;
                $individuelle                   = new Individuelle($individuelleData);
            } else {
                // Si le module n'existe pas, le créer
                $module = Module::create([
                    'name' => $request->input('module'),
                ]);

                // Ajouter l'ID du module créé
                $individuelleData['modules_id'] = $module->id;
                $individuelle                   = new Individuelle($individuelleData);
            }
        }

        $individuelle->save();

        Alert::success("Succès !", "L'enregistrement a été effectué avec succès.");

        return Redirect::back();
    }

    public function showMasculin()
    {
        $individuelles = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('users.civilite', 'M.')
            ->limit(200)
            ->latest()
            ->get();

        $total_count = Individuelle::get();
        $total_count = number_format($total_count->count(), 0, ',', ' ');

        $count_demandeur = number_format($individuelles?->count(), 0, ',', ' ');

        if ($count_demandeur < "1") {
            $title = 'Aucune demande individuelle masculine';
        } elseif ($count_demandeur == "1") {
            $title = $count_demandeur . ' demande individuelle masculine sur un total de ' . $total_count;
        } else {
            $title = 'Liste des ' . $count_demandeur . ' dernières demandes individuelles masculines sur un total de ' . $total_count;
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "desc")->get();

        return view(
            "individuelles.masculin",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function showFeminin()
    {
        $individuelles = Individuelle::join('users', 'users.id', 'individuelles.users_id')
            ->select('individuelles.*')
            ->where('users.civilite', 'Mme')
            ->limit(200)
            ->latest()
            ->get();

        $total_count = Individuelle::get();
        $total_count = number_format($total_count->count(), 0, ',', ' ');

        $count_demandeur = number_format($individuelles?->count(), 0, ',', ' ');

        if ($count_demandeur < "1") {
            $title = 'Aucune demande individuelle féminine';
        } elseif ($count_demandeur == "1") {
            $title = $count_demandeur . ' demande individuelle féminine sur un total de ' . $total_count;
        } else {
            $title = 'Liste des ' . $count_demandeur . ' dernières demandes individuelles féminines sur un total de ' . $total_count;
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "desc")->get();

        return view(
            "individuelles.feminin",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function mesformations(Request $request)
    {
        $user = Auth::user();

        $individuelles = $user->individuelles->where('formations_id', '!=', null);

        $formation_count = $individuelles->count();

        return view("individuelles.show-formation", compact("formation_count", "individuelles"));
    }

    public function nouvellesformations(Request $request)
    {
        $user = Auth::user();

        /* $nouvelle_formations = Formation::join('individuelles', 'formations.id', 'individuelles.formations_id')
            ->select('formations.*')
            ->where('individuelles.users_id', $user->id)
            ->where('formations.statut', 'Nouvelle')->get(); */

        $nouvelle_formations = Auth::user()->individuelles()
            ->join('formations', 'formations.id', '=', 'individuelles.formations_id')
            ->where('formations.statut', 'Nouvelle')
            ->select('individuelles.*')
            ->get();

        return view("individuelles.nouvelle_formations", compact("nouvelle_formations"));
    }

    /* public function demandesdg(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $dakar = Region::where('nom', 'DAKAR')->first();

        $dakarid = $dakar?->id;

        $individuelles = collect(); // Initialiser une collection vide

        if ($dakarid) {
            $individuelles = Individuelle::where(function ($query) use ($dakarid) {
                if ($dakarid) {
                    $query->where('regions_id', $dakarid);
                }
            })
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandesth(Request $request)
    {

        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $thies = Region::where('nom', 'THIES')->first();

        $thiesid = $thies?->id;

        $individuelles = collect(); // Initialiser une collection vide

        if ($thiesid) {
            $individuelles = Individuelle::where(function ($query) use ($thiesid) {
                if ($thiesid) {
                    $query->where('regions_id', $thiesid);
                }
            })
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandeszig(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $ziguinchor = Region::where('nom', 'ZIGUINCHOR')->first();
        $kolda      = Region::where('nom', 'KOLDA')->first();
        $sedhiou    = Region::where('nom', 'SEDHIOU')->first();

        $ziguinchorid = $ziguinchor?->id;
        $koldaid      = $kolda?->id;
        $sedhiouid    = $sedhiou?->id;

        $individuelles = collect(); // Initialiser une collection vide

        if ($ziguinchorid || $koldaid || $sedhiouid) {
            $individuelles = Individuelle::where(function ($query) use ($ziguinchorid, $koldaid, $sedhiouid) {
                if ($ziguinchorid) {
                    $query->where('regions_id', $ziguinchorid);
                }

                if ($koldaid) {
                    $query->orWhere('regions_id', $koldaid);
                }

                if ($sedhiouid) {
                    $query->orWhere('regions_id', $sedhiouid);
                }

            })
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandeskd(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $ziguinchor = Region::where('nom', 'ZIGUINCHOR')->first();
        $kolda      = Region::where('nom', 'KOLDA')->first();
        $sedhiou    = Region::where('nom', 'SEDHIOU')->first();

        $ziguinchorid = $ziguinchor?->id;
        $koldaid      = $kolda?->id;
        $sedhiouid    = $sedhiou?->id;

        $individuelles = collect(); // Initialisation d'une collection vide

        if ($ziguinchorid || $koldaid || $sedhiouid) {
            $individuelles = Individuelle::where(function ($query) use ($ziguinchorid, $koldaid, $sedhiouid) {
                if ($ziguinchorid) {
                    $query->where('regions_id', $ziguinchorid);
                }

                if ($koldaid) {
                    $query->orWhere('regions_id', $koldaid);
                }

                if ($sedhiouid) {
                    $query->orWhere('regions_id', $sedhiouid);
                }

            })
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandeskl(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $kaolack  = Region::where('nom', 'KAOLACK')->first();
        $kaffrine = Region::where('nom', 'KAFFRINE')->first();
        $fatick   = Region::where('nom', 'FATICK')->first();

        $kaolackid  = $kaolack?->id;
        $kaffrineid = $kaffrine?->id;
        $fatickid   = $fatick?->id;

        $individuelles = collect(); // Initialiser une collection vide

        if ($kaolackid || $kaffrineid || $fatickid) {
            $individuelles = Individuelle::where(function ($query) use ($kaolackid, $kaffrineid, $fatickid) {
                if ($kaolackid) {
                    $query->where('regions_id', $kaolackid);
                }

                if ($kaffrineid) {
                    $query->orWhere('regions_id', $kaffrineid);
                }

                if ($fatickid) {
                    $query->orWhere('regions_id', $fatickid);
                }

            })
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandessl(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $saintlouis = Region::where('nom', 'SAINT LOUIS')->first();
        $louga      = Region::where('nom', 'LOUGA')->first();

        $saintlouisid = $saintlouis?->id;
        $lougaid      = $louga?->id;

        $individuelles = collect(); // Initialiser une collection vide

        if ($saintlouisid || $lougaid) {
            $individuelles = Individuelle::where(function ($query) use ($saintlouisid, $lougaid) {
                if ($saintlouisid) {
                    $query->where('regions_id', $saintlouisid);
                }

                if ($lougaid) {
                    $query->orWhere('regions_id', $lougaid);
                }

            })
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandeskg(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $kedougou    = Region::where('nom', 'KEDOUGOU')->first();
        $tambacounda = Region::where('nom', 'TAMBACOUNDA')->first();

        $kedougouid    = $kedougou?->id;
        $tambacoundaid = $tambacounda?->id;

        $individuelles = collect(); // Initialiser une collection vide

        if ($kedougouid || $tambacoundaid) {
            $individuelles = Individuelle::where(function ($query) use ($kedougouid, $tambacoundaid) {
                if ($kedougouid) {
                    $query->where('regions_id', $kedougouid);
                }

                if ($tambacoundaid) {
                    $query->orWhere('regions_id', $tambacoundaid);
                }

            })
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandesmt(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $matam = Region::where('nom', 'MATAM')->first();

        $matamid = $matam?->id;

        $individuelles = collect(); // Collection vide par défaut

        if ($matamid) {
            $individuelles = Individuelle::where('regions_id', $matamid)
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    }

    public function demandesdl(Request $request)
    {
        $total_count = Individuelle::count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $diourbel   = Region::where('nom', 'DIOURBEL')->first();
        $diourbelid = $diourbel?->id;

        $individuelles = collect(); // Collection vide par défaut

        if ($diourbelid) {
            $individuelles = Individuelle::where('regions_id', $diourbelid)
                ->latest()
                ->limit(200)
                ->get();
        }

        $count_demandeur        = $individuelles->count();
        $count_demandeur_format = number_format($count_demandeur, 0, ',', ' ');

        if ($count_demandeur === 0) {
            $title = 'Aucune demande individuelle';
        } elseif ($count_demandeur === 1) {
            $title = "$count_demandeur_format demande individuelle trouvée";
        } else {
            $title = "$count_demandeur_format demandes trouvées";
        }

        $departements = Departement::select('id', 'nom')->orderBy('nom', 'ASC')->get();
        $modules      = Module::orderBy("created_at", "DESC")->get();

        return view(
            "individuelles.index",
            compact('individuelles', 'departements', 'modules', 'title')
        );
    } */

    public function confirmer(Request $request, $id)
    {
        $individuelle = Individuelle::findOrFail($id);

        $individuelle->update([
            'confirmation'      => 'confirmer',
            'motif_declinaison' => null,
        ]);

        Alert::success('Félicitations !', 'Merci pour votre confiance !');

        return redirect()->back();
    }
    public function decliner(Request $request, $id)
    {
        $this->validate($request, [
            'motif' => "required|string",
        ]);

        $individuelle = Individuelle::findOrFail($id);

        $individuelle->update([
            'confirmation'      => 'décliner',
            'motif_declinaison' => $request->motif,
        ]);

        Alert::success('Dommage !', 'Nous espérons vous retrouver bientôt !');

        return redirect()->back();
    }

    public function corbeille()
    {
        $total_count = Individuelle::onlyTrashed()->count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $individuelles = Individuelle::onlyTrashed()
            ->orderByDesc('deleted_at') // Trie par la date de suppression la plus récente
            ->take(250)
            ->get();

        $count_demandeur = number_format($individuelles->count(), 0, ',', ' ');

        if ($count_demandeur < 1) {
            $title = 'Aucun demandeur supprimé';
        } elseif ($count_demandeur == 1) {
            $title = "$count_demandeur demandeur supprimé sur un total de $total_count";
        } else {
            $title = "Liste des $count_demandeur derniers demandeurs supprimés sur un total de $total_count";
        }

        return view("individuelles.corbeille", compact("individuelles", "title"));
    }

    public function validationIndividuelle(Request $request, Individuelle $individuelle)
    {
        $individuelle = Individuelle::findOrFail($request->id);

        // Si l'utilisateur n'est pas super-admin
        if (! Auth::user()->hasRole('super-admin')) {
            switch ($individuelle->statut) {
                case 'Attente':
                    Alert::warning('Désolé !', 'demande déjà validée');
                    break;
                case 'programmer':
                    Alert::warning('Désolé !', 'demande déjà programmée');
                    break;
                case 'Retenue':
                    Alert::warning('Désolé !', 'demande déjà traitée');
                    break;
                case 'Terminée':
                    Alert::warning('Désolé !', 'demandeur déjà formé');
                    break;
                case 'Rejetée':
                    Alert::warning('Désolé !', 'demandeur déjà rejeté');
                    break;
                default:
                    Alert::warning('Désolé !', 'action impossible');
            }

            return redirect()->back(); // On arrête ici pour les non-super-admins
        }

        // Si super-admin, on poursuit la validation
        $individuelle->update([
            'statut'       => 'Attente',
            'validated_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
        ]);

        Validationindividuelle::create([
            'validated_id'     => Auth::user()->id,
            'action'           => 'Attente',
            'individuelles_id' => $individuelle->id,
        ]);

        // Envoi de mail
        $toEmail     = $individuelle?->user?->email;
        $toUserName  = 'Bonjour ! ' . $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name;
        $safeMessage = "Votre demande de formation en <b><i>" . ($individuelle->module->name ?? 'cette formation') .
            "</i></b> a été retenue. Vous pourrez être contacté à tout moment pour le démarrage de la formation.
        Merci pour votre patience ; nous mettons tout en œuvre afin qu’elle puisse débuter dans les plus brefs délais.";

        // Ajouter le lien vers le site
        /* $siteUrl = config('app.url'); // ou une URL en dur comme 'https://sigof.onfp.sn' */
        $siteUrl = 'https://sigof.onfp.sn'; // ou une URL en dur comme 'https://sigof.onfp.sn'
        $safeMessage .= "<p>Consultez notre plateforme : <a href=\"$siteUrl\">$siteUrl</a></p>";

        $subject = 'Notification de validation !';
        $message = strip_tags($safeMessage, '<b><i><p><a>');

        Mail::to($toEmail)->send(new ValidationDemandeIndividuelleNotification($message, $subject, $toEmail, $toUserName));

        return redirect()->back();
    }

    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:4',
        ]);

        $individuelle       = Individuelle::findOrFail($id);
        $individuelle->note = $request->note;
        $individuelle->save();

        Alert::success('Succès !', 'La note a été mise à jour avec succès.');

        return redirect()->back();
    }

    public function rechercherDemandeur(Request $request)
    {
        // Validation basique
        $request->validate([
            'cin'                   => 'nullable|string',
            'name'                  => 'nullable|string',
            'firstname'             => 'nullable|string',
            'telephone_responsable' => 'nullable|string',
            'email'                 => 'nullable|email',
        ]);

        if (
            ! $request->filled('cin') &&
            ! $request->filled('firstname') &&
            ! $request->filled('name') &&
            ! $request->filled('telephone_responsable') &&
            ! $request->filled('email')
        ) {
            Alert::warning('Recherche impossible', 'Veuillez remplir au moins un champ avant de continuer.');
            return redirect()->back();
        }
        /*
        // Requête dynamique
        $query = User::query();

        if ($request->filled('firstname')) {
            $query->where('firstname', 'like', "%$request->firstname%");
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', "%$request->name%");
        }
        if ($request->filled('cin')) {
            $query->where('cin', 'like', "%$request->cin%");
        }
        if ($request->filled('telephone_responsable')) {
            $query->where('telephone', 'like', "%$request->telephone_responsable%");
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', "%$request->email%");
        }

        // Exécution
        $user_liste = $query->distinct()->get();
        $count      = $user_liste->count(); */

        // Requête dynamique avec filtre sur les utilisateurs ayant au moins une individuelle
        $query = User::whereHas('individuelles', function ($q) {
            // Tu peux ajouter des conditions ici si besoin, ou le laisser vide pour juste s'assurer de l'existence
        });

        if ($request->filled('firstname')) {
            $query->where('firstname', 'like', "%{$request->firstname}%");
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }
        if ($request->filled('cin')) {
            $query->where('cin', 'like', "%{$request->cin}%");
        }
        if ($request->filled('telephone_responsable')) {
            $query->where('telephone', 'like', "%{$request->telephone_responsable}%");
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->email}%");
        }

        // Exécution
        $demandeurs = $query->distinct()->get();
        /* $count      = $demandeurs->count();

        // Message titre
        if ($count === 0) {
            $title = 'Aucun demandeur trouvé';
        } elseif ($count === 1) {
            $title = '1 demandeur trouvé';
        } else {
            $title = $count . ' demandeurs trouvés';
        } */

        $totalIndividuelles = number_format($demandeurs?->count(), 0, ',', ' ');

        return view('user.demandeur-individuel', compact(
            'demandeurs',
            /* 'title', */
            'totalIndividuelles'
        ));
    }

    public function forceDelete($uuid)
    {
        $individuelle = Individuelle::withTrashed()->where('uuid', $uuid)->firstOrFail();
        // Supprimer
        $individuelle->forceDelete();

        Alert::success('Succès ', 'Demande supprimé définitivement.');
        return redirect()->back();
    }
    public function restore($uuid)
    {
        $individuelle = Individuelle::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $individuelle->restore();

        return redirect()->back()->with('success', 'Demande restauré avec succès.');
    }

    /* public function exportExcel(string $statut)
    {
        $fileName = "Demandes_individuelles_{$statut}_" . now()->format('Ymd_His') . ".xlsx";

        return Excel::download(
            new ExportIndividuellesStatut($statut),
            $fileName
        );
    } */
    public function exportExcel(int $annee, string $region, string $statut = 'all')
    {
        $regionModel = Region::where('nom', $region)->firstOrFail();

        $chunkSize = 1000;
        $timestamp = now()->format('Ymd_His');

        $folderName = "individuelles_{$annee}_{$region}_{$statut}_{$timestamp}";
        $tempPath = storage_path("app/temp/{$folderName}");

        // Créer le dossier temporaire
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        // ==============================
        // Query de base (année + région)
        // ==============================
        $baseQuery = Individuelle::whereYear('date_depot', $annee)
            ->where('regions_id', $regionModel->id)
            ->when($statut !== 'all', fn($q) => $q->where('statut', $statut));

        $total = $baseQuery->count();
        $chunks = ceil($total / $chunkSize);

        // ==============================
        // Génération des fichiers Excel
        // ==============================
        for ($i = 0; $i < $chunks; $i++) {
            $offset = $i * $chunkSize;

            $query = (clone $baseQuery)
                ->orderBy('id')
                ->skip($offset)
                ->take($chunkSize);

            $fileName = "Individuelles_{$annee}_{$region}_{$statut}_part_" . ($i + 1) . ".xlsx";

            Excel::store(
                new ExportIndividuellesStatutQuery($query),
                "temp/{$folderName}/{$fileName}"
            );
        }

        // ==============================
        // Création du ZIP
        // ==============================
        $zipPath = storage_path("app/temp/Individuelles_{$annee}_{$region}_{$statut}_{$timestamp}.zip");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $zip->addFile($file->getRealPath(), $file->getFilename());
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
