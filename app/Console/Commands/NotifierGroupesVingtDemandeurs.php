<?php

namespace App\Console\Commands;

use App\Mail\NotificationDemandeursMail;
use App\Models\Individuelle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Region;
use App\Models\Module;
use App\Models\NotificationRegion;
use App\Services\BrevoMailer;

class NotifierGroupesVingtDemandeurs extends Command
{
    protected $signature   = 'groupes:verifier-vingt';
    protected $description = 'Envoie un mail si une région atteint 20 demandeurs avec statut "Nouvelle"';

    /* public function handle()
    {
        $this->info("Vérification des augmentations de demandes par région et module au-delà de 20...");

        $groupes = Individuelle::where('statut', 'Nouvelle')
            ->selectRaw('regions_id, modules_id, COUNT(*) as total')
            ->groupBy('regions_id', 'modules_id')
            ->get();

        foreach ($groupes as $groupe) {
            $regionId = $groupe->regions_id;
            $moduleId = $groupe->modules_id;
            $total    = $groupe->total;

            // Ne rien faire si on est en dessous ou égal à 20
            if ($total <= 50) {
                continue;
            }

            // Récupère ou initialise la notification pour cette combinaison
            $notification = NotificationRegion::firstOrNew([
                'region'     => $regionId,
                'modules_id' => $moduleId,
            ]);

            $dernierTotal = $notification->dernier_palier_notifie ?? 0;

            $nomRegion = Region::find($regionId)?->nom ?? "Région ID {$regionId}";
            $nomModule = Module::find($moduleId)?->name ?? "Module ID {$moduleId}";
            // Si le total actuel est supérieur au précédent enregistré, notifier
            if ($total > $dernierTotal) {

                Mail::to(
                    [
                        'lamine.badji@onfp.sn',
                    ]
                )
                    ->send(new NotificationDemandeursMail($nomRegion, $nomModule, $total));

                $notification->dernier_palier_notifie = $total;
                $notification->save();

                $this->info("Notification envoyée pour {$nomRegion} / {$nomModule} : {$total} demandes.");
            } else {
                $this->info("Pas de nouvelle augmentation pour région {$nomModule} / module {$nomRegion}.");
            }
        }

        return 0;
    } */

    /* public function handle()
    {
        $seuil = 20;
        $groupes = Individuelle::whereIn('statut', ['Nouvelle', 'Conforme'])
            ->selectRaw('regions_id, modules_id, COUNT(*) as total')
            ->groupBy('regions_id', 'modules_id')
            ->having('total', '>=', $seuil)
            ->get();

        if ($groupes->isEmpty()) {
            $this->info("Aucun module n'a atteint {$seuil} demandes.");
            return 0;
        }

        // Regrouper par région
        $donnees = [];
        foreach ($groupes as $groupe) {
            $region = Region::find($groupe->regions_id)?->nom ?? "Région ID {$groupe->regions_id}";
            $module = Module::find($groupe->modules_id)?->name ?? "Module ID {$groupe->modules_id}";

            $donnees[$region][] = [
                'module' => $module,
                'total' => $groupe->total,
            ];
        }

        // Envoyer un seul email
        Mail::to('lamine.badji@onfp.sn', 'mohamadou.soumare@onfp.sn')
            ->cc('gorgui.ndiaye@onfp.sn')
            ->send(new NotificationDemandeursMail($donnees, $seuil));

        $this->info("Email récapitulatif envoyé.");
    } */

    public function handle()
    {
        $seuil = 20;

        $groupes = Individuelle::whereIn('statut', ['Nouvelle', 'Conforme'])
            ->selectRaw('regions_id, modules_id, COUNT(*) as total')
            ->groupBy('regions_id', 'modules_id')
            ->having('total', '>=', $seuil)
            ->get();

        if ($groupes->isEmpty()) {
            $this->info("Aucun module n'a atteint {$seuil} demandes.");
            return 0;
        }

        // Regrouper par région
        $donnees = [];
        foreach ($groupes as $groupe) {
            $region = Region::find($groupe->regions_id)?->nom ?? "Région ID {$groupe->regions_id}";
            $module = Module::find($groupe->modules_id)?->name ?? "Module ID {$groupe->modules_id}";

            $donnees[$region][] = [
                'module' => $module,
                'total' => $groupe->total,
            ];
        }

        // Créer l’HTML de l’email (vous pouvez aussi utiliser votre Mailable)
        $htmlContent = "<h2>Récapitulatif des modules ayant atteint {$seuil} demandes</h2>";
        foreach ($donnees as $region => $modules) {
            $htmlContent .= "<h3>{$region}</h3><ul>";
            foreach ($modules as $m) {
                $htmlContent .= "<li>{$m['module']} : {$m['total']} demandes</li>";
            }
            $htmlContent .= "</ul>";
        }

        // Destinataires
        $destinataires = [
            ['email' => 'lamine.badji@onfp.sn', 'name' => 'Lamine Badji'],
        ];

        $mailer = new BrevoMailer();

        foreach ($destinataires as $to) {
            $mailer->sendEmail(
                $to,
                "Récapitulatif des modules >= {$seuil} demandes",
                $htmlContent
            );
        }

        $this->info("Email récapitulatif envoyé via Brevo.");
    }
}
