<?php

namespace App\Services\Support;

use App\Models\User;
use App\Models\Support\SupportTicket;
use App\Models\Support\SupportStatut;
use App\Models\Support\SupportTicketHistorique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupportStatutService
{
    /**
     * Transitions autorisées du cycle de vie des tickets.
     */
    private array $transitions = [
        'NOUVEAU' => ['OUVERT', 'REJETE'],
        'OUVERT' => ['EN_COURS', 'EN_ATTENTE', 'REJETE'],
        'EN_COURS' => ['EN_ATTENTE', 'RESOLU'],
        'EN_ATTENTE' => ['EN_COURS', 'RESOLU'],
        'RESOLU' => ['CLOTURE', 'EN_COURS'],
        'CLOTURE' => ['OUVERT'],
        'REJETE' => ['OUVERT'],
    ];

    /**
     * Changer le statut d'un ticket.
     */
    public function changer(
        SupportTicket $ticket,
        User $acteur,
        string $codeStatut,
        ?string $motif = null
    ): SupportTicket {
        return DB::transaction(function () use (
            $ticket,
            $acteur,
            $codeStatut,
            $motif
        ) {
            // Verrouiller le ticket pour éviter les modifications concurrentes.
            $ticket = SupportTicket::whereKey($ticket->id)
                ->lockForUpdate()
                ->firstOrFail();

            $ticket->load('statut');

            $statutActuel = $ticket->statut;
            $codeActuel = $statutActuel?->code;

            if (!$codeActuel) {
                throw ValidationException::withMessages([
                    'statut' => 'Le statut actuel du ticket est introuvable.',
                ]);
            }

            // Vérifier que le ticket est dans le périmètre de l'acteur.
            $peutVoirTout = $acteur->can('support.tickets.view-all');

            $estAssigne = (int) $ticket->user_assigne_id
                === (int) $acteur->id;

            if (!$peutVoirTout && !$estAssigne) {
                throw ValidationException::withMessages([
                    'ticket' => 'Vous ne pouvez modifier le statut que d’un ticket qui vous est affecté.',
                ]);
            }

            // Vérifier que la transition est autorisée.
            $transitionsPossibles = $this->transitions[$codeActuel] ?? [];

            if (!in_array($codeStatut, $transitionsPossibles, true)) {
                throw ValidationException::withMessages([
                    'statut' => 'Cette transition de statut n’est pas autorisée.',
                ]);
            }

            // Vérifier le statut cible.
            $nouveauStatut = SupportStatut::actifs()
                ->where('code', $codeStatut)
                ->first();

            if (!$nouveauStatut) {
                throw ValidationException::withMessages([
                    'statut' => 'Le statut sélectionné est indisponible.',
                ]);
            }

            // Réouverture d'un ticket résolu, clôturé ou rejeté.
            $estReouverture =
            in_array($codeActuel, ['CLOTURE', 'REJETE'], true)
            || (
                $codeActuel === 'RESOLU'
                && $codeStatut === 'EN_COURS'
            );

            if ($estReouverture) {
                if (!$acteur->can('support.tickets.reopen')) {
                    throw ValidationException::withMessages([
                        'autorisation' => 'Vous n’êtes pas autorisé à rouvrir ce ticket.',
                    ]);
                }
            } elseif (in_array($codeStatut, ['CLOTURE', 'REJETE'], true)) {
                if (!$acteur->can('support.tickets.close')) {
                    throw ValidationException::withMessages([
                        'autorisation' => 'Vous n’êtes pas autorisé à clôturer ou rejeter ce ticket.',
                    ]);
                }
            } elseif (!$acteur->can('support.tickets.update')) {
                throw ValidationException::withMessages([
                    'autorisation' => 'Vous n’êtes pas autorisé à modifier le statut de ce ticket.',
                ]);
            }

            // Justification obligatoire pour certaines transitions.
            if (in_array($codeStatut, ['EN_ATTENTE', 'REJETE'], true)
                || $estReouverture) {
                if (blank($motif)) {
                    throw ValidationException::withMessages([
                        'motif' => 'Une justification est obligatoire pour cette opération.',
                    ]);
                }
            }

            $ancienneValeur = $statutActuel->libelle;

            // Mise à jour du statut.
            $ticket->support_statut_id = $nouveauStatut->id;

            // Gestion des dates et des motifs.
            if ($codeStatut === 'RESOLU') {
                $ticket->date_resolution = now();
                $ticket->motif_attente = null;
                $ticket->motif_rejet = null;
            }

            if ($codeStatut === 'CLOTURE') {
                $ticket->date_cloture = now();
                $ticket->motif_attente = null;
            }

            if ($codeStatut === 'EN_ATTENTE') {
                $ticket->motif_attente = $motif;
            } else {
                $ticket->motif_attente = null;
            }

            if ($codeStatut === 'REJETE') {
                $ticket->motif_rejet = $motif;
                $ticket->date_resolution = null;
                $ticket->date_cloture = null;
            }

            // Une réouverture remet à zéro les dates de fin.
            if ($estReouverture) {
                $ticket->date_resolution = null;
                $ticket->date_cloture = null;
                $ticket->motif_rejet = null;
                $ticket->motif_attente = null;
            }

            $ticket->save();

            // Historisation du changement.
            $description = 'Changement de statut : '
                . $ancienneValeur
                . ' → '
                . $nouveauStatut->libelle;

            if (filled($motif)) {
                $description .= '. Motif : ' . $motif;
            }

            SupportTicketHistorique::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $acteur->id,
                'action' => 'CHANGEMENT_STATUT',
                'champ' => 'support_statut_id',
                'ancienne_valeur' => $ancienneValeur,
                'nouvelle_valeur' => $nouveauStatut->libelle,
                'description' => $description,
            ]);

            return $ticket->fresh([
                'statut',
                'demandeur',
                'assigne',
            ]);
        });
    }
}
