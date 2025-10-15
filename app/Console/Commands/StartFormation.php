<?php
namespace App\Console\Commands;

use App\Models\Formation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StartFormation extends Command
{
    protected $signature = 'formation:start';

    protected $description = 'Démarrer automatiquement les formations prévues pour aujourd’hui à 08h';

    public function handle()
    {
        $today = Carbon::today();

        $formations = Formation::whereDate('date_debut', $today)->get();

        foreach ($formations as $formation) {
            // Exemple : activer la formation
            $formation->statut = 'En cours';
            $formation->save();

            // Optionnel : log ou notification
            $this->info("Formation ID {$formation->id} démarrée.");
        }
    }
}
