<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Support\SupportTicket;
use App\Models\Support\SupportStatut;
use App\Models\Support\SupportTicketHistorique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportPriseEnChargeService
{
    public function prendreEnCharge(
        SupportTicket $ticket,
        User $acteur
    ): SupportTicket {
        return DB::transaction(function () use ($ticket, $acteur) {

            if (!$acteur->can('support.tickets.take-charge')) {
                throw ValidationException::withMessages([
                    'autorisation' => 'Vous n’êtes pas autorisé à prendre en charge un ticket.',
                ]);
            }

            $ticket = SupportTicket::query()
                ->whereKey($ticket->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $ticket->user_assigne_id !== (int) $acteur->id) {
                throw ValidationException::withMessages([
                    'ticket' => 'Ce ticket ne vous est pas affecté.',
                ]);
            }

            $statutActuel = $ticket->statut;

            if (!$statutActuel || $statutActuel->est_terminal) {
                throw ValidationException::withMessages([
                    'ticket' => 'Ce ticket est clôturé ou rejeté et ne peut plus être pris en charge.',
                ]);
            }

            if ($statutActuel->code === 'RESOLU') {
                throw ValidationException::withMessages([
                    'ticket' => 'Ce ticket est déjà résolu. Il doit être rouvert avant toute reprise.',
                ]);
            }

            if ($statutActuel->code === 'EN_COURS') {
                throw ValidationException::withMessages([
                    'ticket' => 'Ce ticket est déjà en cours de traitement.',
                ]);
            }

            $nouveauStatut = SupportStatut::query()
                ->where('code', 'EN_COURS')
                ->where('actif', true)
                ->first();

            if (!$nouveauStatut) {
                throw ValidationException::withMessages([
                    'statut' => 'Le statut EN_COURS est introuvable ou inactif.',
                ]);
            }

            $ancienStatut = $statutActuel->code;
            $ancienneDatePriseEnCharge = $ticket->date_prise_en_charge;

            $ticket->support_statut_id = $nouveauStatut->id;

            if (!$ticket->date_prise_en_charge) {
                $ticket->date_prise_en_charge = now();
            }

            $ticket->save();

            SupportTicketHistorique::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $acteur->id,
                'action' => 'PRISE_EN_CHARGE',
                'champ' => 'support_statut_id',
                'ancienne_valeur' => $ancienStatut,
                'nouvelle_valeur' => 'EN_COURS',
                'description' => 'Ticket pris en charge par '
                    . trim($acteur->firstname . ' ' . $acteur->name)
                    . '. Passage du statut '
                    . $ancienStatut . ' à EN_COURS.',
            ]);

            return $ticket->fresh([
                'categorie',
                'type',
                'priorite',
                'statut',
                'demandeur',
                'assigne',
            ]);
        });
    }
}
