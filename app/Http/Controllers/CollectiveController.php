<?php
namespace App\Http\Controllers;

use App\Models\Arrive;
use App\Models\Collective;
use App\Models\Collectivemodule;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\File;
use App\Models\Ingenieur;
use App\Models\Listecollective;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class CollectiveController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Demandeur|DIOF|ADIOF|Ingenieur|DEC|ADEC']);
        /* $this->middleware("permission:user-view", ["only" => ["index"]]); */
        $this->middleware("permission:collective-view", ["only" => ["index"]]);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }
    public function index()
    {
        $collectives  = Collective::get();
        $departements = Departement::orderBy("created_at", "desc")->get();
        $communes     = Commune::orderBy("created_at", "desc")->get();
        $modules      = Module::orderBy("created_at", "desc")->get();
        $today        = date('Y-m-d');
        $count_today  = Collective::where("created_at", "LIKE", "{$today}%")->count();
        return view('collectives.index', compact('collectives', 'departements', 'communes', 'modules', 'count_today'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            "name"                  => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "sigle"                 => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "email"                 => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "fixe"                  => ["nullable", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "telephone"             => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            /* "module"                =>      ["required","string"], */
            "adresse"               => ["required", "string"],
            "statut"                => ["required", "string"],
            "description"           => ["required", "string", "max:1000"],
            "projetprofessionnel"   => ["required", "string", "max:1000"],
            "departement"           => ["required", "string"],
            "civilite"              => ["required", "string"],
            "prenom"                => ["required", "string"],
            "nom"                   => ["required", "string"],
            "fonction_responsable"  => ["required", "string"],
            "telephone_responsable" => ["required", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "email_responsable"     => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
        ]);

        $user = Auth::user();

        $collective_total = Collective::where('users_id', $user->id)->count();

        if ($collective_total >= 1) {
            Alert::warning('Avertissement !', 'Vous avez atteint la limite autorisée de demandes.');
            return redirect()->back();
        } else {
            /*   $rand = rand(0, 999);
            $letter1 = chr(rand(65, 90));
            $letter2 = chr(rand(65, 90));
            $random = $letter1.''.$rand . '' . $letter2;
            $longueur = strlen($random);

            if ($longueur == 1) {
                $numero_collective   =   strtoupper("0000" . $random);
            } elseif ($longueur >= 2 && $longueur < 3) {
                $numero_collective   =   strtoupper("000" . $random);
            } elseif ($longueur >= 3 && $longueur < 4) {
                $numero_collective   =   strtoupper("00" . $random);
            } elseif ($longueur >= 4 && $longueur < 5) {
                $numero_collective   =   strtoupper("0" . $random);
            } else {
                $numero_collective   =   strtoupper($random);
            } */
            /* $annee = date('y');
            $numero_collective = Collective::get()->last();
            if (isset($numero_collective)) {
                $numero_collective = Collective::get()->last()->numero;
                $numero_collective = ++$numero_collective;
                $longueur = strlen($numero_collective);
                if ($longueur <= 1) {
                    $numero_collective   =   strtolower("0000" . $numero_collective);
                } elseif ($longueur >= 2 && $longueur < 3) {
                    $numero_collective   =   strtolower("000" . $numero_collective);
                } elseif ($longueur >= 3 && $longueur < 4) {
                    $numero_collective   =   strtolower("00" . $numero_collective);
                } elseif ($longueur >= 4 && $longueur < 5) {
                    $numero_collective   =   strtolower("0" . $numero_collective);
                } else {
                    $numero_collective   =   strtolower($numero_collective);
                }
            } else {
                $numero_collective = "00001";
                $numero_collective = 'C' . $annee . $numero_collective;
            } */
            /*  $anneeEnCours = date('Y');
            $an           = date('y');

            $numero_collective = Collective::join('users', 'users.id', 'collectives.users_id')
                ->select('collectives.*')
                ->where('collectives.date_depot', "LIKE", "{$anneeEnCours}%")
                ->get()->last();

            if (isset($numero_collective)) {
                $numero_collective = Collective::join('users', 'users.id', 'collectives.users_id')
                    ->select('collectives.*')
                    ->get()->last()->numero;
                $numero_collective = ++$numero_collective;
            } else {
                $numero_collective = $an . "0001";
                $numero_collective = 'C' . $numero_collective;
            }

            $longueur = strlen($numero_collective);

            if ($longueur <= 1) {
                $numero_collective = strtolower("00000" . $numero_collective);
            } elseif ($longueur >= 2 && $longueur < 3) {
                $numero_collective = strtolower("0000" . $numero_collective);
            } elseif ($longueur >= 3 && $longueur < 4) {
                $numero_collective = strtolower("000" . $numero_collective);
            } elseif ($longueur >= 4 && $longueur < 5) {
                $numero_collective = strtolower("00" . $numero_collective);
            } elseif ($longueur >= 5 && $longueur < 6) {
                $numero_collective = strtolower("0" . $numero_collective);
            } else {
                $numero_collective = strtolower($numero_collective);
            }

            $numero_collective = strtoupper($numero_collective);

            $departement = Departement::where('nom', $request->input("departement"))->get()->first();
            $regionid    = $departement->region->id; */
            $anneeEnCours = date('Y');
            $an           = date('y');

            $prefix = 'C' . $an;

            // Récupérer le dernier numéro pour l'année en cours
            $lastCollective = Collective::where('numero', 'LIKE', "{$prefix}%")
                ->orderBy('id', 'desc')
                ->first();

            if ($lastCollective) {
                // Extraire la partie numérique après "C" + $an
                $lastNumero = intval(substr($lastCollective->numero, 3));
                $newNumero  = $lastNumero + 1;
            } else {
                $newNumero = 1;
            }

            // Générer le numéro formaté
            do {
                $numero_collective = $prefix . str_pad($newNumero, 4, '0', STR_PAD_LEFT);
                $exists            = Collective::where('numero', $numero_collective)->exists();
                if ($exists) {
                    $newNumero++; // Incrémenter et tester à nouveau
                }
            } while ($exists);

            // Récupération de l'ID de la région
            $departement = Departement::where("nom", $request->input("departement"))->first();
            $regionid    = $departement->region->id;

            /* $module_find    = DB::table('modules')->where('name', $request->input("module"))->first(); */

            $collective = Collective::create([
                "name"                   => $request->input("name"),
                "sigle"                  => $request->input("sigle"),
                "numero"                 => $numero_collective,
                "date_depot"             => now(),
                "description"            => $request->input("description"),
                "projetprofessionnel"    => $request->input("projetprofessionnel"),
                "telephone"              => $request->input("telephone"),
                "email"                  => $request->input("email"),
                "email_responsable"      => $request->input("email_responsable"),
                "fixe"                   => $request->input("fixe"),
                "adresse"                => $request->input("adresse"),
                "bp"                     => $request->input("bp"),
                "statut_juridique"       => $request->input("statut"),
                "autre_statut_juridique" => $request->input("autre_statut"),
                "statut_demande"         => 'Nouvelle',
                "civilite_responsable"   => $request->input("civilite"),
                "prenom_responsable"     => $request->input("prenom"),
                "nom_responsable"        => $request->input("nom"),
                "telephone_responsable"  => $request->input("telephone_responsable"),
                "fonction_responsable"   => $request->input("fonction_responsable"),
                "departements_id"        => $departement->id,
                /* "modules_id"                =>       $module_find->id, */
                "regions_id"             => $regionid,
                /* "demandeurs_id"             =>       $demandeur->id, */
                'users_id'               => $user->id,
            ]);

            $collective->save();

            Alert::success("Succès !", "L'enregistrement a été effectué avec succès.");

            return redirect()->back();
        }
    }
    public function addCollective(Request $request)
    {
        $this->validate($request, [
            "name"                  => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "sigle"                 => ["nullable", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "email"                 => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "fixe"                  => ["nullable", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "telephone"             => ["required", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'date_depot'            => ['required', 'date', 'size:10', 'date_format:Y-m-d'],
            "adresse"               => ["required", "string"],
            "statut"                => ["required", "string"],
            "numero_courrier"       => ["required", "string"],
            "description"           => ["required", "string", "max:1000"],
            "projetprofessionnel"   => ["required", "string", "max:1000"],
            "departement"           => ["required", "string"],
            "civilite"              => ["required", "string"],
            "prenom"                => ["required", "string"],
            "nom"                   => ["required", "string"],
            "fonction_responsable"  => ["required", "string"],
            "telephone_responsable" => ["required", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "email_responsable"     => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
        ]);

        /* $anneeEnCours = date('Y');
        $an           = date('y');

        $numero_collective = Collective::join('users', 'users.id', 'collectives.users_id')
            ->select('collectives.*')
            ->where('date_depot', "LIKE", "{$anneeEnCours}%")
            ->get()->last();

        if (isset($numero_collective)) {
            $numero_collective = Collective::join('users', 'users.id', 'collectives.users_id')
                ->select('collectives.*')
                ->get()->last()->numero;
            $numero_collective = ++$numero_collective;
        } else {
            $numero_collective = $an . "0001";
            $numero_collective = 'C' . $numero_collective;
        }

        $longueur = strlen($numero_collective);

        if ($longueur <= 1) {
            $numero_collective = strtolower("00000" . $numero_collective);
        } elseif ($longueur >= 2 && $longueur < 3) {
            $numero_collective = strtolower("0000" . $numero_collective);
        } elseif ($longueur >= 3 && $longueur < 4) {
            $numero_collective = strtolower("000" . $numero_collective);
        } elseif ($longueur >= 4 && $longueur < 5) {
            $numero_collective = strtolower("00" . $numero_collective);
        } elseif ($longueur >= 5 && $longueur < 6) {
            $numero_collective = strtolower("0" . $numero_collective);
        } else {
            $numero_collective = strtolower($numero_collective);
        }

        $numero_collective = strtoupper($numero_collective);

        $departement = Departement::where("nom", $request->input("departement"))->first();
        $regionid    = $departement->region->id; */

        $anneeEnCours = date('Y');
        $an           = date('y');

        $prefix = 'C' . $an;

// Récupérer le dernier numéro pour l'année en cours
        $lastCollective = Collective::where('numero', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastCollective) {
            // Extraire la partie numérique après "C" + $an
            $lastNumero = intval(substr($lastCollective->numero, 3));
            $newNumero  = $lastNumero + 1;
        } else {
            $newNumero = 1;
        }

// Générer le numéro formaté
        do {
            $numero_collective = $prefix . str_pad($newNumero, 4, '0', STR_PAD_LEFT);
            $exists            = Collective::where('numero', $numero_collective)->exists();
            if ($exists) {
                $newNumero++; // Incrémenter et tester à nouveau
            }
        } while ($exists);

// Récupération de l'ID de la région
        $departement = Departement::where("nom", $request->input("departement"))->first();
        $regionid    = $departement->region->id;

        $user = User::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'username'  => $request->input('sigle'),
            'telephone' => $request->input('telephone'),
            'adresse'   => $request->input('adresse'),
            'password'  => Hash::make($request->email),
        ]);

        $user->save();

        $user->assignRole('Demandeur');
        /* $user->assignRole('Collective'); */

        $collective = Collective::create([
            "name"                   => $request->input("name"),
            "numero_courrier"        => $request->input("numero_courrier"),
            "sigle"                  => $request->input("sigle"),
            "numero"                 => $numero_collective,
            "description"            => $request->input("description"),
            "date_depot"             => $request->input("date_depot"),
            "projetprofessionnel"    => $request->input("projetprofessionnel"),
            "telephone"              => $request->input("telephone"),
            "email"                  => $request->input("email"),
            "email_responsable"      => $request->input("email_responsable"),
            "fixe"                   => $request->input("fixe"),
            "adresse"                => $request->input("adresse"),
            "bp"                     => $request->input("bp"),
            "statut_juridique"       => $request->input("statut"),
            "autre_statut_juridique" => $request->input("autre_statut"),
            "statut_demande"         => 'Nouvelle',
            "civilite_responsable"   => $request->input("civilite"),
            "prenom_responsable"     => $request->input("prenom"),
            "nom_responsable"        => $request->input("nom"),
            "telephone_responsable"  => $request->input("telephone_responsable"),
            "fonction_responsable"   => $request->input("fonction_responsable"),
            "departements_id"        => $departement->id,
            "regions_id"             => $regionid,
            'users_id'               => $user->id,
        ]);

        $collective->save();

        Alert::success("Succès !", "L'enregistrement a été effectué avec succès.");

        return redirect()->back();
    }

    public function update(Request $request, Collective $collective)
    {
        /* $collective = Collective::findOrFail($id); */
        $user_id = $collective?->users_id;

        $this->authorize('update', $collective);
        /* $this->authorize('update', $collective); */

        $this->validate($request, [
            "name"                  => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($collective->id)],
            "sigle"                 => ["nullable", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($collective->id)],
            "email"                 => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($collective->id)],
            "fixe"                  => ["nullable", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($collective->id)],
            "telephone"             => ["required", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($collective->id)],
            'date_depot'            => ['nullable', 'date', 'size:10', 'date_format:Y-m-d'],
            "adresse"               => ["required", "string"],
            "statut"                => ["required", "string"],
            "description"           => ["required", "string", "max:1000"],
            "projetprofessionnel"   => ["required", "string", "max:1000"],
            "departement"           => ["required", "string"],
            "civilite"              => ["required", "string"],
            "prenom"                => ["required", "string"],
            "nom"                   => ["required", "string"],
            "fonction_responsable"  => ["required", "string"],
            "telephone_responsable" => ["required", "string", "size:12", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($collective->id)],
            "email_responsable"     => ["required", "string", Rule::unique('collectives')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($collective->id)],
        ]);

        $departement = Departement::where('nom', $request->input("departement"))->first();
        $regionid    = $departement->region->id;

        foreach (Auth::user()->roles as $key => $role) {
        }

        if ($collective->statut_demande != 'Nouvelle' && ! empty($role?->name) && ($role?->name != 'super-admin')) {
            Alert::warning('Attention ! ', 'action impossible demande déjà traitée.');
            return redirect()->back();
        }

        /* if ($request->input(key: "date_depot") == null) {
            $collective->update([
                "numero_courrier"        => $request->input("numero_courrier"),
                "name"                   => $request->input("name"),
                "sigle"                  => $request->input("sigle"),
                "description"            => $request->input("description"),
                "projetprofessionnel"    => $request->input("projetprofessionnel"),
                "telephone"              => $request->input("telephone"),
                "email"                  => $request->input("email"),
                "email_responsable"      => $request->input("email_responsable"),
                "fixe"                   => $request->input("fixe"),
                "adresse"                => $request->input("adresse"),
                "bp"                     => $request->input("bp"),
                "statut_juridique"       => $request->input("statut"),
                "autre_statut_juridique" => $request->input("autre_statut"),
                "civilite_responsable"   => $request->input("civilite"),
                "prenom_responsable"     => $request->input("prenom"),
                "nom_responsable"        => $request->input("nom"),
                "telephone_responsable"  => $request->input("telephone_responsable"),
                "fonction_responsable"   => $request->input("fonction_responsable"),
                "departements_id"        => $departement->id,
                "regions_id"             => $regionid,
                "users_id"               => $user_id,
            ]);
        } else {
            $collective->update([
                "numero_courrier"        => $request->input("numero_courrier"),
                "name"                   => $request->input("name"),
                "sigle"                  => $request->input("sigle"),
                "description"            => $request->input("description"),
                "projetprofessionnel"    => $request->input("projetprofessionnel"),
                "telephone"              => $request->input("telephone"),
                "email"                  => $request->input("email"),
                "email_responsable"      => $request->input("email_responsable"),
                "fixe"                   => $request->input("fixe"),
                "date_depot"             => $request->input(key: "date_depot"),
                "adresse"                => $request->input("adresse"),
                "bp"                     => $request->input("bp"),
                "statut_juridique"       => $request->input("statut"),
                "autre_statut_juridique" => $request->input("autre_statut"),
                "civilite_responsable"   => $request->input("civilite"),
                "prenom_responsable"     => $request->input("prenom"),
                "nom_responsable"        => $request->input("nom"),
                "telephone_responsable"  => $request->input("telephone_responsable"),
                "fonction_responsable"   => $request->input("fonction_responsable"),
                "departements_id"        => $departement->id,
                "regions_id"             => $regionid,
                "users_id"               => $user_id,
            ]);
        } */

        $data = [
            "numero_courrier"        => $request->input("numero_courrier"),
            "name"                   => $request->input("name"),
            "sigle"                  => $request->input("sigle"),
            "description"            => $request->input("description"),
            "projetprofessionnel"    => $request->input("projetprofessionnel"),
            "telephone"              => $request->input("telephone"),
            "email"                  => $request->input("email"),
            "email_responsable"      => $request->input("email_responsable"),
            "fixe"                   => $request->input("fixe"),
            "adresse"                => $request->input("adresse"),
            "bp"                     => $request->input("bp"),
            "statut_juridique"       => $request->input("statut"),
            "autre_statut_juridique" => $request->input("autre_statut"),
            "civilite_responsable"   => $request->input("civilite"),
            "prenom_responsable"     => $request->input("prenom"),
            "nom_responsable"        => $request->input("nom"),
            "telephone_responsable"  => $request->input("telephone_responsable"),
            "fonction_responsable"   => $request->input("fonction_responsable"),
            "departements_id"        => $departement->id,
            "regions_id"             => $regionid,
            "users_id"               => $user_id,
        ];

        // Ajouter `date_depot` seulement si elle est présente
        if ($request->input("date_depot") !== null) {
            $data["date_depot"] = $request->input("date_depot");
        }

        // Effectuer la mise à jour avec les données
        $collective->update($data);

        $collective->save();

        Alert::success("Modification réussie", "Les modifications ont été enregistrées avec succès.");

        return Redirect::back();
    }

    public function edit(Collective $collective)
    {
        /* $collective   = Collective::findOrFail($id); */
        $departements = Departement::orderBy("created_at", "desc")->get();
        $modules      = Module::orderBy("created_at", "desc")->get();
        return view("collectives.update", compact("collective", "departements", "modules"));
    }
    public function show(Collective $collective)
    {
        /* $collective = Collective::findOrFail($id); */
        $this->authorize('view', $collective);

        /*  $this->authorize('view', $collective); */

        $ingenieur = $collective?->ingenieur;

        $listecollective = Listecollective::where('collectives_id', $collective->id)->first();

        $listemodulescollective = Collectivemodule::where("collectives_id", $collective->id)->first();

        $collectivemodules = Collectivemodule::where("collectives_id", $collective->id)->get();

        $collectives = Collective::where('users_id', $collective?->users_id)->first();

        return view(
            'collectives.show',
            compact(
                'ingenieur',
                'collective',
                'collectivemodules',
                'collectives',
                'listecollective',
                'listemodulescollective',
            )
        );
    }

    public function destroy(Collective $collective)
    {
        /* $collective = Collective::find($id); */

        $this->authorize('delete', $collective);

        if ($collective->statut_demande != 'Nouvelle') {
            Alert::warning('Attention !', 'Cette action est impossible, la demande a déjà été traitée.');
            return redirect()->back();
        } else {
            $collective->update([
                'numero' => $collective->numero . '/' . $collective->id,
            ]);

            $collective->save();

            $collective->delete();

            Alert::success('Succès !', 'La demande a été supprimée avec succès.');

            return redirect()->back();
        }
    }
    public function demandesCollective(Request $request)
    {
        $departements     = Departement::latest()->get();
        $modules          = Module::latest()->get();
        $user             = Auth::user();
        $collective       = $user->collectives()->first(); // Récupère le plus récent grâce à `latest() definie dans le modele User`
        $collective_total = $user->collectives()->count(); // Compte le nombre total de collectives

        $files = File::where('users_id', $collective?->user?->id)
            ->whereNotNull('file') // Utilisation de whereNotNull pour plus de clarté
            ->distinct()
            ->get();

        $user_files = File::where('users_id', $collective?->user?->id)
            ->whereNull('file')
            ->where(function ($query) {
                $query->where('sigle', 'AC')
                    ->orWhere('sigle', 'Arrêté')
                    ->orWhere('sigle', 'Autres')
                    ->orWhere('sigle', 'Ninea/RC');
            })
            ->distinct()
            ->get();

        if ($collective_total == 0) {
            return view("collectives.show-collective-aucune", compact("collective_total", "departements", "modules"));
        } else {
            return view("collectives.show-collective", compact("collective_total", "departements", "modules", "files", "user_files"));
        }
    }

    public function rapports(Request $request)
    {
        $title = 'rapports demandes collectives';

        return view('collectives.rapports', compact(
            'title'
        ));
    }
    public function generateRapport(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date|date_format:Y-m-d',
            'to_date'   => 'required|date|date_format:Y-m-d',
        ]);

        $now = Carbon::now()->format('H:i:s');

        $from_date = date_format(date_create($request->from_date), 'd/m/Y');

        $to_date = date_format(date_create($request->to_date), 'd/m/Y');

        if (! empty($request?->statut)) {
            $collectives = Collective::where('statut_juridique', $request?->statut)?->whereBetween(DB::raw('DATE(created_at)'), [$request?->from_date, $request?->to_date])?->get();
        } else {
            $collectives = Collective::whereBetween(DB::raw('DATE(created_at)'), [$request?->from_date, $request?->to_date])?->get();
        }

        $count = $collectives->count();

        if ($from_date == $to_date) {
            if (isset($count) && $count < "1") {
                $title = 'aucune demande collective reçue le ' . $from_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' demande collective reçue le ' . $from_date;
            } else {
                $title = $count . ' demandes collective reçues le ' . $from_date;
            }
        } else {
            if (isset($count) && $count < "1") {
                $title = 'aucune demande collective reçue entre le ' . $from_date . ' et le ' . $to_date;
            } elseif (isset($count) && $count == "1") {
                $title = $count . ' demande collective reçue entre le ' . $from_date . ' et le ' . $to_date;
            } else {
                $title = $count . ' demandes collective reçues entre le ' . $from_date . ' et le ' . $to_date;
            }
        }

        return view('collectives.rapports', compact(
            'collectives',
            'from_date',
            'to_date',
            'title'
        ));
    }

    public function addcollectiveingenieurs($id)
    {
        $collective = Collective::findOrFail($id);
        $ingenieur  = $collective?->ingenieur?->name;

        $ingenieurs = Ingenieur::get();

        $ingenieurCollective = DB::table('collectives')
            ->where('ingenieurs_id', $collective->ingenieurs_id)
            ->pluck('ingenieurs_id', 'ingenieurs_id')
            ->all();

        return view("collectives.ingenieur", compact('collective', 'ingenieurs', 'ingenieur', 'ingenieurCollective'));
    }

    public function givecollectiveingenieurs($id, Request $request)
    {
        $request->validate([
            'ingenieur' => ['required'],
        ]);

        $collective = Collective::findOrFail($id);

        $collective->update([
            "ingenieurs_id"  => $request->input('ingenieur'),
            "statut_demande" => 'Imputée',
        ]);

        $collective->save();

        Alert::success('Succès !', 'La demande collective a été assignée à l\'ingénieur avec succès.');

        return redirect()->back();
    }

    public function fetch(Request $request)
    {
        if ($request->has('query')) {
            $query = $request->input('query');

            $data = Arrive::where('numero_arrive', 'LIKE', "%{$query}%")->get();

            if ($data->isEmpty()) {
                return response()->json(['html' => '']);
            }

            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';

            foreach ($data as $arrive) {
                $output .= '<li data-id="' . $arrive->id . '" data-objet="' . e($arrive->courrier->expediteur) . '">
                            <a href="#">' . e($arrive->numero_arrive) . '</a>
                        </li>';
            }

            $output .= '</ul>';

            return response()->json(['html' => $output]);
        }

        return response()->json(['html' => ''], 400);
    }

    public function getObjetByNumero(Request $request)
    {
        $numero = $request->input('numero');

        // Rechercher l'objet en fonction du numéro
        $arrive = Arrive::where('numero_arrive', $numero)->first();

        if ($arrive) {
            return response()->json([
                'objet'      => $arrive->courrier->expediteur,
                'date_depot' => $arrive->courrier->date_recep->format('Y-m-d'), // Formater la date
            ]);
        } else {
            return response()->json(['objet' => '']); // Retourner une chaîne vide si aucun résultat
        }
    }

    public function corbeille()
    {
        $total_count = Collective::onlyTrashed()->count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $collectives = Collective::onlyTrashed()
            ->orderByDesc('deleted_at') // Trie par la date de suppression la plus récente
            ->take(250)
            ->get();

        $count_demandeur = number_format($collectives->count(), 0, ',', ' ');

        if ($count_demandeur < 1) {
            $title = 'Aucune demande collective supprimée';
        } elseif ($count_demandeur == 1) {
            $title = "$count_demandeur demande collective supprimée sur un total de $total_count";
        } else {
            $title = "Liste des $count_demandeur derniers demandes supprimées sur un total de $total_count";
        }

        return view("collectives.corbeille", compact("collectives", "title"));

    }

    public function forceDelete($uuid)
    {
        $collective = Collective::withTrashed()->where('uuid', $uuid)->firstOrFail();
// Supprimer
        $collective->forceDelete();

        Alert::success('Succès ', 'Demande supprimé définitivement.');
        return redirect()->back();

    }
    public function restore($uuid)
    {
        $collective = Collective::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $collective->restore();

        return redirect()->back()->with('success', 'Demande restauré avec succès.');
    }
}
