<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationInscriptionPchare;
use App\Models\Formulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class FormulaireController extends Controller
{
    // Affichage du formulaire
    public function create()
    {
        return view('formulaire.create');
    }

    // Enregistrement du formulaire
    /* public function store(Request $request)
    {
        $validated = $request->validate([
            'cin'                  => 'required|string|max:14|unique:formulaires,cin',
            'civilite'             => 'required|string|max:5',
            'prenom'               => 'required|string',
            'nom'                  => 'required|string',
            'date_naissance'       => 'required|date',
            'lieu_naissance'       => 'required|string',
            'email'                => 'nullable|email|unique:formulaires,email',
            'telephone'            => 'required|string',
            'telephone_secondaire' => 'nullable|string',
            'adresse'              => 'required|string',
            'dernier_diplome'      => 'nullable|string',
            'nom_etablissement'    => 'required|string',
            'region'               => 'required|string',
            'formation'            => 'required|string',
            'diplome_vise'         => 'required|string',
            'montant_inscription'  => 'required|numeric|min:0',
            'montant_mensualite'   => 'required|numeric|min:0',
            'montant_unique'       => 'nullable|numeric|min:0',
            'duree'                => 'required|integer|min:1|max:3',
            'handicape'            => 'required|string',
            'type_handicap'        => 'nullable|string',
            'orphelin'             => 'required|string',
            'type_orphelin'        => 'nullable|string',
        ]);

        // Convertir les champs numériques vides en null
        $validated['montant_unique'] = $validated['montant_unique'] === '' ? null : $validated['montant_unique'];

        Alert::error('Désolé', 'Les inscriptions n\'ont pas encore démarré.');

        return redirect()->back();

        $formulaire = Formulaire::create($validated);

        Mail::to($validated['email'])->send(new ConfirmationInscriptionPchare($formulaire));

        Alert::success('Succès', 'Inscription effectuée avec succès.');

        return redirect()->route('formulaire.merci');
    } */

    /* public function store(Request $request)
    {
        $validated = $request->validate([
            'cin'                  => 'required|string|max:14|unique:formulaires,cin',
            'civilite'             => 'required|string|max:5',
            'prenom'               => 'required|string',
            'nom'                  => 'required|string',
            'date_naissance'       => 'required|date',
            'lieu_naissance'       => 'required|string',
            'email'                => 'nullable|email|unique:formulaires,email',
            'telephone'            => 'required|string',
            'telephone_secondaire' => 'nullable|string',
            'adresse'              => 'required|string',
            'dernier_diplome'      => 'nullable|string',
            'nom_etablissement'    => 'required|string',
            'region'               => 'required|string',
            'formation'            => 'required|string',
            'diplome_vise'         => 'required|string',
            'montant_inscription'  => 'required|numeric|min:0',
            'montant_mensualite'   => 'required|numeric|min:0',
            'montant_unique'       => 'nullable|numeric|min:0',
            'duree'                => 'required|integer|min:1|max:3',
            'handicape'            => 'required|string',
            'type_handicap'        => 'nullable|string',
            'orphelin'             => 'required|string',
            'type_orphelin'        => 'nullable|string',

            // 🆕 Validation des fichiers
            'facture'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'cin_file'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        // Convertir les champs numériques vides en null
        $validated['montant_unique'] = $validated['montant_unique'] === '' ? null : $validated['montant_unique'];

        // 🧾 Sauvegarde des fichiers
        if ($request->hasFile('facture_file')) {
            $validated['facture_file'] = $request->file('facture_file')->store('factures', 'public');
        }

        if ($request->hasFile('cin_file')) {
            $validated['cin_file'] = $request->file('cin_file')->store('cins', 'public');
        }

        // Vérification si les inscriptions sont fermées
        Alert::error('Désolé', 'Les inscriptions n\'ont pas encore démarré.');
        return redirect()->back();

        // ✅ Enregistrement en base
        $formulaire = Formulaire::create($validated);

        // Envoi d’un mail de confirmation (si email renseigné)
        if (!empty($validated['email'])) {
            Mail::to($validated['email'])->send(new ConfirmationInscriptionPchare($formulaire));
        }

        Alert::success('Succès', 'Inscription effectuée avec succès.');

        return redirect()->route('formulaire.merci');
    } */


    public function store(Request $request)
    {
        $validated = $request->validate([
            'cin'                  => 'required|string|max:14|unique:formulaires,cin',
            'civilite'             => 'required|string|max:5',
            'prenom'               => 'required|string',
            'nom'                  => 'required|string',
            'date_naissance'       => 'required|date',
            'lieu_naissance'       => 'required|string',
            'email'                => 'nullable|email|unique:formulaires,email',
            'telephone'            => 'required|string',
            'telephone_secondaire' => 'nullable|string',
            'adresse'              => 'required|string',
            'dernier_diplome'      => 'nullable|string',
            'nom_etablissement'    => 'required|string',
            'region'               => 'required|string',
            'formation'            => 'required|string',
            'diplome_vise'         => 'required|string',
            'montant_inscription'  => 'required|numeric|min:0',
            'montant_mensualite'   => 'required|numeric|min:0',
            'montant_unique'       => 'nullable|numeric|min:0',
            'duree'                => 'required|integer|min:1|max:3',
            'handicape'            => 'required|string',
            'type_handicap'        => 'nullable|string',
            'orphelin'             => 'required|string',
            'type_orphelin'        => 'nullable|string',
            'facture'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'cin_file'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        // Convertir les champs numériques vides en null
        $validated['montant_unique'] = $validated['montant_unique'] === '' ? null : $validated['montant_unique'];

        // 🔒 Inscriptions non ouvertes (temporaire)
        Alert::error('Désolé', 'Les inscriptions n\'ont pas encore démarré.');
        return redirect()->back();

        // Création du formulaire
        $formulaire = Formulaire::create($validated);

        // 📂 Upload du fichier facture
        if ($request->hasFile('facture_file')) {
            $uploadedFile = $request->file('facture_file');
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            $filePath = $uploadedFile->storeAs('pvs', $filename, 'public');

            $formulaire->update([
                'facture_file' => $filePath,
            ]);
        }

        // 📑 Upload du fichier CIN
        if ($request->hasFile('cin_file')) {
            $uploadedFile = $request->file('cin_file');
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            $filePath = $uploadedFile->storeAs('pvs', $filename, 'public');

            $formulaire->update([
                'cin_file' => $filePath,
            ]);
        }

        // 📧 Envoi du mail de confirmation (si email fourni)
        if (!empty($validated['email'])) {
            Mail::to($validated['email'])->send(new ConfirmationInscriptionPchare($formulaire));
        }

        Alert::success('Succès', 'Inscription effectuée avec succès.');
        return redirect()->route('formulaire.merci');
    }


    public function merci()
    {
        return view('formulaire.merci');
    }
}
