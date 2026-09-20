<?php

namespace App\Console\Commands;

use App\Models\OnfpActivite;
use App\Models\OnfpActiviteNotification;
use App\Services\OnfpNotificationService;
use Illuminate\Console\Command;

class NotifierEcheancesActivites extends Command
{
    protected $signature = 'onfp:notifier-echeances';

    protected $description = "Notifie les responsables et suiveurs des activités dont l'échéance approche";

    public function handle(OnfpNotificationService $service): int
    {
        $joursAvant = (int) config('onfp.notification_jours_avant_echeance', 3);
        $dateCible = now()->addDays($joursAvant)->toDateString();

        $activites = OnfpActivite::query()
            ->whereDate('date_fin_prevue', $dateCible)
            ->whereNotIn('statut', ['terminee', 'annulee'])
            ->with(['responsables', 'suiveurs'])
            ->get();

        $compteur = 0;

        foreach ($activites as $activite) {
            // Évite les doublons si la commande tourne plusieurs fois le même jour
            // pour la même activité (ex. relance manuelle après un premier passage).
            $dejaNotifieAujourdhui = OnfpActiviteNotification::query()
                ->where('activite_id', $activite->id)
                ->where('type', OnfpActiviteNotification::TYPE_ECHEANCE_PROCHE)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($dejaNotifieAujourdhui) {
                continue;
            }

            $employeeIds = $activite->responsables->pluck('employee_id')
                ->merge($activite->suiveurs->pluck('employee_id'))
                ->unique()
                ->values()
                ->all();

            if (empty($employeeIds)) {
                continue;
            }

            $service->notifierEmployes(
                $employeeIds,
                $activite->id,
                OnfpActiviteNotification::TYPE_ECHEANCE_PROCHE,
                "Échéance proche : {$activite->titre}",
                "L'activité « {$activite->titre} » ({$activite->reference}) doit se terminer le "
                    . $activite->date_fin_prevue->format('d/m/Y')
                    . " (dans {$joursAvant} jours).",
                'urgente'
            );

            $compteur++;
        }

        $this->info("{$compteur} activité(s) notifiée(s) pour échéance proche.");

        return self::SUCCESS;
    }
}
