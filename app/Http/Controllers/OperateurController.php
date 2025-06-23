<?php
namespace App\Http\Controllers;

use App\Models\Arrive;
use App\Models\Commissionagrement;
use App\Models\Departement;
use App\Models\File;
use App\Models\Operateur;
use App\Models\Operateureference;
use App\Models\Operateurequipement;
use App\Models\Operateurformateur;
use App\Models\Operateurlocalite;
use App\Models\Operateurmodule;
use App\Models\Region;
use App\Models\User;
use App\Models\Validationoperateur;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

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

    public function index()
    {
        $operateurs   = Operateur::count();
        $total_count  = number_format($operateurs, 0, ',', ' ');
        $departements = Departement::orderBy("nom", "asc")->get();

        /* $statuts = ['agréé', 'rejeté', 'nouveau', 'expirer']; */

        // Récupérer les différents statuts
        /* $statuts = $operateurs->pluck('statut_agrement')->unique()->values()->all();

        $counts = Operateur::whereIn('statut_agrement', $statuts)
            ->selectRaw("statut_agrement, COUNT(*) as count")
            ->groupBy('statut_agrement')
            ->pluck('count', 'statut_agrement');

        $operateur_agreer  = $counts['agréé'] ?? 0;
        $operateur_rejeter = $counts['rejeté'] ?? 0;
        $operateur_nouveau = $counts['nouveau'] ?? 0;
        $operateur_expirer = $counts['expirer'] ?? 0;
        $operateur_total   = $operateur_agreer + $operateur_rejeter + $operateur_nouveau;

        $pourcentage_agreer  = $operateur_total ? ($operateur_agreer / $operateur_total) * 100 : 0;
        $pourcentage_rejeter = $operateur_total ? ($operateur_rejeter / $operateur_total) * 100 : 0;
        $pourcentage_nouveau = $operateur_total ? ($operateur_nouveau / $operateur_total) * 100 : 0;
        $pourcentage_expirer = $operateur_total ? ($operateur_expirer / $operateur_total) * 100 : 0; */

        $operateurs      = Operateur::latest()->take(250)->get();
        $count_operateur = number_format($operateurs->count(), 0, ',', ' ');

        $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "$count_operateur opérateur sur un total de $total_count",
            default => "Liste des $count_operateur derniers opérateurs sur un total de $total_count",
        };

        /* $operateurs = Operateur::select('*')->get(); */
        // Récupérer les différents statuts
        /* $statuts = $operateurs->pluck('statut_agrement')->unique(); */

        // Regrouper par statut_agrement (y compris les null)
        /* $groupesStatutAgrement = $operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        }); */

        $commissionagrements = Commissionagrement::orderBy('commission', 'desc')->get();

        return view("operateurs.index",
            compact(
                "operateurs",
                /* "groupesStatutAgrement", */
                "departements",
                "commissionagrements",
                /* "operateur_agreer",
                "operateur_rejeter",
                "pourcentage_agreer",
                "pourcentage_rejeter",
                "operateur_nouveau",
                "operateur_expirer",
                "pourcentage_nouveau",
                "pourcentage_expirer" */
                "title",
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

        $excludedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC'];
        foreach (Auth::user()->roles as $role) {
            if (! empty($role?->name) && ! in_array($role->name, $excludedRoles)) {
                $this->authorize('view', $operateur);
            }
        }
        return view("operateurs.agrement",
            compact("operateurs",
                "operateur",
                "operateureferences"));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "departement"  => "required|string",
            "quitus"       => ['image', 'required', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            "date_quitus"  => "required|date_format:d/m/Y",
            "type_demande" => "required|string",
        ]);

        $user = Auth::user();

        $operateur_total = Operateur::where('users_id', $user->id)->count();
        $departement     = Departement::where('nom', $request->input("departement"))->first();

        if ($operateur_total >= 1) {
            Alert::warning('Attention ! ', 'Vous avez atteint le nombre de demandes autorisées');
            return redirect()->back();
        }

        $dateString  = $request->input('date_quitus');
        $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

        $anneeEnCours = date('Y');
        $an           = date('y');

        $operateur = Operateur::create([
            'numero_agrement' => "/ONFP/DG/DEC/$anneeEnCours",
            'type_demande'    => $request->input("type_demande"),
            'debut_quitus'    => $date_quitus,
            'annee_agrement'  => date('Y-m-d'),
            'statut_agrement' => 'nouveau',
            'departements_id' => $departement?->id,
            'regions_id'      => $departement?->region?->id,
            'users_id'        => $user->id,
        ]);

        if ($request->hasFile('quitus')) {
            $quitusPath = $request->file('quitus')->store('quitus', 'public');
            Image::make(public_path("/storage/{$quitusPath}"))->save();
            $operateur->update(['quitus' => $quitusPath]);
        }

        Alert::success("Succès ! ", "Demande ajoutée avec succès");
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
            'username'             => ["required", "string", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'fixe'                 => ["required", "string", "size:12", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'telephone'            => ["required", "string", "size:12", Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'bp'                   => ['nullable', 'string'],
            'categorie'            => ['required', 'string'],
            'adresse'              => ['required', 'string', 'max:255'],
            'rccm'                 => ['nullable', 'string'],
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
            "type_demande"         => "required|string",
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
            'password'             => Hash::make($request->input('email')),
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
            "statut_agrement"  => 'nouveau',
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
        if ($request->hasFile('quitus')) {
            $quitusPath = $request->file('quitus')->store('quitus', 'public');
            $operateur->update(['quitus' => $quitusPath]);
        }

        if ($request->hasFile('file_arrete_creation')) {
            $file_arrete_creation = $request->file('file_arrete_creation')->store('uploads', 'public');
            $operateur->update(['file_arrete_creation' => $file_arrete_creation]);
        }

        Alert::success("Félicitations !", "Opérateur ajouté avec succès");

        return redirect()->back();
    }

    public function renewOperateur(Request $request)
    {
        $user = Auth::user();
        foreach ($user->operateurs as $key => $operateur) {
        }
        $this->validate($request, [
            "quitus"      => ['image', 'required', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            "date_quitus" => ['required', 'date_format:d/m/Y'],
        ]);

        /* foreach (Auth::user()->roles as $key => $role) {
            if (! empty($role?->name) && ($role?->name != 'super-admin') && ($role?->name != 'Employe') && ($role?->name != 'admin') && ($role?->name != 'DIOF') && ($role?->name != 'DEC')) {
                $this->authorize('view', $operateur);
            }
        } */

        $rolesAutorises = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC'];

        $userRoles = Auth::user()->roles->pluck('name')->toArray();

        if (! array_intersect($rolesAutorises, $userRoles)) {
            $this->authorize('view', $operateur);
        }

        $dateString  = $request->input('date_quitus');
        $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

        $op = Operateur::create([
            "numero_agrement" => '/ONFP/DG/DEC/' . date('Y'),
            "categorie"       => $operateur?->categorie,
            "statut"          => $operateur?->statut,
            "statut_agrement" => 'nouveau',
            "autre_statut"    => $operateur?->autre_statut,
            "type_demande"    => 'Renouvellement',
            "annee_agrement"  => now()->format('Y-m-d'),
            "rccm"            => $operateur?->registre_commerce,
            "ninea"           => $operateur?->ninea,
            "debut_quitus"    => $date_quitus,
            "departements_id" => $operateur?->departements_id,
            "regions_id"      => $operateur?->departement?->region?->id,
            "users_id"        => $operateur?->users_id,
        ]);

// Gestion du fichier quitus
        if ($request->hasFile('quitus')) {
            $quitusPath = $request->file('quitus')->store('quitus', 'public');
            $op->update(['quitus' => $quitusPath]);
        }

// Clonage des modules de l'opérateur
        foreach ($operateur->operateurmodules as $operateurmodule) {
            Operateurmodule::create([
                "module"               => $operateurmodule->module,
                "domaine"              => $operateurmodule->domaine,
                "categorie"            => $operateurmodule->categorie,
                "niveau_qualification" => $operateurmodule->niveau_qualification,
                "statut"               => $operateurmodule->statut,
                "operateurs_id"        => $op->id,
            ]);
        }

// Clonage des références
        foreach ($operateur->operateureferences as $operateureference) {
            Operateureference::create([
                "organisme"     => $operateureference->organisme,
                "contact"       => $operateureference->contact,
                "periode"       => $operateureference->periode,
                "description"   => $operateureference->description,
                "operateurs_id" => $op->id,
            ]);
        }

// Clonage des formateurs
        foreach ($operateur->operateurformateurs as $operateurformateur) {
            Operateurformateur::create([
                "name"                   => $operateurformateur->name,
                "domaine"                => $operateurformateur->domaine,
                "nbre_annees_experience" => $operateurformateur->nbre_annees_experience,
                "references"             => $operateurformateur->references,
                "operateurs_id"          => $op->id,
            ]);
        }

// Clonage des équipements
        foreach ($operateur->operateurequipements as $operateurequipement) {
            Operateurequipement::create([
                "designation"   => $operateurequipement->designation,
                "quantite"      => $operateurequipement->quantite,
                "etat"          => $operateurequipement->etat,
                "type"          => $operateurequipement->type,
                "operateurs_id" => $op->id,
            ]);
        }

// Clonage des localités
        foreach ($operateur->operateurlocalites as $operateurlocalite) {
            Operateurlocalite::create([
                "name"          => $operateurlocalite->name,
                "region"        => $operateurlocalite->region,
                "operateurs_id" => $op->id,
            ]);
        }

        Alert::success("Succès !", "Votre renouvellement a été pris en compte");

        return redirect()->back();

    }

    public function update(Request $request, Operateur $operateur)
    {
        $user = $operateur->user;

        $this->validate($request, [
            "numero_dossier"       => ['nullable', 'string', Rule::unique(Operateur::class)->ignore($operateur?->id)->whereNull('deleted_at')],
            "numero_arrive"        => ['nullable', 'string', Rule::unique(Operateur::class)->ignore($operateur?->id)->whereNull('deleted_at')],
            "numero_agrement"      => ['nullable', 'string', Rule::unique(Operateur::class)->ignore($operateur?->id)->whereNull('deleted_at')],
            "operateur"            => ['required', 'string', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "username"             => ['required', 'string', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "email"                => ['required', 'string', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "fixe"                 => ['required', 'string', 'size:12', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "telephone"            => ['required', 'string', 'size:12', Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            "telephone_parent"     => ['nullable', 'string', 'size:12'],
            "fonction_responsable" => ['required', 'string'],
            "civilite"             => ['required', 'string'],
            "prenom"               => ['required', 'string'],
            "nom"                  => ['required', 'string'],
            "categorie"            => ['required', 'string'],
            "statut"               => ['required', 'string'],
            "departement"          => ['required', 'string'],
            "adresse"              => ['required', 'string'],
            "ninea"                => ['nullable', 'string'],
            "registre_commerce"    => ['nullable', 'string'],
            "quitus"               => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            "date_quitus"          => ['nullable', 'date_format:d/m/Y'],
            "type_demande"         => ['required', 'string'],
            "arrete_creation"      => ['nullable', 'string'],
            "file_arrete_creation" => ['file', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
            "demande_signe"        => ['nullable', 'string'],
            "formulaire_signe"     => ['nullable', 'string'],
            "web"                  => ['nullable', 'string'],
        ]);

        $departement = Departement::where('nom', $request->input("departement"))->firstOrFail();

        /*      foreach (Auth::user()->roles as $key => $role) {
            if (! empty($role?->name) && ($role?->name != 'super-admin') && ($role?->name != 'Employe') && ($role?->name != 'admin') && ($role?->name != 'DIOF') && ($role?->name != 'DEC')) {
                $this->authorize('update', $operateur);
            }
            if (! empty($role?->name) && ($operateur->statut_agrement != 'nouveau') && ($role?->name != 'super-admin') && ($role?->name != 'Employe') && ($role?->name != 'admin') && ($role?->name != 'DIOF') && ($role?->name != 'DEC')) {
                Alert::warning('Attention ! ', 'action impossible');
                return redirect()->back();
            }
        } */

        $rolesAutorises = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC', 'ADEC', 'ADIOF', 'Ingenieur'];
        $userRoles      = Auth::user()->roles->pluck('name')->toArray();

// Si aucun rôle autorisé n'est trouvé chez l'utilisateur
        if (! array_intersect($rolesAutorises, $userRoles)) {
            // Vérifie la permission "update"
            $this->authorize('update', $operateur);

            // Si le statut n'est pas "nouveau", bloquer l'action
            if ($operateur->statut_agrement != 'nouveau') {
                Alert::warning('Attention !', 'action impossible');
                return redirect()->back();
            }
        }

        $arrive = Arrive::where('numero_arrive', $request->input("numero_arrive"))->first();

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
            'numero_arrive'    => $request->input("numero_arrive"),
            "numero_dossier"   => $request->input("numero_dossier"),
            "numero_agrement"  => $request->input("numero_agrement"),
            "type_demande"     => $request->input("type_demande"),
            "debut_quitus"     => $date_quitus,
            "departements_id"  => $departement?->id,
            "regions_id"       => $departement?->region?->id,
            "users_id"         => $user->id,
            "arrete_creation"  => $request->input("arrete_creation"),
            "demande_signe"    => $request->input("demande_signe"),
            "formulaire_signe" => $request->input("formulaire_signe"),
            "quitusfiscal"     => $request->input("quitusfiscal"),
            "cvsigne"          => $request->input("cvsigne"),
        ]);

        // Gestion des fichiers
        if ($request->hasFile('quitus')) {
            if (! is_null($operateur->quitus)) {
                Storage::disk('public')->delete($operateur->quitus);
            }
            $quitusPath = $request->file('quitus')->store('quitus', 'public');
            $operateur->update(['quitus' => $quitusPath]);
        }

        if ($request->hasFile('file_arrete_creation')) {
            $filePath = $request->file('file_arrete_creation')->store('uploads', 'public');
            $operateur->update(['file_arrete_creation' => $filePath]);
        }

        Alert::success("Succès !", 'Demande modifiée avec succès');
        return redirect()->back();

    }

    public function updated(Request $request, $uuid)
    {
        $operateur   = Operateur::findOrFail($request->id);
        $user        = $operateur->user;
        $departement = Departement::where('nom', $request->input("departement"))->firstOrFail();
        $this->validate($request, [
            "departement"  => ['required', 'string'],
            "quitus"       => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            "date_quitus"  => ['nullable', 'date_format:d/m/Y'],
            "type_demande" => ['required', 'string'],
            'email'        => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($request->id ?? null)->whereNull('deleted_at'),
            ],
        ]);

        $rolesValid       = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC', 'ADEC', 'ADIOF', 'Ingenieur'];
        $rolesUtilisateur = Auth::user()->roles->pluck('name');

// Vérifier si l'utilisateur possède un des rôles valides
        $roleValide = $rolesUtilisateur->intersect($rolesValid)->isNotEmpty();

        if (! $roleValide) {
            // Vérifier le statut de l'opérateur et autoriser l'action si nécessaire
            if ($operateur->statut_agrement !== 'nouveau') {
                Alert::warning('Attention !', 'Action impossible');
                return redirect()->back();
            }

            // Si l'utilisateur n'a pas de rôle valide, on l'autorise à effectuer la mise à jour
            $this->authorize('update', $operateur);
        }

        $dateString  = $request->input('date_quitus');
        $date_quitus = ! empty($dateString) ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

        $operateur->update([
            "type_demande"    => $request->input("type_demande"),
            "debut_quitus"    => $date_quitus,
            "departements_id" => $departement?->id,
            "regions_id"      => $departement?->region?->id,
            "users_id"        => $user->id,
        ]);

        $operateur->save();

        if (request('quitus')) {
            if (! empty($operateur->quitus)) {
                Storage::disk('public')->delete($operateur->quitus);
            }
            $quitusPath = request('quitus')->store('quitus', 'public');
            $quitus     = Image::make(public_path("/storage/{$quitusPath}"));

            $quitus->save();

            $operateur->update([
                'quitus' => $quitusPath,
            ]);
        }

        Alert::success("Succès ! ", 'demande modifiée avec succès');

        return redirect()->back();
    }
    public function edit(Operateur $operateur)
    {
        $departements = Departement::orderBy("nom", "asc")->get();

        /*  foreach (Auth::user()->roles as $key => $role) {
            if (! empty($role?->name) && ($role?->name != 'super-admin') && ($role?->name != 'Employe') && ($role?->name != 'admin') && ($role?->name != 'DIOF') && ($role?->name != 'DEC')) {
                $this->authorize('view', $operateur);
            }
        } */

        $this->authorize('view', $operateur);

        return view("operateurs.update", compact("operateur", "departements"));
    }

    public function show(Operateur $operateur)
    {
        $operateurs         = Operateur::get();
        $operateureferences = Operateureference::get();
        $user               = $operateur->user;

        $userRoles = Auth::user()->roles->pluck('name')->toArray();

        $excludedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC', 'Operateur', 'Ingenieur'];

        if (! empty(array_diff($userRoles, $excludedRoles))) {
            $this->authorize('show', $operateur);
        }

        return view("operateurs.show", compact("operateur", "operateureferences", "operateurs"));
    }

    public function showAgrement($id)
    {
        $operateur          = Operateur::findOrFail($id);
        $operateurs         = Operateur::get();
        $operateureferences = Operateureference::get();
        return view("operateurs.agrements.show", compact("operateur", "operateureferences", "operateurs"));
    }

    public function destroy(Operateur $operateur)
    {

// Delete quitus file if it exists
        if ($operateur->quitus) {
            Storage::disk('public')->delete($operateur->quitus);
        }

// Check if the operator's status is 'nouveau'
        if ($operateur->statut_agrement !== 'nouveau') {
            Alert::warning('Attention !', 'Action impossible');
            return redirect()->back();
        }

// Check if the user has the correct roles to delete the operator
        $validRoles   = ['super-admin', 'Employe', 'admin', 'DIOF', 'DEC'];
        $hasValidRole = Auth::user()->roles->pluck('name')->intersect($validRoles)->isNotEmpty();

// If the user doesn't have a valid role, check if they are authorized to delete
        if (! $hasValidRole) {
            $this->authorize('delete', $operateur);
        }

// Delete the operator and show success alert
        $operateur->delete();
        Alert::success('Succès ! ', $operateur->user->username . ' a été supprimé');

// Redirect back
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

    public function showReference($id)
    {
        $operateur          = Operateur::findOrFail($id);
        $operateureferences = Operateureference::get();

        return view('operateureferences.show', compact('operateur', 'operateureferences'));
    }

    public function showEquipement($id)
    {
        $operateur            = Operateur::findOrFail($id);
        $operateurequipements = Operateurequipement::get();

        return view('operateurequipements.show', compact('operateur', 'operateurequipements'));
    }

    public function showFormateur($id)
    {
        $operateur           = Operateur::findOrFail($id);
        $operateurformateurs = Operateurformateur::get();

        return view('operateurformateurs.show', compact('operateur', 'operateurformateurs'));
    }

    public function showLocalite($id)
    {
        $operateur          = Operateur::findOrFail($id);
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
            if ($operateur->statut_agrement == 'nouveau' || $operateur->statut_agrement == 'non retenu') {
                $operateur->update([
                    'statut_agrement' => 'Retenu',
                ]);

                $operateur->save();

                Alert::success("Effectué !", "l'opérateur " . $operateur?->user?->username . ' a été retenu');

                return redirect()->back();
            } else {
                Alert::warning("Imopssible ", "Car l'opérateur " . $operateur?->user?->username . ' a déjà été validé');

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

        /* if ($operateur->statut_agrement == 'Nouveau' || $operateur->statut_agrement == 'Retenue') { */
        /*  $operateur->update([
            'statut_agrement' => $request->statut,
            'motif'           => $request->input('motif'),
        ]);

        $operateur->save();
 */
        $motif = $request->input('motif') ?? $request->statut;

        $operateur->update([
            'statut_agrement' => $request->statut,
            'motif'           => $motif,
            /* 'commissionagrements_id' => null, */
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
        /* } else {
            Alert::warning("Imopssible ", "Car l'opérateur " . $operateur?->user?->username . ' a déjà été validé');

            return redirect()->back();
        } */
    }

    public function agreerOperateur($id)
    {
        $operateur             = Operateur::findOrFail($id);
        $moduleoperateur_count = $operateur->operateurmodules->count();

        $count_nouveau = $operateur->operateurmodules->where('statut', 'nouveau')->count();

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
        if ($operateur->statut_agrement != 'nouveau') {
            Alert::warning('Attention ! ', 'action impossible opérateur déjà traité');
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

    public function devenirOperateur()
    {
        $user = Auth::user();

        // Récupérer l'opérateur lié à l'utilisateur
        $operateur  = Operateur::where('users_id', $user->id)->orderBy("created_at", "desc")->first();
        $operateurA = Operateur::where('users_id', $user->id)->orderBy("created_at", "desc")->get();
        $operateurs = Operateur::all();

        $departements = Departement::orderBy("nom", "asc")->get();

        $operateur_total = $operateurs->count();

        // Récupérer les fichiers associés à l'utilisateur
        $files = File::where('users_id', $user->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();

        $user_files = File::where('users_id', $user?->id)
            ->whereNull('file')
            ->whereNotIn('sigle', ['CIN', 'DAC', 'DP', 'CR', 'AD', 'Bulletins'])
            ->distinct()
            ->get();

        if ($operateur_total >= 1 && $operateur) {
            // Récupérer les counts des relations de l'opérateur
            $module_count     = Operateurmodule::where('operateurs_id', $operateur->id)->exists() ? "valide" : "invalide";
            $reference_count  = Operateureference::where('operateurs_id', $operateur->id)->exists() ? "valide" : "invalide";
            $equipement_count = Operateurequipement::where('operateurs_id', $operateur->id)->exists() ? "valide" : "invalide";
            $formateur_count  = Operateurformateur::where('operateurs_id', $operateur->id)->exists() ? "valide" : "invalide";
            $localite_count   = Operateurlocalite::where('operateurs_id', $operateur->id)->exists() ? "valide" : "invalide";

            // Déterminer le statut de la demande
            $statut_demande = ($module_count === "valide" && $reference_count === "valide" && $equipement_count === "valide" &&
                $formateur_count === "valide" && $localite_count === "valide") ? "valide" : "invalide";

            // Retourner la vue avec les données
            return view('operateurs.show-operateur',
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
                    'localite_count'
                ));
        } else {
            // Si aucun opérateur n'est trouvé, afficher une vue différente
            return view('operateurs.show-operateur-aucun',
                compact(
                    'departements',
                    'operateur',
                    'operateurs',
                    'user'
                ));
        }

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

        $dompdf->loadHtml(view('operateurs.fichesynthese',
            compact(
                'commission',
                'operateurs',
                'title'
            )));

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

        $dompdf->loadHtml(view('operateurs.fichesyntheseoperateur',
            compact(
                'operateur',
                'title'
            )));

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

        $dompdf->loadHtml(view('operateurs.lettreagrement',
            compact(
                'operateurs',
                'title'
            )));

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

        $count = $operateurs->count();

        // Gestion du titre des résultats
        $title = match ($count) {
            0 => 'Aucun opérateur trouvé',
            1 => '1 opérateur trouvé',
            default => "$count opérateurs trouvés"
        };

        // Regrouper par statut_agrement (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        });

        return view('operateurs.index', compact(
            'operateurs',
            'departements',
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
            'title'
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
        $operateurs = Operateur::when($statut !== 'Aucun statut', function ($query) use ($statut) {
            $query->where('statut_agrement', $statut);
        }, function ($query) {
            $query->whereNull('statut_agrement');
        })
            ->get();

        // Regrouper par statut (y compris les null)
        $groupes = $operateurs->groupBy(function ($item) {
            return $item->user->categorie ?? 'Aucune';
        });

        $operateur_liste = $operateurs->take(50);

        $total_count = number_format($operateurs->count(), 0, ',', ' ');

        $count_operateur = number_format($operateur_liste->count(), 0, ',', ' ');

        $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "$count_operateur opérateur sur un total de $total_count",
            default => "Liste des $count_operateur derniers opérateurs sur un total de $total_count",
        };
        return view('operateurs.filtrageoperateur-statut', compact('operateurs', 'statut', 'groupes', 'title'));
    }

    public function filtrerOperateurParStatutCategorie($statut, $categorie)
    {
        /* $operateurs = Operateur::when($statut !== 'Aucun statut', function ($query) use ($statut) {
            $query->where('statut_agrement', $statut);
        }, function ($query) {
            $query->whereNull('statut_agrement');
        })
            ->get(); */

        if ($categorie === 'Aucune') {
            $operateurs = Operateur::when($statut !== 'Aucun statut', function ($query) use ($statut) {
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
            $operateurs = Operateur::when($statut !== 'Aucun statut', function ($query) use ($statut) {
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

        $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "Structure $categorie(s) | $count_operateur opérateur sur un total de $total_count",
            default => "Structure $categorie | liste des $count_operateur derniers opérateurs sur un total de $total_count",
        };
        return view('operateurs.filtrageoperateur-statut-categorie', compact('operateurs', 'statut', 'groupes', 'title', 'categorie'));
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
        $operateurs = Operateur::where('commissionagrements_id', $calid)->get();
        $cal        = Commissionagrement::findOrFail($calid);

        /* $total_count  = number_format($operateurs->count(), 0, ',', ' '); */
        $departements = Departement::orderBy("nom", "asc")->get();

        $operateur_liste = Operateur::latest()->take(50)->get();
        $count_operateur = number_format($operateur_liste->count(), 0, ',', ' ');

        /* $title = match ($count_operateur) {
            "0" => 'Aucun opérateur',
            "1" => "$count_operateur opérateur sur un total de $total_count",
            default => "Liste des $count_operateur derniers opérateurs sur un total de $total_count",
        }; */

        $title = $cal?->commission . ' du ' . $cal?->date?->translatedFormat('l d F Y') . ' à ' . $cal?->lieu;

        $groupesStatutAgrement = $operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        });

        return view("operateurs.cal",
            compact(
                "operateurs",
                "groupesStatutAgrement",
                "departements",
                "title",
                "cal",
            ));

    }
}
