<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreParcMissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => 'required|string|max:50|unique:parc_missions',
            /* 'vehicule_id' => 'nullable|exists:parc_vehicules,id',
            'chauffeur_id' => 'nullable|exists:parc_chauffeurs,id', */
            'objet' => 'required|string|max:255',
            'lieu_depart' => 'required|string|max:255',
            'lieu_arrivee' => 'required|string|max:255',
            'date_depart' => 'required|date|after_or_equal:today',
            'date_retour' => 'required|date|after_or_equal:date_depart',
            'distance_km' => 'nullable|integer|min:0',
            'indemnites_total' => 'nullable|numeric|min:0',
            'statut' => 'required|in:planifiee,en_cours,cloturee,annulee',

            // Nouveaux champs
            'departement' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'itineraire' => 'required|string',
            'taux_journalier' => 'nullable|numeric|min:0',
            'indemnite_mission' => 'nullable|numeric|min:0',
            'frais_deplacement' => 'nullable|numeric|min:0',
            'avance' => 'nullable|numeric|min:0',
            'reliquat' => 'nullable|numeric|min:0',
            'commentaires' => 'nullable|string',
            'autres' => 'nullable|string',
            'type_mission_id' => 'required|exists:parc_type_missions,id',
            'created_by' => 'nullable|exists:users,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $dateDepart = $this->date_depart;
        $dateRetour = $this->date_retour;
        $taux       = (float) $this->taux_journalier;
        $avance     = (float) $this->avance; // null ou vide sera casté à 0

        $jours = 1;

        if ($dateDepart && $dateRetour) {
            $jours = Carbon::parse($dateDepart)
                ->diffInDays(Carbon::parse($dateRetour));
        }

        // 2️⃣ Calcul indemnité totale
        $indemnitesTotal = $jours * $taux;

        // 3️⃣ Calcul reliquat
        $reliquat = max($indemnitesTotal - $avance, 0);

        $this->merge([
            /* 'vehicule_id'  => $this->vehicule_id ?: null,
            'chauffeur_id' => $this->chauffeur_id ?: null, */
            'type_mission_id' => $this->type_mission_id ?: null,
            'indemnites_total' => $jours * $taux,
            'reliquat'         => $reliquat,
        ]);
    }
}
