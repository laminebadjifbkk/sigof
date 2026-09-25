<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoteFraisRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Un <select> ou <input> laissé vide envoie '' et non null.
     * Sans cette conversion, MySQL rejette '' sur une colonne entière
     * (note_acompte_id) ou date (periode_debut/fin) — d'où l'erreur
     * "Incorrect integer value: '' for column 'note_acompte_id'".
     */
    protected function prepareForValidation()
    {
        $champsNullable = [
            'note_acompte_id',
            'periode_debut',
            'periode_fin',
            'session_label',
            'lieu',
            'beneficiaires',
            'banque_rib',
        ];

        $this->merge(
            collect($this->only($champsNullable))
                ->map(fn($valeur) => $valeur === '' ? null : $valeur)
                ->all()
        );

        // Même souci possible dans les lignes : unite peut arriver vide.
        if ($this->has('lignes')) {
            $lignes = collect($this->input('lignes'))->map(function ($ligne) {
                $ligne['unite'] = ($ligne['unite'] ?? '') === '' ? null : $ligne['unite'];

                return $ligne;
            })->all();

            $this->merge(['lignes' => $lignes]);
        }
    }

    public function rules()
    {
        $noteFrais = $this->route('notes_frai'); // null en création

        return [
            'formations_id' => ['required', 'integer', 'exists:formations,id'],
            'type' => ['required', Rule::in(['ACOMPTE', 'DEFINITIVE'])],
            'taux_acompte' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'note_acompte_id' => [
                'nullable',
                'integer',
                Rule::exists('notes_frais', 'id')->where('type', 'ACOMPTE'),
            ],
            'session_label' => ['nullable', 'string', 'max:200'],
            'lieu' => ['nullable', 'string', 'max:200'],
            'beneficiaires' => ['nullable', 'string', 'max:200'],
            'banque_rib' => ['nullable', 'string', 'max:200'],
            'periode_debut' => ['nullable', 'date'],
            'periode_fin' => ['nullable', 'date', 'after_or_equal:periode_debut'],

            'lignes' => ['required', 'array', 'min:1'],
            'lignes.*.id' => ['nullable', 'integer', 'exists:note_frais_lignes,id'],
            'lignes.*.rubriques_id' => ['required', 'integer', 'exists:rubriques,id'],
            'lignes.*.unite' => ['nullable', 'string', 'max:45'],
            'lignes.*.qte' => ['required', 'numeric', 'min:0'],
            'lignes.*.pu' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'lignes.required' => 'Ajoutez au moins une ligne de frais.',
            'lignes.*.rubriques_id.required' => 'Sélectionnez une rubrique pour chaque ligne.',
            'periode_fin.after_or_equal' => 'La date de fin doit être postérieure à la date de début.',
        ];
    }
}
