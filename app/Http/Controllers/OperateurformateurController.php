<?php

namespace App\Http\Controllers;

use App\Models\Operateurformateur;
use App\Models\ValidationOperateurFormateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class OperateurformateurController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            "name"                   => ["required", "string", "max:255"],
            "domaine"                => ["required", "string", "max:255"],
            "nbre_annees_experience" => ["required", "string", "max:10"],
            "reference"              => ["nullable", "string", "max:255"],
            "cv"                     => ['file', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:2048'],
        ]);

        $operateurformateur = Operateurformateur::create([
            "name"                   => $request->input("name"),
            "domaine"                => $request->input("domaine"),
            "nbre_annees_experience" => $request->input("nbre_annees_experience"),
            "references"             => $request->input("reference"),
            "statut"                 => 'Nouveau',
            "operateurs_id"          => $request->input("operateur"),
        ]);

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
            "name"                   => ["required", "string", "max:255"],
            "domaine"                => ["required", "string", "max:255"],
            "nbre_annees_experience" => ["required", "string", "max:10"],
            "reference"              => ["nullable", "string", "max:255"],
            "cv"                     => ['file', 'sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:2048'],
        ]);

        $operateurformateur = Operateurformateur::findOrFail($id);
        $user               = auth()->user();
        /* if ($operateurformateur->operateur->statut_agrement != 'Nouveau') { */
        if (
            ! in_array($operateurformateur->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement', 'Conforme', 'À corriger', 'sous réserve'])
            /* && ! $user->hasRole('super-admin'
            ) */
        ) {
            Alert::warning('Attention ! ', 'action impossible, déjà traité');
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
        // Vérifie si l'utilisateur connecté est super-admin
        $user = auth()->user();
        /* if ($operateurformateur->operateur->statut_agrement != 'Nouveau') { */
        if (
            ! in_array($operateurformateur->operateur->statut_agrement, ['Nouveau', 'Extension', 'Renouvellement', 'Conforme'])
            && ! $user->hasRole('super-admin')
        ) {
            Alert::warning('Attention ! ', 'action impossible');
            return redirect()->back();
        } else {

            $operateurformateur->delete();

            Alert::success("Succès ! ", 'Suppression effectuée avec succès');
            return redirect()->back();
        }
    }

    public function validationFormateur(Request $request, $id)
    {
        $request->validate([
            'motif' => $request->statut !== 'Oui' ? 'required|string' : 'nullable|string',
        ]);

        $operateurformateur = Operateurformateur::findOrFail($id);
        $statut             = $operateurformateur->statut;

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

        $operateurformateur->update([
            'statut' => $request->statut,
            'motif'  => $motif,
        ]);

        $ValidationOperateurFormateur = new ValidationOperateurFormateur([
            'action'                 => $request->statut,
            'motif'                  => $motif,
            'validated_id'           => Auth::user()->id,
            'operateurformateurs_id' => $operateurformateur->id,

        ]);

        $ValidationOperateurFormateur->save();

        Alert::success('Succès !', 'Validation effectuée avec succès');

        return redirect()->back();
    }
}
