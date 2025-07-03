<?php
namespace App\Console\Commands;

use App\Models\Formation;
use App\Models\User;
use App\Notifications\EvaluationReminderDEC; // Ou le bon modèle qui contient la date de l'évaluation
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

// À créer

class SendEvaluationReminder extends Command
{
    protected $signature   = 'email:send-evaluation-reminders';
    protected $description = 'Envoie un rappel à la DEC pour les évaluations à venir';

    public function handle()
    {
        $now      = now()->startOfDay();     // Date du jour sans l'heure
        $todayAt8 = now()->setTime(8, 0, 0); // Aujourd'hui à 08h00

        // Dates cibles
        $dateJmoins5 = $now->copy()->addDays(5);
        $dateJmoins4 = $now->copy()->addDays(4);
        $dateJmoins3 = $now->copy()->addDays(3);
        $dateJmoins2 = $now->copy()->addDays(2);
        $dateJmoins1 = $now->copy()->addDay();
        $dateJ       = $now;

        // Vérifie si on est aujourd’hui à 08h
        $isTodayAt8 = now()->format('H:i') === '08:50';

        // Regrouper les dates cibles
        $dates = [
            'Jour J-5' => $dateJmoins5,
            'Jour J-4' => $dateJmoins4,
            'Jour J-3' => $dateJmoins3,
            'Jour J-2' => $dateJmoins2,
            'Jour J-1' => $dateJmoins1,
        ];

        if ($isTodayAt8) {
            $dates['Jour J'] = $dateJ;
        }

        foreach ($dates as $label => $targetDate) {
            $formations = Formation::whereDate('date_fin', $targetDate)->get();

            if ($formations->isEmpty()) {
                $this->info("Aucune formation à évaluer le {$targetDate->format('d/m/Y')} ({$label}).");
                continue;
            }

            foreach ($formations as $formation) {
                // 📌 Tu peux choisir l’un des deux blocs :

                // Bloc 1 : Utilisateurs avec rôle DEC
                /* $usersDEC = \App\Models\User::role('DEC')->get(); */

                // Bloc 2 : Emails fixes (décommente si tu préfères)
                $emails = array_filter([
                    /* 'ouly.toure@onfp.sn',
                    'amsatou.paye@onfp.sn', */
                    'lamine.badji@onfp.sn',
                    /* $formation?->ingenieur?->user?->email,
                    $formation?->onfpevaluateur?->email, */
                ]);

                if (! empty($emails)) {
                    $usersDEC = User::whereIn('email', $emails)->get();

                    Notification::send($usersDEC, new EvaluationReminderDEC($formation, $label));
                }

            }

            $this->info("Rappels envoyés pour les évaluations du {$targetDate->format('d/m/Y')} ({$label}).");
        }
    }
}
