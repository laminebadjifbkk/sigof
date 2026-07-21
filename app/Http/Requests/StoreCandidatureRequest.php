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
            'civilite'       => 'required|in:M.,Mme',
            'prenom'         => ['required', 'string', 'max:200'],
            'nom'            => ['required', 'string', 'max:200'],
            'email'          => ['required', 'email', 'max:200', 'unique:users,email'],
            'telephone'      => ['required', 'string', 'max:200'],
            'date_naissance' => ['required', 'date', 'before_or_equal:today'],
            'lieu_naissance' => 'required|string|max:255',
            'adresse'        => 'required|string|max:255',
            'region_id'      => 'required|exists:regions,id',

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
            'certification_fichier'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'cv'                       => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'video_presentation' => [
                'nullable',
                'file',
                'mimes:mp4,mov',
                'max:30720', // 30 Mo en Ko (30 * 1024)
            ],
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

            // Vérification de la durée de la vidéo (2 minutes max)
            if ($this->hasFile('video_presentation') && $this->file('video_presentation')->isValid()) {
                $duration = $this->getVideoDuration($this->file('video_presentation'));

                if ($duration !== null && $duration > 120) {
                    $validator->errors()->add(
                        'video_presentation',
                        'La vidéo de présentation ne doit pas dépasser 2 minutes (durée actuelle : ' . gmdate('i:s', $duration) . ').'
                    );
                }
            }
        });
    }

    /**
     * Récupère la durée d'une vidéo en secondes via ffprobe (nécessite FFmpeg installé sur le serveur).
     */
    private function getVideoDuration($file): ?float
    {
        $path = $file->getRealPath();

        $ffprobe = shell_exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($path) . " 2>&1");

        return $ffprobe ? (float) trim($ffprobe) : null;
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
