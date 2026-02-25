<?php
namespace App\Console\Commands;

use App\Models\Individuelle;
use App\Models\User;
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

                /* // Si TOUS les fichiers ont `file === null` → OK
                return $files->every(fn($file) => $file->file === null); */

                // Compter les fichiers valides (file !== null)
                $validFilesCount = $files->filter(fn($file) => $file->file !== null)->count();

                // ✅ Cas 2 : tous les fichiers sont vides → OK
                if ($validFilesCount === 0) {
                    return true;
                }

                // ✅ Cas 3 & 4 : 1 ou 2 fichiers valides → OK
                if ($validFilesCount < 2) {
                    return true;
                }
                // ✅ Cas 3 & 4 : 1, 2 ou 3 fichiers valides → OK
                if ($validFilesCount < 3) {
                    return true;
                }

                // ❌ Cas 5 : plus de 2 fichiers valides → Exclu
                return false;

            });

        $systemUserId = User::orderBy('id')->first()?->id;
        $count        = 0;

        foreach ($individuelles as $individuelle) {
            $individuelle->statut      = 'Non conforme';
            $individuelle->canceled_by = 'Systeme';
            $individuelle->save();

            $validationindividuelle = Validationindividuelle::create([
                'validated_id'     => $systemUserId,
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
