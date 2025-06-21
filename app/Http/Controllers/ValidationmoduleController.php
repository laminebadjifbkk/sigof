<?php
namespace App\Http\Controllers;

use App\Models\Moduleoperateurstatut;
use App\Models\Operateurmodule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ValidationmoduleController extends Controller
{
    public function update($id)
    {
        $operateurmodule = Operateurmodule::findOrFail($id);

        if ($operateurmodule->statut == 'agréé') {
            Alert::warning('Désolé ! ', 'déjà agréé');
        } else {
            $operateurmodule->update([
                'statut'   => 'agréé',
                'users_id' => Auth::user()->id,
            ]);

            $operateurmodule->save();

            $moduleoperateurstatut = new Moduleoperateurstatut([
                'statut'              => "agréé",
                'validated_id'        => Auth::user()->id,
                'operateurmodules_id' => $operateurmodule->id,

            ]);

            $moduleoperateurstatut->save();

            Alert::success('Effectué !', 'le module ' . $operateurmodule->module . ' a été agréé');
        }

        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {

        /* $statut = $request->statut;

        $this->validate($request, [
            "motif" => "required|string",
        ]); */

        $statut = $request->statut;

        $request->validate([
            'motif' => $request->statut !== 'agréé' ? 'required|string' : 'nullable|string',
        ]);

        $operateurmodule = Operateurmodule::findOrFail($id);
        $motif           = $request->input('motif') ?? $request->statut;
        $operateurmodule->update([
            'statut'   => $request->statut,
            'motif'    => $motif,
            'users_id' => Auth::user()->id,
        ]);

        $operateurmodule->save();

        $moduleoperateurstatut = new Moduleoperateurstatut([
            'statut'              => $request->statut,
            'motif'               => $motif,
            'validated_id'        => Auth::user()->id,
            'operateurmodules_id' => $operateurmodule->id,

        ]);

        $moduleoperateurstatut->save();

        Alert::success('Succès !', 'le module ' . $operateurmodule->module . ' est ' . $request->statut, );

        return redirect()->back();
    }
}
