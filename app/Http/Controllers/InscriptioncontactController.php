<?php
namespace App\Http\Controllers;

use App\Mail\ConfirmationInscription;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kris\LaravelFormBuilder\FormBuilder;

class InscriptioncontactController extends Controller
{
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\App\Forms\InscriptioncontactForm::class, [
            'method' => 'POST',
            'url'    => route('inscriptioncontact.store'),
        ]);

        return view('inscriptioncontact.create', compact('form'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'structure'   => 'required|string|max:255',
            'nom'         => 'required|string|max:255',
            'fonction'    => 'nullable|string|max:255',
            'telephone'   => 'nullable|string|max:50',
            'email'       => 'nullable|email|max:255',
            'commentaire' => 'nullable|email|max:255',
        ]);

        // Enregistrer en base
        $inscription = Inscription::create($validated);

        // Envoyer l'email
        Mail::to($inscription->email)->send(new ConfirmationInscription($inscription));

        // Si tu ne stockes pas, tu peux par exemple juste afficher un message :
        /* return back()->with('success', 'Votre confirmation a été enregistrée. Merci !'); */

        // Ici tu peux envoyer un mail ou traiter les données si nécessaire
        return redirect()->route('inscriptioncontact.merci');
    }

    public function merci()
    {
        return view('inscriptioncontact.merci');
    }
}
