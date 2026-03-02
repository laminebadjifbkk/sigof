<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Individuelle;
use App\Models\Module;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ModuleController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DIOF|ADIOF|Ingenieur|']);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }
    public function index(Request $request)
    {
        $total = Module::count();
        $totalModules = number_format($total, 0, ',', ' ');

        $query = Module::query();

        if ($statut = $request->query('statut')) {
            $query->where('statut', $statut);
        }

        $modules = $query
            ->latest()
            ->limit(100)
            ->get();

        $affichees = $modules?->count();
        $total     = $totalModules ?? ($modules instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $modules->total()
            : $modules?->count());

        $domaines = Domaine::select('id', 'uuid', 'name', 'created_at')
            ->latest('created_at')->get();

        $regions = Region::select('id', 'uuid', 'nom', 'created_at')
            ->latest('created_at')->get();

        return view(
            "modules.index",
            compact(
                "modules",
                "domaines",
                "regions",
                "affichees",
                "total",
            )
        );
    }

    public function edit(Module $module)
    {
        /* $module   = Module::find($id); */
        $domaines = Domaine::orderBy("created_at", "desc")->get();
        return view("modules.update", compact("module", "domaines"));
    }

    public function show(Request $request, $module)
    {
        $module = Module::where('uuid', $request->module)->firstOrFail();

        // 🔹 Récupérer les demandes individuelles liées au module
        $query = Individuelle::where('modules_id', $module->id);

        // 🔹 Filtrer par statut si présent
        if ($statut = $request->query('statut')) {
            $query->where('statut', $statut);
        }

        // 🔹 Récupération (si tu veux limiter à 100)
        $individuelles = $query
            ->latest()
            ->limit(100)
            ->get();

        // 🔹 Total réel des demandes liées au module (sans limite)
        $total = Individuelle::where('modules_id', $module->id)->count();

        // 🔹 Nombre affiché (après filtre et limite)
        $affichees = $individuelles->count();

        // 🔹 Récupérer les différents statuts
        $statuts = $individuelles->pluck('statut')->unique();

        // 🔹 Regrouper par statut (y compris null)
        $groupes = $individuelles->groupBy(function ($item) {
            return $item->statut ?? 'Aucun';
        });


        $module->load('individuelles.projet');

        $availableColors = ['table-primary', 'table-success', 'table-warning', 'table-info', 'table-danger'];
        $sigleColors = [];
        $colorIndex = 0;

        $module->individuelles = $module->individuelles->map(function ($individuelle) use (&$sigleColors, &$colorIndex, $availableColors) {

            $sigle = $individuelle->projet?->sigle;
            $rowClass = '';

            if (!empty($sigle)) {
                if (!isset($sigleColors[$sigle])) {
                    $sigleColors[$sigle] = $availableColors[$colorIndex % count($availableColors)];
                    $colorIndex++;
                }

                $rowClass = $sigleColors[$sigle];
            }

            $individuelle->row_class = $rowClass;

            return $individuelle;
        });

        return view(
            "modules.show",
            compact("module", "affichees", "total", "groupes")
        );
    }

    public function update(Request $request, Module $module)
    {
        $this->validate($request, [
            "name"                 => [
                "required",
                "string",
                Rule::unique(Module::class)->ignore($module->id)->whereNull('deleted_at')
            ],
            "domaine"              => ["required", "string"],
            "niveau_qualification" => ["required", "string"],
        ]);

        /* $module = Module::findOrFail($id); */

        $module->update([
            'name'                 => $request->input('name'),
            'domaines_id'          => $request->input('domaine'),
            'niveau_qualification' => $request->input('niveau_qualification'),
        ]);

        $module->save();

        Alert::success('Succès !', 'Les modifications ont été enregistrées avec succès.');

        return redirect()->back();
    }

    public function modulelocalite($idlocalite, $idmodule)
    {
        $localite = Region::findOrFail($idlocalite);
        $module   = Module::findOrFail($idmodule);

        $individuelles = Individuelle::where('regions_id', $idlocalite)->where('modules_id', $idmodule)->get();

        return view("modules.modulelocalite", compact("module", "localite", "individuelles"));
    }

    public function modulelocalitestatut($idlocalite, $idmodule, $statut)
    {
        $localite = Region::findOrFail($idlocalite);
        $module   = Module::findOrFail($idmodule);

        $individuelles = Individuelle::where('regions_id', $idlocalite)
            ->where('modules_id', $idmodule)
            ->where('statut', $statut)->get();

        return view("modules.modulelocalitestatut", compact("module", "localite", "individuelles", "statut"));
    }

    public function modulestatut($statut, $idmodule)
    {
        $module = Module::findOrFail($idmodule);

        $individuelles = Individuelle::where('statut', $statut)->where('modules_id', $idmodule)->get();

        return view("modules.modulestatut", compact("module", "statut", "individuelles"));
    }

    public function modulestatutlocalite($idlocalite, $idmodule, $statut)
    {
        $localite = Region::findOrFail($idlocalite);
        $module   = Module::findOrFail($idmodule);

        $individuelles = Individuelle::where('regions_id', $idlocalite)
            ->where('modules_id', $idmodule)
            ->where('statut', $statut)->get();

        return view(
            "modules.modulestatutlocalite",
            compact(
                "module",
                "localite",
                "individuelles",
                "statut"
            )
        );
    }

    public function addModule(Request $request)
    {
        $this->validate($request, [
            "name"    => ["required", "string", Rule::unique(Module::class)->whereNull('deleted_at')],
            "domaine" => ["nullable", "string"],
        ]);

        $module = Module::create([
            'name'        => $request->input('name'),
            'domaines_id' => $request->input('domaine'),
        ]);

        $module->save();

        Alert::success('Succès !', 'Le module ' . $module->name . ' a été ajouté avec succès');

        return redirect()->back();
    }

    public function destroy(Module $module)
    {
        /* $module = Module::find($id); */
        // Vérifiez si le module est lié à des demandes individuelles
        if ($module->individuelles()->count() > 0) {
            // Si des demandes individuelles sont liées, empêchez la suppression
            Alert::warning('Attention !', 'Ce module est lié à une ou plusieurs demandes individuelles et ne peut pas être supprimé.');
            return redirect()->back();
        }

        // Si aucune demande individuelle n'est liée, procédez à la suppression
        $module->delete();

        Alert::success('Succès !', 'Le module ' . $module->name . ' a été supprimé avec succès');

        return redirect()->back();
    }
    public function rapports(Request $request)
    {
        $title   = 'rapports opérateurs';
        $regions = Region::orderBy("created_at", "desc")->get();
        return view('modules.rapports', compact(
            'title',
            'regions',
        ));
    }
    public function generateRapport(Request $request)
    {
        $this->validate($request, [
            'region' => 'required|string',
            'module' => 'required|string',
            'statut' => 'required|string',
        ]);

        $region = Region::findOrFail($request->region);

        $individuelles = Individuelle::join('modules', 'individuelles.modules_id', 'modules.id')
            ->select('individuelles.*')
            ->where('statut', 'LIKE', "%{$request->statut}%")
            ->where('regions_id', "{$request->region}")
            ->where('modules.name', 'LIKE', "%{$request->module}%")
            ->distinct()
            ->get();

        $count = $individuelles->count();

        /* if (isset($count) && $count <= "1") {
            $individuelle = 'demandeur';
            if (isset($request->statut) && $request->statut == "Nouvelle") {
                $statut = 'Nouvelle';
            } elseif (isset($request->statut) && $request->statut == "Former") {
                $statut = 'a terminé la formation';
            } elseif (isset($request->statut) && $request->statut == 'Rejetée') {
                $statut = 'Rejeté';
            } elseif (isset($request->statut) && $request->statut == 'Attente') {
                $statut = 'en attente de formation';
            } elseif (isset($request->statut) && $request->statut == "Retenue") {
                $statut = 'Retenue';
            } else {
                $statut = $request->statut;
            }
        } else {
            $individuelle = 'demandeurs';
            if (isset($request->statut) && $request->statut == "Nouvelle") {
                $statut = 'Nouvelle';
            } elseif (isset($request->statut) && $request->statut == "Former") {
                $statut = 'ont terminé leur formation';
            } elseif (isset($request->statut) && $request->statut == 'Rejetée') {
                $statut = 'Rejetés';
            } elseif (isset($request->statut) && $request->statut == 'Attente') {
                $statut = 'en attente de formation';
            } elseif (isset($request->statut) && $request->statut == "Fetenu") {
                $statut = 'Retenus';
            } else {
                $statut = $request->statut;
            }
        } */

        // Définir l'assignation de $individuelle en fonction de $count
        $individuelle = ($count <= 1) ? 'demandeur' : 'demandeurs';

        // Définir le statut en fonction de la valeur de $request->statut
        $statutMapping = [
            "Nouvelle" => ($count <= 1) ? 'Nouvelle' : 'Nouvelle',
            "Former"   => ($count <= 1) ? 'a terminé la formation' : 'ont terminé leur formation',
            "Rejetée"  => ($count <= 1) ? 'Rejeté' : 'Rejetés',
            "Attente"  => 'en attente de formation',
            "Retenue"  => ($count <= 1) ? 'Retenue' : 'Retenus',
        ];

        // Utiliser la valeur par défaut du statut si elle n'est pas dans le tableau de mapping
        $statut = $statutMapping[$request->statut] ?? $request->statut;

        $title = $count . ' ' . $individuelle . ' ' . $statut . ' en ' . $request->module . ' dans la région de  ' . $region->nom;

        $regions = Region::orderBy("created_at", "desc")->get();

        return view('modules.rapports', compact(
            'individuelles',
            'title',
            'regions',
        ));
    } /*
    public function reports(Request $request)
    {
        $title = 'rapports opérateurs';

        $modules = Module::orderBy("created_at", "desc")->get();
        $domaines = Domaine::orderBy("created_at", "desc")->get();
        $regions = Region::orderBy("created_at", "desc")->get();

        $total_count = Module::count();

        $count_module = number_format($modules?->count(), 0, ',', ' ');

        if ($count_module < "1") {
            $title = 'Aucun module';
        } elseif ($count_module == "1") {
            $title = $count_module . ' module sur un total de ' . $total_count;
        } else {
            $title = 'Liste des ' . $count_module . ' modules sur un total de ' . $total_count;
        }

        return view(
            "modules.index",
            compact(
                "modules",
                "domaines",
                "regions",
                "title"
            )
        );
    }
    public function generateReport(Request $request)
    {
        $this->validate($request, [
            'region' => 'required|string',
            'module' => 'required|string',
            'statut' => 'required|string',
        ]);

        $region = Region::findOrFail($request->region);

        $individuelles = Individuelle::join('modules', 'individuelles.modules_id', 'modules.id')
            ->select('individuelles.*')
            ->where('statut', 'LIKE', "%{$request->statut}%")
            ->where('regions_id',  "{$request->region}")
            ->where('modules.name', 'LIKE', "%{$request->module}%")
            ->distinct()
            ->get();

        $count = $individuelles->count();

        if (isset($count) && $count <= "1") {
            $individuelle = 'demandeur';
            if (isset($request->statut) && $request->statut == "Nouvelle") {
                $statut = 'nouveau';
            } elseif (isset($request->statut) && $request->statut == "Terminée") {
                $statut = 'a terminé la formation';
            } elseif (isset($request->statut) && $request->statut == 'Rejetée') {
                $statut = 'rejeté';
            } elseif (isset($request->statut) && $request->statut == 'Attente') {
                $statut = 'en attente de formation';
            } elseif (isset($request->statut) && $request->statut == "retenue") {
                $statut = 'Retenue';
            } else {
                $statut = $request->statut;
            }
        } else {
            $individuelle = 'demandeurs';
            if (isset($request->statut) && $request->statut == "Nouvelle") {
                $statut = 'nouveaux';
            } elseif (isset($request->statut) && $request->statut == "Terminée") {
                $statut = 'ont terminé leur formation';
            } elseif (isset($request->statut) && $request->statut == 'Rejetée') {
                $statut = 'rejetés';
            } elseif (isset($request->statut) && $request->statut == 'Attente') {
                $statut = 'en attente de formation';
            } elseif (isset($request->statut) && $request->statut == "retenue") {
                $statut = 'retenus';
            } else {
                $statut = $request->statut;
            }
        }
        $title = $count . ' ' . $individuelle . ' ' . $statut . ' en ' . $request->module . ' dans la région de  ' . $region->nom;

        $regions = Region::orderBy("created_at", "desc")->get();

        return view('modules.rapports', compact(
            'individuelles',
            'title',
            'regions',
        ));
    } */
    public function corbeille()
    {
        $total_count = Module::onlyTrashed()->count();
        $total_count = number_format($total_count, 0, ',', ' ');

        $modules = Module::onlyTrashed()
            ->latest()
            ->take(100)
            ->get();

        $count_module = number_format($modules->count(), 0, ',', ' ');

        if ($count_module < 1) {
            $title = 'Aucun module supprimé';
        } elseif ($count_module == 1) {
            $title = "$count_module module supprimé sur un total de $total_count";
        } else {
            $title = "Liste des $count_module derniers modules supprimés sur un total de $total_count";
        }

        return view("modules.corbeille", compact("modules", "title"));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
        ]);

        $total = Module::count();
        $totalModules = number_format($total, 0, ',', ' ');

        $query = Module::query();

        // Si tu veux filtrer par statut (POST)
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par nom du module
        $query->where('name', 'like', '%' . $request->module . '%');

        $modules = $query
            ->latest()
            ->limit(100)
            ->get();

        $affichees = $modules?->count();
        $total     = $totalModules ?? ($modules instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $modules->total()
            : $modules?->count());

        $domaines = Domaine::select('id', 'uuid', 'name', 'created_at')
            ->latest('created_at')->get();

        $regions = Region::select('id', 'uuid', 'nom', 'created_at')
            ->latest('created_at')->get();

        return view(
            'modules.index',
            compact(
                "modules",
                "domaines",
                "regions",
                "affichees",
                "total",
            )
        );
    }
}
