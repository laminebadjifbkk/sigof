<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ParcMission;
use Carbon\Carbon;

class UpdateMissionStatus extends Command
{
    protected $signature = 'missions:update-status';
    protected $description = 'Met à jour automatiquement le statut des missions, chauffeurs et véhicules';

    public function handle()
    {
        $now = Carbon::now();
        $hour = $now->format('H');

        if (in_array($hour, [8, 13, 15, 17])) {

            // 08h00 ou 13h00 -> Démarrer les missions
            $missions = ParcMission::where('statut', '!=', 'en_cours')
                ->whereDate('date_depart', $now->toDateString())
                ->get();

            foreach ($missions as $mission) {
                $mission->statut = 'en_cours';
                $mission->save();

                // Chauffeurs indisponibles
                foreach ($mission->employees as $employee) {
                    $chauffeur = $employee->chauffeur;
                    if ($chauffeur) {
                        $chauffeur->statut = 'en_mission';
                        $chauffeur->save();
                    }
                }

                // Véhicules en mission
                foreach ($mission->vehicules as $vehicule) {
                    $vehicule->etat = 'en_mission';
                    $vehicule->save();
                }
            }

            $this->info("Missions démarrées et statuts mis à jour à {$hour}h 00.");
        }

        if ($hour == 17) {
            // 17h00 -> Clôturer les missions
            $missions = ParcMission::where('statut', 'en_cours')
                ->whereDate('date_retour', $now->toDateString())
                ->get();

            foreach ($missions as $mission) {
                $mission->statut = 'cloturee';
                $mission->save();

                foreach ($mission->employees as $employee) {
                    $chauffeur = $employee->chauffeur; // récupérer le chauffeur lié
                    if ($chauffeur) {
                        $chauffeur->statut = 'disponible';
                        $chauffeur->save();
                    }
                }

                // Véhicules disponible
                foreach ($mission->vehicules as $vehicule) {
                    $vehicule->etat = 'disponible';
                    $vehicule->save();
                }
            }

            $this->info('Missions clôturées et statuts mis à jour à 17h00.');
        }
    }
}
