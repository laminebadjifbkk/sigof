<?php
namespace App\Http\Controllers;

use App\Mail\NotificationRejetCollective;
use App\Models\Collective;
use App\Models\Validationcollective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class ValidationcollectiveController extends Controller
{
    public function update($id)
    {
        $collective = Collective::findOrFail($id);
        if ($collective->statut_demande == 'Attente') {
            Alert::warning('Désolez !', 'demande déjà validée');
        } elseif ($collective->statut_demande == 'Retenue') {
            Alert::warning('Désolez !', 'demande déjà retenue');
        } else {
            $collective->update([
                'statut_demande' => 'Attente',
                'validated_by'   => Auth::user()->firstname . ' ' . Auth::user()->name,
            ]);

            $collective->save();

            $validated_by = new Validationcollective([
                'validated_id'   => Auth::user()->id,
                'action'         => 'Attente',
                'collectives_id' => $collective->id,
            ]);

            $validated_by->save();

            Alert::success('Félicitation !', 'demande acceptée');
        }

        /* return redirect()->back()->with("status", "Demande validée"); */
        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        /* $this->validate($request, [
            "motif" => "required|string",
        ]);

        $collective   = Collective::findOrFail($id);

        if ($collective->statut_demande == 'Rejetée') {
            Alert::warning('Désolez !', 'demande déjà rejetée');
        }  elseif ($collective->statut_demande == 'Retenue') {
            Alert::warning('Désolez !', 'demande déjà retenue');
        } else {
            $collective->update([
                'statut_demande'                => 'Rejetée',
                'canceled_by'           =>  Auth::user()->firstname . ' ' . Auth::user()->name,
            ]);

            $collective->save();

            $validated_by = new Validationcollective([
                'validated_id'       =>      Auth::user()->id,
                'action'             =>      'Rejetée',
                'motif'              =>      $request->input('motif'),
                'collectives_id'   =>      $collective->id
            ]);

            $validated_by->save();

            Alert::success('Fait ! ', 'demande rejetée');
        }

        return redirect()->back(); */

        $this->validate($request, [
            "motif" => "required|string",
        ]);

        $collective = Collective::findOrFail($id);
        $statut     = $collective->statut_demande;

        // Bloquer certains statuts uniquement pour les non-super-admins
        if (! auth()->user()->hasRole('super-admin')) {
            $messages = [
                'Rejetée'    => 'demande déjà rejetée',
                'Programmer' => 'demande déjà programmée',
                'Attente'    => 'demande déjà traitée',
                'Retenue'    => 'demande déjà traitée',
                'Terminée'   => 'demandeur déjà formé',
                'Former'     => 'demandeur déjà formé',
                'À corriger' => 'demandeur déjà traitée',
                'Non validé' => 'demandeur déjà traitée',
            ];

            if (array_key_exists($statut, $messages)) {
                Alert::warning('Désolé !', $messages[$statut]);
                return redirect()->back();
            }
        }

        // Continuer le rejet (autorisé pour super-admin ou cas autorisé)
        $collective->update([
            'statut_demande' => $request?->statut,
            'canceled_by'    => Auth::user()->firstname . ' ' . Auth::user()->name,
        ]);

        $validation = Validationcollective::create([
            'validated_id'   => Auth::user()->id,
            'action'         => $request?->statut,
            'motif'          => $request->input('motif'),
            'collectives_id' => $collective->id,
        ]);

        // Envoi de mail
        /* $toEmail = $collective?->user?->email; */
        $toEmail = [$collective?->user?->email, $collective?->email];

        if (empty($toEmail)) {
            Alert::warning('Désolé !', 'Aucun email trouvé pour l\'utilisateur.');
            return redirect()->back();
        }
        $toUserName = 'Bonjour ' . $collective?->user?->civilite . ' ' . $collective?->user?->firstname . ' ' . $collective?->user?->name . ',';

        foreach ($collective->collectivemodules as $key => $value) {

            $statutdemande = $value?->statut;

            switch ($statutdemande) {
                case 'Attente':
                    $messagestatutdemande = 'validée et est en attente de formation';
                    $suiteMessage         = false;
                    break;
                case 'À corriger':
                    $messagestatutdemande = 'en attente de corrections pour le motif suivant';
                    $suiteMessage         = true;
                    break;
                case 'Validé':
                    $messagestatutdemande = 'validée et est en attente de formation';
                    $suiteMessage         = true;
                    break;
                case 'Non validé':
                    $messagestatutdemande = 'Non validée pour le motif suivant';
                    $suiteMessage         = true;
                    break;
                default:
                    $messagestatutdemande = 'en cours de traitement';
                    $suiteMessage         = false;
                    break;
            }

            $moduleName = $value->module ?? 'votre module';
            $motif      = $value->motif ?? 'non précisé';

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

            /* $subject = 'Notification de rejet de votre demande de formation';
        $message = strip_tags($safeMessage, '<b><i><p><a><br>'); */

            $statut = strtolower($request?->statut);

            switch ($statut) {
                case 'Validée':
                    $subject = "Votre demande de formation en « {$moduleName} » est validée";
                    break;
                case 'À corriger':
                    $subject = "Des ajustements sont requis pour votre demande en « {$moduleName} »";
                    break;
                case 'Non validé':
                    $subject = "Votre demande de formation en « {$moduleName} » n'a pas été validée";
                    break;
                default:
                    $subject = "Mise à jour concernant votre demande de formation";
                    break;
            }

            $message = strip_tags($safeMessage, '<b><i><p><a><br>');

            Mail::to($toEmail)->send(new NotificationRejetCollective($message, $subject, $toEmail, $toUserName));
        }

        return redirect()->back();
    }
}
