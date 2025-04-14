<?php
namespace App\Http\Controllers;

use App\Mail\NotificationRejetIndividuelle;
use App\Mail\ValidationDemandeIndividuelleNotification;
use App\Models\Individuelle;
use App\Models\Validationindividuelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class ValidationIndividuelleController extends Controller
{
    /* public function update($id)
    {
        $individuelle = Individuelle::findOrFail($id);

        foreach (Auth::user()->roles as $role) {
            if (! empty($role?->name) && ($role?->name != 'super-admin')) {
                if ($individuelle->statut == 'Attente') {
                    Alert::warning('Désolez !', 'demande déjà validée');
                } elseif ($individuelle->statut == 'programmer') {
                    Alert::warning('Désolez !', 'demande déjà programmée');
                } elseif ($individuelle->statut == 'Retenue') {
                    Alert::warning('Désolez !', 'demande déjà traitée');
                } elseif ($individuelle->statut == "Terminée") {
                    Alert::warning('Désolez !', 'demandeur déjà formé');
                } elseif ($individuelle->statut == 'Rejetée') {
                    Alert::warning('Désolez !', 'demandeur déjà rejetée');
                } else {
                    Alert::warning('Désolez !', 'action impossible');
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
        $toEmail     = $individuelle?->user?->email;
        $toUserName  = 'Bonjour ! ' . $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name;
        $safeMessage = "Votre demande de formation en <b><i>" . ($individuelle->module->name ?? 'cette formation') .
            "</i></b> a été retenue. Vous pourrez être contacté à tout moment pour le démarrage de la formation.
        Merci pour votre patience ; nous mettons tout en œuvre afin qu’elle puisse débuter dans les plus brefs délais.";

        // Ajouter le lien vers le site
        /* $siteUrl = config('app.url'); // ou une URL en dur comme 'https://sigof.onfp.sn' */
        $siteUrl = 'https://sigof.onfp.sn'; // ou une URL en dur comme 'https://sigof.onfp.sn'
        $safeMessage .= "<p>Consultez notre plateforme : <a href=\"$siteUrl\">$siteUrl</a></p>";

        $subject = 'Notification de validation !';
        $message = strip_tags($safeMessage, '<b><i><p><a>');

        Mail::to($toEmail)->send(new ValidationDemandeIndividuelleNotification($message, $subject, $toEmail, $toUserName));

        return redirect()->back();

    }

    public function destroy(Request $request, $id)
    {
        $this->validate($request, [
            "motif" => "required|string",
        ]);

        /* $individuelle = Individuelle::findOrFail($id);

        if ($individuelle->statut == 'Rejetée') {
            Alert::warning('Désolez !', 'demande déjà rejetée');
        } elseif ($individuelle->statut == 'programmer') {
            Alert::warning('Désolez !', 'demande déjà programmée');
        } elseif ($individuelle->statut == 'Attente') {
            Alert::warning('Désolez !', 'demande déjà traitée');
        } elseif ($individuelle->statut == 'Retenue') {
            Alert::warning('Désolez !', 'demande déjà traitée');
        } elseif ($individuelle->statut == "Terminée") {
            Alert::warning('Désolez !', 'demandeur déjà formé');
        } else {
            $individuelle->update([
                'statut'      => 'Rejetée',
                'canceled_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
            ]);

            $individuelle->save();

            $validated_by = new Validationindividuelle([
                'validated_id'     => Auth::user()->id,
                'action'           => 'Rejetée',
                'motif'            => $request->input('motif'),
                'individuelles_id' => $individuelle->id,
            ]);

            $validated_by->save();

            Alert::success('Fait ! ', 'demande rejetée');
        } */

        $individuelle = Individuelle::findOrFail($id);
        $statut       = $individuelle->statut;

        // Bloquer certains statuts uniquement pour les non-super-admins
        if (! auth()->user()->hasRole('super-admin')) {
            $messages = [
                'Rejetée'    => 'demande déjà rejetée',
                'programmer' => 'demande déjà programmée',
                'Attente'    => 'demande déjà traitée',
                'Retenue'    => 'demande déjà traitée',
                'Terminée'   => 'demandeur déjà formé',
            ];

            if (array_key_exists($statut, $messages)) {
                Alert::warning('Désolé !', $messages[$statut]);
                return redirect()->back();
            }
        }

        // Continuer le rejet (autorisé pour super-admin ou cas autorisé)
        $individuelle->update([
            'statut'      => 'Rejetée',
            'canceled_by' => Auth::user()->firstname . ' ' . Auth::user()->name,
        ]);

        $validation = Validationindividuelle::create([
            'validated_id'     => Auth::user()->id,
            'action'           => 'Rejetée',
            'motif'            => $request->input('motif'),
            'individuelles_id' => $individuelle->id,
        ]);

        // Envoi de mail
        $toEmail    = $individuelle?->user?->email;
        $toUserName = 'Bonjour ' . $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name . ',';

        $safeMessage = "Nous vous informons, avec regret, que votre demande de formation en <b><i>" .
            ($individuelle->module->name ?? 'cette formation') .
            "</i></b> n’a pas été retenue pour le motif suivant : <b><i>" .
            ($request->input('motif') ?? 'non précisé') .
            "</i></b>.<br><br>Nous vous invitons à vous connecter à votre compte afin d’apporter les ajustements nécessaires.
    Merci pour votre compréhension. Nous restons disponibles et espérons recevoir les modifications dans les plus brefs délais.";

// Ajouter le lien vers le site
        $siteUrl = 'https://sigof.onfp.sn'; // ou mettre en dur : 'https://sigof.onfp.sn'
        $safeMessage .= "<p>Consultez notre plateforme : <a href=\"$siteUrl\">$siteUrl</a></p>";

        $subject = 'Notification de rejet de votre demande de formation';
        $message = strip_tags($safeMessage, '<b><i><p><a><br>');

        Mail::to($toEmail)->send(new NotificationRejetIndividuelle($message, $subject, $toEmail, $toUserName));

        return redirect()->back();

        /* Alert::success('Succes !', 'demande rejetée avec succès');
        return redirect()->back(); */
    }
}
