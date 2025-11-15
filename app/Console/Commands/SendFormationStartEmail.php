<?php
namespace App\Console\Commands;

use App\Mail\FormationStartNotification;
use App\Models\Formation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFormationStartEmail extends Command
{
    protected $signature   = 'notify:formation-start';
    protected $description = 'Envoie un e-mail pour informer du démarrage d\'une formation';

    public function handle()
    {
        // Utilisation explicite du fuseau horaire Dakar
        /* $now      = Carbon::now('Africa/Dakar')->startOfDay();
        $todayAt8 = Carbon::now('Africa/Dakar')->setTime(8, 0, 0);

        $dateToday    = $now;
        $dateTomorrow = $now->copy()->addDay();

        // Vérifie l’heure actuelle au fuseau "Africa/Dakar"
        $isTodayAt8 = Carbon::now('Africa/Dakar')->format('H:i') === '08:00';

        // Initialisation des dates à traiter
        $dates = [];

        if ($isTodayAt8) {
            $dates["Aujourd'hui"] = $dateToday;
        }

        $dates["Demain"] = $dateTomorrow; */

        $now = now(); // une seule fois

        $startOfDay = $now->copy()->startOfDay();      // Date du jour à minuit
        $todayAt800 = $now->copy()->setTime(8, 35, 0); // Aujourd'hui à 08h35

        // Dates cibles
        $dateJmoins1 = $now->copy()->addDay();
        $dateJ       = $now;

        // Vérifie si on est aujourd’hui à 08h
        $isTodayAt8 = now()->format('H:i') === '08:35';

        // Regrouper les dates cibles
        $dates = [
            'Demain' => $dateJmoins1,
        ];

        if ($isTodayAt8) {
            $dates['Aujourd\'hui'] = $dateJ;
        }

        foreach ($dates as $label => $targetDate) {
            $targetDateFormatted = $targetDate->format('Y-m-d');

            $formations = Formation::whereDate('date_debut', $targetDateFormatted)->get();
            /* $formations = Formation::whereDate('date_debut', $targetDate)->get(); */

            if ($formations->isEmpty()) {
                $this->info("Aucune formation ne démarre le {$targetDate->format('d/m/Y')} ({$label}).");
                continue;
            }

            foreach ($formations as $formation) {
                // Emails des destinataires
                $emails = array_filter(array_merge([
                    'ouly.toure@onfp.sn',
                    'dado.toure@onfp.sn',
                    'amsatou.paye@onfp.sn',
                    'lamine.badji@onfp.sn',
                    /* 'bara.lo@onfp.sn', */
                    'SerigneMansourSy.FALL@onfp.sn',
                    /* 'aissatou.deme@tresor.gouv.sn', */
                    'MaimounaGadio.AW@onfp.sn',
                    'ramet.ndiaye@onfp.sn',
                    'seckseynabou27@gmail.com',
                    'seynabou.seck@onfp.sn',
                    'mamebigue.ciss@onfp.sn',
                    'gorgui.ndiaye@onfp.sn',
                    'mohamadou.soumare@onfp.sn',
                    's.fall@onfp.sn',
                    'elhadjigorgui.diouf@onfp.sn',
                    $formation?->ingenieur?->user?->email,
                ]));

                foreach ($emails as $email) {
                    Mail::to($email)->send(new FormationStartNotification($formation, $label));
                }
            }

            $this->info("Notifications envoyées pour les formations qui démarrent le {$targetDate->format('d/m/Y')} ({$label}).");
        }
    }
}
