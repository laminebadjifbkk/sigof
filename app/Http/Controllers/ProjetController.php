<?php
namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Individuelle;
use App\Models\Projet;
use App\Models\Projetlocalite;
use App\Models\Projetmodule;
use App\Models\Region;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class ProjetController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Demandeur|DIOF|ADIOF|Ingenieur|DPP|DG|Employe|CCP']);
        $this->middleware("permission:projet-view", ["only" => ["index"]]);
        $this->middleware("permission:projet-create", ["only" => ["create", "store"]]);
        $this->middleware("permission:projet-update", ["only" => ["update", "edit"]]);
        $this->middleware("permission:projet-show", ["only" => ["show"]]);
        $this->middleware("permission:projet-delete", ["only" => ["destroy"]]);
    }

    public function index()
    {
        $projets = Projet::orderBy('created_at', 'desc')->get();

        return view('projets.index', compact('projets'));
    }

    public function addProjet(Request $request)
    {
        $this->validate($request, [
            "name"           => ["required", "string", Rule::unique('projets')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "sigle"          => ["required", "string", Rule::unique('projets')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            "date_signature" => ["date", "size:10", "date_format:Y-m-d"],
            "description"    => ["required", "string"],
            "duree"          => ["nullable", "string"],
            "budjet"         => ["nullable", "numeric"],
            "effectif"       => ["nullable", "string"],
            "debut"          => ["nullable", "date", "size:10", "date_format:Y-m-d"],
            "fin"            => ["nullable", "date", "size:10", "date_format:Y-m-d"],
            "type"           => ["required", "string"],
            "type_projet"    => ["required", "string"],
        ]);

        $debut = $request->input('debut') ?: null;
        $fin   = $request->input('fin') ?: null;

        $projet = new Projet([
            'name'           => $request->input('name'),
            'sigle'          => $request->input('sigle'),
            'date_signature' => $request->input('date_signature'),
            'description'    => $request->input('description'),
            'duree'          => $request->input('duree'),
            'budjet'         => (float) $request->input('budjet'),
            'debut'          => $debut,
            'fin'            => $fin,
            'effectif'       => $request->input('effectif'),
            'type_localite'  => $request->input('type'),
            'type_projet'    => $request->input('type_projet'),
            'statut'         => 'Attente',

        ]);

        $projet->save();

        Alert::success('Succès !', 'Partenaire ajouté avec succès');

        return redirect()->back();
    }

    public function show(Projet $projet)
    {
        /* $projet          = Projet::findOrFail($id); */
        $projetlocalites = Projetlocalite::where('projets_id', $projet->id)->get();

        $moduleLocalites = $projet->projetlocalites->pluck('lacalite', 'lacalite')->all();

        // Récupérer les individuelles avec Eloquent et relations pour plus de clarté
        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->get();

        // Récupérer les différents statuts
        $statuts = $individuelles->pluck('statut')->unique();

        // Regrouper par statut (y compris les null)
        $groupes = $individuelles->groupBy(function ($item) {
            return $item->statut ?? 'Aucun statut';
        });

        return view(
            'projets.show',
            compact(
                'projet',
                'projetlocalites',
                'individuelles',
                'statuts',
                'groupes',
                'moduleLocalites'
            )
        );
    }

    public function edit(Request $request, Projet $projet)
    {
        /* $projet = Projet::findOrFail($id); */

        return view('projets.update', compact('projet'));
    }

    public function update(Request $request, Projet $projet)
    {
        /* $projet = Projet::find($id); */

        $this->validate($request, [
            "name"            => ["required", "string", Rule::unique('projets')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($projet->id)],
            "sigle"           => ["required", "string", Rule::unique('projets')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($projet->id)],
            "date_signature"  => ["required", "date", "size:10", "date_format:Y-m-d"],
            "description"     => ["required", "string"],
            "duree"           => ["nullable", "string"],
            "budjet"          => ["nullable", "numeric"],
            "effectif"        => ["nullable", "string"],
            "debut"           => ["nullable", "date", "size:10", "date_format:Y-m-d"],
            "fin"             => ["nullable", "date", "size:10", "date_format:Y-m-d"],
            "type"            => ["required", "string"],
            "type_projet"     => ["required", "string"],
            "date_ouverture"  => ["nullable", "string", "date_format:Y-m-d"],
            "date_fermeture"  => ["nullable", "string", "date_format:Y-m-d"],
            'image'           => ['image', 'nullable', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'convention_file' => ['file', 'nullable', 'mimes:pdf', 'max:2048'],
        ]);

        /* if (!empty($request->input('date_ouverture'))) {
            $date_ouverture = $request->input('date_ouverture');
        } else {
            $date_ouverture = null;
        }
        if (!empty($request->input('date_fermeture'))) {
            $date_fermeture = $request->input('date_fermeture');
        } else {
            $date_fermeture = null;
        }
        if (!empty($request->input('debut'))) {
            $debut = $request->input('debut');
        } else {
            $debut = null;
        }
        if (!empty($request->input('fin'))) {
            $fin = $request->input('fin');
        } else {
            $fin = null;
        } */

        $date_ouverture = $request->input('date_ouverture') ?: null;
        $date_fermeture = $request->input('date_fermeture') ?: null;
        $debut          = $request->input('debut') ?: null;
        $fin            = $request->input('fin') ?: null;

        /*  if (request('image')) {

            $imagePath = request('image')->store('projets', 'public');

            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(2400, 2400);

            $image->save();

        } else {
            $imagePath = $projet->image;
        } */
        if (request()->hasFile('image')) {

            // Supprimer l'ancien fichier s'il existe
            if ($projet->image && Storage::disk('public')->exists($projet->image)) {
                Storage::disk('public')->delete($projet->image);
            }

            $originalName = request()->file('image')->getClientOriginalName();
            $filename     = time() . '_' . $originalName;

            $path = request()->file('image')->storeAs('projets', $filename, 'public');

            $image = Image::make(public_path("storage/{$path}"))->save(); // conserve la taille

            $imagePath = $path;

        } else {
            $imagePath = $projet->image;
        }

        /* if (request('convention_file')) {

            $filePath = request('convention_file')->store('projets', 'public');

            $file            = $request->file('convention_file');
            $filenameWithExt = $file->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Remove unwanted characters
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
        } else {
            $filePath = $projet->convention_file;
        }

        $projet->update([
            'name'            => $request->input('name'),
            'sigle'           => $request->input('sigle'),
            'date_signature'  => $request->input('date_signature'),
            'description'     => $request->input('description'),
            'duree'           => $request->input('duree'),
            'budjet'          => $request->input('budjet'),
            'debut'           => $debut,
            'fin'             => $fin,
            'effectif'        => $request->input('effectif'),
            'type_localite'   => $request->input('type'),
            'type_projet'     => $request->input('type_projet'),
            'date_ouverture'  => $date_ouverture,
            'date_fermeture'  => $date_fermeture,
            'image'           => $imagePath,
            'convention_file' => $filePath,
        ]); */

        if (request()->hasFile('convention_file')) {
            // Supprimer l'ancien fichier s'il existe
            if ($projet->convention_file && Storage::disk('public')->exists($projet->convention_file)) {
                Storage::disk('public')->delete($projet->convention_file);
            }

            $file         = request()->file('convention_file');
            $originalName = $file->getClientOriginalName();
            $filename     = pathinfo($originalName, PATHINFO_FILENAME);
            $extension    = $file->getClientOriginalExtension();

                                                                        // Nettoyage du nom
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename); // Supprime caractères spéciaux
            $filename = preg_replace("/\s+/", '-', $filename);          // Remplace espaces par tirets

            $finalFilename = time() . '_' . $filename . '.' . $extension;

            $filePath = $file->storeAs('projets', $finalFilename, 'public');
        } else {
            $filePath = $projet->convention_file;
        }

        $projet->update([
            'name'            => $request->input('name'),
            'sigle'           => $request->input('sigle'),
            'date_signature'  => $request->input('date_signature'),
            'description'     => $request->input('description'),
            'duree'           => $request->input('duree'),
            'budjet'          => (float) $request->input('budjet'),
            'debut'           => $debut,
            'fin'             => $fin,
            'effectif'        => $request->input('effectif'),
            'type_localite'   => $request->input('type'),
            'type_projet'     => $request->input('type_projet'),
            'date_ouverture'  => $date_ouverture,
            'date_fermeture'  => $date_fermeture,
            'image'           => $imagePath,
            'convention_file' => $filePath,
        ]);

        Alert::success('Succès', 'Modification effectuée avec succès');

        return redirect()->back();
    }
    public function destroy(Projet $projet)
    {
        /* $projet = Projet::findOrFail($id); */

        // Supprimer l'ancien fichier s'il existe
        if ($projet->image && Storage::disk('public')->exists($projet->image)) {
            Storage::disk('public')->delete($projet->image);
        }

        // Supprimer l'ancien fichier s'il existe
        if ($projet->convention_file && Storage::disk('public')->exists($projet->convention_file)) {
            Storage::disk('public')->delete($projet->convention_file);
        }

        $projet->delete();

        Alert::success('Succès !', 'Suppression effectuée avec succès');

        return redirect()->back();
    }

    public function projetsIndividuelle($uuid)
    {
        $user            = Auth::user();
        $projet          = Projet::where('uuid', $uuid)->firstOrFail();
        $type_localite   = $projet->type_localite;
        $projetlocalites = Projetlocalite::where('projets_id', $projet->id)
            ->orderBy("created_at", "desc")->get();

        $projetmodules = Projetmodule::where('projets_id', $projet->id)
            ->orderBy("created_at", "desc")
            ->get();

        $individuelle = Individuelle::where('users_id', $user->id)
            ->where('projets_id', $projet->id)
            ->where('numero', '!=', null)
            ->get();

        // Récupérer les fichiers associés à l'utilisateur
        $files = File::where('users_id', $user->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();

        /* $user_files = File::where('users_id', $user->id)
            ->whereNull('file')
            ->distinct()
            ->get(); */

        $user_files = File::where('users_id', $user?->id)
            ->whereNull('file')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC'])
            ->distinct()
            ->get();

        $statut_projet = $projet->statut;

        if ($statut_projet == 'ouvert') {
            $statut = $statut_projet;
        } else {
            $statut = null;
        }

        $individuelle_total = $individuelle->count();

        $individuelles = Individuelle::where('users_id', $user->id)
            ->where('projets_id', $projet->id)
            ->get();

        if ($individuelle_total == 0) {
            return view(
                "individuelles.show-projet-aucune",
                compact(
                    "individuelle_total",
                    "projetlocalites",
                    "projetmodules",
                    "individuelles",
                    "statut",
                    "projet"
                )
            );
        } else {
            return view(
                "individuelles.show-projet",
                compact(
                    "individuelle_total",
                    "projetlocalites",
                    "projetmodules",
                    "individuelles",
                    "files",
                    "user_files",
                    "statut",
                    "projet"
                )
            );
        }
    }

    public function ouvrirProjet($id)
    {
        $projet = Projet::findOrFail($id);

        $projet->update([
            'statut' => 'ouvert',
        ]);

        $projet->save();

        Alert::success('Succès !', 'Les dépôts pour ' . $projet->sigle . ' sont est ouverts');

        return redirect()->back();
    }

    public function fermerProjet($id)
    {

        $projet = Projet::findOrFail($id);

        $projet->update([
            'statut' => 'fermer',
        ]);

        $projet->save();

        Alert::success('Succès', 'Les dépôts pour ' . $projet->sigle . ' sont fermés');

        return redirect()->back();
    }

    public function showprojetProgramme(Request $request)
    {
        $user = Auth::user();

        $projets = Individuelle::join('projets', 'projets.id', 'individuelles.projets_id')
            ->select('projets.*')
            ->where('individuelles.users_id', $user->id)
            ->where('individuelles.projets_id', '!=', null)
            ->where('projets.statut', 'ouvert')
            ->orwhere('projets.statut', 'fermer')
            ->distinct()
            ->get();

        return view(
            "individuelles.show-projetprogramme",
            compact(
                "projets"
            )
        );
    }
    public function projetsBeneficiaire(Request $request, $uuid)
    {
        $projet = Projet::where('uuid', $uuid)->firstOrFail();

        return view('projets.individuelle', compact('projet'));
    }

    public function terminer($id)
    {
        $projet         = Projet::findOrFail($id);
        $projet->statut = 'Terminé';
        $projet->save();

        Alert::success('Succès', 'Projet terminé avec succès');

        return redirect()->back();
    }

    public function filtrerParStatut($module, $statut, $projetmoduleid)
    {

        $projetmodule = Projetmodule::findOrFail($projetmoduleid);
        $projet       = $projetmodule->projet;

        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->whereHas('module', function ($query) use ($module) {
                $query->where('name', $module);
            })
            ->when($statut !== 'Aucun statut', function ($query) use ($statut) {
                $query->where('statut', $statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->get();

        return view('projets.filtrage-statut', compact('individuelles', 'statut', 'module', 'projet', 'projetmodule'));
    }

    public function filtrerProjetParStatut($statut, $projetid)
    {

        $projet = Projet::findOrFail($projetid);

        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->when($statut !== 'Aucun statut', function ($query) use ($statut) {
                $query->where('statut', $statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->get();

        return view('projets.filtrageprojet-statut', compact('individuelles', 'statut', 'projet'));
    }

    public function filtrerProjetLocaliteParStatut($statut, $projetlocaliteid, $typelocalite, $localite)
    {

        /* Alert::info('Info !', 'En cours de développement');

        return redirect()->back(); */

        $projetlocalite = Projetlocalite::findOrFail($projetlocaliteid);
        $projet         = $projetlocalite->projet;

        $region = Region::where('nom', $projetlocalite->localite)->first();

        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->where('regions_id', $region?->id) // sécurise si $region est null
            ->when($statut !== 'Aucun statut', function ($query) use ($statut) {
                $query->where('statut', $statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->get();

        return view('projetlocalites.filtragelocalite-statut', compact('individuelles', 'statut', 'projetlocalite', 'region', 'typelocalite', 'localite', 'projet'));
    }

    public function listeSelectionnes(Request $request)
    {

        /*  $statut        = $request->input('statut');
        $projetmodule  = Projetmodule::findOrFail($request->input('projetmoduleid'));
        $projet        = $projetmodule->projet;
        $module        = $projetmodule->module;

        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->whereHas('module', function ($query) use ($module) {
                $query->where('name', $module);
            })
            ->when($statut !== 'Aucun statut', function ($query) use ($statut) {
                $query->where('statut', $statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->get(); */

        $statut       = $request->input('statut');
        $projetmodule = Projetmodule::findOrFail($request->input('projetmoduleid'));
        $projet       = $projetmodule->projet;
        $module       = $projetmodule->module;

        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->whereHas('module', function ($query) use ($module) {
                $query->where('name', $module);
            })
            ->when($statut !== 'Aucun statut', function ($query) use ($statut) {
                $query->where('statut', $statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->orderBy('note', 'desc') // 🔽 Classement par note décroissante
            ->get();

        $title = $projet->sigle . ',liste des candidats selectionnés pour la formation en ' . $projetmodule->module;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('projets.liste-selectionne', compact(
            'projet',
            'projetmodule',
            'individuelles',
            'title',
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = $projet->sigle . ',liste des candidats selectionnés pour la formation en  ' . $projetmodule->module . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);

    }

    public function listeAttente(Request $request)
    {

        /*  $statut        = $request->input('statut');
        $projetmodule  = Projetmodule::findOrFail($request->input('projetmoduleid'));
        $projet        = $projetmodule->projet;
        $module        = $projetmodule->module;

        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->whereHas('module', function ($query) use ($module) {
                $query->where('name', $module);
            })
            ->when($statut !== 'Aucun statut', function ($query) use ($statut) {
                $query->where('statut', $statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->get(); */

        $statut       = $request->input('statut');
        $projetmodule = Projetmodule::findOrFail($request->input('projetmoduleid'));
        $projet       = $projetmodule->projet;
        $module       = $projetmodule->module;

        $individuelles = Individuelle::where('projets_id', $projet->id)
            ->whereHas('module', function ($query) use ($module) {
                $query->where('name', $module);
            })
            ->when($statut !== 'Aucun statut', function ($query) use ($statut) {
                $query->where('statut', $statut);
            }, function ($query) {
                $query->whereNull('statut');
            })
            ->orderBy('note', 'desc') // 🔽 Classement par note décroissante
            ->get();

        $title = $projet->sigle . ',liste des candidats selectionnés pour la formation en ' . $projetmodule->module;

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Formation');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('projets.liste-attente', compact(
            'projet',
            'projetmodule',
            'individuelles',
            'title',
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = $projet->sigle . ',liste des candidats selectionnés pour la formation en  ' . $projetmodule->module . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);

    }
}
