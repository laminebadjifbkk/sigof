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

        // Missions à démarrer à 08h
        $missionsStart = ParcMission::where('statut', '!=', 'en_cours')
            ->whereDate('date_depart', $now->toDateString())
            ->whereTime('date_depart', '<=', $now->toTimeString())
            ->get();

        foreach ($missionsStart as $mission) {
            $mission->statut = 'en_cours';
            $mission->save();

            // Mettre les chauffeurs indisponibles
            foreach ($mission->employees as $chauffeur) {
                $chauffeur->statut = 'indisponible';
                $chauffeur->save();
            }

            // Mettre les véhicules hors service
            foreach ($mission->vehicules as $vehicule) {
                $vehicule->etat = 'hors_service';
                $vehicule->save();
            }
        }

        // Missions à clôturer à 17h
        $missionsEnd = ParcMission::where('statut', 'en_cours')
            ->whereDate('date_retour', $now->toDateString())
            ->whereTime('date_retour', '<=', $now->toTimeString())
            ->get();

        foreach ($missionsEnd as $mission) {
            $mission->statut = 'cloturee';
            $mission->save();

            // Revenir les chauffeurs actifs
            foreach ($mission->employees as $chauffeur) {
                $chauffeur->statut = 'actif';
                $chauffeur->save();
            }

            // Remettre les véhicules operationnels
            foreach ($mission->vehicules as $vehicule) {
                $vehicule->etat = 'operationnel';
                $vehicule->save();
            }
        }

        $this->info('Missions, chauffeurs et véhicules mis à jour.');
    }
}
