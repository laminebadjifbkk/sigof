<?php
namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    protected $signature   = 'db:backup';
    protected $description = 'Sauvegarde la base de données MySQL';

    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql.gz';
        $path     = storage_path("app/backups/{$filename}");

        $db      = config('database.connections.mysql');
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s | gzip > %s',
            $db['username'],
            $db['password'],
            $db['host'],
            $db['database'],
            $path
        );

        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("Sauvegarde réussie: {$filename}");
        } else {
            $this->error("Erreur lors de la sauvegarde.");
        }
    }
}
// Supprimer les sauvegardes de plus de 10 jours
collect(File::glob(storage_path('app/backups/*.sql.gz')))
    ->each(function ($file) {
        if (filemtime($file) < now()->subDays(10)->timestamp) {
            unlink($file);
        }
    });
