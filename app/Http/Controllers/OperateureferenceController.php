<?php
namespace App\Http\Controllers;

use App\Models\Operateureference;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OperateureferenceController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            "organisme"   => ["required", "string"],
            "contact"     => ['nullable', 'size:12'], // Assuming contact is a phone number with 12 digits
            "periode"     => ["required", "string"],
            "description" => ["required", "string"],
        ]);

        $operateureference = Operateureference::create([
            "organisme"     => $request->input("organisme"),
            "contact"       => $request->input("contact"),
            "periode"       => $request->input("periode"),
            "description"   => $request->input("description"),
            "operateurs_id" => $request->input("operateur"),
        ]);

        $operateureference->save();

        Alert::success('Succès !', 'Enregistrement effectué');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "organisme"   => ["required", "string"],
            "contact"     => ["nullable", "size:12"], // Assuming contact is a phone number with 12 digits
            "periode"     => ["required", "string"],
            "description" => ["required", "string"],
        ]);

        $operateureference = Operateureference::findOrFail($id);
        if ($operateureference->operateur->statut_agrement != 'Nouveau') {
            Alert::warning('Attention ! ', 'action impossible');
            return redirect()->back();
        }
        $operateureference->update([
            "organisme"     => $request->input("organisme"),
            "contact"       => $request->input("contact"),
            "periode"       => $request->input("periode"),
            "description"   => $request->input("description"),
            "operateurs_id" => $request->input("operateur"),
        ]);

        $operateureference->save();

        Alert::success('Succès !', 'La modification a été effectuée');

        return redirect()->back();
    }

    public function show($id)
    {
        $operateureference = Operateureference::find($id);
        return view('operateureferences.show', compact('operateureference'));
    }

    public function destroy($id)
    {
        $operateureference = Operateureference::find($id);

        if ($operateureference->operateur->statut_agrement != 'Nouveau') {
            Alert::warning('Attention ! ', 'action impossible');
            return redirect()->back();
        } else {
            $operateureference->delete();
            Alert::success("Succès ", 'La référence a été supprimée avec succès');
            return redirect()->back();
        }
    }
}
