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
            'user_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'matricule' => 'required|string|unique:parc_chauffeurs,matricule|max:20',
            'nom' => 'required|string|max:50',
            'prenom' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'required|in:actif,indisponible,archive',
            'permis_numero' => 'nullable|string|max:50',
            'permis_categories' => 'nullable|string|max:50',
            'permis_expire_le' => 'nullable|date|after_or_equal:today',
        ];
    }
}