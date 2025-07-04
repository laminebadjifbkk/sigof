<?php
namespace App\Http\Controllers;

use App\Models\Individuelle;
use App\Models\Validationindividuelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ValidationIndividuelleController extends Controller
{
    /* public function update($id)
    {
        $individuelle = Individuelle::findOrFail($id);

        foreach (Auth::user()->roles as $role) {
            if (! empty($role?->name) && ($role?->name != 'super-admin')) {
                if ($individuelle->statut == 'Attente') {
                    Alert::warning('Désolé !', 'demande déjà validée');
                } elseif ($individuelle->statut == 'programmer') {
                    Alert::warning('Désolé !', 'demande déjà programmée');
                } elseif ($individuelle->statut == 'Retenue') {
                    Alert::warning('Désolé !', 'demande déjà traitée');
                } elseif ($individuelle->statut == "Terminée") {
                    Alert::warning('Désolé !', 'demandeur déjà formé');
                } elseif ($individuelle->statut == 'Rejetée') {
                    Alert::warning('Désolé !', 'demandeur déjà rejetée');
                } else {
                    Alert::warning('Désolé !', 'action impossible');
                }
            } else {
                $individuelle->update([
                    'statut'       => 'Attente',
                    'validated_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
                ]);

                $individuelle->save();

                $validated_by = new Validationindividuelle([
                    'validated_id'     => Auth::user()->id,
                    'action'           => 'Attente',
                    'individuelles_id' => $individuelle->id,
                ]);

                $validated_by->save();

                $toEmail     = $individuelle?->user?->email;
                $toUserName  = 'Félicitations ! ' . $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name;
                $safeMessage = "Votre demande de formation en <b><i>" . ($individuelle->module->name ?? 'cette formation') .
                    "</i></b> a été retenue. Vous pouvez désormais bénéficier de nos offres de formations.";
                $subject = 'Notification de validation !';
                $message = strip_tags($safeMessage, '<b><i><p>'); // Autorise seulement <b>, <i>, et <p>
                Mail::to($toEmail)->send(new ValidationDemandeIndividuelleNotification($message, $subject, $toEmail, $toUserName));

            }
        }
        return redirect()->back();
    } */

    public function update($id)
    {
        // cette méthode est utilisée pour valider une demande de formation individuelle
        // est désormés transférer à la function validationIndividuelle dans le controller IndividuelleController
        $individuelle = Individuelle::findOrFail($id);

        // Si l'utilisateur n'est pas super-admin
        if (! Auth::user()->hasRole('super-admin')) {
            switch ($individuelle->statut) {
                case 'Attente':
                    Alert::warning('Désolé !', 'demande déjà validée');
                    break;
                case 'programmer':
                    Alert::warning('Désolé !', 'demande déjà programmée');
                    break;
                case 'Retenue':
                    Alert::warning('Désolé !', 'demande déjà traitée');
                    break;
                case 'Conforme':
                    $messagestatutdemande = 'conforme';
                    $suiteMessage         = true;
                    break;
                case 'Non conforme':
                    $messagestatutdemande = 'non conforme car : ';
                    $suiteMessage         = true;
                    break;
                case 'Terminée':
                    Alert::warning('Désolé !', 'demandeur déjà formé');
                    break;
                case 'Rejetée':
                    Alert::warning('Désolé !', 'demandeur déjà rejeté');
                    break;
                default:
                    Alert::warning('Désolé !', 'action impossible');
            }

            return redirect()->back(); // On arrête ici pour les non-super-admins
        }

        // Si super-admin, on poursuit la validation
        $individuelle->update([
            'statut'       => 'Attente',
            'validated_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
        ]);

        Validationindividuelle::create([
            'validated_id'     => Auth::user()->id,
            'action'           => 'Attente',
            'individuelles_id' => $individuelle->id,
        ]);

        // Envoi de mail
        /* $toEmail     = $individuelle?->user?->email;
        $toUserName  = 'Bonjour ! ' . $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name;
        $safeMessage = "Votre demande de formation en <b><i>" . ($individuelle->module->name ?? 'cette formation') .
            "</i></b> a été retenue. Vous pourrez être contacté à tout moment pour le démarrage de la formation.
        Merci pour votre patience ; nous mettons tout en œuvre afin qu’elle puisse débuter dans les plus brefs délais.";

        $safeMessage .= "<p>Consultez notre plateforme : <a href=\"$siteUrl\">$siteUrl</a></p>";

        $subject = 'Notification de validation !';
        $message = strip_tags($safeMessage, '<b><i><p><a>');

        Mail::to($toEmail)->send(new ValidationDemandeIndividuelleNotification($message, $subject, $toEmail, $toUserName)); */

        return redirect()->back();

    }

    public function destroy(Request $request, $id)
    {

        /*  $statut = $request->statut;

        if ($statut !== 'Attente' || $statut !== 'Conforme') {
            $request->validate([
                'motif' => 'required|string',
            ]);
        } */

        $request->validate([
            'motif' => $request->statut !== 'Conforme' ? 'required|string' : 'nullable|string',
        ]);

        $individuelle = Individuelle::findOrFail($id);
        $statut       = $individuelle->statut;

        // Bloquer certains statuts uniquement pour les non-super-admins
        if (! auth()->user()->hasAnyRole(['super-admin', 'Ingenieur'])) {
            $messages = [
                'Rejetée'      => 'demande déjà rejetée',
                'Programmer'   => 'demande déjà programmée',
                'Attente'      => 'demande déjà traitée',
                'Retenue'      => 'demande déjà traitée',
                'Terminée'     => 'demandeur déjà formé',
                'Former'       => 'demandeur déjà formé',
                'À corriger'   => 'demandeur déjà traitée',
                'Non validé'   => 'demandeur déjà traitée',
                'Conforme'     => 'demandeur déjà traitée',
                'Non conforme' => 'demandeur déjà traitée',
            ];

            if (array_key_exists($statut, $messages)) {
                Alert::warning('Désolé !', $messages[$statut]);
                return redirect()->back();
            }
        }

        // Continuer le rejet (autorisé pour super-admin ou cas autorisé)
        
        $motif = $request->input('motif') ?? $request->statut;

        $individuelle->update([
            'statut'      => $request->statut,
            'canceled_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
        ]);

        $validation = Validationindividuelle::create([
            'validated_id'     => Auth::user()->id,
            'action'           => $request->statut,
            'motif'            => $motif,
            'individuelles_id' => $individuelle->id,
        ]);

        // Envoi de mail
        /*  $toEmail = $individuelle?->user?->email;

        if (empty($toEmail)) {
            Alert::warning('Désolé !', 'Aucun email trouvé pour l\'utilisateur.');
            return redirect()->back();
        }

        $toUserName = 'Bonjour ' . $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name . ',';
        $statutdemande = $request?->statut;

        switch ($statutdemande) {
            case 'Attente':
                $messagestatutdemande = 'validée et est en attente de formation';
                $suiteMessage         = false;
                break;
            case 'À corriger':
                $messagestatutdemande = 'en attente de corrections pour le motif suivant';
                $suiteMessage         = true;
                break;
            case 'Conforme':
                $messagestatutdemande = 'conforme';
                $suiteMessage         = true;
                break;
            case 'Non conforme':
                $messagestatutdemande = 'non conforme car : ';
                $suiteMessage         = true;
            default:
                $messagestatutdemande = 'en cours de traitement';
                $suiteMessage         = false;
                break;
        }

        $moduleName = $individuelle->module->name ?? 'cette formation';
        $motif      = $request->input('motif') ?? 'non précisé';

        $safeMessage = "Nous vous informons que votre demande de formation pour le module <b><i>{$moduleName}</i></b> est <b>{$messagestatutdemande}</b>";

        if ($suiteMessage) {
            $safeMessage .= " :<br><b><i>{$motif}</i></b>";
            $safeMessage .= ".<br><br>
    Nous vous invitons à vous connecter à votre compte afin de consulter les détails et, le cas échéant, apporter les ajustements nécessaires.<br><br>
    Merci pour votre compréhension. Nous restons à votre disposition pour toute information complémentaire.";
        } else {
            $safeMessage .= ".<br><br>
    Merci pour votre compréhension. Nous restons à votre disposition pour toute information complémentaire.";
        }

// Ajouter le lien vers le site
        $siteUrl = 'https://sigof.onfp.sn'; // ou mettre en dur : 'https://sigof.onfp.sn'
        $safeMessage .= "<p>Consultez notre plateforme : <a href=\"$siteUrl\">$siteUrl</a></p>";

        $moduleName = $individuelle->module->name ?? 'votre module de formation';
        $statut     = strtolower($request?->statut);

        switch ($statut) {
            case 'Attente':
                $subject = "Votre demande de formation en « {$moduleName} » est acceptée";
                break;
            case 'À corriger':
                $subject = "Des ajustements sont requis pour votre demande en « {$moduleName} »";
                break;
            case 'Non validé':
                $subject = "Votre demande de formation en « {$moduleName} » n'a pas été validée";
                break;
            case 'Conforme':
                $subject = "Votre demande de formation en « {$moduleName} » est conforme";
                break;
            case 'Non conforme':
                $subject = "Votre demande de formation en « {$moduleName} » est non conforme";
            default:
                $subject = "Mise à jour concernant votre demande de formation";
                break;
        }

        $message = strip_tags($safeMessage, '<b><i><p><a><br>');

        Mail::to($toEmail)->send(new NotificationRejetIndividuelle($message, $subject, $toEmail, $toUserName)); */

        return redirect()->back();
    }
}
