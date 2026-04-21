<?php

namespace App\Http\Controllers;

use App\Models\Operateurequipement;
use App\Models\ValidationOperateurEquipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class OperateurequipementController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            "designation" => ["required", "string"],
            "quantite"    => ["required", "string"],
            "etat"        => ["required", "string"],
            "type"        => ["required", "string"],
        ]);

        $operateurequipement = Operateurequipement::create([
            "designation"   => $request->input("designation"),
            "quantite"      => $request->input("quantite"),
            "etat"          => $request->input("etat"),
            "type"          => $request->input("type"),
            "statut"        => 'Nouveau',
            "operateurs_id" => $request->input("operateur"),
        ]);

        Alert::success('Félicitation !', 'Enregistrement effectué');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "designation" => ["required", "string"],
            "quantite"    => ["required", "string"],
            "etat"        => ["required", "string"],
            "type"        => ["required", "string"],
        ]);

        $operateurequipement = Operateurequipement::findOrFail($id);
        if (! in_array($operateurequipement->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement', 'À corriger'])) {
            /* if ($operateurequipement->statut !== 'Nouveau') { */
            Alert::warning('Attention ! ', 'action impossible');
            return redirect()->back();
        }
        $operateurequipement->update([
            "designation"   => $request->input("designation"),
            "quantite"      => $request->input("quantite"),
            "etat"          => $request->input("etat"),
            "type"          => $request->input("type"),
            "operateurs_id" => $request->input("operateur"),
        ]);

        $operateurequipement->save();

        Alert::success('Félicitation !', 'Modification effectuée');

        return redirect()->back();
    }

    public function show($id)
    {
        $operateurequipement = Operateurequipement::find($id);
        return view('operateurequipements.show', compact('operateurequipement'));
    }

    public function destroy($id)
    {
        $operateurequipement = Operateurequipement::find($id);

        if (! in_array($operateurequipement->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement'])) {
            Alert::warning('Attention ! ', 'action impossible');
            return redirect()->back();
        } else {

            $operateurequipement->delete();

            Alert::success("Fait ! ", 'supprimé avec succès');
            return redirect()->back();
        }
    }

    public function validationEquipement(Request $request, $id)
    {
        $request->validate([
            'motif' => $request->statut !== 'Oui' ? 'required|string' : 'nullable|string',
        ]);

        $operateurequipement = Operateurequipement::findOrFail($id);
        $statut              = $operateurequipement->statut;

        // Bloquer certains statuts uniquement pour les non-super-admins
        if (! auth()->user()->hasAnyRole(['super-admin', 'Ingenieur', 'DEC'])) {
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
                'Oui'          => 'demandeur déjà traitée',
                'Non'          => 'demandeur déjà traitée',
                'Non conforme' => 'demandeur déjà traitée',
            ];

            if (array_key_exists($statut, $messages)) {
                Alert::warning('Désolé !', $messages[$statut]);
                return redirect()->back();
            }
        }

        $motif = $request->input('motif') ?? $request->statut;

        $operateurequipement->update([
            'statut' => $request->statut,
            'motif'  => $motif,
        ]);

        $ValidationOperateurEquipement = new ValidationOperateurEquipement([
            'action'                 => $request->statut,
            'motif'                  => $motif,
            'validated_id'           => Auth::user()->id,
            'operateurformateurs_id' => $operateurequipement->id,

        ]);

        $ValidationOperateurEquipement->save();

        Alert::success('Succès !', 'Validation effectuée avec succès');

        return redirect()->back();
    }
}
