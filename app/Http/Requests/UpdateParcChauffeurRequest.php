<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParcChauffeurRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Ici tu peux mettre une logique de permission (Gate/Policy).
        // Pour l'instant, on autorise tout utilisateur authentifié.
        return true;
    }

    /**
     * Règles de validation pour la mise à jour d'un chauffeur.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'matricule' => [
                'required',
                'string',
                'max:20',
                Rule::unique('parc_chauffeurs', 'matricule')->ignore($this->route('parc_chauffeur'))
            ],
            'nom' => 'required|string|max:50',
            'prenom' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:20',
            'permis_numero' => 'nullable|string|max:30',
            'permis_categories' => 'nullable|string|max:50',
            'permis_expire_le' => 'nullable|date|after_or_equal:today',
            'statut' => 'required|in:actif,indisponible,archive',
        ];
    }

    /**
     * Messages personnalisés (optionnel).
     */
    public function messages(): array
    {
        return [
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule est déjà attribué à un autre chauffeur.',
            'nom.required' => 'Le nom est obligatoire.',
            'permis_expire_le.after_or_equal' => 'La date d’expiration du permis doit être aujourd’hui ou une date future.',
            'statut.in' => 'Le statut doit être soit "actif" soit "indisponible".',
        ];
    }
}
