<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParcTypeMissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle' => 'required|string|max:255|unique:parc_type_missions,libelle,' 
                        . $this->route('parc_type_mission')->id,
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
