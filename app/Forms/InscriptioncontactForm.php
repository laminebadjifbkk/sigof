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
                'rules' => 'required',
                'attr' => ['placeholder' => 'Ex: ONFP, Ministère, Entreprise…', 'class' => 'form-control rounded-3']
            ])
            ->add('nom', 'text', [
                'label' => 'Prénom et Nom du représentant',
                'rules' => 'required',
                'attr' => ['placeholder' => 'Ex: Mamadou Diop', 'class' => 'form-control rounded-3']
            ])
            ->add('fonction', 'text', [
                'label' => 'Fonction',
                'attr' => ['placeholder' => 'Ex: Directeur Général', 'class' => 'form-control rounded-3']
            ])
            ->add('telephone', 'text', [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Ex: 77 123 45 67', 'class' => 'form-control rounded-3']
            ])
            ->add('email', 'email', [
                'label' => 'Adresse mail',
                'attr' => ['placeholder' => 'exemple@email.com', 'class' => 'form-control rounded-3']
            ])
            ->add('submit', 'submit', [
                'label' => 'Envoyer ma confirmation',
                'attr' => ['class' => 'btn btn-primary w-100 mt-3 rounded-3']
            ]);
    }
}
