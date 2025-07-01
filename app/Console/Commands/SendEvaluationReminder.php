<?php
namespace App\Console\Commands;

use App\Models\Formation;
use App\Notifications\EvaluationReminderDEC; // Ou le bon modèle qui contient la date de l'évaluation
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

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
        $isTodayAt8 = now()->format('H:i') === '08:00';

        // Regrouper les dates cibles
        $dates = [
            'J-5' => $dateJmoins5,
            'J-4' => $dateJmoins4,
            'J-3' => $dateJmoins3,
            'J-2' => $dateJmoins2,
            'J-1' => $dateJmoins1,
        ];

        if ($isTodayAt8) {
            $dates['J'] = $dateJ;
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
                $usersDEC = \App\Models\User::role('DEC')->get();

                // Bloc 2 : Emails fixes (décommente si tu préfères)
                /*
            $emails = [
                'dec1@example.com',
                'dec2@example.com',
                'responsable@onfp.sn',
            ];
            $usersDEC = User::whereIn('email', $emails)->get();
            */
                Notification::send($usersDEC, new EvaluationReminderDEC($formation, $label));

            }

            $this->info("Rappels envoyés pour les évaluations du {$targetDate->format('d/m/Y')} ({$label}).");
        }
    }
}
