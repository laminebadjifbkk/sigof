<?php

namespace App\Http\Controllers;

use App\Models\Operateurlocalite;
use App\Models\Region;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OperateurlocaliteController extends Controller
{
    /* public function store(Request $request)
    {
        $this->validate($request, [
            "name"   => ["required", "string"],
            "region" => ["required", "string"],
        ]);

        $operateurlocalite = Operateurlocalite::create([
            "name"          => $request->input("name"),
            "region"        => $request->input("region"),
            "operateurs_id" => $request->input("operateur"),
        ]);

        $operateurlocalite->save();

        Alert::success('Félicitation !', 'Enregistrement effectué');

        return redirect()->back();
    } */
    public function store(Request $request)
    {
        // 🔥 Cas : toutes les régions
        if ($request->filled('all_regions')) {

            // Validation minimale
            $request->validate([
                "operateur" => ["required", "exists:operateurs,id"],
            ]);

            // 🚫 Vérifier si des régions existent déjà
            $exists = Operateurlocalite::where('operateurs_id', $request->operateur)->exists();

            if ($exists) {
                Alert::warning('Attention', 'Des régions existent déjà pour cet opérateur');
                return redirect()->back();
            }

            $regions = Region::all();

            foreach ($regions as $region) {
                Operateurlocalite::create([
                    "operateurs_id" => $request->input("operateur"),
                    "region"        => $region->nom,
                    "name"          => $region->nom,
                ]);
            }

            Alert::success('Félicitation !', 'Toutes les régions ont été ajoutées');

            return redirect()->back();
        }

        // 🔥 Cas normal
        $request->validate([
            "operateur" => ["required", "exists:operateurs,id"],
            "name"      => ["required", "string"],
            "region"    => ["required", "string"],
        ]);

        Operateurlocalite::create([
            "name"          => $request->input("name"),
            "region"        => $request->input("region"),
            "operateurs_id" => $request->input("operateur"),
        ]);

        Alert::success('Félicitation !', 'Enregistrement effectué');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "name"   => ["required", "string"],
            "region" => ["required", "string"],
        ]);

        $operateurlocalite = Operateurlocalite::findOrFail($id);
        if (! in_array($operateurlocalite->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement', 'À corriger', 'sous réserve'])) {
            Alert::warning('Attention ! ', 'Action impossible');
            return redirect()->back();
        }
        $operateurlocalite->update([
            "name"          => $request->input("name"),
            "region"        => $request->input("region"),
            "operateurs_id" => $request->input("operateur"),
        ]);

        $operateurlocalite->save();

        Alert::success('Félicitation !', 'Modification effectuée');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $operateurlocalite = Operateurlocalite::find($id);
        if (! in_array($operateurlocalite->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement', 'À corriger'])) {
            Alert::warning('Attention ! ', 'Action impossible');
            return redirect()->back();
        } else {

            $operateurlocalite->delete();

            Alert::success("Succès ! ", 'Suppression avec succès');
            return redirect()->back();
        }
    }
}
