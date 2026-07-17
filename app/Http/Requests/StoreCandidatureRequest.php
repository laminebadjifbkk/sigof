<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Étape 1 — Profil (table users)
            'prenom'         => ['required', 'string', 'max:200'],
            'nom'            => ['required', 'string', 'max:200'],
            'email'          => ['required', 'email', 'max:200', 'unique:users,email'],
            'telephone'      => ['required', 'string', 'max:200'],
            'date_naissance' => ['required', 'date', 'before_or_equal:today'],

            // Étape 2 — Langues
            'langue_specialisation' => ['required', 'string', 'exists:langues_specialisations,code'],
            'certification'         => ['nullable', 'string', 'max:255'],
            'diplome'                => ['required', Rule::in(['licence', 'master', 'doctorat', 'certification'])],
            'langue_maternelle'      => ['required', 'string', 'max:200'],
            'niveau_francais'        => ['required', Rule::in(['c1', 'c2'])],
            'langue_vivante_2'       => ['nullable', 'string', 'max:200'],

            // Étape 3 — Disponibilité et affectation
            'disponible_debut'     => ['required', 'date'],
            'disponible_fin'       => ['required', 'date', 'after_or_equal:disponible_debut'],
            'zone'                  => ['required', Rule::in(['diamniadio', 'dakar_centre', 'saly', 'indifferent'])],
            'delegation_souhaitee'  => ['nullable', 'string', 'max:255'],

            // Étape 4 — Documents
            'piece_identite'          => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'diplome_fichier'         => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'certification_fichier'   => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'cv'                       => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'attestation'              => ['required', 'accepted'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('date_naissance')) {
                $age = Carbon::parse($this->date_naissance)->age;
                if ($age < 21 || $age > 35) {
                    $validator->errors()->add(
                        'date_naissance',
                        'Le programme est ouvert aux candidats de 21 à 35 ans.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'required'  => 'Le champ :attribute est obligatoire.',
            'email'     => 'L\'adresse e-mail n\'est pas valide.',
            'unique'    => 'Cette adresse e-mail est déjà utilisée.',
            'accepted'  => 'Vous devez accepter la charte du programme.',
            'mimes'     => 'Le format du fichier :attribute n\'est pas accepté.',
            'max'       => 'Le fichier :attribute dépasse la taille maximale autorisée (5 Mo).',
        ];
    }
}
