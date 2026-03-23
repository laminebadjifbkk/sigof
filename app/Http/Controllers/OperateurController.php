<?php

namespace App\Http\Controllers;

use App\Exports\OperateursAgrementExport;
use App\Exports\OperateursExport;
use App\Models\Arrive;
use App\Models\Commissionagrement;
use App\Models\Courrier;
use App\Models\Departement;
use App\Models\Domaine;
use App\Models\File;
use App\Models\Historiqueagrement;
use App\Models\Operateur;
use App\Models\Operateurcategorie;
use App\Models\Operateureference;
use App\Models\Operateurequipement;
use App\Models\Operateurformateur;
use App\Models\Operateurlocalite;
use App\Models\Operateurmodule;
use App\Models\Region;
use App\Models\User;
use App\Models\Validationoperateur;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use ZipArchive;

class OperateurController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Operateur|DIOF|ADIOF|Ingenieur|DEC|ADEC|Demandeur|Employe']);
        $this->middleware("permission:operateur-view", ["only" => ["index"]]);
        /* $this->middleware('dec')->only(['create', 'destroy', 'store', 'update', 'agrement', 'agrements',
            'addOperateur', 'renewOperateur', 'update', 'updated', 'showAgrement', 'show', 'fetch', 'fetchModuleOperateur',
            'fetchOperateurModule', 'showReference', 'validateOperateur', 'nonRetenu', 'agreerOperateur', 'agreerAllModuleOperateur',
            'retirerOperateur', 'devenirOperateur', 'rapports', 'generateRapport']);
        $this->middleware('operateur')->only(['create', 'destroy', 'store', 'update']); */
    }

    public function index(Request $request)
    {
        // Total global
        $totalOperateurs = number_format(Operateur::count(), 0, ',', ' ');

        // Base query
        $query = Operateur::query();

        // Filtre dynamique si besoin plus tard
        /* if ($statut = $request->query('statut_agrement')) {
            $query->where('statut_agrement', $statut);
        } else {
            $query->whereIn('statut_agrement', [
                'agréé',
                'sous réserve',
                'Extension',
                'Renouvellement'
            ]);
        } */

        // Liste principale
        $operateurs = $query
            ->latest()
            ->limit(350)
            ->get();

        // Départements
        $departements = Departement::orderBy('nom')->get(['id', 'nom']);
        /*  $groupesStatutAgrement = $allOperateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun';
        }); */

        $groupes = Operateur::select(DB::raw('YEAR(annee_agrement) as annee'))
            ->selectRaw('COUNT(*) as total')
            ->groupBy('annee')
            ->orderByDesc('annee')
            ->paginate(1); // ← une ligne par page

        $commissionagrements = Commissionagrement::select('*')->orderBy('commission', 'desc')->get();

        $affichees = $operateurs?->count();
        $total     = $totalOperateurs ?? ($operateurs instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $operateurs->total()
            : $operateurs?->count());

        $recherche = null;

        return view(
            "operateurs.index",
            compact(
                "operateurs",
                "groupes",
                "departements",
                "commissionagrements",
                "affichees",
                "total",
                /* "operateur_agreer",
                "operateur_rejeter",
                "pourcentage_agreer",
                "pourcentage_rejeter",
                "operateur_nouveau",
                "operateur_expirer",
                "pourcentage_nouveau",
                "pourcentage_expirer" */
                /* "title", */
                "totalOperateurs",
                "recherche",
            )
        );
    }

    public function parAnnee(Request $request, $annee)
    {
        // Base filtrée
        $baseQuery = Operateur::whereYear('annee_agrement', $annee);

        // ✅ TOTAL (année filtrée)
        $totalOperateurs = number_format(
            (clone $baseQuery)->count(),
            0,
            ',',
            ' '
        );

        // ✅ LISTE (avec tri)
        $operateurs = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->limit(350)
            ->get();

        // ✅ GROUPES (sans created_at !)
        $groupes = Operateur::whereYear('annee_agrement', $annee)
            ->select('statut_agrement', DB::raw('COUNT(*) as total'))
            ->groupBy('statut_agrement')
            ->orderByDesc('total')
            ->get(); // ← get() au lieu de paginate

        $affichees = $operateurs?->count();
        $total     = $totalOperateurs ?? ($operateurs instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $operateurs->total()
            : $operateurs?->count());

        return view('operateurs.par_annee', compact(
            'operateurs',
            'groupes',
            'affichees',
            'total',
            'annee'
        ));
    }

    public function parAnneeEtStatut(Request $request, $annee, $statut)
    {
        // Base query filtrée par année ET statut
        $query = Operateur::whereYear('annee_agrement', $annee)
            ->where('statut_agrement', $statut);

        // Total pour cette combinaison
        $totalOperateurs = number_format($query->count(), 0, ',', ' ');

        // Liste principale (limité à 200 par exemple)
        $operateurs = $query
            ->orderByDesc('created_at')
            ->limit(350)
            ->get();

        // Départements si nécessaire
        $departements = Departement::orderBy('nom')->get(['id', 'nom']);

        // Groupement éventuel (par exemple pour afficher d’autres statuts dans la même année)
        $groupes = Operateur::whereYear('annee_agrement', $annee)
            ->select('statut_agrement', DB::raw('COUNT(*) as total'))
            ->groupBy('statut_agrement')
            ->orderByDesc('total')
            ->get();

        $affichees = $operateurs->count();
        $total     = $totalOperateurs ?? ($operateurs instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $operateurs->total()
            : $operateurs?->count());

        return view('operateurs.par_annee_et_statut', compact(
            'operateurs',
            'groupes',
            'totalOperateurs',
            'total',
            'affichees',
            'annee',
            'statut',
            'departements'
        ));
    }

    public function create()
    {
        $departements = Departement::orderBy("nom", "asc")->get();
        return view('operateurs.create', compact('departements'));
    }

    public function agrement()
    {
        $operateurs   = Operateur::latest()->get();
        $departements = Departement::orderBy("nom", "asc")->get();

        $type_demandes = ['Nouvelle', 'Renouvellement'];
        $counts        = Operateur::whereIn('type_demande', $type_demandes)
            ->selectRaw("type_demande, COUNT(*) as count")
            ->groupBy('type_demande')
            ->pluck('count', 'type_demande');

        $operateur_new   = $counts['Nouvelle'] ?? 0;
        $operateur_renew = $counts['Renouvellement'] ?? 0;
        $operateur_total = $operateur_new + $operateur_renew;

        $pourcentage_new   = $operateur_total ? ($operateur_new / $operateur_total) * 100 : 0;
        $pourcentage_renew = $operateur_total ? ($operateur_renew / $operateur_total) * 100 : 0;

        return view("operateurs.agrements.index", compact("operateurs", "departements", "operateur_new", "operateur_renew", "pourcentage_new", "pourcentage_renew"));
    }

    //cette fonction permet de valider l'agrement des operateurs
    public function agrements($id)
    {
        $operateur          = Operateur::findOrFail($id);
        $operateurs         = Operateur::all();
        $operateureferences = Operateureference::all();

        /*  $excludedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC'];
        foreach (Auth::user()->roles as $role) {
            if (! empty($role?->name) && ! in_array($role->name, $excludedRoles)) {
                $this->authorize('view', $operateur);
            }
        } */

        $this->authorize('view', $operateur);

        // Récupérer les counts des relations de l'opérateur
        $module_count     = Operateurmodule::where('operateurs_id', $operateur->id)->exists();
        $reference_count  = Operateureference::where('operateurs_id', $operateur->id)->exists();
        $equipement_count = Operateurequipement::where('operateurs_id', $operateur->id)->exists();
        $formateur_count  = Operateurformateur::where('operateurs_id', $operateur->id)->exists();
        $localite_count   = Operateurlocalite::where('operateurs_id', $operateur->id)->exists();

        /* 
        // Déterminer le statut de la demande
        $statut_demande = ($module_count === "complète" && $reference_count === "complète" && $equipement_count === "complète" &&
            $formateur_count === "complète" && $localite_count === "complète"); */

        $statut_demande = $operateur->profilEstComplet() ? 'complète' : 'incomplète';

        /*  $dateQuitus = $operateur?->debut_quitus;
        $diff       = $dateQuitus?->diff(now());

        $diffText = '';

        if ($diff) {
            if ($diff->y > 0) {
                $diffText = $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
                if ($diff->m > 0) {
                    $diffText .= ' et ' . $diff->m . ' mois';
                }
            } elseif ($diff->m > 0) {
                $diffText = $diff->m . ' mois';
            } else {
                $diffText = $diff->d . ' jours';
            }
        } */

        $dateQuitus = $operateur?->debut_quitus
            ? Carbon::parse($operateur->debut_quitus)
            : null;

        // Calcul de la différence pour le texte
        $diffText = $dateQuitus?->locale('fr')->diffForHumans(now(), true);

        // Calcul de la différence en mois pour le badge
        $diffInMonths = $dateQuitus ? ($dateQuitus->diffInYears(now()) * 12 + $dateQuitus->diffInMonths(now()) % 12) : 0;

        $sections = [
            [
                'label' => 'Modules',
                'icon' => 'bi-journal-code text-info',
                'count' => $operateur->operateurmodules->count(),
                'route' => route('operateurs.show', $operateur),
            ],
            [
                'label' => 'Références',
                'icon' => 'bi-bookmark-check text-primary',
                'count' => $operateur->operateureferences->count(),
                'route' => route('showReference', $operateur->uuid),
            ],
            [
                'label' => 'Équipements & Infrastructures',
                'icon' => 'bi-hdd-network text-warning',
                'count' => $operateur->operateurequipements->count(),
                'route' => route('showEquipement', $operateur->uuid),
            ],
            [
                'label' => 'Formateurs',
                'icon' => 'bi-person-workspace text-success',
                'count' => $operateur->operateurformateurs->count(),
                'route' => route('showFormateur', $operateur->uuid),
            ],
            [
                'label' => 'Localités',
                'icon' => 'bi-geo-alt text-danger',
                'count' => $operateur->operateurlocalites->count(),
                'route' => route('showLocalite', $operateur->uuid),
            ],

            [
                'label' => 'Validité quitus',
                'icon' => 'bi-file-earmark-text text-dark',
                'count' => $diffText,
                'badge' => $diffInMonths > 3 ? 'bg-danger' : 'bg-info',
                /* 'route' => null, */
                'modal' => "EditOperateurModal{$operateur->id}",
            ],
        ];

        return view(
            "operateurs.agrement",
            compact(
                "operateurs",
                "operateur",
                "statut_demande",
                "formateur_count",
                "operateureferences",
                'dateQuitus',
                'diffInMonths',
                'diffText',
                'sections',
            )
        );
    }

    public function store(Request $request)
    {
        /*  $this->validate($request, [
            "departement"  => "required|string",
            "quitus"       => ['image', 'required', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            "date_quitus"  => "required|date_format:d/m/Y",
            "type_demande" => "required|in:Nouvelle,Renouvellement,Extension",
        ]); */

        $user = Auth::user();

        $this->validate($request, [
            "departement"  => "required|string",
            "type_demande" => "required|in:Nouvelle,Renouvellement,Extension",
            // Appliquer la règle conditionnellement
            'date_quitus' => [
                'nullable',
                Rule::requiredIf(function () use ($user) {
                    return $user->categorie !== 'Public' || $user->statut !== 'Etablissement public';
                }),
                'date_format:d/m/Y',
            ],
        ]);

        $operateur_total = Operateur::where('users_id', $user->id)->count();

        $departement = Departement::where('nom', $request->input("departement"))->first();

        if ($operateur_total >= 1) {
            Alert::warning('Attention ! ', 'Vous avez atteint le nombre de demandes autorisées');
            return redirect()->back();
        }

        $dateString  = $request->input('date_quitus');
        $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

        $anneeEnCours = date('Y');
        $an           = date('y');

        $type_demande = $request->input("type_demande");
        /* $numero_agrement = "/ONFP/DG/DEC/$anneeEnCours"; */

        Operateur::create([
            /* 'numero_agrement' => $numero_agrement, */
            'type_demande'    => $type_demande,
            'debut_quitus'    => $date_quitus,
            'annee_agrement'  => date('Y-m-d'),
            'statut_agrement' => 'Nouveau',
            'departements_id' => $departement?->id,
            'regions_id'      => $departement?->region?->id,
            'users_id'        => $user->id,
        ]);

        /* if ($request->hasFile('quitus')) {
            $quitusPath = $request->file('quitus')->store('quitus', 'public');
            Image::make(public_path("/storage/{$quitusPath}"))->save();
            $operateur->update(['quitus' => $quitusPath]);
        } */

        /* if ($request->hasFile('quitus')) {

            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('quitus');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('quitus', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateur->update([
                'quitus' => $filePath,
            ]);

        } */

        Alert::success("Succès ! ", "Demande enregistrée avec succès");
        return redirect()->back();
    }
    public function addOperateur(Request $request)
    {
        $this->validate($request, [
            'operateur'            => ["required", "string", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'email'                => ["required", "email", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'username'             => ["nullable", "string", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'fixe'                 => ["required", "string", "size:9", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'telephone'            => ["required", "string", "size:9", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'bp'                   => ['nullable', 'string'],
            'categorie'            => ['required', 'string'],
            'adresse'              => ['required', 'string', 'max:255'],
            /* 'rccm'                 => ['nullable', 'string'], */
            'ninea'                => ["nullable", "string", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'web'                  => ['nullable', 'string', 'max:255'],
            'civilite'             => ['required', 'string', 'max:8'],
            'prenom'               => ['required', 'string', 'max:150'],
            'email_responsable'    => ["nullable", "email", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "numero_agrement"      => ["nullable", "string", Rule::unique('operateurs')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "statut"               => "required|string",
            "autre_statut"         => "nullable|string",
            "departement"          => "required|string",
            "quitus"               => ['image', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            "date_quitus"          => "nullable|date_format:d/m/Y",
            "type_demande"         => "required|in:Nouvelle,Renouvellement,Extension",
            "arrete_creation"      => "nullable|string",
            "file_arrete_creation" => ['file', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
            "demande_signe"        => "nullable|string",
            "formulaire_signe"     => "nullable|string",
        ]);

        $user = User::create([
            'civilite'             => $request->input("civilite"),
            'firstname'            => $request->input("prenom"),
            'name'                 => $request->input("nom"),
            'operateur'            => $request->input("operateur"),
            'username'             => $request->input("username"),
            'email'                => $request->input('email'),
            'fixe'                 => $request->input("fixe"),
            'telephone'            => $request->input("telephone"),
            'adresse'              => $request->input("adresse"),
            'password'             => Hash::make($request->email),
            'created_by'           => Auth::id(),
            'updated_by'           => Auth::id(),
            'categorie'            => $request->input("categorie"),
            'email_responsable'    => $request->input("email_responsable"),
            'fonction_responsable' => $request->input("fonction_responsable"),
            'telephone_parent'     => $request->input("telephone_parent"),
            'rccm'                 => $request->input("rccm"),
            'ninea'                => $request->input("ninea"),
            'bp'                   => $request->input("bp"),
            'statut'               => $request->input("statut"),
            'autre_statut'         => $request->input("autre_statut"),
            'quitusfiscal'         => $request->input("quitusfiscal"),
            'cvsigne'              => $request->input("cvsigne"),
            'web'                  => $request->input("web"),
        ]);

        $departement = Departement::where('nom', $request->input("departement"))->first();

        $dateString  = $request->input('date_quitus');
        $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

        $numero_agrement = $request->input("numero_agrement") ?: $request->input("numero_arrive") . '/ONFP/DG/DEC/' . date('Y');

        $operateur = Operateur::create([
            "numero_dossier"   => $request->input("numero_dossier"),
            'numero_arrive'    => $request->input("numero_arrive"),
            "numero_agrement"  => $numero_agrement,
            "type_demande"     => $request->input("type_demande"),
            "debut_quitus"     => $date_quitus,
            "annee_agrement"   => now()->format('Y-m-d'),
            "statut_agrement"  => 'Nouveau',
            "departements_id"  => $departement?->id,
            "regions_id"       => $departement?->region?->id,
            "users_id"         => $user->id,
            "arrete_creation"  => $request->input("arrete_creation"),
            "demande_signe"    => $request->input("demande_signe"),
            "formulaire_signe" => $request->input("formulaire_signe"),
            "quitusfiscal"     => $request->input("quitusfiscal"),
            "cvsigne"          => $request->input("cvsigne"),
        ]);

        $user->assignRole('Operateur');

        // Gestion des fichiers
        /* if ($request->hasFile('quitus')) {
            $quitusPath = $request->file('quitus')->store('quitus', 'public');
            $operateur->update(['quitus' => $quitusPath]);
        } */

        if ($request->hasFile('quitus')) {

            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('quitus');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('quitus', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateur->update([
                'quitus' => $filePath,
            ]);
        }

        /* if ($request->hasFile('file_arrete_creation')) {
            $file_arrete_creation = $request->file('file_arrete_creation')->store('uploads', 'public');
            $operateur->update(['file_arrete_creation' => $file_arrete_creation]);
        } */

        if ($request->hasFile('file_arrete_creation')) {

            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('file_arrete_creation');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateur->update([
                'file_arrete_creation' => $filePath,
            ]);
        }

        Alert::success("Félicitations !", "Opérateur ajouté avec succès");

        return redirect()->back();
    }

    public function renewOperateur(Request $request)
    {

        $user = Auth::user();

        $operateur = $user->operateurs()->orderByDesc('id')->first();

        // Vérifier s'il existe un opérateur
        if (! $operateur || ! $operateur->annee_agrement) {
            Alert::error('Erreur', 'Aucun agrément trouvé. Veuillez d\'abord effectuer une demande.');
            return back();
        }

        $this->validate($request, [
            // Appliquer la règle conditionnellement
            "date_quitus"  => [
                Rule::requiredIf(function () use ($user) {
                    return $user->categorie !== 'Public' || $user->statut !== 'Etablissement public';
                }),
                'nullable', // Permet de ne rien mettre si ce n'est pas requis
                'date_format:d/m/Y',
            ],
            "type_demande" => "required|in:Nouvelle,Renouvellement,Extension",
        ]);

        $annee_agrement = $operateur->commissionagrements()
            ->orderByDesc('fin_commission')
            ->first();

        // Date actuelle
        $now = Carbon::now();

        // Vérifie que $annee_agrement est bien un objet et qu’il a une date
        if ($annee_agrement && $annee_agrement->fin_commission) {
            $dateAgrement = Carbon::parse($annee_agrement->fin_commission);
            $diffAnnee    = $dateAgrement->diffInYears($now);
        } else {
            $diffAnnee = null;
        }

        $dateString  = $request->input('date_quitus');
        $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

        if ($diffAnnee < 2) {

            Alert::warning('Désolé !', 'Vous ne pouvez pas renouveler votre agrément pour le moment car il est toujours valable.');

            return back();
        } elseif ($diffAnnee >= 2 && $diffAnnee < 4) {

            $commissionagrement = Commissionagrement::where('statut', 'Ouvert')->first();

            if (! $commissionagrement) {

                Alert::error('Désolé', 'Aucun agrément n\'est lancé pour le moment.');

                return redirect()->back();
            }

            $operateur->update([
                "statut_agrement" => 'Nouveau',
                "type_demande"    => $request->input("type_demande"),
                "debut_quitus"    => $date_quitus,
            ]);

            $operateur->commissionagrements()->syncWithoutDetaching([$commissionagrement?->id]);

            Alert::success('Succès !', 'Votre demande d\'extension a été prise en compte.');
            return back();
        } elseif ($diffAnnee >= 4) {

            $op = Operateur::create([
                "categorie"       => $operateur?->categorie,
                "statut"          => $operateur?->statut,
                "statut_agrement" => 'Nouveau',
                "type_demande"    => 'Nouvelle',
                "autre_statut"    => $operateur?->autre_statut,
                "annee_agrement"  => now()->format('Y-m-d'),
                "rccm"            => $operateur?->registre_commerce,
                "ninea"           => $operateur?->ninea,
                "debut_quitus"    => $date_quitus,
                "departements_id" => $operateur?->departements_id,
                "regions_id"      => $operateur?->departement?->region?->id,
                "users_id"        => $operateur?->users_id,
            ]);

            // Clonage des modules de l'opérateur
            foreach ($operateur?->operateurmodules as $operateurmodule) {
                Operateurmodule::create([
                    "module"               => $operateurmodule?->module,
                    "domaine"              => $operateurmodule?->domaine,
                    "categorie"            => $operateurmodule?->categorie,
                    "niveau_qualification" => $operateurmodule?->niveau_qualification,
                    "statut"               => $operateurmodule?->statut,
                    "operateurs_id"        => $op?->id,
                ]);
            }

            // Clonage des références
            foreach ($operateur?->operateureferences as $operateureference) {
                Operateureference::create([
                    "organisme"     => $operateureference?->organisme,
                    "contact"       => $operateureference?->contact,
                    "periode"       => $operateureference?->periode,
                    "description"   => $operateureference?->description,
                    "operateurs_id" => $op?->id,
                ]);
            }

            // Clonage des formateurs
            foreach ($operateur?->operateurformateurs as $operateurformateur) {
                Operateurformateur::create([
                    "name"                   => $operateurformateur?->name,
                    "domaine"                => $operateurformateur?->domaine,
                    "nbre_annees_experience" => $operateurformateur?->nbre_annees_experience,
                    "references"             => $operateurformateur?->references,
                    "operateurs_id"          => $op?->id,
                ]);
            }

            // Clonage des équipements
            foreach ($operateur->operateurequipements as $operateurequipement) {
                Operateurequipement::create([
                    "designation"   => $operateurequipement?->designation,
                    "quantite"      => $operateurequipement?->quantite,
                    "etat"          => $operateurequipement?->etat,
                    "type"          => $operateurequipement?->type,
                    "operateurs_id" => $op?->id,
                ]);
            }

            // Clonage des localités
            foreach ($operateur?->operateurlocalites as $operateurlocalite) {
                Operateurlocalite::create([
                    "name"          => $operateurlocalite?->name,
                    "region"        => $operateurlocalite?->region,
                    "operateurs_id" => $op?->id,
                ]);
            }

            $commissionagrement = Commissionagrement::where('statut', 'Ouvert')->first();

            if (! $commissionagrement) {
                Alert::error('Désolé', 'Aucun agrément n\'est lancé pour le moment.');
                return redirect()->back();
            }

            $operateur->commissionagrements()->syncWithoutDetaching([$commissionagrement?->id]);

            Alert::success('Succès !', 'Votre nouvelle agrément a été créé avec succès.');

            return back();
        } else {

            /* $dateString  = $request->input('date_quitus');
            $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

            $operateur->update([
                "statut_agrement" => 'Nouveau',
                "type_demande"    => 'Renouvellement',
                "debut_quitus"    => $date_quitus,
            ]); */

            Alert::warning("Désolez !", "Impossible de réaliser cette opération pour le moment !");

            return redirect()->back();
        }
    }

    public function update(Request $request, Operateur $operateur)
    {
        $user = $operateur->user;

        $this->validate($request, [
            "numero_dossier"       => ['nullable', 'string', Rule::unique(Operateur::class)->ignore($operateur?->id)->whereNull('deleted_at')],
            "numero_arrive"        => ['nullable', 'string', Rule::unique(Operateur::class)->ignore($operateur?->id)->whereNull('deleted_at')],
            "numero_agrement"      => ['nullable', 'string', Rule::unique(Operateur::class)->ignore($operateur?->id)->whereNull('deleted_at')],
            "operateur"            => ['required', 'string', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "username"             => ['nullable', 'string', Rule::unique('users', 'username')
                ->ignore($user->id)
                ->where(fn($query) => $query->whereNull('deleted_at'))],
            "email"                => ['required', 'string', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "fixe"                 => ['required', 'string', 'size:9', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "telephone"            => ['required', 'string', 'size:9', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "telephone_parent"     => ['nullable', 'string', 'size:9'],
            "fonction_responsable" => ['required', 'string'],
            "civilite"             => ['required', 'string'],
            "prenom"               => ['required', 'string'],
            "nom"                  => ['required', 'string'],
            "categorie"            => ['required', 'string'],
            "statut"               => ['required', 'string'],
            "departement"          => ['required', 'string'],
            "operateurcategorie"   => ['nullable', 'string'],
            "adresse"              => ['required', 'string'],
            "ninea"                => ['nullable', 'string'],
            "registre_commerce"    => ['nullable', 'string'],
            /* "quitus"               => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'], */
            "date_quitus"          => ['nullable', 'date_format:d/m/Y'],
            "type_demande"         => "required|in:Nouvelle,Renouvellement,Extension",
            "arrete_creation"      => ['nullable', 'string'],
            "file_arrete_creation" => ['file', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
            "demande_signe"        => ['nullable', 'string'],
            "formulaire_signe"     => ['nullable', 'string'],
            "web"                  => ['nullable', 'string'],
            "annee_agrement"       => ['nullable', 'date'],
        ]);

        $departement = Departement::where('nom', $request->input("departement"))->firstOrFail();
        if ($request->input("operateurcategorie")) {
            $operateurcategorie    = Operateurcategorie::where('name', $request->input("operateurcategorie"))->firstOrFail();
            $operateurcategorie_id = $operateurcategorie?->id;
        } else {
            $operateurcategorie_id = null;
        }

        // Si aucun rôle autorisé n'est trouvé chez l'utilisateur
        /* if (! array_intersect($rolesAutorises, $userRoles)) { */
        // Vérifie la permission "update"
        $this->authorize('update', $operateur);

        // Si le statut n'est pas "nouveau", bloquer l'action
        /* if ($operateur->statut_agrement != 'nouveau') {
            Alert::warning('Attention !', 'action impossible');
            return redirect()->back();
        } */
        /* } */

        /* $arrive = Arrive::where('numero_arrive', $request->input("numero_arrive"))->first(); */

        $user->update([
            'civilite'             => $request->input("civilite"),
            'firstname'            => $request->input("prenom"),
            'name'                 => $request->input("nom"),
            'operateur'            => $request->input("operateur"),
            'username'             => $request->input("username"),
            'email'                => $request->input('email'),
            "fixe"                 => $request->input("fixe"),
            "telephone"            => $request->input("telephone"),
            "adresse"              => $request->input("adresse"),
            "categorie"            => $request->input("categorie"),
            "email_responsable"    => $request->input("email_responsable"),
            "fonction_responsable" => $request->input("fonction_responsable"),
            "telephone_parent"     => $request->input("telephone_parent"),
            "rccm"                 => $request->input("registre_commerce"),
            "ninea"                => $request->input("ninea"),
            "bp"                   => $request->input("bp"),
            "statut"               => $request->input("statut"),
            "autre_statut"         => $request->input("autre_statut"),
            "web"                  => $request->input("web"),
            'updated_by'           => Auth::id(),
        ]);

        $dateString  = $request->input('date_quitus');
        $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

        $operateur->update([
            'numero_arrive'          => $request->input("numero_arrive"),
            "numero_dossier"         => $request->input("numero_dossier"),
            "numero_agrement"        => $request->input("numero_agrement"),
            "type_demande"           => $request->input("type_demande"),
            "debut_quitus"           => $date_quitus,
            "departements_id"        => $departement?->id,
            "operateurcategories_id" => $operateurcategorie_id,
            "regions_id"             => $departement?->region?->id,
            "users_id"               => $user->id,
            "arrete_creation"        => $request->input("arrete_creation"),
            "demande_signe"          => $request->input("demande_signe"),
            "formulaire_signe"       => $request->input("formulaire_signe"),
            "quitusfiscal"           => $request->input("quitusfiscal"),
            "cvsigne"                => $request->input("cvsigne"),
            "annee_agrement"         => $request->input("annee_agrement"),
        ]);

        // Gestion des fichiers

        /*  if ($request->hasFile('quitus')) {

            if (! is_null($operateur->quitus)) {
                Storage::disk('public')->delete($operateur->quitus);
            }
            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('quitus');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('quitus', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateur->update([
                'quitus' => $filePath,
            ]);

        } */

        /* if ($request->hasFile('file_arrete_creation')) {
            $filePath = $request->file('file_arrete_creation')->store('uploads', 'public');
            $operateur->update(['file_arrete_creation' => $filePath]);
        } */

        if ($request->hasFile('file_arrete_creation')) {

            if (! is_null($operateur->file_arrete_creation)) {
                Storage::disk('public')->delete($operateur->file_arrete_creation);
            }
            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('file_arrete_creation');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateur->update([
                'file_arrete_creation' => $filePath,
            ]);
        }

        Alert::success("Succès !", 'Demande modifiée avec succès');
        return redirect()->back();
    }

    public function updated(Request $request, $uuid)
    {
        $operateur = Operateur::findOrFail($request->id);
        /* if (strtolower($operateur->file8) === 'oui') {
            Alert::error('Attention !', 'Impossible de modifier car les informations ont déjà certifiés.');
            return redirect()->back();
        } */
        $user        = $operateur->user;
        $departement = Departement::where('nom', $request->input("departement"))->firstOrFail();

        $this->validate($request, [
            "departement"  => "required|string",
            "type_demande" => "required|in:Nouvelle,Renouvellement,Extension",
            // Appliquer la règle conditionnellement
            'date_quitus' => [
                'nullable',
                Rule::requiredIf(function () use ($user) {
                    return $user->categorie !== 'Public' || $user->statut !== 'Etablissement public';
                }),
                'date',
            ],
        ]);

        /* $this->validate($request, [
            "departement"  => ['required', 'string'],
            "date_quitus"  => ['nullable', 'date_format:d/m/Y'],
            "type_demande" => "required|in:Nouvelle,Renouvellement,Extension",
            'email'        => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($request->id ?? null)->whereNull('deleted_at'),
            ],
        ]); */

        // Si l'utilisateur n'a pas de rôle valide, on l'autorise à effectuer la mise à jour
        $this->authorize('update', $operateur);

        // Vérifier le statut de l'opérateur et autoriser l'action si nécessaire
        /*  if ($operateur->statut_agrement === 'Nouveau') {
            Alert::warning('Attention !', 'Action impossible');
            return redirect()->back();
        } */

        $dateString = $request->input('date_quitus');

        $date_quitus = !empty($dateString)
            ? Carbon::createFromFormat('Y-m-d', $dateString)
            : null;

        $operateur->update([
            "type_demande"    => $request->input("type_demande"),
            "debut_quitus"    => $date_quitus,
            "departements_id" => $departement?->id,
            "regions_id"      => $departement?->region?->id,
            "users_id"        => $user->id,
        ]);

        $operateur->save();

        /*  if (request('quitus')) {
            if (! empty($operateur->quitus)) {
                Storage::disk('public')->delete($operateur->quitus);
            }
            $quitusPath = request('quitus')->store('quitus', 'public');
            $quitus     = Image::make(public_path("/storage/{$quitusPath}"));

            $quitus->save();

            $operateur->update([
                'quitus' => $quitusPath,
            ]);
        } */

        /* if ($request->hasFile('quitus')) {

            if (! is_null($operateur->quitus)) {
                Storage::disk('public')->delete($operateur->quitus);
            }
            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('quitus');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('quitus', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateur->update([
                'quitus' => $filePath,
            ]);

        } */

        Alert::success("Succès ! ", 'La demande a été modifiée avec succès');

        return redirect()->back();
    }

    public function edit(Operateur $operateur)
    {
        $departements        = Departement::orderBy("nom", "asc")->get();
        $operateurcategories = Operateurcategorie::orderBy("name", "asc")->get();

        $this->authorize('view', $operateur);

        return view("operateurs.update", compact("operateur", "departements", "operateurcategories"));
    }

    /* public function show(Operateur $operateur)
    {
        $operateurs         = Operateur::get();
        $domaines         = Domaine::get();
        $operateureferences = Operateureference::get();
        $user               = $operateur->user;

        $this->authorize('show', $operateur);

        $files = File::where('users_id', $user?->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();


        // Vérification des documents
        $hasAuto = $files->contains(
            fn($file) => $file->sigle === 'Autorisation',
        );

        $hasNinea = $files->contains(
            fn($file) => $file->sigle === 'Ninea',
        );

        $hasOrganigramme = $files->contains(
            fn($file) => $file->sigle === 'Organigramme',
        );

        $hasQuitus = $files->contains(
            fn($file) => $file->sigle === 'Quitus',
        );

        $hasRC = $files->contains(
            fn($file) => $file->sigle === 'Ninea/RC',
        );

        $labels = [
            'Ninea ou registre de commerce' => 'Registre de commerce',
        ];

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereIn(
                'sigle',
                [
                    'Ninea/RC',
                    'Ninea',
                    'AC',
                    'Quitus',
                    'Arrêté',
                    'Non-fonctionnaire',
                    'Organigramme',
                    'Contrat',
                    'Titre',
                    'Justificatif',
                    'ADEDGI',
                    'ABE',
                    'CME',
                    'CP',
                    'DENO',
                    'Bail',
                ]
            )
            ->orderBy('sigle', 'asc')
            ->distinct()
            ->get();

        return view(
            "operateurs.show",
            compact(
                "operateur",
                "operateureferences",
                "operateurs",
                "user_files",
                'user',
                'files',
                'labels',
                'domaines',
                'hasAuto',
                'hasNinea',
                'hasOrganigramme',
                'hasQuitus',
                'hasRC'
            )
        );
    } */

    public function show(Operateur $operateur)
    {
        $operateurs          = Operateur::get();
        $domaines            = Domaine::get();
        $operateureferences  = Operateureference::get();
        $user                = $operateur->user;

        $this->authorize('show', $operateur);

        // 🔹 Charger les fichiers avec distinction
        $files = File::where('users_id', $user?->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();

        // 🔹 Vérification des documents
        $hasAuto          = $files->contains(fn($file) => $file->sigle === 'Autorisation');
        $hasNinea         = $files->contains(fn($file) => $file->sigle === 'Ninea');
        $hasOrganigramme  = $files->contains(fn($file) => $file->sigle === 'Organigramme');
        $hasQuitus        = $files->contains(fn($file) => $file->sigle === 'Quitus');
        $hasRC            = $files->contains(fn($file) => $file->sigle === 'Ninea/RC');

        $labels = [
            'Ninea ou registre de commerce' => 'Registre de commerce',
        ];

        // 🔹 Fichiers utilisateurs "templates" (sans fichier associé)
        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereIn(
                'sigle',
                [
                    'Ninea/RC',
                    'Ninea',
                    'AC',
                    'Quitus',
                    'Arrêté',
                    'Non-fonctionnaire',
                    'Organigramme',
                    'Contrat',
                    'Titre',
                    'Justificatif',
                    'ADEDGI',
                    'ABE',
                    'CME',
                    'CP',
                    'DENO',
                    'Bail'
                ]
            )
            ->orderBy('sigle', 'asc')
            ->distinct()
            ->get();

        // 🔹 Charger les counts pour les badges dynamiques (relations directes)
        $operateur->loadCount([
            'operateurmodules',
            'operateureferences',
            'operateurequipements',
            'operateurformateurs',
            'formations',
        ]);

        // 🔹 Charger le count des fichiers de l'utilisateur lié
        $operateur->load([
            'user' => function ($query) {
                $query->withCount('files');
            }
        ]);

        $validations = $operateur?->validationoperateurs;

        return view(
            "operateurs.show",
            compact(
                "operateur",
                "operateureferences",
                "operateurs",
                "user_files",
                'user',
                'files',
                'labels',
                'domaines',
                'validations',
                'hasAuto',
                'hasNinea',
                'hasOrganigramme',
                'hasQuitus',
                'hasRC'
            )
        );
    }

    public function showAgrement($id)
    {
        $operateur          = Operateur::findOrFail($id);
        $operateurs         = Operateur::get();
        $operateureferences = Operateureference::get();

        // Récupérer les counts des relations de l'opérateur
        $module_count     = Operateurmodule::where('operateurs_id', $operateur->id)->exists();
        $reference_count  = Operateureference::where('operateurs_id', $operateur->id)->exists();
        $equipement_count = Operateurequipement::where('operateurs_id', $operateur->id)->exists();
        $formateur_count  = Operateurformateur::where('operateurs_id', $operateur->id)->exists();
        $localite_count   = Operateurlocalite::where('operateurs_id', $operateur->id)->exists();

        // Compter les fichiers liés à l'utilisateur (champ file non nul)
        $fichiers_total = $operateur->user?->files()
            ->whereNotNull('file')
            ->count();

        /* function getStatutFichiers($categorie, $fichiers_total)
        {
            if ($categorie !== 'Public') {
                return $fichiers_total >= 4 ? 'complète' : 'incomplète';
            } else {
                return $fichiers_total >= 1 ? 'complète' : 'incomplète';
            }
        } */

        // Utilisation
        /* $fichier_count = getStatutFichiers($operateur?->user?->categorie, $fichiers_total); */

        // Statut global
        /* $statut_demande = (
            $module_count === "complète" &&
            $reference_count === "complète" &&
            $equipement_count === "complète" &&
            $formateur_count === "complète" &&
            $localite_count === "complète" &&
            $fichier_count === "complète"
        ); */


        $statut_demande = $operateur->profilEstComplet() ? 'complète' : 'incomplète';

        $departements = Departement::orderBy("nom", "asc")->get();

        $labels = [
            'Ninea ou registre de commerce' => 'Registre de commerce',
        ];

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereIn(
                'sigle',
                [
                    'Ninea/RC',
                    'Ninea',
                    'AC',
                    'Quitus',
                    'Arrêté',
                    'Non-fonctionnaire',
                    'Organigramme',
                    'Contrat',
                    'Titre',
                    'Justificatif',
                    'ADEDGI',
                    'ABE',
                    'CME',
                    'CP',
                    'DENO',
                    'Bail',
                ]
            )
            ->orderBy('sigle', 'asc')
            ->distinct()
            ->get();

        $dateAgrement = $operateur->commissionagrements()
            ->orderByDesc('fin_commission')
            ->first();

        $dateExpiration = $dateAgrement
            ? Carbon::parse($dateAgrement?->fin_commission)->addYears(4)
            : null;
        $estExpire = $dateExpiration?->isPast();

        $dateExtension = $dateAgrement
            ? Carbon::parse($dateAgrement?->fin_commission)->addYears(2)
            : null;
        $estExtension = $dateExtension?->isPast();

        $dateQuitus = $operateur?->debut_quitus
            ? Carbon::parse($operateur->debut_quitus)
            : null;

        // Calcul de la différence pour le texte
        $diffText = $dateQuitus?->locale('fr')->diffForHumans(now(), true);

        // Calcul de la différence en mois pour le badge
        $diffInMonths = $dateQuitus ? ($dateQuitus->diffInYears(now()) * 12 + $dateQuitus->diffInMonths(now()) % 12) : 0;

        $sections = [
            [
                'label' => 'Modules',
                'icon' => 'bi-journal-code text-info',
                'count' => $operateur->operateurmodules->count(),
                'route' => route('operateurs.show', $operateur),
            ],
            [
                'label' => 'Références',
                'icon' => 'bi-bookmark-check text-primary',
                'count' => $operateur->operateureferences->count(),
                'route' => route('showReference', $operateur->uuid),
            ],
            [
                'label' => 'Équipements & Infrastructures',
                'icon' => 'bi-hdd-network text-warning',
                'count' => $operateur->operateurequipements->count(),
                'route' => route('showEquipement', $operateur->uuid),
            ],
            [
                'label' => 'Formateurs',
                'icon' => 'bi-person-workspace text-success',
                'count' => $operateur->operateurformateurs->count(),
                'route' => route('showFormateur', $operateur->uuid),
            ],
            [
                'label' => 'Localités',
                'icon' => 'bi-geo-alt text-danger',
                'count' => $operateur->operateurlocalites->count(),
                'route' => route('showLocalite', $operateur->uuid),
            ],

            [
                'label' => 'Validité quitus',
                'icon' => 'bi-file-earmark-text text-dark',
                'count' => $diffText,
                'badge' => $diffInMonths > 3 ? 'bg-danger' : 'bg-info',
                /* 'route' => null, */
                'modal' => "EditOperateurModal{$operateur->id}",
            ],
        ];

        return view(
            "operateurs.agrements.show",
            compact(
                "operateur",
                "operateureferences",
                "operateurs",
                'statut_demande',
                'module_count',
                'reference_count',
                'equipement_count',
                'formateur_count',
                'localite_count',
                /* 'fichier_count', */
                'dateAgrement',
                'dateExpiration',
                'estExpire',
                'dateExtension',
                'estExtension',
                'dateQuitus',
                'diffInMonths',
                'diffText',
                'sections',
                'departements',
                'labels',
            )
        );
    }

    public function destroy(Operateur $operateur)
    {
        $this->authorize('delete', $operateur);

        // Vérifier que le statut permet la suppression
        /* if ($operateur->statut_agrement !== 'Nouveau') {
            Alert::warning('Action refusée', 'Seuls les opérateurs avec le statut "Nouveau" peuvent être supprimés.');
            return redirect()->back();
        } */

        if (strtolower($operateur->file8) === 'oui') {
            Alert::error('Attention !', 'Impossible de supprimer car les informations ont déjà certifiés.');
            return redirect()->back();
        }

        // Supprimer le fichier quitus s’il existe
        if ($operateur->quitus) {
            Storage::disk('public')->delete($operateur->quitus);
        }

        // Supprimer l’opérateur
        $operateur->delete();

        // Message de succès
        Alert::success('Succès !', 'L\'opérateur a été supprimé avec succès.');

        return redirect()->back();
    }

    public function fetch(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data  = DB::table('modules')
                ->whereNull('deleted_at') // Exclure les enregistrements supprimés
                ->where('name', 'LIKE', "%{$query}%")
                ->distinct()
                ->get();

            $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
            foreach ($data as $row) {
                $output .= '
            <li><a class="dropdown-item" href="#">' . $row->name . '</a></li>
            ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function fetchModuleOperateur(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data  = DB::table('operateurmodules')
                ->whereNull('deleted_at')
                ->where('module', 'LIKE', "%{$query}%")
                ->select('module') // Ne sélectionner que la colonne module
                ->distinct()
                ->get();

            $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
            foreach ($data as $row) {
                $output .= '<li><a class="dropdown-item" href="#">' . $row->module . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function fetchOperateurModule(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data  = DB::table('operateurmodules')
                ->whereNull('deleted_at') // Exclure les enregistrements supprimés
                ->where('module', 'LIKE', "%{$query}%")
                ->distinct() // Optimisation : Filtrage de l'unicité au niveau SQL
                ->get();

            $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
            foreach ($data as $row) {
                $output .= '<li><a class="dropdown-item" href="#">' . $row->module . '</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function showReference($uuid)
    {
        $operateur          = Operateur::where('uuid', $uuid)->firstOrFail();
        $operateureferences = Operateureference::get();

        return view('operateureferences.show', compact('operateur', 'operateureferences'));
    }

    public function showEquipement($uuid)
    {
        $operateur            = Operateur::where('uuid', $uuid)->firstOrFail();
        $operateurequipements = Operateurequipement::get();

        return view('operateurequipements.show', compact('operateur', 'operateurequipements'));
    }

    public function showFormateur($uuid)
    {
        $operateur           = Operateur::where('uuid', $uuid)->firstOrFail();
        $operateurformateurs = Operateurformateur::get();

        return view('operateurformateurs.show', compact('operateur', 'operateurformateurs'));
    }

    public function showLocalite($uuid)
    {
        $operateur          = Operateur::where('uuid', $uuid)->firstOrFail();
        $operateurlocalites = Operateurlocalite::get();
        $regions            = Region::get();

        return view('operateurlocalites.show', compact('operateur', 'operateurlocalites', 'regions'));
    }

    /* Validation automatique */
    public function validateOperateur($id)
    {
        $operateur = Operateur::findOrFail($id);

        $moduleoperateur_count = $operateur->operateurmodules->count();

        if ($moduleoperateur_count > 0) {
            if ($operateur->statut_agrement == 'Nouveau' || $operateur->statut_agrement == 'Non conforme') {
                $operateur->update([
                    'statut_agrement' => 'Conforme',
                ]);

                $operateur->save();

                Alert::success("Succès !", "L'opérateur " . $operateur?->user?->username . ' a été retenu');

                return redirect()->back();
            } else {
                Alert::warning("Impossible ", "Car l'opérateur " . $operateur?->user?->username . ' a déjà été validé');

                return redirect()->back();
            }
        } else {
            Alert::warning('Désolé ! ', 'assurez-vous d\'avoir ajouté au moins un module');
            return redirect()->back();
        }
    }

    public function nonRetenu(Request $request, $id)
    {
        $request->validate([
            'motif' => $request->statut !== 'Conforme' ? 'required|string' : 'nullable|string',
        ]);

        $operateur = Operateur::findOrFail($id);
        $statut    = $operateur->statut_agrement;

        // Bloquer certains statuts uniquement pour les non-super-admins
        if (! auth()->user()->hasAnyRole(['super-admin', 'Ingenieur'])) {
            $messages = [
                'rejeté'       => 'demande déjà rejeté',
                'Programmer'   => 'demande déjà programmée',
                'Attente'      => 'demande déjà traitée',
                'Retenue'      => 'demande déjà traitée',
                'Terminée'     => 'demandeur déjà formé',
                'Former'       => 'demandeur déjà formé',
                'À corriger'   => 'demandeur déjà traitée',
                'Non validé'   => 'demandeur déjà traitée',
                'Conforme'     => 'demandeur déjà traitée',
                'Non conforme' => 'demandeur déjà traitée',
            ];

            if (array_key_exists($statut, $messages)) {
                Alert::warning('Désolé !', $messages[$statut]);
                return redirect()->back();
            }
        }

        $motif = $request->input('motif') ?? $request->statut;

        $operateur->update([
            'statut_agrement' => $request->statut,
            'motif'           => $motif,
        ]);

        $validationoperateur = new Validationoperateur([
            'action'        => $request->statut,
            'motif'         => $motif,
            'validated_id'  => Auth::user()->id,
            'session'       => $operateur?->session_agrement,
            'operateurs_id' => $operateur->id,

        ]);

        $validationoperateur->save();

        Alert::success('Succès !', $operateur?->user?->username . " est " . $request->statut);

        return redirect()->back();
    }

    public function validationAgrement(Request $request, $id)
    {
        $request->validate([
            'motif' => $request->statut !== 'Conforme' ? 'required|string' : 'nullable|string',
        ]);

        $operateur = Operateur::findOrFail($id);
        $statut    = $operateur->statut_agrement;

        // Bloquer certains statuts uniquement pour les non-super-admins
        if (! auth()->user()->hasAnyRole(['super-admin', 'Ingenieur'])) {
            $messages = [
                'rejeté'       => 'demande déjà rejeté',
                'Programmer'   => 'demande déjà programmée',
                'Attente'      => 'demande déjà traitée',
                'Retenue'      => 'demande déjà traitée',
                'Terminée'     => 'demandeur déjà formé',
                'Former'       => 'demandeur déjà formé',
                'À corriger'   => 'demandeur déjà traitée',
                'Non validé'   => 'demandeur déjà traitée',
                'Conforme'     => 'demandeur déjà traitée',
                'Non conforme' => 'demandeur déjà traitée',
            ];

            if (array_key_exists($statut, $messages)) {
                Alert::warning('Désolé !', $messages[$statut]);
                return redirect()->back();
            }
        }

        $motif = $request->input('motif') ?? $request->statut;

        $operateur->update([
            'statut_agrement' => $request->statut,
            'motif'           => $motif,
        ]);

        $validationoperateur = new Validationoperateur([
            'action'        => $request->statut,
            'motif'         => $motif,
            'validated_id'  => Auth::user()->id,
            'session'       => $operateur?->session_agrement,
            'operateurs_id' => $operateur->id,

        ]);

        $validationoperateur->save();

        Alert::success('Succès !', $operateur?->user?->username . " est " . $request->statut);

        return redirect()->back();
    }

    public function agreerOperateur($id)
    {
        $operateur             = Operateur::findOrFail($id);
        $moduleoperateur_count = $operateur->operateurmodules->count();

        $count_nouveau = $operateur->operateurmodules->where('statut', 'Nouveau')->count();

        if ($count_nouveau > 0) {
            Alert::warning('Désolé ! ', 'il reste de(s) module(s) à traiter');
            return redirect()->back();
        } elseif ($moduleoperateur_count <= '0') {
            Alert::warning('Désolé ! ', 'aucun module disponible pour cet opérateur');
            return redirect()->back();
        } else {
            $operateur->update([
                'statut_agrement' => 'agréé',
                'motif'           => null,
                'date'            => date('Y-m-d'),
            ]);

            $operateur->save();

            $validateoperateur = new Validationoperateur([
                'validated_id'  => Auth::user()->id,
                'action'        => 'agréé',
                'session'       => $operateur?->session_agrement,
                'operateurs_id' => $operateur?->id,

            ]);

            $validateoperateur->save();

            Alert::success("Succès !", "L'opérateur " . $operateur?->user?->username . ' a été agréé');
            return redirect()->back();
        }
    }

    public function agreerAllModuleOperateur($id)
    {
        $operateur = Operateur::findOrFail($id);

        foreach ($operateur->operateurmodules as $key => $operateurmodule) {

            $operateurmodule->update([
                'statut'   => 'agréé',
                'users_id' => Auth::user()->id,
            ]);

            $operateurmodule->save();

            Alert::success('Succès !', 'Tous les modules ont été agréés');
        }

        return redirect()->back();
    }

    public function retirerOperateur($id)
    {
        $operateur = Operateur::findOrFail($id);
        /* if ($operateur->statut_agrement != 'Nouveau') {
            Alert::warning('Attention ! ', 'action impossible opérateur déjà traité');
            return redirect()->back();
        } */

        if ($operateur->statut_agrement != 'Nouveau' && $operateur->statut_agrement != 'Non conforme') {
            Alert::warning('Attention !', 'Action impossible : opérateur déjà traité');
            return redirect()->back();
        }

        $operateur->update([
            'statut_agrement'        => 'Retiré',
            'commissionagrements_id' => null,
        ]);

        $operateur->save();

        $validateoperateur = new Validationoperateur([
            'validated_id'  => Auth::user()->id,
            'action'        => 'Retiré',
            'session'       => $operateur?->session_agrement,
            'operateurs_id' => $operateur?->id,

        ]);

        $validateoperateur->save();

        Alert::success("Succès !", "L'opérateur a été retiré");

        return redirect()->back();
    }

    public function retirerOperateurCommission($idoperateur, $idcommissionagrement)
    {
        $operateur = Operateur::findOrFail($idoperateur);

        $operateur->commissionagrements()->detach($idcommissionagrement);

        // Enregistrer historique (facultatif)
        Historiqueagrement::create([
            'operateurs_id'          => $operateur->id,
            'commissionagrements_id' => $idcommissionagrement,
            'statut'                 => 'Retiré de la commission',
            'validated_id'           => Auth::id(),
        ]);

        Alert::success("Succès !", "L'opérateur a été retiré de la commission d'agrément");

        return redirect()->back();
    }

    /*  public function devenirOperateur()
    {
        $user = Auth::user();
        // Si l'utilisateur N'EST PAS un opérateur, on stoppe avec une exception 403
        if (! $user->hasRole('Operateur')) {
            abort(403, 'Accès refusé.');
        }

        // Récupérer l'opérateur lié à l'utilisateur
        $operateur  = Operateur::where('users_id', $user->id)->orderBy("created_at", "desc")->first();
        $operateurA = Operateur::where('users_id', $user->id)->orderBy("created_at", "desc")->get();
        $operateurs = Operateur::all();

        $operateur_total = $operateurs->count();

        // Récupérer les fichiers associés à l'utilisateur
        $files = File::where('users_id', $user->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();

        $departements = Departement::orderBy("nom", "asc")->get();

        $labels = [
            'Ninea ou registre de commerce' => 'Registre de commerce',
        ];

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereIn(
                'sigle',
                [
                    'Ninea/RC',
                    'Ninea',
                    'AC',
                    'Quitus',
                    'Arrêté',
                    'Non-fonctionnaire',
                    'Organigramme',
                    'Contrat',
                    'Titre',
                    'Justificatif',
                    'ADEDGI',
                    'ABE',
                    'CME',
                    'CP',
                    'DENO',
                    'Bail',
                ]
            )
            ->orderBy('sigle', 'asc')
            ->distinct()
            ->get();

        if ($operateur_total >= 1 && $operateur) {
            // Récupérer les counts des relations de l'opérateur
            $module_count     = Operateurmodule::where('operateurs_id', $operateur->id)->exists();
            $reference_count  = Operateureference::where('operateurs_id', $operateur->id)->exists();
            $equipement_count = Operateurequipement::where('operateurs_id', $operateur->id)->exists();
            $formateur_count  = Operateurformateur::where('operateurs_id', $operateur->id)->exists();
            $localite_count   = Operateurlocalite::where('operateurs_id', $operateur->id)->exists();

            // Compter les fichiers liés à l'utilisateur (champ file non nul)
            $fichiers_total = $operateur->user?->files()
                ->whereNotNull('file')
                ->count();

            function getStatutFichiers($categorie, $fichiers_total)
            {
                if ($categorie !== 'Public') {
                    return $fichiers_total >= 4 ? 'complète' : 'incomplète';
                } else {
                    return $fichiers_total >= 1 ? 'complète' : 'incomplète';
                }
            }

            // Utilisation
            $fichier_count = getStatutFichiers($operateur?->user?->categorie, $fichiers_total);

            // Statut global
            $statut_demande = (
                $module_count === "complète" &&
                $reference_count === "complète" &&
                $equipement_count === "complète" &&
                $formateur_count === "complète" &&
                $localite_count === "complète" &&
                $fichier_count === "complète"
            );

            $dernierAgrement = $operateur->commissionagrements()
                ->orderByDesc('fin_commission')
                ->first();

            $dateAgrement = $dernierAgrement
                ? Carbon::parse($dernierAgrement->fin_commission)
                : null;

            $dateExpiration = $dateAgrement?->copy()->addYears(4);
            $estExpire      = $dateExpiration?->isPast();

            $dateExtension = $dateAgrement?->copy()->addYears(2);
            $estExtension  = $dateExtension?->isPast();

            $dateQuitus = $operateur?->debut_quitus
                ? Carbon::parse($operateur->debut_quitus)
                : null;

            $dateQuitus = $operateur?->debut_quitus
                ? Carbon::parse($operateur->debut_quitus)
                : null;

            // Calcul de la différence pour le texte
            $diffText = $dateQuitus?->locale('fr')->diffForHumans(now(), true);

            // Calcul de la différence en mois pour le badge
            $diffInMonths = $dateQuitus ? ($dateQuitus->diffInYears(now()) * 12 + $dateQuitus->diffInMonths(now()) % 12) : 0;

            $sections = [
                [
                    'label' => 'Modules',
                    'icon' => 'bi-journal-code text-info',
                    'count' => $operateur->operateurmodules->count(),
                    'route' => route('operateurs.show', $operateur),
                ],
                [
                    'label' => 'Références',
                    'icon' => 'bi-bookmark-check text-primary',
                    'count' => $operateur->operateureferences->count(),
                    'route' => route('showReference', $operateur->uuid),
                ],
                [
                    'label' => 'Équipements & Infrastructures',
                    'icon' => 'bi-hdd-network text-warning',
                    'count' => $operateur->operateurequipements->count(),
                    'route' => route('showEquipement', $operateur->uuid),
                ],
                [
                    'label' => 'Formateurs',
                    'icon' => 'bi-person-workspace text-success',
                    'count' => $operateur->operateurformateurs->count(),
                    'route' => route('showFormateur', $operateur->uuid),
                ],
                [
                    'label' => 'Localités',
                    'icon' => 'bi-geo-alt text-danger',
                    'count' => $operateur->operateurlocalites->count(),
                    'route' => route('showLocalite', $operateur->uuid),
                ],

                [
                    'label' => 'Validité quitus',
                    'icon' => 'bi-file-earmark-text text-dark',
                    'count' => $diffText,
                    'badge' => $diffInMonths > 3 ? 'bg-danger' : 'bg-info',
                    'modal' => "EditOperateurModal{$operateur->id}",
                ],
            ];

            // Retourner la vue avec les données
            return view(
                'operateurs.show-operateur',
                compact(
                    'operateur_total',
                    'user_files',
                    'files',
                    'departements',
                    'operateur',
                    'operateurA',
                    'operateurs',
                    'statut_demande',
                    'module_count',
                    'reference_count',
                    'equipement_count',
                    'formateur_count',
                    'localite_count',
                    'dateAgrement',
                    'dateExpiration',
                    'estExpire',
                    'dateExtension',
                    'estExtension',
                    'dateQuitus',
                    'labels',
                    'diffText',
                    'sections',
                )
            );
        } else {
            // Si aucun opérateur n'est trouvé, afficher une vue différente
            return view(
                'operateurs.show-operateur-aucun',
                compact(
                    'departements',
                    'operateur',
                    'operateurs',
                    'user'
                )
            );
        }
    } */

    public function devenirOperateur()
    {
        $user = Auth::user();

        // Vérifier le rôle
        if (! $user->hasRole('Operateur')) {
            abort(403, 'Accès refusé.');
        }

        // Charger l'opérateur avec toutes les relations nécessaires
        $operateur = Operateur::with([
            'operateurmodules',
            'operateureferences',
            'operateurequipements',
            'operateurformateurs',
            'operateurlocalites',
            'commissionagrements',
            'user.files'
        ])->where('users_id', $user->id)
            ->orderByDesc('id')
            ->first();

        $operateurs = Operateur::where('users_id', $user->id)->orderByDesc('id')->get();
        $operateur_total = $operateurs->count();

        $departements = Departement::orderBy('nom', 'asc')->get();

        $labels = [
            'Ninea ou registre de commerce' => 'Registre de commerce',
        ];

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereIn('sigle', [
                'Ninea/RC',
                'Ninea',
                'AC',
                'Quitus',
                'Arrêté',
                'Non-fonctionnaire',
                'Organigramme',
                'Contrat',
                'Titre',
                'Justificatif',
                'ADEDGI',
                'ABE',
                'CME',
                'CP',
                'DENO',
                'Autorisation',
                'Bail'
            ])
            ->orderBy('sigle', 'asc')
            ->distinct()
            ->get();

        // Récupérer les fichiers associés à l'utilisateur
        $files = File::where('users_id', $user->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();

        //Pour les établissements Publics
        //Pour les établissements Privés
        $hasNinea = $files->contains(
            fn($file) => $file->sigle === 'Ninea',
        );

        //Pour les établissements Publics
        //Pour les établissements Privés
        $hasQuitus = $files->contains(
            fn($file) => $file->sigle === 'Quitus',
        );

        //Pour les établissements Publics
        //Pour les établissements Privés
        $hasAC = $files->contains(
            fn($file) => $file->sigle === 'AC',
        );

        //Pour les établissements Privés
        $hasContrat = $files->contains(
            fn($file) => $file->sigle === 'Contrat',
        );

        //Pour les établissements Privés
        $hasNF = $files->contains(
            fn($file) => $file->sigle === 'Non-fonctionnaire',
        );

        //Pour les établissements Privés

        /* $hasRC = $files->contains(
            fn($file) => $file->sigle === 'Ninea/RC',
        ); */


        /* $hasRC = $files->contains(
            fn($file) => in_array($file->sigle, [
                'Ninea/RC',
                'AC',
            ]),
        ); */

        // Vérification des documents
        /* $hasAuto = $files->contains(
            fn($file) => $file->sigle === 'Autorisation',
        ); */

        /* $hasOrganigramme = $files->contains(
            fn($file) => $file->sigle === 'Organigramme',
        ); */

        $statuts = [
            'GIE',
            'SA',
            'SUARL',
            'SAS',
            'SARL',
            'SNC',
            'SCS',
            'Association',
            'Etablissement public',
            'Entreprise individuelle',
            'Autre',
        ];

        $selected = old('statut', $user?->statut);

        if ($operateur_total >= 1 && $operateur) {

            // Catégorie de l'utilisateur
            $cat = $operateur->user?->categorie;

            // Initialiser les booléens
            $hasNinea = false;
            $hasQuitus = false;
            $hasAC = false;
            $hasContrat = false;
            $hasNF = false;

            if ($cat === 'Public') {
                $hasNinea = $files->contains(fn($file) => $file->sigle === 'Ninea');
                $hasAC    = $files->contains(fn($file) => $file->sigle === 'AC');
                $hasQuitus = $files->contains(fn($file) => $file->sigle === 'Quitus');
                // Publics ne nécessitent pas AC, Contrat, NF
            } else {
                // Privés
                $hasNinea    = $files->contains(fn($file) => $file->sigle === 'Ninea');
                $hasQuitus   = $files->contains(fn($file) => $file->sigle === 'Quitus');
                $hasAC       = $files->contains(fn($file) => $file->sigle === 'AC');
                $hasContrat  = $files->contains(fn($file) => $file->sigle === 'Contrat');
                $hasNF       = $files->contains(fn($file) => $file->sigle === 'Non-fonctionnaire');
            }

            // Statuts des relations
            $module_count     = $operateur->operateurmodules->isNotEmpty();
            $reference_count  = $operateur->operateureferences->isNotEmpty();
            $equipement_count = $operateur->operateurequipements->isNotEmpty();
            $formateur_count  = $operateur->operateurformateurs->isNotEmpty();
            $localite_count   = $operateur->operateurlocalites->isNotEmpty();

            /* 
            // Statut global
            $statut_demande = collect([$module_count, $reference_count, $equipement_count, $formateur_count, $localite_count])
                ->every(fn($s) => $s === 'complète') ? 'complète' : 'incomplète'; */

            $statut_demande = $operateur->profilEstComplet() ? 'complète' : 'incomplète';

            // Dernier agrément et dates
            $dernierAgrement = $operateur->commissionagrements->sortByDesc('fin_commission')->first();
            $dateAgrement    = $dernierAgrement ? Carbon::parse($dernierAgrement->fin_commission) : null;

            $dateExpiration  = $dateAgrement?->copy()->addYears(4);
            $estExpire       = $dateExpiration?->isPast();

            $dateExtension   = $dateAgrement?->copy()->addYears(2);
            $estExtension    = $dateExtension?->isPast();

            $dateQuitus = $operateur?->debut_quitus ? Carbon::parse($operateur->debut_quitus) : null;
            $diffText   = $dateQuitus?->locale('fr')->diffForHumans(now(), true);
            $diffInMonths = $dateQuitus ? $dateQuitus->diffInMonths(now()) : 0;

            // Sections pour la vue
            $sections = [
                ['label' => 'Modules', 'icon' => 'bi-journal-code text-info', 'count' => $operateur->operateurmodules->count(), 'route' => route('operateurs.show', $operateur)],
                ['label' => 'Références', 'icon' => 'bi-bookmark-check text-primary', 'count' => $operateur->operateureferences->count(), 'route' => route('showReference', $operateur->uuid)],
                ['label' => 'Équipements & Infrastructures', 'icon' => 'bi-hdd-network text-warning', 'count' => $operateur->operateurequipements->count(), 'route' => route('showEquipement', $operateur->uuid)],
                ['label' => 'Formateurs', 'icon' => 'bi-person-workspace text-success', 'count' => $operateur->operateurformateurs->count(), 'route' => route('showFormateur', $operateur->uuid)],
                ['label' => 'Localités', 'icon' => 'bi-geo-alt text-danger', 'count' => $operateur->operateurlocalites->count(), 'route' => route('showLocalite', $operateur->uuid)],
                ['label' => 'Validité quitus', 'icon' => 'bi-file-earmark-text text-dark', 'count' => $diffText, 'badge' => $diffInMonths > 3 ? 'bg-danger' : 'bg-info', 'modal' => "EditOperateurModal{$operateur->id}"]
            ];


            $estCertifie = boolval($operateur->file8);

            // Retourner la vue principale
            return view('operateurs.show-operateur', compact(
                'operateur_total',
                'user_files',
                'files',
                'departements',
                'operateur',
                'operateurs',
                'statut_demande',
                'module_count',
                'reference_count',
                'equipement_count',
                'formateur_count',
                'localite_count',
                'dateAgrement',
                'dateExpiration',
                'estExpire',
                'dateExtension',
                'estExtension',
                'dateQuitus',
                'labels',
                'diffText',
                'hasNinea',
                'hasAC',
                'hasContrat',
                'hasQuitus',
                'hasNF',
                'statuts',
                'estCertifie',
                'sections'
            ));
        } else {
            $hasRequiredFields =
                collect([
                    $user?->operateur,
                    $user?->ninea,
                    $user?->fonction_responsable,
                    $user?->email,
                ])
                ->filter()
                ->count() === 4;

            return view(
                'operateurs.show-operateur-aucun',
                compact(
                    'departements',
                    'operateur',
                    'hasRequiredFields',
                    'statuts',
                    'selected',
                    /*  'operateurs', */
                    'user'
                )
            );
        }
    }


    public function mesFormations()
    {
        $user = Auth::user();
        // Si l'utilisateur N'EST PAS un opérateur, on stoppe avec une exception 403
        if (! $user->hasRole('Operateur')) {
            abort(403, 'Accès refusé.');
        }

        // Récupérer l'opérateur lié à l'utilisateur
        $operateur = Operateur::where('users_id', $user->id)->orderBy("created_at", "desc")->first();

        // Si aucun opérateur n'est trouvé, afficher une vue différente
        return view(
            'operateurs.mesformation',
            compact('operateur')
        );
    }

    public function rapports(Request $request)
    {
        $title          = 'rapports opérateurs';
        $regions        = Region::orderBy("created_at", "desc")->get();
        $module_statuts = Operateurmodule::get()->unique('statut');

        $operateurs = Operateur::get();

        // Regrouper par statut (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut';
        });

        return view('operateurs.rapports', compact(
            'title',
            'regions',
            'module_statuts',
            'groupes'
        ));
    }

    public function generateRapport(Request $request)
    {

        if ($request->valeur_region == "1") {
            $this->validate($request, [
                'region' => 'required|string',
                'statut' => 'required|string',
            ]);

            $region = Region::findOrFail($request->region);

            $operateurs = Operateur::where('statut_agrement', 'LIKE', "{$request->statut}")
                ->where('regions_id', "{$request->region}")
                ->get();

            // Regrouper par statut_agrement (y compris les null)
            $groupes = $operateurs->groupBy(function ($item) {
                return $item->statut_agrement ?? 'Aucun statut agrement';
            });

            $count = $operateurs->count();

            $statut = $request->statut;

            $title = $count . ' opérateur(s) ' . $statut . '(s) à ' . $region->nom;
        } elseif ($request->valeur_module == "1") {
            $this->validate($request, [
                'module' => 'required|string',
                'statut' => 'required|string',
            ]);

            $operateurs = Operateur::join('operateurmodules', 'operateurs.id', 'operateurmodules.operateurs_id')
                ->select('operateurs.*')
                ->where('statut_agrement', 'LIKE', "%{$request->statut}%")
                ->where('operateurmodules.module', 'LIKE', "%{$request->module}%")
                ->distinct()
                ->get();

            // Regrouper par statut_agrement (y compris les null)
            $groupes = $operateurs->groupBy(function ($item) {
                return $item->statut_agrement ?? 'Aucun statut agrement';
            });

            $count = $operateurs->count();

            $statut = $request->statut;

            $title = $count . ' opérateur(s) ' . $statut . '(s) en ' . $request->module;
        } else {
            $this->validate($request, [
                'region' => 'required|string',
                'module' => 'required|string',
                'statut' => 'required|string',
            ]);

            $region = Region::findOrFail($request->region);

            $operateurs = Operateur::join('operateurmodules', 'operateurs.id', 'operateurmodules.operateurs_id')
                ->select('operateurs.*')
                ->where('statut_agrement', 'LIKE', "%{$request->statut}%")
                ->where('regions_id', "{$request->region}")
                ->where('operateurmodules.module', 'LIKE', "%{$request->module}%")
                ->distinct()
                ->get();

            // Regrouper par statut_agrement (y compris les null)
            $groupes = $operateurs->groupBy(function ($item) {
                return $item->statut_agrement ?? 'Aucun statut agrement';
            });

            $count = $operateurs->count();

            $statut = $request->statut;

            $title = $count . ' opérateur(s) ' . $statut . '(s) dans la région de  ' . $region->nom . ' en ' . $request->module;
        }

        $regions        = Region::orderBy("created_at", "desc")->get();
        $module_statuts = Operateurmodule::get()->unique('statut');

        return view('operateurs.rapports', compact(
            'module_statuts',
            'operateurs',
            'title',
            'groupes',
            'regions'
        ));
    }

    public function observations(Request $request, $id)
    {
        $this->validate($request, [
            'observation'       => 'required|string',
            'visite_conformite' => 'required|string',
        ]);

        $operateur = Operateur::findOrFail($id);

        $operateur->update([
            'observations'      => $request->input('observation'),
            'visite_conformite' => $request->input('visite_conformite'),
        ]);

        $operateur->save();

        Alert::success('Félicitations', 'Observations enregistrées');
        return redirect()->back();
    }

    public function ficheSynthese(Request $request)
    {
        $commission = Commissionagrement::find($request->input('id'));

        $operateurs = Operateur::where('statut_agrement', '!=', 'non retenu')
            ->where('commissionagrements_id', $request->input('id'))
            ->get();

        $title = 'Fiche de synthèse ' . $commission?->commission . ' du ' . $commission?->date?->translatedFormat('l d F Y') . ' à ' . $commission?->lieu;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view(
            'operateurs.fichesynthese',
            compact(
                'commission',
                'operateurs',
                'title'
            )
        ));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('Letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Fiche de synthèse ' . $commission?->commission . ' du ' . $commission?->date?->translatedFormat('l d F Y') . ' à ' . $commission?->lieu . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function ficheSyntheseOperateur(Request $request)
    {
        $operateur = Operateur::findOrFail($request->input('id'));

        $title = 'Fiche de synthèse ' . $operateur?->user?->operateur;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view(
            'operateurs.fichesyntheseoperateur',
            compact(
                'operateur',
                'title'
            )
        ));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('Letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Fiche de synthèse ' . $operateur?->user?->operateur . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function lettreAgrement(Request $request)
    {
        $commission = Commissionagrement::find($request->input('id'));

        $operateurs_count = Operateur::where('statut_agrement', 'agréé')
            ->where('commissionagrements_id', $request->input('id'))
            ->count();

        /* $operateurs = Operateur::offset($request->value1)->limit($request->value2)->where('statut_agrement', 'agréé')
        ->where('commissionagrements_id', $request->input('id'))
        ->get(); */

        $operateurs = Operateur::where('statut_agrement', 'agréé')
            ->where('commissionagrements_id', $request->input('id'))
            ->get();

        $title = 'Lettres agrément opérateurs, ' . $commission?->commission . ' du ' . $commission?->date?->translatedFormat('l d F Y') . ' à ' . $commission?->lieu;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view(
            'operateurs.lettreagrement',
            compact(
                'operateurs',
                'title'
            )
        ));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('Letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Lettres agrément opérateurs, ' . $commission?->commission . ' du ' . $commission?->date?->translatedFormat('l d F Y') . ' à ' . $commission?->lieu . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function generateReport(Request $request)
    {
        /* dd("ok"); */
        $this->validate($request, [
            'operateur_name'  => 'nullable|string',
            'operateur_sigle' => 'nullable|string',
            'numero_agrement' => 'nullable|string',
            'telephone'       => 'nullable|string',
            'email'           => 'nullable|email',
        ]);

        // Vérifier si au moins un champ est renseigné
        $searchFields = [
            $request?->operateur_name,
            $request?->operateur_sigle,
            $request?->telephone,
            $request?->numero_agrement,
            $request?->email,
        ];

        if (empty(array_filter($searchFields))) {
            Alert::warning('Oups !', 'Renseigner au moins un champ pour rechercher');
            return redirect()->back();
        }

        // Récupération des départements
        $departements = Departement::latest()->get();

        /* $statuts = $operateurs->pluck('statut_agrement')->unique()->values()->all(); */

        // Comptage des statuts avec une seule requête SQL
        /* $statCounts = Operateur::whereIn('statut_agrement', $statuts)
            ->selectRaw("
            SUM(statut_agrement = 'agréé') AS agreer,
            SUM(statut_agrement = 'rejeté') AS rejeter,
            SUM(statut_agrement = 'nouveau') AS nouveau,
            SUM(statut_agrement = 'expirer') AS expirer,
            COUNT(*) AS total
        ")->first();

        $operateur_total     = $statCounts->total;
        $pourcentage_agreer  = $operateur_total ? ($statCounts->agreer / $operateur_total) * 100 : 0;
        $pourcentage_rejeter = $operateur_total ? ($statCounts->rejeter / $operateur_total) * 100 : 0;
        $pourcentage_nouveau = $operateur_total ? ($statCounts->nouveau / $operateur_total) * 100 : 0;
        $pourcentage_expirer = $operateur_total ? ($statCounts->expirer / $operateur_total) * 100 : 0;

        $operateur_agreer  = $statCounts->agreer;
        $operateur_rejeter = $statCounts->rejeter;
        $operateur_nouveau = $statCounts->nouveau;
        $operateur_expirer = $statCounts->expirer; */

        // Requête de recherche optimisée
        $operateurs = Operateur::join('users', 'users.id', '=', 'operateurs.users_id')
            ->select('operateurs.*')
            ->when($request?->operateur_name, fn($query, $value) => $query->where('operateur', 'LIKE', "%$value%"))
            ->when($request?->operateur_sigle, fn($query, $value) => $query->where('username', 'LIKE', "%$value%"))
            ->when($request?->numero_agrement, fn($query, $value) => $query->where('numero_agrement', 'LIKE', "%$value%"))
            ->when($request?->telephone, function ($query, $value) {
                $query->where(function ($subQuery) use ($value) {
                    $subQuery->where('users.fixe', 'LIKE', "%$value%")
                        ->orWhere('telephone', 'LIKE', "%$value%")
                        ->orWhere('telephone_secondaire', 'LIKE', "%$value%")
                        ->orWhere('telephone_parent', 'LIKE', "%$value%");
                });
            })
            ->when($request?->email, fn($query, $value) => $query->where('users.email', 'LIKE', "%$value%"))
            ->distinct()
            ->get();

        $allOperateurs = Operateur::select('*')->get();
        // Récupérer les différents statuts
        /* $statuts = $operateurs->pluck('statut_agrement')->unique(); */

        // Regrouper par statut_agrement (y compris les null)
        $groupesStatutAgrement = $allOperateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        });
        /* $count               = $operateurs->count(); */
        $commissionagrements = Commissionagrement::orderBy('commission', 'desc')->get();

        // Gestion du titre des résultats
        /* $title = match ($count) {
            0 => 'Aucun opérateur trouvé',
            1 => '1 opérateur trouvé',
            default => "$count opérateurs trouvés"
        };
 */

        $totalOperateurs = number_format($operateurs?->count(), 0, ',', ' ');

        // Regrouper par statut_agrement (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        });

        $affichees = $operateurs?->count();
        $total     = $totalOperateurs ?? ($operateurs instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $operateurs->total()
            : $operateurs?->count());


        $recherche = 1;

        return view('operateurs.index', compact(
            'operateurs',
            'departements',
            'commissionagrements',
            'totalOperateurs',
            'groupesStatutAgrement',
            'affichees',
            'total',
            'recherche',
            /* 'statCounts',
            'pourcentage_agreer',
            'pourcentage_rejeter',
            'pourcentage_nouveau',
            'pourcentage_expirer',
            "operateur_agreer",
            "operateur_rejeter",
            "operateur_nouveau",
            "operateur_expirer", */
            "groupes",
            /* 'title' */
        ));
    }

    public function agreer(Request $request)
    {
        $title      = "Liste des opérateurs agréés";
        $operateurs = Operateur::where('statut_agrement', 'agréé')->get();
        return view(
            'operateurs.agreer',
            compact(
                'title',
                'operateurs'
            )
        );
    }

    public function expirer(Request $request)
    {
        $title      = "Liste des opérateurs dont l'agrément est arrivé à expiration";
        $operateurs = Operateur::where('statut_agrement', 'expirer')->get();
        return view(
            'operateurs.expirer',
            compact(
                'title',
                'operateurs'
            )
        );
    }

    public function lettreOperateur(Request $request)
    {

        $operateur = Operateur::findOrFail($request->id);

        $title = 'Lettres agrément , ' . $operateur?->user?->operateur;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('operateurs.lettreoperateur', compact(
            'operateur',
            'title'
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('Letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Lettres agrément opérateurs, ' . $operateur?->user?->operateur . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function filtrerOperateurParStatut($statut)
    {
        $operateurs = Operateur::when($statut !== 'Aucun', function ($query) use ($statut) {
            $query->where('statut_agrement', $statut);
        }, function ($query) {
            $query->whereNull('statut_agrement');
        })->get();

        // Regrouper par statut (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->user->categorie ?? 'Aucune';
        });

        $operateur_liste = $operateurs->take(50);

        $total_count = number_format($operateurs->count(), 0, ',', ' ');

        $count_operateur = number_format($operateur_liste->count(), 0, ',', ' ');

        /* $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "$count_operateur opérateur sur un total de $total_count",
            default => "Liste des $count_operateur derniers opérateurs sur un total de $total_count",
        }; */

        $totalOperateurs = number_format($operateurs->count(), 0, ',', ' ');

        $affichees = $operateurs?->count();
        $total     = $totalOperateurs ?? ($operateurs instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $operateurs->total()
            : $operateurs?->count());

        return view(
            'operateurs.filtrageoperateur-statut',
            compact(
                'operateurs',
                'statut',
                'groupes',
                'totalOperateurs',
                'affichees',
                'total'
            )
        );
    }

    public function filtrerOperateurParStatutCommission($statut, $commission)
    {
        $commissionagrement = Commissionagrement::findOrFail($commission);

        $operateurs = Operateur::where('statut_agrement', $statut)
            ->whereHas('commissionagrements', function ($query) use ($commission) {
                $query->where('commissionagrement_id', $commission);
            })
            ->get();

        if ($statut === 'sous réserve') {
            return view(
                'operateurs.commissionagrements.statutsousreserve',
                compact(
                    'operateurs',
                    'statut',
                    'commissionagrement'
                )
            );
        } elseif ($statut === 'rejeté') {
            return view(
                'operateurs.commissionagrements.statutrejete',
                compact(
                    'operateurs',
                    'statut',
                    'commissionagrement'
                )
            );
        } else {
            return view(
                'operateurs.commissionagrements.statut',
                compact(
                    'operateurs',
                    'statut',
                    'commissionagrement'
                )
            );
        }
    }

    public function exporterOperateursPDF($statut, $commission)
    {
        if ($statut === 'agréé' || $statut === 'sous réserve' || $statut === 'rejeté') {

            $commissionagrement = Commissionagrement::findOrFail($commission);

            $operateurs = Operateur::when($statut !== 'Aucun', function ($query) use ($statut) {
                $query->where('statut_agrement', $statut);
            }, function ($query) {
                $query->whereNull('statut_agrement');
            })
                ->when(! empty($commission), function ($query) use ($commission) {
                    $query->whereHas('commissionagrements', function ($q) use ($commission) {
                        $q->where('commissionagrements.id', $commission);
                    });
                })
                /* ->with(['operateurmodules.domaine', 'operateurmodules.niveau_qualification']) */
                ->get();

            //landscape ou portrait
            $pdf = Pdf::loadView('operateurs.pdf', compact('operateurs', 'statut', 'commissionagrement'))
                ->setPaper('A4', 'landscape');

            $date = now();
            $name = 'Liste des opérateurs ' . $statut . ' en ' . $commissionagrement?->date?->format('Y') . ' ' . $date;

            return $pdf->download($name . ".pdf");
        } else {
            Alert::error('Attention', 'Impossible de télécharger avec le statut : ' . $statut);
        }

        return redirect()->back();
    }

    public function exporterlettreagrementPDF($statut, $commission)
    {
        try {
            if ($statut !== 'agréé') {
                Alert::error('Attention', 'Impossible de télécharger les lettres : statut invalide.');
                return redirect()->back();
            }

            // Récupération de la commission
            $commissionagrement = Commissionagrement::findOrFail($commission);

            // Récupération des opérateurs agréés associés à la commission
            $operateurs = Operateur::query()
                ->where('statut_agrement', $statut)
                ->when(!empty($commission), function ($query) use ($commission) {
                    $query->whereHas('commissionagrements', function ($q) use ($commission) {
                        $q->where('commissionagrements.id', $commission);
                    });
                })
                ->get();

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view(
                'operateurs.leslettreoperateur',
                compact(
                    'operateurs',
                    'statut',
                    'commissionagrement'
                )
            ));


            // (Optional) Setup the paper size and orientation (portrait ou landscape)
            $dompdf->setPaper('Letter', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            /* $name = 'Lettres agrément opérateurs, ' . $commissionagrement->commission . '.pdf'; */
            $name = 'Lettres_agrement_operateurs_' . $commissionagrement->commission . '.pdf';

            // Optionnel : remplacer les caractères accentués
            $name = str_replace(
                [' ', 'é', 'è', 'ê', 'à', 'ç', ','],
                ['_', 'e', 'e', 'e', 'a', 'c', ''],
                $name
            );

            // Pour forcer le téléchargement
            /* $dompdf->stream($name, ['Attachment' => true]); */

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors de la génération du PDF.');
            return redirect()->back();
        }
    }

    public function exporterOperateursExcel($statut, $commission)
    {
        if ($statut === 'agréé' || $statut === 'sous réserve' || $statut === 'rejeté') {

            $commissionagrement = Commissionagrement::findOrFail($commission);

            $date = now()->format('Y-m-d');

            $fileName = 'Liste des opérateurs ' . $statut . ' en ' . $commissionagrement?->date?->format('Y') . ' (' . $date . ').xlsx';

            /*  if ($statut === 'sous réserve') {
            return view('operateurs.commissionagrements.statutsousreserve',
                compact('operateurs',
                    'statut', 'commissionagrement')
            );
        } elseif ($statut === 'rejeté') {
            return view('operateurs.commissionagrements.statutrejete',
                compact('operateurs',
                    'statut', 'commissionagrement')
            );
        } else {
            return view('operateurs.commissionagrements.statut',
                compact('operateurs',
                    'statut', 'commissionagrement')
            );
        } */

            return Excel::download(new OperateursAgrementExport($statut, $commissionagrement), $fileName);
        } else {
            Alert::error('Attention', 'Impossible de télécharger avec le statut : ' . $statut);
            return redirect()->back();
        }
    }

    public function filtrerOperateurParStatutCategorie($statut, $categorie)
    {
        /* $operateurs = Operateur::when($statut !== 'Aucun', function ($query) use ($statut) {
            $query->where('statut_agrement', $statut);
        }, function ($query) {
            $query->whereNull('statut_agrement');
        })
            ->get(); */

        if ($categorie === 'Aucune') {
            $operateurs = Operateur::when($statut !== 'Aucun', function ($query) use ($statut) {
                $query->where('statut_agrement', $statut);
            }, function ($query) {
                $query->whereNull('statut_agrement');
            })
                ->when($categorie !== 'Toutes', function ($query) use ($categorie) {
                    $query->whereHas('user', function ($q) use ($categorie) {
                        $q->where('categorie', null);
                    });
                })
                ->get();
        } else {
            $operateurs = Operateur::when($statut !== 'Aucun', function ($query) use ($statut) {
                $query->where('statut_agrement', $statut);
            }, function ($query) {
                $query->whereNull('statut_agrement');
            })
                ->when($categorie !== 'Toutes', function ($query) use ($categorie) {
                    $query->whereHas('user', function ($q) use ($categorie) {
                        $q->where('categorie', $categorie);
                    });
                })
                ->get();
        }

        // Regrouper par statut (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->user->categorie ?? 'Aucune';
        });

        $operateur_liste = $operateurs->take(50);

        $total_count = number_format($operateurs->count(), 0, ',', ' ');

        $count_operateur = number_format($operateur_liste->count(), 0, ',', ' ');

        /* $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "Structure $categorie(s) | $count_operateur opérateur sur un total de $total_count",
            default => "Structure $categorie | liste des $count_operateur derniers opérateurs sur un total de $total_count",
        }; */
        $affichees = $operateurs?->count();
        $total     = $totalOperateurs ?? ($operateurs instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $operateurs->total()
            : $operateurs?->count());

        return view(
            'operateurs.filtrageoperateur-statut-categorie',
            compact(
                'operateurs',
                'statut',
                'groupes',
                'categorie',
                'affichees',
                'total'
            )
        );
    }

    public function validationsRejetMessageOP(Request $request)
    {
        /* $individuelle = Individuelle::findOrFail($request?->input('id')); */
        $operateur = Operateur::with(['validationoperateurs' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($request->input('id'));

        return view("operateurs.validationsrejetmessageop", compact('operateur'));
    }

    public function filtrerOperateurParCAL($calid)
    {
        /* $operateurs = Operateur::where('commissionagrements_id', $calid)->get(); */
        $cal = Commissionagrement::findOrFail($calid);

        /* $total_count  = number_format($operateurs->count(), 0, ',', ' '); */
        $departements = Departement::orderBy("nom", "asc")->get();

        /* $operateur_liste = Operateur::latest()->take(50)->get();
        $count_operateur = number_format($operateur_liste->count(), 0, ',', ' '); */

        /* $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "$count_operateur opérateur sur un total de $total_count",
            default => "Liste des $count_operateur derniers opérateurs sur un total de $total_count",
        }; */

        $title = $cal?->commission . ' du ' . $cal?->date?->translatedFormat('l d F Y') . ' à ' . $cal?->lieu;

        $groupesStatutAgrement = $cal?->operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        });

        // On récupère la commission
        $commissionagrement = Commissionagrement::with('operateurs')->findOrFail($calid);

        // Récupérer les opérateurs liés
        $operateurs = $commissionagrement?->operateurs;

        /* $operateurs = Operateur::where('statut_agrement', $statut)
            ->whereHas('commissionagrements', function ($query) use ($commission) {
                $query->where('commissionagrement_id', $commission);
            })
            ->get(); */

        /* Pour exporter opérateurs + scans */
        // Taille d’une tranche
        $step = 25;
        // Découper en chunks de 25
        $chunks = $operateurs->chunk($step);

        /* Pour exporter les lettres agrement */
        // Taille d’une tranche
        $taille = 20;
        // Découper en taille de 20
        $coupes = $operateurs->chunk($taille);

        // Regrouper par statut (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->region->nom ?? 'Aucune région';
        });

        return view(
            "operateurs.cal",
            compact(
                /* "operateurs", */
                "groupesStatutAgrement",
                "departements",
                "title",
                "cal",
                "chunks",
                "step",
                "taille",
                "coupes",
                "groupes",
            )
        );
    }

    public function filtrerOperateurParCALRegion($calid, $region)
    {
        /* $operateurs = Operateur::where('commissionagrements_id', $calid)->get(); */
        $cal = Commissionagrement::findOrFail($calid);

        /* $total_count  = number_format($operateurs->count(), 0, ',', ' '); */
        $region       = Region::where("nom", $region)->firstOrFail();
        $departements = $region->departements;

        /* $operateur_liste = Operateur::latest()->take(50)->get();
        $count_operateur = number_format($operateur_liste->count(), 0, ',', ' '); */

        /* $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "$count_operateur opérateur sur un total de $total_count",
            default => "Liste des $count_operateur derniers opérateurs sur un total de $total_count",
        }; */

        $title = $cal?->commission . ' du ' . $cal?->date?->translatedFormat('l d F Y') . ' à ' . $cal?->lieu;

        /* $groupesStatutAgrement = $cal?->operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        }); */

        $groupesStatutAgrement = $cal?->operateurs
            ->where('regions_id', $region?->id) // filtre par région
            ->groupBy(function ($item) {
                return $item->statut_agrement ?? 'Aucun statut agrement';
            });
        /*
        // On récupère la commission
        $commissionagrement = Commissionagrement::with('operateurs')->findOrFail($calid);

        // Récupérer les opérateurs liés
        $operateurs = $commissionagrement->operateurs; */

        // On récupère la commission
        $commissionagrement = Commissionagrement::findOrFail($calid);

        // Récupérer uniquement les opérateurs de la région demandée
        $operateurs = $commissionagrement->operateurs()
            ->where('regions_id', $region?->id)
            ->get();

        /* Pour exporter opérateurs + scans */
        // Taille d’une tranche
        $step = 25;
        // Découper en chunks de 25
        $chunks = $operateurs->chunk($step);

        /* Pour exporter les lettres agrement */
        // Taille d’une tranche
        $taille = 20;
        // Découper en taille de 20
        $coupes = $operateurs->chunk($taille);

        // Regrouper par statut (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->region->nom ?? 'Aucune région';
        });

        return view(
            "operateurs.calregion",
            compact(
                "operateurs",
                "groupesStatutAgrement",
                "departements",
                "region",
                "title",
                "cal",
                "chunks",
                "step",
                "taille",
                "coupes",
                "groupes",
            )
        );
    }

    public function corbeille()
    {
        $total_count = Operateur::onlyTrashed()->count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $operateurs = Operateur::onlyTrashed()
            ->orderByDesc('deleted_at') // Trie par la date de suppression la plus récente
            ->take(250)
            ->get();

        $count_operateur = number_format($operateurs->count(), 0, ',', ' ');

        if ($count_operateur < 1) {
            $title = 'Aucun opérateur supprimé';
        } elseif ($count_operateur == 1) {
            $title = "$count_operateur opérateur supprimé sur un total de $total_count";
        } else {
            $title = "Liste des $count_operateur derniers opérateurs supprimés sur un total de $total_count";
        }

        return view("operateurs.corbeille", compact("operateurs", "title"));
    }

    public function forceDelete($uuid)
    {
        $operateur = Operateur::withTrashed()->where('uuid', $uuid)->firstOrFail();

        // Supprimer le quitus de profil si besoin
        if ($operateur->quitus && Storage::exists($operateur->quitus)) {
            Storage::delete($operateur->quitus);
        }
        // Supprimer l'operateur
        $operateur->forceDelete();

        Alert::success('Succès ', 'Opérateur supprimé définitivement.');
        return redirect()->back();
    }
    public function restore($uuid)
    {
        $operateur = Operateur::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $operateur->restore();

        Alert::success('Succès ', 'Opérateur restauré avec succès.');
        return redirect()->back();
    }

    public function certifierOperateur(Request $request, $uuid)
    {
        $request->validate([
            'certification_phrase' => ['required', 'string'],
        ]);

        $phraseAttendue = "Je certifie que les informations que j'ai fournies sont correctes.";

        if (trim($request->certification_phrase) !== $phraseAttendue) {
            Alert::error('Erreur ', 'La phrase de certification est incorrecte.');
            return redirect()->back();
        }

        $operateur = Operateur::where('uuid', $uuid)->firstOrFail();

        if (strtolower($operateur->file8) === 'oui') {
            Alert::error('Déjà certifié', 'Vous avez déjà certifié vos informations.');
            return redirect()->back();
        }

        $commissionagrement = Commissionagrement::where('statut', 'Ouvert')->first();

        if (! $commissionagrement) {
            Alert::error('Désolé', 'Aucun agrément n\'est lancé pour le moment.');
            return redirect()->back();
        }

        // Exemple d'action : marquer comme certifié
        $operateur->file8 = 'Oui';
        /* $operateur->commissionagrements_id = $commissionagrement->id; */
        $operateur->save();

        $operateur->commissionagrements()->syncWithoutDetaching([$commissionagrement?->id]);

        $anneeEnCours = date('Y');
        $an           = date('y');

        // Récupération du dernier numéro de courrier pour l'année en cours
        $numCourrier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('courriers.annee', $anneeEnCours)
            ->get()->last();

        if ($numCourrier) {
            // Si un courrier existe, incrémenter son numéro
            $numCourrier = ++$numCourrier->numero_arrive;
        } else {
            // Si aucun courrier n'existe, initialiser avec l'année et le numéro 0001
            $numCourrier = $an . "0001";
        }

        // Mise en forme du numéro de courrier en ajoutant des zéros au début
        $numCourrier = str_pad($numCourrier, 6, '0', STR_PAD_LEFT);

        $courrier = Courrier::create([
            'numero_courrier' => $numCourrier,
            'date_recep'      => now(),
            'date_cores'      => now(),
            'annee'           => $anneeEnCours,
            'objet'           => 'DEMANDE AGREMENT OPERATEUR',
            'expediteur'      => $operateur?->user?->operateur,
            /* 'reference'       => strtoupper($request->input('reference')),
            'numero_reponse'  => $request->input('numero_reponse'),
            'date_reponse'    => $date_reponse,
            'observation'     => strtoupper($request->input('observation')), */
            'type'            => 'operateur',
            "user_create_id"  => Auth::user()->id,
            "user_update_id"  => Auth::user()->id,
            'users_id'        => Auth::user()->id,
        ]);

        $arrive = Arrive::create([
            'numero_arrive' => $numCourrier,
            'type'          => 'operateur',
            'courriers_id'  => $courrier?->id,
        ]);

        // Récupération du dernier numéro de courrier pour l'année en cours
        // Récupère le dernier numéro de dossier commençant par l'année en cours
        $dernier = Operateur::where('numero_dossier', 'like', $an . '%')
            ->orderByDesc('numero_dossier')
            ->first();

        if ($dernier) {
            // Extraire les 3 derniers chiffres et incrémenter
            $lastNumber = (int) substr($dernier->numero_dossier, -3);
            $numDossier = $an . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Premier dossier de l'année
            $numDossier = $an . '001';
        }

        $operateur->update([
            'numero_arrive'   => $numCourrier,
            "numero_dossier"  => $numDossier,
            "numero_agrement" => $numCourrier . '/ONFP/DG/DEC/' . $anneeEnCours,
        ]);

        Alert::success('Succès ', 'Informations certifiées avec succès.');

        return redirect()->back();
    }

    public function certificationOperateur(Request $request, $uuid)
    {

        $operateur = Operateur::where('uuid', $uuid)->firstOrFail();

        /* if (strtolower($operateur->file8) === 'oui') { */

        $anneeEnCours = date('Y');
        $an           = date('y');

        // Récupération du dernier numéro de courrier pour l'année en cours
        $numCourrier = Arrive::join('courriers', 'courriers.id', 'arrives.courriers_id')
            ->select('arrives.*')
            ->where('courriers.annee', $anneeEnCours)
            ->get()->last();

        if ($numCourrier) {
            // Si un courrier existe, incrémenter son numéro
            $numCourrier = ++$numCourrier->numero_arrive;
        } else {
            // Si aucun courrier n'existe, initialiser avec l'année et le numéro 0001
            $numCourrier = $an . "0001";
        }

        // Mise en forme du numéro de courrier en ajoutant des zéros au début
        $numCourrier = str_pad($numCourrier, 6, '0', STR_PAD_LEFT);

        $courrier = Courrier::create([
            'numero_courrier' => $numCourrier,
            'date_recep'      => now(),
            'date_cores'      => now(),
            'annee'           => $anneeEnCours,
            'objet'           => 'DEMANDE AGREMENT OPERATEUR',
            'expediteur'      => $operateur?->user?->operateur,
            'type'            => 'operateur',
            "user_create_id"  => Auth::user()->id,
            "user_update_id"  => Auth::user()->id,
            'users_id'        => Auth::user()->id,
        ]);

        $arrive = Arrive::create([
            'numero_arrive' => $numCourrier,
            'type'          => 'operateur',
            'courriers_id'  => $courrier?->id,
        ]);

        // Récupération du dernier numéro de courrier pour l'année en cours
        // Récupère le dernier numéro de dossier commençant par l'année en cours
        $dernier = Operateur::where('numero_dossier', 'like', $an . '%')
            ->orderByDesc('numero_dossier')
            ->first();

        if ($dernier) {
            // Extraire les 3 derniers chiffres et incrémenter
            $lastNumber = (int) substr($dernier->numero_dossier, -3);
            $numDossier = $an . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Premier dossier de l'année
            $numDossier = $an . '001';
        }

        $operateur->update([
            'numero_arrive'   => $numCourrier,
            "numero_dossier"  => $numDossier,
            "numero_agrement" => $numCourrier . '/ONFP/DG/DEC/' . $anneeEnCours,
        ]);

        Alert::success('Succès ', 'Informations certifiées avec succès.');

        return redirect()->back();

        /* } else {

            Alert::error('Erreur', 'Informations non certifiées.');

            return redirect()->back();
        } */
    }

    public function exportAvecScansAll(Request $request)
    {
        $commissionagrement = $request->commissionagrement;

        $query = Operateur::with('user.files', 'commissionagrements');

        if ($commissionagrement) {
            $query->whereHas('commissionagrements', function ($q) use ($commissionagrement) {
                $q->where('commissionagrement_id', $commissionagrement);
            });
        }

        $operateurs = $query->get();

        $tempPath  = storage_path('app/temp_export');
        $timestamp = now()->format('d-m-Y_H-i-s');
        $zipPath   = storage_path("app/public/export_operateurs_du_{$timestamp}.zip");

        // Nettoyer le dossier temporaire
        if (is_dir($tempPath)) {
            \File::deleteDirectory($tempPath);
        }
        mkdir($tempPath, 0777, true);

        // 1️⃣ Générer l'Excel
        // Excel::store(new OperateursExport, 'temp_export/operateurs.xlsx');
        // Générer l'Excel avec ces opérateurs
        // Nettoyer le nom pour éviter les espaces et caractères spéciaux
        $commission = Commissionagrement::findOrFail($request->commissionagrement);

        // Prend le bon champ (libelle, nom, etc.) et slug pour éviter caractères interdits
        $commissionName = Str::slug($commission->commission, '_');

        // $fileName = "temp_export/operateurs_{$commissionName}.xlsx";
        $fileName = $tempPath . "/operateurs_{$commissionName}.xlsx";
        $export   = new OperateursExport($operateurs);
        Excel::store($export, "temp_export/operateurs_{$commissionName}.xlsx", 'local');

        // ou carrément :
        // Excel::store($export, basename($fileName), [
        //   'disk' => 'local',
        //   'root' => $tempPath,
        //]);

        // On passe juste la collection d'opérateurs à l'export
        $export = new OperateursExport($operateurs);

        Excel::store($export, $fileName); // Ici on utilise bien $fileName

        // Copier les fichiers de chaque opérateur
        foreach ($operateurs as $operateur) {
            if (! $operateur->user) {
                continue;
            }

            $operateurFolder = $tempPath . '/' . $this->sanitizeFileName($operateur->user->username ?? $operateur->user->id);

            if (! is_dir($operateurFolder)) {
                mkdir($operateurFolder, 0777, true);
            }

            // === Fichiers liés à l'opérateur ===
            $files = $operateur?->user?->files ?? collect();

            foreach ($files as $file) {
                $sourceFile = $file?->file;
                if (! $sourceFile || ! is_string($sourceFile)) {
                    continue;
                }

                $sourcePath = storage_path('app/public/' . $sourceFile);
                if (! file_exists($sourcePath)) {
                    continue;
                }

                $filename = $file->legende
                    ? $this->sanitizeFileName($file->legende) . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION)
                    : basename($sourcePath);

                $destination = $operateurFolder . '/' . $filename;
                @copy($sourcePath, $destination);
            }

            // === CV des formateurs de l'opérateur ===
            $formateurs = $operateur->operateurformateurs ?? collect();
            foreach ($formateurs as $formateur) {
                $cvFile = $formateur?->file;
                if (! $cvFile || ! is_string($cvFile)) {
                    continue;
                }

                $cvPath = storage_path('app/public/' . $cvFile);
                if (! file_exists($cvPath)) {
                    continue;
                }

                // On peut inclure le nom du formateur pour éviter les collisions
                $cvFilename = $this->sanitizeFileName(
                    ('CV_' . $formateur?->name ?? 'formateur') . '_' . ($formateur?->name ?? '')
                ) . '.' . pathinfo($cvPath, PATHINFO_EXTENSION);

                $destination = $operateurFolder . '/' . $cvFilename;
                @copy($cvPath, $destination);
            }
        }

        // Créer le ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (! $file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempPath) + 1); // relative path dans le ZIP
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        // Télécharger le ZIP
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function sanitizeName($name)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '_', $name);
    }

    public function exportAvecScans(Request $request)
    {
        $commissionagrement = $request->commissionagrement;

        // 🔹 Récupération des paramètres de tranche
        $offset = (int) $request->get('offset', 0);
        $limit  = (int) $request->get('limit', 25); // par défaut 25

        // 🔹 Requête sur les opérateurs de la commission
        $query = Operateur::with('user.files', 'commissionagrements')
            ->whereHas('commissionagrements', function ($q) use ($commissionagrement) {
                $q->where('commissionagrement_id', $commissionagrement);
            });

        // 🔹 Ne récupérer que la tranche
        $operateurs = $query->skip($offset)->take($limit)->get();

        // 🔹 Dossier temporaire
        $tempPath  = storage_path('app/temp_export');
        $timestamp = now()->format('d-m-Y_H-i-s');

        // Inclure la tranche dans le nom du ZIP pour plus de clarté
        $zipPath = storage_path("app/public/export_operateurs_{$offset}_à_" . ($offset + $operateurs->count()) . "_{$timestamp}.zip");

        // Nettoyer le dossier temporaire
        if (is_dir($tempPath)) {
            \File::deleteDirectory($tempPath);
        }
        mkdir($tempPath, 0777, true);

        // 🔹 Générer l'Excel
        /*  $commission     = Commissionagrement::findOrFail($commissionagrement);
        $commissionName = Str::slug($commission->commission, '_');
        $fileName       = $tempPath . "/operateurs_{$commissionName}_{$offset}_à_" . ($offset + $operateurs->count()) . ".xlsx"; */

        // 🔹 Générer l'Excel
        /* $commission     = Commissionagrement::findOrFail($commissionagrement);
        $commissionName = Str::slug($commission->commission, '_');
        $fileName       = "operateurs_{$commissionName}_{$offset}_à_" . ($offset + $operateurs->count()) . ".xlsx";

        Excel::store(new OperateursExport($operateurs), "temp_export/{$fileName}");

        copy(storage_path("app/temp_export/{$fileName}"), $tempPath . "/{$fileName}"); */

        // Nettoyer le nom pour éviter les espaces et caractères spéciaux
        $commission = Commissionagrement::findOrFail($request->commissionagrement);

        // Prend le bon champ (libelle, nom, etc.) et slug pour éviter caractères interdits
        $commissionName = Str::slug($commission->commission, '_');

        // $fileName = "temp_export/operateurs_{$commissionName}.xlsx";
        $fileName = $tempPath . "/operateurs_{$commissionName}.xlsx";
        $export   = new OperateursExport($operateurs);
        Excel::store($export, "temp_export/operateurs_{$commissionName}.xlsx", 'local');

        // ou carrément :
        // Excel::store($export, basename($fileName), [
        //   'disk' => 'local',
        //   'root' => $tempPath,
        //]);

        // On passe juste la collection d'opérateurs à l'export
        $export = new OperateursExport($operateurs);

        Excel::store($export, $fileName); // Ici on utilise bien $fileName

        /* $export = new OperateursExport($operateurs);
        Excel::store($export, $fileName); */

        // 🔹 Copier les fichiers de chaque opérateur
        foreach ($operateurs as $operateur) {
            if (! $operateur->user) {
                continue;
            }

            $operateurFolder = $tempPath . '/' . $this->sanitizeFileName($operateur?->user?->display_operateur);
            if (! is_dir($operateurFolder)) {
                mkdir($operateurFolder, 0777, true);
            }

            // === Fichiers liés à l'opérateur ===
            $files = $operateur?->user?->files ?? collect();

            foreach ($files as $file) {
                $sourceFile = $file?->file;
                if (! $sourceFile || ! is_string($sourceFile)) {
                    continue;
                }

                $sourcePath = storage_path('app/public/' . $sourceFile);
                if (! file_exists($sourcePath)) {
                    continue;
                }

                $filename = $file->legende
                    ? $this->sanitizeFileName($file->legende) . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION)
                    : basename($sourcePath);

                $destination = $operateurFolder . '/' . $filename;
                @copy($sourcePath, $destination);
            }

            // === CV des formateurs de l'opérateur ===
            $formateurs = $operateur->operateurformateurs ?? collect();
            foreach ($formateurs as $formateur) {
                $cvFile = $formateur?->file;
                if (! $cvFile || ! is_string($cvFile)) {
                    continue;
                }

                $cvPath = storage_path('app/public/' . $cvFile);
                if (! file_exists($cvPath)) {
                    continue;
                }

                // On peut inclure le nom du formateur pour éviter les collisions
                $cvFilename = $this->sanitizeFileName(
                    ('CV_' . $formateur?->name ?? 'formateur') . '_' . ($formateur?->name ?? '')
                ) . '.' . pathinfo($cvPath, PATHINFO_EXTENSION);

                $destination = $operateurFolder . '/' . $cvFilename;
                @copy($cvPath, $destination);
            }
        }

        // Créer le ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (! $file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempPath) + 1); // relative path dans le ZIP
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        // Télécharger le ZIP
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function sanitizeFileName($name)
    {
        // Remplacer les caractères interdits par "_"
        $name = preg_replace('/[\/\\\?\%\*\:\|\"<>\.]/', '_', $name);

        // Remplacer les espaces par "_"
        /* $name = preg_replace('/\s+/', '_', $name); */
        $name = preg_replace('/\s+/', ' ', $name);

        return $name;
    }

    public function exportficheSynthese(Request $request, $commissionagrement)
    {
        $commission = Commissionagrement::find($commissionagrement);

        $offset = (int) $request->get('offset', 0);
        $limit  = (int) $request->get('limit', 20);

        $query = Operateur::whereHas('commissionagrements', function ($q) use ($commissionagrement) {
            $q->where('commissionagrement_id', $commissionagrement);
        });

        $operateurs = $query->skip($offset)->take($limit)->get();

        $title = 'Fiche de synthèse ' . $commission?->commission . ' du ' . $commission?->date?->translatedFormat('l d F Y') . ' à ' . $commission?->lieu;

        $dompdf  = new \Dompdf\Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('operateurs.fichesynthese', compact(
            'commission',
            'operateurs',
            'title'
        )));

        $dompdf->setPaper('Letter', 'portrait');
        $dompdf->render();

        // 🔹 Nom lisible pour affichage dans le navigateur (avec underscores pour éviter les problèmes de caractères)
        $displayName = 'Fiche de synthese ' . $commission?->commission . ' ' .
            $commission?->date_ouverture?->format('d-m-Y') . ' au ' .
            $commission?->date_fermeture?->format('d-m-Y') . '.pdf';

        // 🔹 Nom safe pour l’en-tête HTTP
        $safeName = rawurlencode($displayName);

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $displayName . '"; filename*=UTF-8\'\'' . $safeName);
    }

    public function exportficheSyntheseRegion(Request $request, $commissionagrement, $region)
    {
        // On récupère la commission
        $commission = Commissionagrement::findOrFail($commissionagrement);

        $offset = (int) $request->get('offset', 0);
        $limit  = (int) $request->get('limit', 20);

        $query = Operateur::whereHas('commissionagrements', function ($q) use ($commissionagrement, $region) {
            $q->where('commissionagrement_id', $commissionagrement)
                ->where('regions_id', $region); // ✅ ici $region est un id
        });

        $operateurs = $query->skip($offset)->take($limit)->get();

        $region = Region::findOrFail($region);

        $title = 'Fiche de synthèse région de ' . $region?->nom . ', ' . $commission?->commission . ' du ' . $commission?->date?->translatedFormat('l d F Y') . ' à ' . $commission?->lieu;

        $dompdf  = new \Dompdf\Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('operateurs.fichesyntheseregion', compact(
            'commission',
            'operateurs',
            'region',
            'title'
        )));

        $dompdf->setPaper('Letter', 'portrait');
        $dompdf->render();

        // 🔹 Nom lisible pour affichage dans le navigateur (avec underscores pour éviter les problèmes de caractères)
        $displayName = 'Fiche de synthese région de ' . $region?->nom . ', ' .
            $commission?->commission . ' ' .
            $commission?->date_ouverture?->format('d-m-Y') . ' au ' .
            $commission?->date_fermeture?->format('d-m-Y') . '.pdf';

        // 🔹 Nom safe pour l’en-tête HTTP
        $safeName = rawurlencode($displayName);

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $displayName . '"; filename*=UTF-8\'\'' . $safeName);
    }

    public function exportficheSyntheseAll(Request $request, $commissionagrement)
    {
        $commission = Commissionagrement::find($commissionagrement);

        /* $operateurs = Operateur::with('user.files')
            ->whereHas('commissionagrements', function ($query) use ($commissionagrement) {
                $query->where('commissionagrement_id', $commissionagrement);
            })
            ->get(); */

        $operateurs = $commission?->operateurs;

        if ($operateurs->count() > 25) {
            Alert::warning("Attention", "Le nombre de fiches à télécharger est trop élevé. Veuillez effectuer le téléchargement par lots.");
            return redirect()->back();
        }

        $commissionName = Str::slug($commission?->commission, '_');
        $title          = "Fiche de synthèse {$commissionName}";

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view(
            'operateurs.fichesynthese',
            compact(
                'commission',
                'operateurs',
                'title'
            )
        ));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('Letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = "Fiche de synthèse {$commissionName}" . ".pdf";

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function exportficheSyntheseAllRegion(Request $request, $commissionagrement, $region)
    {
        /* $commission = Commissionagrement::find($commissionagrement);

        $operateurs = $commission?->operateurs; */

        // On récupère la commission
        $commission = Commissionagrement::findOrFail($commissionagrement);

        // Récupérer uniquement les opérateurs de la région demandée
        $operateurs = $commission->operateurs()
            ->where('regions_id', $region)
            ->get();

        if ($operateurs->count() > 25) {
            Alert::warning("Attention", "Le nombre de fiches à télécharger est trop élevé. Veuillez effectuer le téléchargement par lots.");
            return redirect()->back();
        }

        $commissionName = Str::slug($commission?->commission, '_');
        $title          = "Fiche de synthèse {$commissionName}";

        $region = Region::findOrFail($region);

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view(
            'operateurs.fichesyntheseregion',
            compact(
                'commission',
                'operateurs',
                'region',
                'title'
            )
        ));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('Letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = "Fiche de synthèse {$commissionName}" . ".pdf";

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
    }

    public function detachOperateur($commission, $operateur)
    {
        $operateur = Operateur::findOrFail($operateur);
        $operateur->commissionagrements()->detach($commission);
        $operateur->save();

        return redirect()->back()->with('status', 'Opérateur détaché avec succès !');
    }

    public function changeUser(Operateur $operateur)
    {
        $this->authorize('update', $operateur);

        $users = User::whereHas('operateur')->get();

        return view('operateurs.change-user', compact('operateur', 'users'));
    }

    public function updateUser(Request $request, Operateur $operateur)
    {
        $this->authorize('update', $operateur);

        $request->validate([
            'user_id' => ['required', 'exists:users,id']
        ]);

        $user = User::findOrFail($request->user_id);

        // 🔥 Mise à jour de la relation
        $operateur->update([
            'users_id' => $user->id,
        ]);

        Alert::success("Succès !", "Utilisateur changé avec succès");

        return redirect()->back();
    }
}
