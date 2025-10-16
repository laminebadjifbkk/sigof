<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class InscriptioncontactForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('structure', 'text', [
                'label' => 'Nom de la structure invitée',
                'rules' => 'required|string|min:2',
                'attr' => [
                    'placeholder' => 'Ex : Société ABC',
                    'class' => 'form-control'
                ]
            ])
            ->add('representant', 'text', [
                'label' => 'Prénom et Nom du représentant',
                'rules' => 'required|string|min:3',
                'attr' => [
                    'placeholder' => 'Ex : Awa Ndiaye',
                    'class' => 'form-control'
                ]
            ])
            ->add('fonction', 'text', [
                'label' => 'Fonction',
                'rules' => 'required|string',
                'attr' => [
                    'placeholder' => 'Ex : Responsable RH',
                    'class' => 'form-control'
                ]
            ])
            ->add('telephone', 'text', [
                'label' => 'Téléphone',
                'rules' => 'required|regex:/^(77|78|76|70|75)[0-9]{7}$/',
                'attr' => [
                    'placeholder' => 'Ex : 77 123 45 67',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', 'email', [
                'label' => 'Adresse mail',
                'rules' => 'required|email',
                'attr' => [
                    'placeholder' => 'exemple@domaine.com',
                    'class' => 'form-control'
                ]
            ])
            ->add('Confirmer', 'submit', [
                'attr' => [
                    'class' => 'btn btn-success w-100 mt-3',
                    'style' => 'font-weight:bold;'
                ]
            ]);
    }
}
