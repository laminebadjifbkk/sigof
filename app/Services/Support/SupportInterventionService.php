<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Support\SupportTicket;
use App\Models\Support\SupportTicketHistorique;
use App\Models\Support\SupportTicketIntervention;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportInterventionService
{
    public function enregistrer(
        SupportTicket $ticket,
        User $acteur,
        array $donnees
    ): SupportTicketIntervention {

        if (!$acteur->can('support.interventions.create')) {
            throw ValidationException::withMessages([
                'autorisation' => 'Vous n’êtes pas autorisé à enregistrer une intervention.',
            ]);
        }

        return DB::transaction(function () use ($ticket, $acteur, $donnees) {

            $ticket = SupportTicket::whereKey($ticket->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Les responsables ayant accès à tous les tickets
            // peuvent intervenir sur n'importe quel ticket.
            // Un technicien ne peut intervenir que sur un ticket qui lui est affecté.
            if (
                !$acteur->can('support.tickets.view-all')
                && (int) $ticket->user_assigne_id !== (int) $acteur->id
            ) {
                throw ValidationException::withMessages([
                    'ticket' => 'Vous ne pouvez intervenir que sur un ticket qui vous est affecté.',
                ]);
            }

            $ticket->loadMissing('statut');

            if ($ticket->statut?->est_terminal) {
                throw ValidationException::withMessages([
                    'ticket' => 'Impossible d’enregistrer une intervention sur un ticket clôturé ou rejeté.',
                ]);
            }

            $intervention = SupportTicketIntervention::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $acteur->id,
                'date_intervention' => $donnees['date_intervention'],
                'diagnostic' => $donnees['diagnostic'],
                'travaux_realises' => $donnees['travaux_realises'],
                'duree_minutes' => $donnees['duree_minutes'],
                'resultat' => $donnees['resultat'],
                'observations' => $donnees['observations'] ?? null,
            ]);

            SupportTicketHistorique::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $acteur->id,
                'action' => 'INTERVENTION',
                'champ' => null,
                'ancienne_valeur' => null,
                'nouvelle_valeur' => $intervention->resultat,
                'description' => 'Intervention enregistrée : '
                    . $intervention->resultat
                    . ' — Durée : '
                    . $intervention->duree_minutes
                    . ' minute(s).',
            ]);

            return $intervention->load('user');
        });
    }
}
