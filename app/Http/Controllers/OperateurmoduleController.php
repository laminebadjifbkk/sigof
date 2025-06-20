<?php
namespace App\Http\Controllers;

use App\Models\Moduleoperateurstatut;
use App\Models\Operateur;
use App\Models\Operateurmodule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class OperateurmoduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DIOF|DEC|DPP|Operateur|Ingenieur']);
    }

    public function index()
    {
        $operateurmodules = Operateurmodule::take(50)
            ->latest()
            ->get();

        $module_statuts = Operateurmodule::get()->unique('statut');
        /* $operateurs     = Operateur::orderBy('created_at', 'desc')->get(); */
        return view(
            "operateurmodules.index",
            compact(
                "operateurmodules",
                /* "operateurs", */
                "module_statuts",
            )
        );
    }

    public function store(Request $request)
    {
        /* $this->validate($request, [
            'modules.*.module' => 'required|unique:operateurmodules,module',
            'domaines.*.domaine' => 'required|unique:operateurmodules,domaine',
            'niveau_qualifications.*.niveau_qualification' => 'required|unique:operateurmodules,niveau_qualification',
        ]); */

        $this->validate($request, [
            'module'               => 'required|string',
            'domaine'              => 'required|string',
            'categorie'            => 'required|string',
            'niveau_qualification' => 'required|string',
        ]);

        $total_module         = Operateurmodule::where('operateurs_id', $request->input('operateur'))->count();
        $operateurmodule_find = DB::table('operateurmodules')->where('module', $request->input("module"))->first();
        $operateur_find       = Operateurmodule::where('operateurs_id', $request->input('operateur'))->get();

        $operateur = Operateur::findOrFail($request->input('operateur'));

        if (isset($operateurmodule_find)) {
            foreach ($operateur_find as $key => $value) {
                if ($value->module == $operateurmodule_find->module) {
                    Alert::warning('Attention ! ' . $value->module, 'a déjà été choisi');
                    return redirect()->back();
                }
            }

            $operateurmodule = new Operateurmodule([
                "module"               => $request->input("module"),
                "domaine"              => $request->input("domaine"),
                "categorie"            => $request->input("categorie"),
                'niveau_qualification' => $request->input('niveau_qualification'),
                'statut'               => 'nouveau',
                'operateurs_id'        => $request->input('operateur'),
            ]);

            $operateurmodule->save();
        } elseif ($operateur->user->categorie == 'Privé' && $total_module >= 30) {
            Alert::warning('Attention ! ', 'Vous avez atteint le nombre de modules autorisés');
            return redirect()->back();
        } elseif ($total_module >= 40) {
            Alert::warning('Attention ! ', 'Vous avez atteint le nombre de modules autorisés');
            return redirect()->back();
        } else {
            $operateurmodule = new Operateurmodule([
                "module"               => $request->input("module"),
                "domaine"              => $request->input("domaine"),
                "categorie"            => $request->input("categorie"),
                'niveau_qualification' => $request->input('niveau_qualification'),
                'statut'               => 'nouveau',
                'operateurs_id'        => $request->input('operateur'),
            ]);

            $operateurmodule->save();

            $moduleoperateurstatut = new Moduleoperateurstatut([
                'statut'              => "nouveau",
                'operateurmodules_id' => $operateurmodule->id,

            ]);

            $moduleoperateurstatut->save();
        }

        Alert::success('Succès ! ', 'Le module a été ajouté avec succès');

        return redirect()->back();
    }

    public function update(Request $request, Operateurmodule $operateurmodule)
    {
        $this->validate($request, [
            'module'               => 'required|string',
            'domaine'              => 'required|string',
            'categorie'            => 'required|string',
            'niveau_qualification' => 'required|string',
        ]);

        $roleNames       = Auth::user()->roles->pluck('name')->toArray();
        $restrictedRoles = ['super-admin', 'Employe', 'admin', 'DIOF', 'ADIOF', 'Ingenieur', 'DEC', 'ADEC'];

        if (! empty(array_diff($roleNames, $restrictedRoles))) {
            if ($operateurmodule->statut !== 'nouveau') {
                Alert::warning('Attention ! ', 'action impossible module déjà traité');
                return redirect()->back();
            }
        }

        /* foreach (Auth::user()->roles as $key => $role) {
            if (! empty($role?->name) && ($role?->name != 'super-admin') && ($role?->name != 'admin') && ($role?->name != 'DIOF') && ($role?->name != 'DEC')) {
                if ($operateurmodule->statut != 'nouveau') {
                    Alert::warning('Attention ! ', 'action impossible module déjà traité');
                    return redirect()->back();
                }
            }
        } */

        $operateurmodule_find = DB::table('operateurmodules')->where('module', $request->input("module"))->first();

        /* $operateurmodule_count    = DB::table('operateurmodules')
            ->where('module', $request->input("module"))
            ->where('operateurs_id', $operateurmodule->operateurs_id)
            ->count(); */

        $operateur_find = Operateurmodule::where('operateurs_id', $operateurmodule->operateurs_id)->get();

        if (! empty($operateurmodule_find) && $operateurmodule_find->module == $operateurmodule->module) {
            $operateurmodule->update([
                "module"               => $request->input("module"),
                "domaine"              => $request->input("domaine"),
                "categorie"            => $request->input("categorie"),
                'niveau_qualification' => $request->input('niveau_qualification'),
                'operateurs_id'        => $operateurmodule->operateurs_id,
            ]);
            Alert::success('Succès !', 'Le module ' . $operateurmodule->module . ' a été mis à jour avec succès');
            $operateurmodule->save();
        } elseif (! empty($operateurmodule_find)) {
            foreach ($operateur_find as $value) {
                if (($value->module == $operateurmodule_find->module)) {
                    Alert::warning('Attention ! ' . $value->module, 'a déjà été choisi');
                    return redirect()->back();
                } else {
                    $operateurmodule->update([
                        "module"               => $request->input("module"),
                        "domaine"              => $request->input("domaine"),
                        "categorie"            => $request->input("categorie"),
                        'niveau_qualification' => $request->input('niveau_qualification'),
                        'operateurs_id'        => $operateurmodule->operateurs_id,
                    ]);
                    Alert::success($operateurmodule->module, 'mis à jour');
                    $operateurmodule->save();
                    return redirect()->back();
                }
            }
        } else {
            $operateurmodule->update([
                "module"               => $request->input("module"),
                "domaine"              => $request->input("domaine"),
                "categorie"            => $request->input("categorie"),
                'niveau_qualification' => $request->input('niveau_qualification'),
                'operateurs_id'        => $operateurmodule->operateurs_id,
            ]);

            Alert::success($operateurmodule->module, 'mis à jour');
            $operateurmodule->save();
        }
        return redirect()->back();
    }

    public function show(Operateurmodule $operateurmodule)
    {
        $modulename       = $operateurmodule->module;
        $operateurmodules = Operateurmodule::where('module', $modulename)->get();

        return view("operateurmodules.show", compact("operateurmodules", "modulename"));
    }
    public function destroy(Operateurmodule $operateurmodule)
    {
        /* $operateurmodule = Operateurmodule::find($id); */

        foreach (Auth::user()->roles as $role) {
            if (! empty($role?->name) && ($role?->name == 'super-admin')) {
                Alert::success('Succès !', 'Le module a été supprimé avec succès');
                $operateurmodule->delete();
                return redirect()->back();
            } elseif ($operateurmodule->statut != 'nouveau') {
                Alert::warning('Attention ! ', 'action impossible module déjà traité');
                return redirect()->back();
            } else {
                $operateurmodule->delete();
                Alert::success('Succès !', 'Le module a été supprimé avec succès');
                return redirect()->back();
            }
        }
    }

    public function rapports()
    {
        $operateurmodules = Operateurmodule::take(50)
            ->latest()
            ->get();

        $module_statuts = Operateurmodule::get()->unique('statut');
        $operateurs     = Operateur::orderBy('created_at', 'desc')->get();
        return view(
            "operateurmodules.index",
            compact(
                "operateurmodules",
                "operateurs",
                "module_statuts",
            )
        );
    }
    public function generateRapport(Request $request)
    {
        $this->validate($request, [
            'module' => 'required|string',
        ]);

        $operateurs = Operateur::orderBy('created_at', 'desc')->get();

        /* $operateurmodules = Operateurmodule::where('module', $request?->module)->get(); */

        // Convertir en minuscules
        $module = $request?->module;
        $modulenameLower = strtolower($module);

        /* $keywords = explode(' ', $request?->module); */

        // Supprimer uniquement les parenthèses, mais garder le contenu
        $modulenameClean = str_replace(['(', ')'], ' ', $modulenameLower);

        $articles = ['le', 'la', 'les', 'un', 'une', 'de', 'du', 'des', 'en', 'et', 'à', 'au', 'aux', 'pour', 'par', 'dans', 'sur', 'avec'];

        $keywords = array_filter(
            explode(' ', $modulenameClean),
            fn($word) => strlen($word) >= 3 && ! in_array($word, $articles)
        );

        /* $query = Operateurmodule::where('statut', 'agréé');

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->orWhere('module', 'like', '%' . $word . '%');
            }
        });

        $operateurmodules = $query->get(); */

        $operateurs = Operateur::where('statut_agrement', 'agréé')
            ->whereHas('operateurmodules', function ($query) use ($keywords) {
                $query->where('statut', 'agréé');
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('module', 'like', '%' . $word . '%');
                    }
                });
            })
            ->get();

        return view('operateurmodules.report', compact(
            /* 'operateurmodules', */
            'operateurs',
            'module',
            /* 'module_statuts', */
        ));
    }
}
