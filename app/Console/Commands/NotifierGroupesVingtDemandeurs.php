<?php
namespace App\Console\Commands;

use App\Mail\NotificationDemandeursMail;
use App\Models\Individuelle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifierGroupesVingtDemandeurs extends Command
{
    protected $signature   = 'groupes:verifier-vingt';
    protected $description = 'Envoie un mail si une région atteint 20 demandeurs avec statut "Nouvelle"';

    /* public function handle()
    {
        // Regroupe les demandeurs "Nouvelle" par région et compte
        $groupes = Individuelle::where('statut', 'Nouvelle')
            ->selectRaw('region, COUNT(*) as total')
            ->groupBy('region')
            ->having('total', '>=', 20)
            ->get();

        if ($groupes->isEmpty()) {
            $this->info("Aucun groupe de 20 demandeurs trouvé.");
            return 0;
        }

        foreach ($groupes as $groupe) {
            $region = $groupe->region;
            $total = $groupe->total;

            // Préparer les données pour l’email
            $data = [
                'region' => $region,
                'total' => $total,
            ];

            // Envoyer un mail
            Mail::send('emails.notif-vingt-demandeurs', $data, function ($message) use ($region) {
                $message->to(['lamine.badji@onfp.sn', 'dado.toure@onfp.sn'])
                        ->subject("20 demandes 'Nouvelles' atteintes pour la région : {$region}");
            });

            $this->info("Email envoyé pour la région {$region} ({$total} demandes)");
        }

        return 0;
    } */

    /* public function handle()
    {
        \App\Models\NotificationRegion::truncate();
        $this->info("Toutes les notifications ont été réinitialisées.");

        $groupes = Individuelle::where('statut', 'Nouvelle')
            ->selectRaw('regions_id, COUNT(*) as total')
            ->groupBy('regions_id')
            ->having('total', '>=', 20)
            ->get();

        if ($groupes->isEmpty()) {
            $this->info("Aucun groupe de 20 demandeurs trouvé.");
            return 0;
        }

        foreach ($groupes as $groupe) {
            $region = $groupe->region;
            $total  = $groupe->total;

            // Vérifier si une notification a déjà été envoyée pour cette région
            if (NotificationRegion::where('region', $region)->exists()) {
                $this->info("Notification déjà envoyée pour la région {$region}, on ignore.");
                continue;
            }

            // Envoyer le mail
            Mail::send('emails.notif-vingt-demandeurs', [
                'region' => $region,
                'total'  => $total,
            ], function ($message) use ($region) {
                $message->to(['lamine.badji@onfp.sn', 'dado.toure@onfp.sn'])
                    ->subject("20 demandes 'Nouvelles' atteintes pour la région : {$region}");
            });

            // Marquer cette région comme notifiée
            NotificationRegion::create(['region' => $region]);

            $this->info("Email envoyé pour la région {$region} ({$total} demandes)");
        }
    } */

    /* public function handle()
    {
        $this->info("Vérification des paliers de 20 demandeurs par région...");

        $groupes = Individuelle::where('statut', 'Nouvelle')
            ->selectRaw('regions_id, COUNT(*) as total')
            ->groupBy('regions_id')
            ->get();

        foreach ($groupes as $groupe) {
            $regionId = $groupe->regions_id;
            $total    = $groupe->total;

            // Récupère ou initialise la notification pour cette région
            $notification  = NotificationRegion::firstOrNew(['region' => $regionId]);
            $dernierPalier = $notification->dernier_palier_notifie ?? 0;

            // Vérifie si on a franchi un nouveau palier
            $prochainPalier = floor($total / 20) * 20;

            if ($prochainPalier > $dernierPalier) {
                $nomRegion = \App\Models\Region::find($regionId)?->nom ?? "Région ID {$regionId}";

                Mail::send('emails.notif-vingt-demandeurs', [
                    'region' => $nomRegion,
                    'total'  => $total,
                ], function ($message) use ($nomRegion, $prochainPalier) {
                    $message->to(['lamine.badji@onfp.sn', 'badjilaminefbkk@gmail.com'])
                        ->subject("⚠️ {$prochainPalier} demandes 'Nouvelles' dans la région : {$nomRegion}");
                });

                $notification->dernier_palier_notifie = $prochainPalier;
                $notification->save();

                $this->info("Notification envoyée pour {$nomRegion} à {$prochainPalier} demandes.");
            } else {
                $this->info("Aucun nouveau palier atteint pour région ID {$regionId}.");
            }
        }

        return 0;
    } */

    /* public function handle()
    {
        $this->info("Vérification des paliers de 20 demandeurs par région et par module...");

        $groupes = Individuelle::where('statut', 'Nouvelle')
            ->selectRaw('regions_id, modules_id, COUNT(*) as total')
            ->groupBy('regions_id', 'modules_id')
            ->get();

        foreach ($groupes as $groupe) {
            $regionId = $groupe->regions_id;
            $moduleId = $groupe->modules_id;
            $total    = $groupe->total;

            // Récupère ou initialise la notification pour cette combinaison
            $notification = \App\Models\NotificationRegion::firstOrNew([
                'region'    => $regionId,
                'modules_id' => $moduleId,
            ]);

            $dernierPalier  = $notification->dernier_palier_notifie ?? 0;
            $prochainPalier = floor($total / 20) * 20;

            if ($prochainPalier > $dernierPalier) {
                $nomRegion = \App\Models\Region::find($regionId)?->nom ?? "Région ID {$regionId}";
                $nomModule = \App\Models\Module::find($moduleId)?->name ?? "Module ID {$moduleId}";

                Mail::send('emails.notif-vingt-demandeurs', [
                    'region' => $nomRegion,
                    'module' => $nomModule,
                    'total'  => $total,
                ], function ($message) use ($nomRegion, $nomModule, $prochainPalier) {
                    $message->to(['lamine.badji@onfp.sn', 'badjilaminefbkk@gmail.com'])
                        ->subject("⚠️ {$prochainPalier} demandes 'Nouvelles' pour {$nomModule} dans la région : {$nomRegion}");
                });

                $notification->dernier_palier_notifie = $prochainPalier;
                $notification->save();

                $this->info("Notification envoyée pour {$nomRegion} / {$nomModule} à {$prochainPalier} demandes.");
            } else {
                $this->info("Aucun nouveau palier atteint pour région ID {$regionId} / module ID {$moduleId}.");
            }
        }

        return 0;
    } */

    public function handle()
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
            if ($total <= 20) {
                continue;
            }

            // Récupère ou initialise la notification pour cette combinaison
            $notification = \App\Models\NotificationRegion::firstOrNew([
                'region'     => $regionId,
                'modules_id' => $moduleId,
            ]);

            $dernierTotal = $notification->dernier_palier_notifie ?? 0;

            $nomRegion = \App\Models\Region::find($regionId)?->nom ?? "Région ID {$regionId}";
            $nomModule = \App\Models\Module::find($moduleId)?->name ?? "Module ID {$moduleId}";
            // Si le total actuel est supérieur au précédent enregistré, notifier
            if ($total > $dernierTotal) {

                /* Mail::send('emails.notif-vingt-demandeurs', [
                    'region' => $nomRegion,
                    'module' => $nomModule,
                    'total'  => $total,
                ], function ($message) use ($nomRegion, $nomModule, $total) {
                    $message->to(['lamine.badji@onfp.sn', 'badjilaminefbkk@gmail.com'])
                        ->subject("🔔 {$total} demandes 'Nouvelles' pour {$nomModule} dans la région : {$nomRegion}");
                }); */

                Mail::to(
                    ['lamine.badji@onfp.sn',
                        /* 'dado.toure@onfp.sn',
                        'mohamadou.soumare@onfp.sn',
                        'ndiayego104@yahoo.fr',
                        'diallomansa10@gmail.com',
                        'mourtallabiteye89@yahoo.fr',
                        'diadiafall3@gmail.com',
                        'cissbigue6@gmail.com',
                        'mouhamadoulaminebara@onfp.sn', */
                    ])
                    ->send(new NotificationDemandeursMail($nomRegion, $nomModule, $total));

                $notification->dernier_palier_notifie = $total;
                $notification->save();

                $this->info("Notification envoyée pour {$nomRegion} / {$nomModule} : {$total} demandes.");
            } else {
                $this->info("Pas de nouvelle augmentation pour région {$nomModule} / module {$nomRegion}.");
            }
        }

        return 0;
    }

}
