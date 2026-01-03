<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParcMissionPersonnelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ou policy si tu en as
    }

    public function rules(): array
    {
        return [
            /* ================= CHAUFFEURS ================= */
            'chauffeurs' => ['sometimes', 'array'],
            'chauffeurs.*.selected' => ['nullable', 'boolean'],
            'chauffeurs.*.vehicule_id' => ['nullable', 'integer', 'exists:parc_vehicules,id'],

            /* ================= EMPLOYÉS ================= */
            'employees' => ['sometimes', 'array'],
            'employees.*.selected' => ['nullable', 'boolean'],
            'employees.*.role' => [
                'nullable',
                Rule::in(['participant', 'responsable', 'observateur']),
            ],
            'employees.*.vehicule_id' => ['nullable', 'integer', 'exists:parc_vehicules,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'employees.*.role.in' => 'Le rôle sélectionné est invalide.',
            'employees.*.vehicule_id.exists' => 'Le véhicule sélectionné est invalide.',
            'chauffeurs.*.vehicule_id.exists' => 'Le véhicule sélectionné pour le chauffeur est invalide.',
        ];
    }

    /**
     * Nettoyage & normalisation des données
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'chauffeurs' => collect($this->input('chauffeurs', []))
                ->map(fn($c) => [
                    'selected' => isset($c['selected']),
                    'vehicule_id' => empty($c['vehicule_id']) ? null : $c['vehicule_id'],
                ])
                ->toArray(),

            'employees' => collect($this->input('employees', []))
                ->map(fn($e) => [
                    'selected' => isset($e['selected']),
                    'role' => $e['role'] ?? null,
                    'vehicule_id' => empty($e['vehicule_id']) ? null : $e['vehicule_id'],
                ])
                ->toArray(),
        ]);
    }

    /**
     * Validation métier
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Chauffeurs
            foreach ($this->input('chauffeurs', []) as $id => $data) {
                if (empty($data['selected']) && !empty($data['vehicule_id'])) {
                    $validator->errors()->add(
                        "chauffeurs.$id.vehicule_id",
                        "Un véhicule ne peut être affecté qu’à un chauffeur sélectionné."
                    );
                }
            }

            // Employés
            foreach ($this->input('employees', []) as $id => $data) {
                if (empty($data['selected']) && !empty($data['vehicule_id'])) {
                    $validator->errors()->add(
                        "employees.$id.vehicule_id",
                        "Un véhicule ne peut être affecté qu’à un employé sélectionné."
                    );
                }
            }
        });
    }
}
