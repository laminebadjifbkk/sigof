<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\InscriptioncontactForm;

class InscriptioncontactController extends Controller
{
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(InscriptioncontactForm::class, [
            'method' => 'POST',
            'url' => route('inscriptioncontact.store')
        ]);

        return view('inscriptioncontact.create', compact('form'));
    }

    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(InscriptioncontactForm::class);

        if (!$form->isValid()) {
            return redirect()->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        $data = $form->getFieldValues();

        // Ici tu peux enregistrer en base de données, envoyer un mail, etc.
        // Exemple d’enregistrement :
        // Inscription::create($data);

        return redirect()
            ->back()
            ->with('success', 'Merci ! Votre participation a été confirmée avec succès.');
    }
}
