<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParcVehiculeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autoriser la validation
    }

    public function rules(): array
    {
        return [
            'immatriculation' => [
                'required',
                'string',
                'max:20',
                Rule::unique('parc_vehicules', 'immatriculation')->ignore($this->route('parc_vehicule')),
            ],
            'marque' => 'required|string|max:50',
            'modele' => 'nullable|string|max:50',
            'annee' => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
            'categorie' => 'nullable|string|max:50',
            'energie' => 'nullable|in:diesel,essence,hybride,electrique',
            'consommation_moyenne' => 'nullable|numeric|min:0',
            'capacite_reservoir' => 'nullable|numeric|min:0',
            'kilometrage_actuel' => 'nullable|integer|min:0',
            'etat' => 'required|in:operationnel,maintenance,hors_service',
            'assurance_expire_le' => 'nullable|date|after_or_equal:today',
            'visite_technique_expire_le' => 'nullable|date|after_or_equal:today',
            'chauffeur_id' => 'nullable|exists:parc_chauffeurs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'immatriculation.required' => 'L’immatriculation est obligatoire.',
            'immatriculation.unique'   => 'Cette immatriculation est déjà attribuée à un autre véhicule.',
            'marque.required'          => 'La marque est obligatoire.',
            'chauffeur_id.exists'      => 'Le chauffeur sélectionné n’existe pas.',
        ];
    }
}