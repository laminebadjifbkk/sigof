<?php
namespace App\Console\Commands;

use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FermerModulesProjet extends Command
{
    protected $signature   = 'projets:fermer-modules';
    protected $description = 'Ferme les modules des projets dont la date de fermeture est aujourd\'hui.';

    public function handle()
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
    }
}
