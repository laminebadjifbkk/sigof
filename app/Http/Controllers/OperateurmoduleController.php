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
        $operateurmodules = Operateurmodule::whereHas('operateur')
            ->latest()
            ->take(50)
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
        // 🔹 Validation
        $request->validate([
            'module'               => 'required|string',
            'domaine'              => 'required|string',
            'niveau_qualification' => 'required|string',
            'categorie'            => 'nullable|string',
        ]);

        $operateurId = $request->input('operateur');
        $moduleName  = $request->input('module');

        $operateur = Operateur::findOrFail($operateurId);

        // 🔹 Vérifier le statut de l'opérateur
        if (!in_array($operateur->statut_agrement, ["Nouveau", "À corriger"])) {
            Alert::warning('Action impossible !', 'Opérateur déjà traité');
            return redirect()->back();
        }

        // 🔹 Total modules existants pour cet opérateur
        $totalModules = $operateur->operateurmodules()->count();

        // 🔹 Vérifier si le module existe déjà
        $moduleExists = $operateur->operateurmodules()
            ->where('module', $moduleName)
            ->exists();

        if ($moduleExists) {
            Alert::warning('Attention !', "Le module {$moduleName} a déjà été choisi");
            return redirect()->back();
        }

        // 🔹 Limite de modules
        if (($operateur->user->categorie !== 'Public' && $totalModules >= 5) || $totalModules >= 20) {
            Alert::error('Avertissement !', 'Vous avez atteint le nombre de modules autorisés');
            return redirect()->back();
        }

        // 🔹 Créer le module
        $operateurmodule = $operateur->operateurmodules()->create([
            "module"               => $moduleName,
            "domaine"              => $request->input("domaine"),
            "categorie"            => $request->input("categorie"),
            'niveau_qualification' => $request->input('niveau_qualification'),
            'statut'               => 'Nouveau',
        ]);

        // 🔹 Créer le statut associé
        Moduleoperateurstatut::create([
            'statut'              => "Nouveau",
            'operateurmodules_id' => $operateurmodule->id,
        ]);

        Alert::success('Succès !', 'Le module a été ajouté avec succès');
        return redirect()->back();
    }

    public function update(Request $request, Operateurmodule $operateurmodule)
    {
        // 🔹 Validation
        $request->validate([
            'module'               => 'required|string',
            'domaine'              => 'required|string',
            'niveau_qualification' => 'required|string',
            'categorie'            => 'nullable|string',
        ]);

        $roleNames = Auth::user()->roles->pluck('name')->toArray();

        // 🔹 Vérifications pour utilisateurs non super-admin / DEC
        if (!in_array('super-admin', $roleNames, true) && !in_array('DEC', $roleNames, true)) {
            if (!in_array($operateurmodule->operateur?->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement', 'À corriger'], true)) {
                Alert::warning('Action impossible !', 'Opérateur déjà traité');
                return redirect()->back();
            }

            if (in_array($operateurmodule->statut, ['agréé', 'rejeté', 'sous réserve'], true)) {
                Alert::warning('Action impossible !', 'Module déjà traité');
                return redirect()->back();
            }
        }

        $moduleName = $request->input('module');

        // 🔹 Vérifier si un autre module du même opérateur a le même nom
        $moduleExists = Operateurmodule::where('operateurs_id', $operateurmodule->operateurs_id)
            ->where('module', $moduleName)
            ->where('id', '<>', $operateurmodule->id)
            ->exists();

        if ($moduleExists) {
            Alert::warning('Attention !', "Le module {$moduleName} a déjà été choisi");
            return redirect()->back();
        }

        // 🔹 Mise à jour
        $operateurmodule->update([
            "module"               => $moduleName,
            "domaine"              => $request->input("domaine"),
            "categorie"            => $request->input("categorie"),
            'niveau_qualification' => $request->input('niveau_qualification'),
            'operateurs_id'        => $operateurmodule->operateurs_id,
        ]);

        Alert::success('Succès !', "Le module {$moduleName} a été mis à jour avec succès");

        return redirect()->back();
    }

    public function show(Operateurmodule $operateurmodule)
    {
        $modulename       = $operateurmodule->module;
        $operateurmodules = Operateurmodule::where('module', $modulename)->get();

        return view("operateurmodules.show", compact("operateurmodules", "modulename"));
    }
    /* public function destroy(Operateurmodule $operateurmodule)
    {
        foreach (Auth::user()->roles as $role) {
            if (! empty($role?->name) && ($role?->name == 'super-admin')) {
                Alert::success('Succès !', 'Le module a été supprimé avec succès');
                $operateurmodule->delete();
                return redirect()->back();
            } elseif (! in_array($operateurmodule->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement'])) {
                Alert::warning('Attention ! ', 'action impossible module déjà traité');
                return redirect()->back();
            } elseif (in_array($operateurmodule?->statut, ['agréé', 'rejeté', 'sous réserve'], true)) {
                Alert::warning('Action impossible !', 'Module déjà traité');
                return redirect()->back();
            } else {
                $operateurmodule->delete();
                Alert::success('Succès !', 'Le module a été supprimé avec succès');
                return redirect()->back();
            }
        }
    } */

    public function destroy(Operateurmodule $operateurmodule)
    {
        $user = Auth::user();

        // ✅ 1. Si super-admin → suppression directe
        if ($user->roles->contains('name', 'super-admin')) {
            $operateurmodule->delete();
            Alert::success('Succès !', 'Le module a été supprimé avec succès');
            return redirect()->back();
        }

        // ❌ 2. Vérifier le statut de l'opérateur
        if (!in_array($operateurmodule->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement'])) {
            Alert::warning('Attention !', 'Action impossible : module déjà traité');
            return redirect()->back();
        }

        // ❌ 3. Vérifier le statut du module
        if (in_array($operateurmodule->statut, ['agréé', 'rejeté', 'sous réserve'], true)) {
            Alert::warning('Action impossible !', 'Module déjà traité');
            return redirect()->back();
        }

        // ✅ 4. Suppression normale
        $operateurmodule->delete();
        Alert::success('Succès !', 'Le module a été supprimé avec succès');

        return redirect()->back();
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
        $module          = $request?->module;
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

        /* $operateurs = Operateur::whereIn('statut_agrement', ['agréé', 'sous réserve', 'Extension', 'Renouvellement'])
            ->whereHas('operateurmodules', function ($query) use ($keywords) {
                $query->where('statut', 'agréé'); */
        $operateurs = Operateur::whereHas('operateurmodules', function ($query) use ($keywords) {
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
