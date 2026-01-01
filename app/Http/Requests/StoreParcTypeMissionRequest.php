<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcTypeMissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // autoriser l’accès
    }

    public function rules(): array
    {
        return [
            'libelle' => 'required|string|max:255|unique:parc_type_missions,libelle',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique'   => 'Ce type de mission existe déjà.',
        ];
    }
}
