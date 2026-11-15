<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Support\SupportIncident;
use App\Models\Support\SupportIncidentType;
use App\Models\Support\SupportIncidentStatut;
use App\Models\Support\SupportIncidentHistorique;
use App\Models\Support\SupportPriorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportIncidentService
{
    public function __construct(
        private SupportIncidentReferenceService $referenceService
    ) {
    }

    /**
     * Créer un incident et son historique initial.
     */
    public function creer(
        array $donnees,
        User $acteur
    ): SupportIncident {
        if (!$acteur->can('support.incidents.create')) {
            throw ValidationException::withMessages([
                'autorisation' => 'Vous n’êtes pas autorisé à déclarer un incident.',
            ]);
        }

        return DB::transaction(function () use ($donnees, $acteur) {

            // Vérifier le type d'incident.
            $type = SupportIncidentType::query()
                ->whereKey($donnees['support_incident_type_id'])
                ->where('actif', true)
                ->first();

            if (!$type) {
                throw ValidationException::withMessages([
                    'support_incident_type_id' => 'Le type d’incident sélectionné est indisponible.',
                ]);
            }

            // Vérifier la priorité.
            $priorite = SupportPriorite::actives()
                ->whereKey($donnees['support_priorite_id'])
                ->first();

            if (!$priorite) {
                throw ValidationException::withMessages([
                    'support_priorite_id' => 'La priorité sélectionnée est indisponible.',
                ]);
            }

            // Récupérer le statut initial.
            $statut = SupportIncidentStatut::actifs()
                ->where('code', 'DECLARE')
                ->first();

            if (!$statut) {
                throw ValidationException::withMessages([
                    'statut' => 'Le statut initial DECLARE est introuvable ou inactif.',
                ]);
            }

            // Créer l'incident.
            $incident = SupportIncident::create([
                'reference' => $this->referenceService->generer(),
                'titre' => $donnees['titre'],
                'description' => $donnees['description'],

                'support_incident_type_id' => $type->id,
                'support_priorite_id' => $priorite->id,
                'support_incident_statut_id' => $statut->id,

                'user_declarant_id' => $acteur->id,
                'user_responsable_id' => null,

                'date_detection' => $donnees['date_detection'],
                'date_declaration' => now(),

                'user_create_id' => $acteur->id,
                'user_update_id' => null,
            ]);

            // Enregistrer l'historique initial.
            SupportIncidentHistorique::create([
                'support_incident_id' => $incident->id,
                'user_id' => $acteur->id,
                'action' => 'CREATION',
                'champ' => null,
                'ancienne_valeur' => null,
                'nouvelle_valeur' => $incident->reference,
                'description' => 'Déclaration de l’incident '
                    . $incident->reference
                    . ' par '
                    . trim($acteur->firstname . ' ' . $acteur->name)
                    . '. Statut initial : DECLARE.',
            ]);

            return $incident->fresh([
                'type',
                'priorite',
                'statut',
                'declarant',
                'responsable',
                'historiques',
            ]);
        });
    }
}
