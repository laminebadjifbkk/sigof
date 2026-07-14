<?php
/* namespace App\Console\Commands;

use App\Mail\EvaluationReminderDECMail;
use App\Models\Formation;
use App\Models\User; // Ou le bon modèle qui contient la date de l'évaluation
use App\Services\BrevoMailer;
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

                // Bloc 2 : Emails fixes (décommente si tu préfères)
                $emails = array_filter(array_merge([
                    'ouly.toure@onfp.sn',
                    'dado.toure@onfp.sn',
                    'amsatou.paye@onfp.sn',
                    'lamine.badji@onfp.sn',
                    'ramet.ndiaye@onfp.sn',
                    // 'bara.lo@onfp.sn',
                    'mamebigue.ciss@onfp.sn',
                    'gorgui.ndiaye@onfp.sn',
                    'mohamadou.soumare@onfp.sn',
                    's.fall@onfp.sn',
                    'elhadjigorgui.diouf@onfp.sn',
                    $formation?->ingenieur?->user?->email,
                ], $formation?->onfpevaluateurs?->pluck('email')->toArray() ?? []));

                if (! empty($emails)) {
                    $usersDEC = User::whereIn('email', $emails)->get();

                    // Notification::send($usersDEC, new EvaluationReminderDEC($formation, $label));
                    Mail::to($usersDEC)->send(new EvaluationReminderDECMail($formation, $label));
                }

            }

            $this->info("Rappels envoyés pour les évaluations du {$targetDate->format('d/m/Y')} ({$label}).");
        }
    }
} */

namespace App\Console\Commands;

use App\Models\Formation;
use Illuminate\Console\Command;
use App\Services\BrevoMailer;

class SendEvaluationReminder extends Command
{
    protected $signature   = 'email:send-evaluation-reminders';
    protected $description = 'Envoie un rappel à la DEC pour les évaluations à venir via Brevo';

    public function handle()
    {
        $now = now()->startOfDay();
        $currentTime = now()->format('H:i');

        $mailer = app(BrevoMailer::class);

        // Dates pour rappel
        $dates = [
            /* 'dans 5 jours' => $now->copy()->addDays(5),
            'dans 4 jours' => $now->copy()->addDays(4),
            'dans 3 jours' => $now->copy()->addDays(3), */
            'dans 2 jours' => $now->copy()->addDays(2),
            'demain'       => $now->copy()->addDay(),
        ];

        if ($currentTime === 8 && now()->minute === 10) {
            $dates["Aujourd'hui"] = $now;
        }

        foreach ($dates as $label => $targetDate) {

            $formations = Formation::with(['ingenieur.user', 'onfpevaluateurs'])
                ->whereDate('date_pv', $targetDate)
                ->get();

            if ($formations->isEmpty()) {
                $this->info("Aucune formation à évaluer le {$targetDate->format('d/m/Y')} ({$label}).");
                continue;
            }

            /* foreach ($formations as $formation) {

                // Destinataires
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
                    'ticana92@gmail.com',
                    'binamcheikhou@gmail.com',
                    'seckseynabou27@gmail.com',
                    'seynabou.seck@onfp.sn',
                    'mamebigue.ciss@onfp.sn',
                    'gorgui.ndiaye@onfp.sn',
                    'mohamadou.soumare@onfp.sn',
                    's.fall@onfp.sn',
                    'a.drame@onfp.sn',
                    'elhadjigorgui.diouf@onfp.sn',
                    'kanealkhalifa94@gmail.com',
                    'luneba.ab@gmail.com',
                    'fatou.ba@onfp.sn',
                    'gueyesuntech3@gmail.com',
                    'gibrile.faye@onfp.sn',
                    $formation?->ingenieur?->user?->email
                ])
                    ->merge($formation?->onfpevaluateurs?->pluck('email') ?? [])
                    ->filter()
                    ->unique()
                    ->values();

                if ($emails->isEmpty()) {
                    $this->warn("Pas de destinataires pour la formation ID {$formation->id}");
                    continue;
                }

                // 🔥 Génération HTML (IMPORTANT)
                $htmlContent = view(
                    'emails.evaluation-reminder-dec',
                    [
                        'formation' => $formation,
                        'label' => $label
                    ]
                )->render();

                // Sujet
                $subject = "Rappel évaluation : {$formation->intitule} ({$label})";

                foreach ($emails as $email) {

                    try {

                        $mailer->sendEmail(
                            [
                                'email' => $email,
                                'name' => 'Destinataire'
                            ],
                            $subject,
                            $htmlContent
                        );

                        $this->info("✔ Rappel envoyé à {$email}");
                    } catch (\Exception $e) {

                        $this->error("✖ Erreur {$email} : " . $e->getMessage());
                    }
                }
            } */

            foreach ($formations as $formation) {

                $emails = collect([
                    'lamine.badji@onfp.sn',
                    'ouly.toure@onfp.sn',
                    'dado.toure@onfp.sn',
                    'amsatou.paye@onfp.sn',
                    'SerigneMansourSy.FALL@onfp.sn',
                    'MaimounaGadio.AW@onfp.sn',
                    'ramet.ndiaye@onfp.sn',
                    'ticana92@gmail.com',
                    'binamcheikhou@gmail.com',
                    'seckseynabou27@gmail.com',
                    'seynabou.seck@onfp.sn',
                    'mamebigue.ciss@onfp.sn',
                    'gorgui.ndiaye@onfp.sn',
                    'mohamadou.soumare@onfp.sn',
                    's.fall@onfp.sn',
                    'a.drame@onfp.sn',
                    'elhadjigorgui.diouf@onfp.sn',
                    'kanealkhalifa94@gmail.com',
                    'luneba.ab@gmail.com',
                    'fatou.ba@onfp.sn',
                    'gueyesuntech3@gmail.com',
                    'gibrile.faye@onfp.sn',

                    // Chef de direction de l'ingénieur
                    data_get($formation, 'ingenieur.user.employee.direction.chef.user.email'),

                    // Ingénieur
                    data_get($formation, 'ingenieur.user.email'),
                ])
                    ->merge($formation->onfpevaluateurs?->pluck('email') ?? collect())
                    ->filter(fn($email) => filled($email))
                    ->unique()
                    ->sort()
                    ->values();

                if ($emails->isEmpty()) {
                    $this->warn("Aucun destinataire pour la formation #{$formation->id}");
                    continue;
                }

                $subject = sprintf(
                    'Rappel évaluation : %s (%s)',
                    $formation->intitule,
                    $label
                );

                $htmlContent = view('emails.evaluation-reminder-dec', [
                    'formation' => $formation,
                    'label'     => $label,
                ])->render();

                foreach ($emails as $email) {
                    try {
                        $mailer->sendEmail(
                            [
                                'email' => $email,
                                'name'  => 'Destinataire',
                            ],
                            $subject,
                            $htmlContent
                        );

                        $this->info("✔ {$email}");
                    } catch (\Throwable $e) {
                        $this->error("✖ {$email} : {$e->getMessage()}");
                    }
                }
            }
        }

        $this->info("Processus terminé via Brevo.");

        return Command::SUCCESS;
    }
}
