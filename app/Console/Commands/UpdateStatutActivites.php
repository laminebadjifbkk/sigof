<?php

namespace App\Console\Commands;

use App\Models\ActiviteQuotidienne;
use App\Mail\ActiviteEnRetardMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class UpdateStatutActivites extends Command
{
    protected $signature = 'activites:statuts';
    protected $description = 'Met à jour les statuts des activités automatiquement';

    public function handle()
    {
        $activites = ActiviteQuotidienne::with('validateur')
            ->whereNotIn('statut', ['terminee', 'validee', 'rejete'])
            ->get();

        foreach ($activites as $activite) {

            if ($activite->heure_debut && $activite->heure_fin) {

                $now = Carbon::now();

                // activité en cours
                if ($now->between($activite->heure_debut, $activite->heure_fin)) {

                    if ($activite->statut !== 'en_cours') {
                        $activite->update(['statut' => 'en_cours']);
                    }

                }

                // activité en retard
                if ($now->greaterThan($activite->heure_fin)) {

                    if ($activite->statut !== 'retard') {

                        $activite->update(['statut' => 'retard']);

                        // Notification email au responsable
                        if ($activite->validateur && $activite->validateur->email) {

                            Mail::to($activite->validateur->email)
                                ->send(new ActiviteEnRetardMail($activite));

                        }
                    }
                }
            }
        }

        $this->info('Statuts des activités mis à jour avec succès.');
    }
}