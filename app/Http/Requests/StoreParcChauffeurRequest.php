<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcChauffeurRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employe_id' => 'required|exists:employees,id', // obligatoire pour lier le chauffeur à un employé
            /* 'matricule' => 'required|string|unique:parc_chauffeurs,matricule|max:20',
            'nom' => 'required|string|max:50',
            'prenom' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:20', */
            'statut' => 'required|in:actif,indisponible,archive',
            'permis_numero' => 'nullable|string|max:50',
            'permis_categories' => 'nullable|string|max:50',
            'permis_expire_le' => 'required|date|after_or_equal:today',
        ];
    }
}
