<?php
namespace App\Http\Controllers;

use App\Models\Operateurformateur;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OperateurformateurController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            "name"                   => ["required", "string"],
            "domaine"                => ["required", "string"],
            "nbre_annees_experience" => ["required", "string"],
            "reference"              => ["nullable", "string"],
            "cv"                     => ['file', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
        ]);

        $operateurformateur = Operateurformateur::create([
            "name"                   => $request->input("name"),
            "domaine"                => $request->input("domaine"),
            "nbre_annees_experience" => $request->input("nbre_annees_experience"),
            "references"             => $request->input("reference"),
            "operateurs_id"          => $request->input("operateur"),
        ]);

        $operateurformateur->save();

        if ($request->hasFile('cv')) {
            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('cv');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateurformateur->update([
                'file' => $filePath,
            ]);

        }

        Alert::success('Succès !', 'Enregistrement effectué avec succès');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "name"                   => ["required", "string"],
            "domaine"                => ["required", "string"],
            "nbre_annees_experience" => ["required", "string"],
            "reference"              => ["nullable", "string"],
            "cv"                     => ['file', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
        ]);

        $operateurformateur = Operateurformateur::findOrFail($id);
        if ($operateurformateur->operateur->statut_agrement != 'nouveau') {
            Alert::warning('Attention ! ', 'action impossible');
            return redirect()->back();
        }
        $operateurformateur->update([
            "name"                   => $request->input("name"),
            "domaine"                => $request->input("domaine"),
            "nbre_annees_experience" => $request->input("nbre_annees_experience"),
            "references"             => $request->input("reference"),
            "operateurs_id"          => $request->input("operateur"),
        ]);

        $operateurformateur->save();

        if ($request->hasFile('cv')) {

            if (! is_null($operateurformateur->cv)) {
                Storage::disk('public')->delete($operateurformateur->cv);
            }
            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('cv');

            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $operateurformateur->update([
                'file' => $filePath,
            ]);

        }

        Alert::success('Succès !', 'Modification effectuée avec succès');

        return redirect()->back();
    }

    public function show($id)
    {
        $operateurformateur = Operateurformateur::find($id);
        return view('operateurformateurs.show', compact('operateurformateur'));
    }

    public function destroy($id)
    {
        $operateurformateur = Operateurformateur::find($id);
        if ($operateurformateur->operateur->statut_agrement != 'nouveau') {
            Alert::warning('Attention ! ', 'action impossible');
            return redirect()->back();
        } else {

            $operateurformateur->delete();

            Alert::success("Succès ! ", 'Suppression effectuée avec succès');
            return redirect()->back();
        }
    }
}
