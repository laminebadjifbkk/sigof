<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParcMissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => 'required|string|max:50|unique:parc_missions,reference,' . $this->route('parc_mission'),
            /* 'vehicule_id' => 'nullable|exists:parc_vehicules,id',
            'chauffeur_id' => 'nullable|exists:parc_chauffeurs,id', */
            'type_mission_id' => 'nullable|exists:parc_type_missions,id',
            'objet' => 'required|string|max:255',
            'lieu_depart' => 'required|string|max:255',
            'lieu_arrivee' => 'required|string|max:255',
            'date_depart' => 'required|date',
            'date_retour' => 'nullable|date',
            /* 'date_depart' => 'required|date|after_or_equal:today',
            'date_retour' => 'nullable|date|after_or_equal:date_depart', */
            'distance_km' => 'nullable|integer|min:0',
            'indemnites_total' => 'nullable|numeric|min:0',
            'statut' => 'required|in:planifiee,en_cours,cloturee,annulee',
        ];
    }
}
