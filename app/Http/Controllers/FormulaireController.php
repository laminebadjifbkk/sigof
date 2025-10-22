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
        ]);

        // Convertir les champs numériques vides en null
        $validated['montant_unique'] = $validated['montant_unique'] === '' ? null : $validated['montant_unique'];

        /* Alert::error('Désolé', 'Les inscriptions n\'ont pas encore démarré.');

        return redirect()->back(); */

        $formulaire = Formulaire::create($validated);

        Mail::to($validated['email'])->send(new ConfirmationInscriptionPchare($formulaire));

        Alert::success('Succès', 'Inscription effectuée avec succès.');

        return redirect()->route('formulaire.merci');
    }

    public function merci()
    {
        return view('formulaire.merci');
    }
}
