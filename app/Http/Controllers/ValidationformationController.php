<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Validationformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ValidationformationController extends Controller
{
    public function update($id)
    {
        $formation   = Formation::findOrFail($id);

        $count = $formation->individuelles->count();
        
        if ($count == '0' || empty($formation->operateur)) {
            Alert::warning('Désolé !', 'action non autorisée');
        } else {
            if ($formation->statut == "Terminée") {
                Alert::warning('Désolé !', 'formation déjà exécutée');
            } elseif ($formation->statut == "En cours") {
                Alert::warning('Désolé !', 'formation en cours...');
            } else {
                $formation->update([
                    'statut'             => "En cours",
                    'validated_by'       =>  Auth::user()->firstname . ' ' . Auth::user()->name,
                ]);

                $formation->save();

                $validated_by = new Validationformation([
                    'validated_id'       =>       Auth::user()->id,
                    'action'             =>      "En cours",
                    'formations_id'      =>      $formation->id
                ]);

                $validated_by->save();

                Alert::success('Félicitation !', 'la formation est lancée');
            }
        }

        /* return redirect()->back()->with("status", "Demande validée"); */
        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        $this->validate($request, [
            "motif" => "required|string",
        ]);

        $formation   = Formation::findOrFail($id);

        if ($formation->statut == 'Annulée') {
            Alert::warning('Désolé !', 'formation déjà annulée');
        } elseif ($formation->statut == "Terminée") {
            Alert::warning('Désolé !', 'formation déjà exécutée');
        } else {
            $formation->update([
                'statut'                => 'Annulée',
                'canceled_by'           =>  Auth::user()->firstname . ' ' . Auth::user()->name,
            ]);

            $formation->save();

            $validated_by = new Validationformation([
                'validated_id'       =>      Auth::user()->id,
                'action'             =>      'Annulée',
                'motif'              =>      $request->input('motif'),
                'formations_id'   =>      $formation->id
            ]);

            $validated_by->save();

            Alert::success('Fait ! ', 'formation annulée');
        }

        return redirect()->back();
    }
}
