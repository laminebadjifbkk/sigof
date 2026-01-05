<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcVehiculeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // tu peux ajouter une logique d'autorisation si besoin
    }

    public function rules()
    {
        return [
            'immatriculation' => 'required|string|max:20|unique:parc_vehicules,immatriculation,' . $this->route('parc_vehicule'),
            'marque' => 'required|string|max:50',
            'modele' => 'nullable|string|max:50',
            'annee' => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
            'categorie' => 'nullable|string|max:50',
            'energie' => 'nullable|in:diesel,essence,hybride,electrique',
            'consommation_moyenne' => 'nullable|numeric|min:0',
            'capacite_reservoir' => 'nullable|numeric|min:0',
            'kilometrage_actuel' => 'nullable|integer|min:0',
            'etat' => 'required|in:operationnel,maintenance,hors_service',
            'assurance_expire_le' => 'required|date|after_or_equal:today',
            'visite_technique_expire_le' => 'required|date|after_or_equal:today',
            /* 'assurance_expire_le' => 'required|date|after_or_equal:today',
            'visite_technique_expire_le' => 'required|date|after_or_equal:today', */
            'chauffeur_id' => 'required|exists:parc_chauffeurs,id',
        ];
    }
}
