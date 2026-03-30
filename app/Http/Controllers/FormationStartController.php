<?php

// app/Http/Controllers/FormationStartController.php

/* namespace App\Http\Controllers;

use App\Mail\FormationStartNotification;
use App\Models\Formation;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class FormationStartController extends Controller
{
    public function send(Formation $formation)
    {
        Alert::warning("En raison de soucis techniques auprès de SENUM SA, l’envoi automatique des mails de démarrage est temporairement indisponible. 
Nous vous prions de bien vouloir les transmettre directement via votre messagerie, le temps que la situation soit rétablie.");

        return redirect()->back();

        // Liste des destinataires fixes + l’ingénieur lié
        $emails = array_filter(array_merge([
            'ouly.toure@onfp.sn',
            'dado.toure@onfp.sn',
            'amsatou.paye@onfp.sn',
            'lamine.badji@onfp.sn',
            // 'bara.lo@onfp.sn',
            'SerigneMansourSy.FALL@onfp.sn',
            // 'aissatou.deme@tresor.gouv.sn',
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

        // Envoi du mail à chaque destinataire
        foreach ($emails as $email) {
            Mail::to($email)->send(new FormationStartNotification($formation, "Aujourd'hui"));
        }

        // Exemple : activer la formation
        $formation->statut = 'En cours';
        $formation->save();

        return back()->with('success', 'Mails envoyés avec succès aux bénéficiaires.');
    }
} */

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Services\BrevoMailer;
use RealRashid\SweetAlert\Facades\Alert;

class FormationStartController extends Controller
{
    public function send(Formation $formation)
    {
        $mailer = app(BrevoMailer::class);

        $label = "";

        $defaultEmails = [
            'lamine.badji@onfp.sn',
            /* 'ouly.toure@onfp.sn',
            'dado.toure@onfp.sn',
            'amsatou.paye@onfp.sn',
            'SerigneMansourSy.FALL@onfp.sn',
            'MaimounaGadio.AW@onfp.sn',
            'ramet.ndiaye@onfp.sn',
            'seckseynabou27@gmail.com',
            'seynabou.seck@onfp.sn',
            'mamebigue.ciss@onfp.sn',
            'gorgui.ndiaye@onfp.sn',
            'mohamadou.soumare@onfp.sn',
            's.fall@onfp.sn',
            'elhadjigorgui.diouf@onfp.sn', */
        ];

        $emails = collect($defaultEmails)
            ->merge([
                data_get($formation, 'ingenieur.user.email'),
                data_get($formation, 'ingenieur.user.employee.chef.user.email'),
            ])
            ->filter()
            ->map(fn($email) => strtolower(trim($email)))
            ->unique()
            ->values();

        $moduleName = data_get($formation, 'module.name')
            ?? data_get($formation, 'collectivemodule.module')
            ?? 'Module non défini';

        $htmlContent = view('emails.formation-start', [
            'formation' => $formation,
            'label' => $label
        ])->render();

        foreach ($emails as $email) {
            try {

                $subject = "Démarrage formation : {$moduleName} {$label}";

                $mailer->sendEmail(
                    [
                        'email' => $email,
                        'name' => 'Destinataire'
                    ],
                    $subject,
                    $htmlContent
                );
            } catch (\Exception $e) {
                logger()->error("Erreur envoi mail {$email} : " . $e->getMessage());
            }
        }

        $formation->update(['statut' => 'En cours']);

        Alert::success('Succès', 'Mails envoyés avec succès aux bénéficiaires.');
        return redirect()->back();
    }
}
