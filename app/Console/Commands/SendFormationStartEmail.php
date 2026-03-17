<?php
/* namespace App\Console\Commands;

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
            // $formations = Formation::whereDate('date_debut', $targetDate)->get(); 

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
                    //'bara.lo@onfp.sn', 
                    'SerigneMansourSy.FALL@onfp.sn',
                    //'aissatou.deme@tresor.gouv.sn',
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
} */

namespace App\Console\Commands;

use App\Models\Formation;
use App\Services\BrevoMailer;
use Illuminate\Console\Command;

class SendFormationStartEmail extends Command
{
    protected $signature = 'notify:formation-start';
    protected $description = 'Notifier le démarrage des formations';

    public function handle()
    {
        $now = now();

        $dates = [
            'Demain' => $now->copy()->addDay(),
        ];

        // Entre 08h00 et 08h59 on envoie aussi pour aujourd'hui
        if ($now->hour === 8) {
            $dates["Aujourd'hui"] = $now;
        }

        $mailer = app(BrevoMailer::class);

        foreach ($dates as $label => $targetDate) {

            $formations = Formation::with('ingenieur.user')
                ->whereDate('date_debut', $targetDate->toDateString())
                ->get();

            if ($formations->isEmpty()) {

                $this->warn(
                    "Aucune formation ne démarre le "
                        . $targetDate->format('d/m/Y')
                        . " ({$label})"
                );

                continue;
            }

            foreach ($formations as $formation) {

                $emails = collect([
                    'lamine.badji@onfp.sn',
                    'ouly.toure@onfp.sn',
                    'dado.toure@onfp.sn',
                    'amsatou.paye@onfp.sn',
                    //'bara.lo@onfp.sn', 
                    'SerigneMansourSy.FALL@onfp.sn',
                    //'aissatou.deme@tresor.gouv.sn',
                    'MaimounaGadio.AW@onfp.sn',
                    'ramet.ndiaye@onfp.sn',
                    'seckseynabou27@gmail.com',
                    'seynabou.seck@onfp.sn',
                    'mamebigue.ciss@onfp.sn',
                    'gorgui.ndiaye@onfp.sn',
                    'mohamadou.soumare@onfp.sn',
                    's.fall@onfp.sn',
                    'a.drame@onfp.sn',
                    'elhadjigorgui.diouf@onfp.sn',
                    $formation?->ingenieur?->user?->email
                ])
                    ->filter()
                    ->unique()
                    ->values();

                $htmlContent = view(
                    'emails.formation-start',
                    [
                        'formation' => $formation,
                        'label' => $label
                    ]
                )->render();

                foreach ($emails as $email) {

                    try {

                        $moduleName = $formation?->module?->name
                            ?? $formation?->collectivemodule?->module
                            ?? 'Module non défini';

                        $subject = "Démarrage formation : {$moduleName} ({$label})";

                        $mailer->sendEmail(
                            [
                                'email' => $email,
                                'name' => 'Destinataire'
                            ],
                            $subject,
                            $htmlContent
                        );

                        $this->info("✔ Email envoyé à {$email}");
                    } catch (\Exception $e) {

                        $this->error("✖ Erreur envoi {$email} : " . $e->getMessage());
                    }
                }
            }

            $this->line(
                "Notifications traitées pour "
                    . $targetDate->format('d/m/Y')
                    . " ({$label})"
            );
        }

        $this->info("Commande terminée.");

        return Command::SUCCESS;
    }
}
