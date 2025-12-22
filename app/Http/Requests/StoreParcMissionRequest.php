<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcMissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => 'required|string|max:50|unique:parc_missions',
            'vehicule_id' => 'nullable|exists:parc_vehicules,id',
            'chauffeur_id' => 'nullable|exists:parc_chauffeurs,id',
            'objet' => 'required|string|max:255',
            'lieu_depart' => 'required|string|max:255',
            'lieu_arrivee' => 'required|string|max:255',
            'date_depart' => 'required|date|after_or_equal:today',
            'date_retour' => 'nullable|date|after_or_equal:date_depart',
            'distance_km' => 'nullable|integer|min:0',
            'indemnites_total' => 'nullable|numeric|min:0',
            'statut' => 'required|in:planifiee,en_cours,terminee,annulee',
        ];
    }
}
