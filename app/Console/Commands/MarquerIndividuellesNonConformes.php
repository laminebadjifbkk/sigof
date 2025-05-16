<?php
namespace App\Console\Commands;

use App\Models\Individuelle;
use App\Models\Validationindividuelle;
use Illuminate\Console\Command;

class MarquerIndividuellesNonConformes extends Command
{
    protected $signature   = 'individuelles:mark-non-conformes';
    protected $description = 'Met les individuelles à Non conforme si statut Nouvelle et fichiers utilisateur null';

    public function handle()
    {
        $individuelles = Individuelle::where('statut', 'Nouvelle')
            ->whereHas('projet', function ($query) {
                $query->where('statut', 'fermer');
            })
            ->whereHas('user')   // s'assurer que l'individuelle a un user
            ->with('user.files') // eager load pour éviter N+1
            ->get()
            ->filter(function ($individuelle) {
                $user = $individuelle->user;

                if (! $user) {
                    return false;
                }

                $files = $user->files;

                // Si l'utilisateur n'a aucun fichier → OK
                if ($files->isEmpty()) {
                    return true;
                }

                // Si TOUS les fichiers ont `file === null` → OK
                return $files->every(fn($file) => $file->file === null);
            });

        $count = 0;

        foreach ($individuelles as $individuelle) {
            $individuelle->statut      = 'Non conforme';
            $individuelle->canceled_by = 'Systeme';
            $individuelle->save();

            $validationindividuelle = Validationindividuelle::create([
                'validated_id'     => $individuelle?->user?->id,
                'action'           => 'Non conforme',
                'motif'            => 'Dossier incomplet',
                'individuelles_id' => $individuelle?->id,
            ]);

            $count++;
        }

        $this->info("$count individuelles mises à jour en Non conforme.");
        return Command::SUCCESS;
    }

}
