<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Support\SupportTicket;
use App\Models\Support\SupportTicketAffectation;
use App\Models\Support\SupportTicketHistorique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportAffectationService
{
    /**
     * Affecter ou réaffecter un ticket à un technicien.
     */
    public function affecter(
        SupportTicket $ticket,
        User $nouveauTechnicien,
        User $acteur,
        ?string $motif = null
    ): SupportTicketAffectation {

        return DB::transaction(function () use (
            $ticket,
            $nouveauTechnicien,
            $acteur,
            $motif
        ) {

            // Vérifier les droits de l'utilisateur qui effectue l'opération.
            /* if (!$acteur->hasAnyRole([
                'chef-service-informatique',
                'responsable-support',
            ])) {
                throw ValidationException::withMessages([
                    'autorisation' => 'Vous n’êtes pas autorisé à affecter un ticket.',
                ]);
            } */
           if (!$acteur->can('support.tickets.assign')) {
                throw ValidationException::withMessages([
                    'autorisation' => 'Vous n’êtes pas autorisé à affecter un ticket.',
                ]);
            }

            // Vérifier que le destinataire est bien un technicien support.
            if (!$nouveauTechnicien->hasRole('technicien-support')) {
                throw ValidationException::withMessages([
                    'technicien' => 'Seul un technicien support peut recevoir un ticket.',
                ]);
            }

            // Verrouiller le ticket pendant l'opération.
            $ticketVerrouille = SupportTicket::query()
                ->whereKey($ticket->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Vérifier que le ticket n'est pas dans un état terminal.
            $estTerminal = $ticketVerrouille->statut()
                ->value('est_terminal');

            if ($estTerminal) {
                throw ValidationException::withMessages([
                    'ticket' => 'Impossible d’affecter un ticket clôturé ou rejeté.',
                ]);
            }

            $ancienTechnicienId = $ticketVerrouille->user_assigne_id;

            // Éviter une affectation identique.
            if ((int) $ancienTechnicienId === (int) $nouveauTechnicien->id) {
                throw ValidationException::withMessages([
                    'technicien' => 'Ce technicien est déjà affecté à ce ticket.',
                ]);
            }

            $dateAffectation = now();

            // Enregistrer l'affectation.
            $affectation = SupportTicketAffectation::create([
                'support_ticket_id' => $ticketVerrouille->id,
                'ancien_user_assigne_id' => $ancienTechnicienId,
                'nouveau_user_assigne_id' => $nouveauTechnicien->id,
                'affecte_par_user_id' => $acteur->id,
                'date_affectation' => $dateAffectation,
                'motif' => $motif,
            ]);

            // Mettre à jour le technicien actuellement affecté.
            $ticketVerrouille->user_assigne_id = $nouveauTechnicien->id;
            $ticketVerrouille->save();

            // Enregistrer l'opération dans l'historique du ticket.
            $estReaffectation = !is_null($ancienTechnicienId);

            $nomTechnicien = trim(
                $nouveauTechnicien->firstname . ' ' . $nouveauTechnicien->name
            );

            SupportTicketHistorique::create([
                'support_ticket_id' => $ticketVerrouille->id,
                'user_id' => $acteur->id,
                'action' => $estReaffectation
                    ? 'REAFFECTATION'
                    : 'AFFECTATION',
                'champ' => 'user_assigne_id',
                'ancienne_valeur' => $ancienTechnicienId
                    ? (string) $ancienTechnicienId
                    : null,
                'nouvelle_valeur' => (string) $nouveauTechnicien->id,
                'description' => ($estReaffectation
                    ? 'Réaffectation'
                    : 'Affectation initiale')
                    . ' du ticket au technicien ' . $nomTechnicien
                    . ($motif ? '. Motif : ' . $motif : ''),
            ]);

            return $affectation;
        });
    }
}
