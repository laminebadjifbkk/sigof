<?php
namespace App\Console\Commands;

use App\Mail\EvaluationReminderDECMail;
use App\Models\Formation;
use App\Models\User; // Ou le bon modèle qui contient la date de l'évaluation
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

// À créer

class SendEvaluationReminder extends Command
{
    protected $signature   = 'email:send-evaluation-reminders';
    protected $description = 'Envoie un rappel à la DEC pour les évaluations à venir';

    public function handle()
    {
        $now      = now()->startOfDay();     // Date du jour sans l'heure
        $todayAt8 = now()->setTime(8, 20, 0); // Aujourd'hui à 08h20

        // Dates cibles
        $dateJmoins5 = $now->copy()->addDays(5);
        $dateJmoins4 = $now->copy()->addDays(4);
        $dateJmoins3 = $now->copy()->addDays(3);
        $dateJmoins2 = $now->copy()->addDays(2);
        $dateJmoins1 = $now->copy()->addDay();
        $dateJ       = $now;

        // Vérifie si on est aujourd’hui à 08h
        $isTodayAt8 = now()->format('H:i') === '08:20';

        // Regrouper les dates cibles
        $dates = [
            'dans 5 jours' => $dateJmoins5,
            'dans 4 jours' => $dateJmoins4,
            'dans 3 jours' => $dateJmoins3,
            'dans 2 jours' => $dateJmoins2,
            'demain'       => $dateJmoins1,
        ];

        if ($isTodayAt8) {
            $dates['Aujourd\'hui'] = $dateJ;
        }

        foreach ($dates as $label => $targetDate) {
            $formations = Formation::whereDate('date_pv', $targetDate)->get();

            if ($formations->isEmpty()) {
                $this->info("Aucune formation à évaluer le {$targetDate->format('d/m/Y')} ({$label}).");
                continue;
            }

            foreach ($formations as $formation) {
                // 📌 Tu peux choisir l’un des deux blocs :

                // Bloc 1 : Utilisateurs avec rôle DEC
                /* $usersDEC = \App\Models\User::role('DEC')->get(); */

                // Bloc 2 : Emails fixes (décommente si tu préfères)
                $emails = array_filter(array_merge([
                    'ouly.toure@onfp.sn',
                    'dado.toure@onfp.sn',
                    'amsatou.paye@onfp.sn',
                    'lamine.badji@onfp.sn',
                    'ramet.ndiaye@onfp.sn',
                    /* 'bara.lo@onfp.sn', */
                    'mamebigue.ciss@onfp.sn',
                    'gorgui.ndiaye@onfp.sn',
                    'mohamadou.soumare@onfp.sn',
                    's.fall@onfp.sn',
                    'elhadjigorgui.diouf@onfp.sn',
                    $formation?->ingenieur?->user?->email,
                ], $formation?->onfpevaluateurs?->pluck('email')->toArray() ?? []));

                if (! empty($emails)) {
                    $usersDEC = User::whereIn('email', $emails)->get();

                    /* Notification::send($usersDEC, new EvaluationReminderDEC($formation, $label)); */
                    Mail::to($usersDEC)->send(new EvaluationReminderDECMail($formation, $label));
                }

            }

            $this->info("Rappels envoyés pour les évaluations du {$targetDate->format('d/m/Y')} ({$label}).");
        }
    }
}
