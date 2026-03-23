<?php

namespace App\Console\Commands;

use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FermerModulesProjet extends Command
{
    protected $signature   = 'projets:fermer-modules';
    protected $description = 'Ferme les modules des projets dont la date de fermeture est aujourd\'hui.';

    /* public function handle()
    {
                                       // Obtenir la date actuelle à 00h00
        $aujourdHui = Carbon::today(); // C'est la date du jour à minuit
                                       // Chercher les projets dont la date de fermeture est aujourd'hui
        $projets = Projet::whereDate('date_fermeture', '=', $aujourdHui->toDateString())->get();

        if ($projets->isEmpty()) {
            $this->info("Aucun projet à fermer ce jour.");
            return Command::SUCCESS;
        }

        foreach ($projets as $projet) {
            foreach ($projet->projetmodules as $module) {
                if ($module->statut !== 'fermé') {
                    $module->statut = 'fermé';
                    $module->save();
                    $this->info("Projet #{$projet->sigle} - Module #{$module->module} fermé.");
                }
            }
            if ($projet->statut !== 'fermer') {
                $projet->statut = 'fermer';
                $projet->save();
                $this->info("Projet #{$projet->sigle} fermé.");
            }
        }

        $this->info("Tous les modules des projets à fermer aujourd'hui ont été traités.");
        return Command::SUCCESS;
    } */

    public function handle()
    {
        $maintenant = Carbon::now(); // date et heure actuelles

        // Récupérer tous les projets dont l'ouverture ou la fermeture concerne aujourd'hui ou avant
        /* $projets = Projet::with('projetmodules')
            ->get(); // on traite tous les projets pour vérifier date_ouverture et date_fermeture */

        $projets = Projet::with('projetmodules')
            ->whereDate('date_ouverture', '<=', now())
            ->orWhereDate('date_fermeture', '<=', now())
            ->get();

        if ($projets->isEmpty()) {
            $this->info("Aucun projet à traiter.");
            return Command::SUCCESS;
        }

        foreach ($projets as $projet) {
            $ouverture = $projet->date_ouverture;
            $fermeture = $projet->date_fermeture;

            if ($maintenant->lt($ouverture)) {
                $projet->statut = 'à venir';
            }

            // 1️⃣ Entre ouverture et fermeture → statut 'ouvert'
            if ($maintenant->gte($ouverture) && $maintenant->lt($fermeture)) {
                if ($projet->statut !== 'ouvert') {
                    $projet->statut = 'ouvert';
                    $projet->save();
                    $this->info("Projet #{$projet->sigle} ouvert.");
                }

                foreach ($projet->projetmodules as $module) {
                    if ($module->statut !== 'ouvert') {
                        $module->statut = 'ouvert';
                        $module->save();
                        $this->info("Module #{$module->module} du projet #{$projet->sigle} ouvert.");
                    }
                }
            }

            // 2️⃣ Après fermeture → statut 'fermé'
            if ($maintenant->gte($fermeture)) {
                if ($projet->statut !== 'fermé') {
                    $projet->statut = 'fermé';
                    $projet->save();
                    $this->info("Projet #{$projet->sigle} fermé.");
                }

                foreach ($projet->projetmodules as $module) {
                    if ($module->statut !== 'fermé') {
                        $module->statut = 'fermé';
                        $module->save();
                        $this->info("Module #{$module->module} du projet #{$projet->sigle} fermé.");
                    }
                }
            }
        }

        $this->info("Tous les projets et modules ont été mis à jour selon leurs dates d'ouverture et de fermeture.");
        return Command::SUCCESS;
    }
}
