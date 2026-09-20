<?php

namespace App\Services;

use App\Models\OnfpActiviteNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class OnfpNotificationService
{
    public function __construct(
        private BrevoMailer $brevoMailer
    ) {}

    /**
     * Crée une notification en base pour chaque employé fourni,
     * puis tente l'envoi par email via Brevo. L'échec d'un envoi email
     * n'empêche pas la création des autres notifications.
     *
     * @param array<int> $employeeIds
     */
    public function notifierEmployes(
        array $employeeIds,
        ?int $activiteId,
        string $type,
        string $titre,
        string $message,
        string $priorite = 'normale'
    ): void {
        $employeeIds = array_unique(array_filter($employeeIds));

        foreach ($employeeIds as $employeeId) {
            $notification = OnfpActiviteNotification::create([
                'activite_id' => $activiteId,
                'employee_id' => $employeeId,
                'type' => $type,
                'titre' => $titre,
                'message' => $message,
                'priorite' => $priorite,
            ]);

            $this->envoyerEmail($notification);
        }
    }

    private function envoyerEmail(OnfpActiviteNotification $notification): void
    {
        $notification->loadMissing(['employee.user', 'activite']);

        $user = $notification->employee?->user;

        if (!$user?->email) {
            return;
        }

        $nomDestinataire = trim(($user->firstname ?? '') . ' ' . ($user->name ?? '')) ?: $user->email;

        $htmlContent = View::make('emails.onfp.notification', [
            'titre' => $notification->titre,
            'message' => $notification->message,
            'activite' => $notification->activite,
            'priorite' => $notification->priorite,
        ])->render();

        try {
            $this->brevoMailer->sendEmail(
                ['email' => $user->email, 'name' => $nomDestinataire],
                $notification->titre,
                $htmlContent
            );

            $notification->update(['envoye_at' => now()]);
        } catch (\Throwable $e) {
            // On ne bloque jamais la création de la notification pour un
            // échec d'envoi email : la notification reste visible dans
            // l'app, seul l'email échoue. BrevoMailer logge déjà l'erreur
            // de son côté, on ajoute juste le contexte notification ici.
            Log::warning('Échec envoi email notification (Brevo)', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
