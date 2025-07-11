<?php
namespace App\Http\Controllers;

use App\Models\Collectivemodule;
use App\Models\Formation;
use App\Models\Listecollective;
use App\Models\Module;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CollectivemoduleController extends Controller
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
        $collectivemodules = Collectivemodule::get();
        return view('collectivemodules.index', compact('collectivemodules'));
    }

    public function store(Request $request)
    {
        /* $this->validate($request, [
            "module_name" => "required|string",
        ]);

        $module_collective_total = Collectivemodule::where('collectives_id', $request->input('collectiveid'))->count();

        if ($module_collective_total >= 2) {
            Alert::warning('Attention ! ', 'Vous avez atteint le nombre de modules autoriés');
            return redirect()->back();
        } else {

            $module_find = DB::table('modules')->where('name', $request->input("module_name"))->first();

            if (isset($module_find)) {
                $collectivemodule = Collectivemodule::create([
                    'module'         => $request->input('module_name'),
                    'statut'         => 'Nouveau',
                    'collectives_id' => $request->input('collective'),
                ]);
            } else {

                $module = new Module([
                    'name' => $request->input('module_name'),
                ]);

                $module->save();

                $collectivemodule = Collectivemodule::create([
                    'module'         => $request->input('module_name'),
                    'statut'         => 'Nouveau',
                    'collectives_id' => $request->input('collective'),
                ]);
            }

            $collectivemodule->save();

            Alert::success('Fait ! ', 'module ajouté avec succès');
        }

        return redirect()->back(); */

        $this->validate($request, [
            "module_name"          => "required|string",
            "niveau_qualification" => "required|string",
            "collectiveid"         => "required|exists:collectives,id",
        ]);

        // Vérifier si le module existe déjà pour cette collective
        $module_exists = Collectivemodule::where('collectives_id', $request->input('collectiveid'))
            ->where('module', $request->input('module_name'))
            ->exists();

        if ($module_exists) {
            Alert::warning('Attention !', 'Ce module existe déjà pour cette collective.');
            return redirect()->back();
        }

        // Vérifier la limite de modules pour la collective
        $module_collective_total = Collectivemodule::where('collectives_id', $request->input('collectiveid'))->count();

        if ($module_collective_total >= 4) {
            Alert::warning('Attention !', 'Vous avez atteint le nombre maximum de modules autorisés.');
            return redirect()->back();
        }

        // Trouver ou créer le module
        $module = Module::firstOrCreate(['name' => $request->input('module_name')]);

        // Ajouter le module à la collective
        $collectivemodule = Collectivemodule::create([
            'module'               => $module->name,
            'statut'               => 'Nouveau',
            'niveau_qualification' => $request->input('niveau_qualification'),
            'collectives_id'       => $request->input('collectiveid'),
        ]);

        Alert::success('Succès !', 'Module ajouté avec succès.');
        return redirect()->back();

    }
    public function update(Request $request, Collectivemodule $collectivemodule)
    {
        $this->validate($request, [
            /* "module_name"             => "required|string|unique:collectivemodules,module,except,id", */
            "module_name"          => "required|string",
            "niveau_qualification" => "required|string",
        ]);

        /* $collectivemodule = Collectivemodule::find($id); */
        $this->authorize('update', $collectivemodule);

        if (! empty($collectivemodule->formations_id)) {
            Alert::warning('Désolé ! ', 'action impossible');
            return redirect()->back();
        } else {
            $collectivemodule->update([
                'module'               => $request->input('module_name'),
                'niveau_qualification' => $request->input('niveau_qualification'),
                'collectives_id'       => $request->input('collective'),
            ]);

            $collectivemodule->save();

            Alert::success('Succès ! ', 'Modification effectuée avec succès');

            return redirect()->back();
        }
    }

    public function show(Collectivemodule $collectivemodule)
    {
        /* $collectivemodule = Collectivemodule::find($id); */
        $this->authorize('view', $collectivemodule);
        return view("collectives.showliste", compact('collectivemodule'));
    }

    public function destroy(Collectivemodule $collectivemodule)
    {
        /* $collectivemodule = Collectivemodule::find($id); */
        $this->authorize('delete', $collectivemodule);

        if ($collectivemodule && $collectivemodule->listecollectives()->count() > 0) {
            Alert::warning('Attention', 'Ce module contient des membres et ne peut pas être supprimé.');
        } else {
            $collectivemodule->delete();
            Alert::success('Succès', 'Suppression effectuée avec succès');
        }

        return redirect()->back();
    }

    public function validerModuleCollective(Request $request)
    {
        $collectivemodule = Collectivemodule::findOrFail($request->id);

        $collectivemodule->update([
            'statut' => 'Attente',
        ]);

        $collectivemodule->save();
        Alert::success('Module validé !', 'Merci à bientôt');

        return redirect()->back();
    }

    public function rejeterModuleCollective(Request $request)
    {

        $request->validate([
            'motif' => $request->statut !== 'Conforme' ? 'required|string' : 'nullable|string',
        ]);

        $motif = $request->input('motif') ?? $request->statut;

        $collectivemodule = Collectivemodule::findOrFail($request->id);

        $collectivemodule->update([
            'motif'  => $motif,
            'statut' => $request->statut,
        ]);

        $collectivemodule->save();

        Alert::success('Succès !', 'La module ' . $collectivemodule->module . ' a été traité avec succès');

        return redirect()->back();
    }

    public function supprimerModuleCollective(Request $request)
    {
        $collectivemodule = Collectivemodule::findOrFail($request->idmodule);

        $formation = Formation::findOrFail($request->idformation);

        $formation->update([
            'ingenieurs_id' => null,
        ]);

        $formation->save();

        $collectivemodule->update([
            'statut'        => 'Nouvelle',
            'formations_id' => null,
        ]);

        $collectivemodule->save();

        Alert::success('Succès !', 'Module supprimé avec succès');

        return redirect()->back();
    }

    public function changerModule(Request $request, $id)
    {
        $liste                       = Listecollective::findOrFail($id);
        $liste->collectivemodules_id = $request->collectivemodules_id;
        $liste->save();

        Alert::success('Succès !', 'Changement de module effectué avec succès.');

        return redirect()->back();
    }
}
