<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('backup:run')->everyMinute();
        /* $schedule->command('backup:clean')->everyFiveMinutes();
        $schedule->command('backup:run')->everyFiveMinutes(); */
        /* $schedule->command('backup:clean')->dailyAt('01:00'); // Nettoyage tous les jours à 1h du matin
        $schedule->command('backup:run')->dailyAt('02:00');   // Sauvegarde tous les jours à 2h du matin */
        $schedule->command('db:backup')->dailyAt('03:00');
        /* $schedule->command('email:send-birthday')->dailyAt('00:00'); */
        $schedule->command('email:send-finagrement')->dailyAt('08:00');
        /* $schedule->command('email:notify-end-agreements')->dailyAt('08:10'); */
        $schedule->command('email:send-training-reminders')->dailyAt('08:15'); // Informer les collègues du Démarre les formations prévues pour aujourd'hui à 08h35
        $schedule->command('projets:fermer-modules')
            ->twiceDaily(8, 17)
            ->withoutOverlapping()
            ->runInBackground();
        /* $schedule->command('individuelles:mark-non-conformes')->dailyAt('18:00'); */ // Exécute chaque jour à 01h du matin
        foreach ([1, 5] as $day) {
            $schedule->command('groupes:verifier-vingt')->weeklyOn($day, '07:00'); // Lundi et Vendredi à 7h
        }
        $schedule->command('email:send-evaluation-reminders')->dailyAt('08:20');
        $schedule->command('formation:start')->dailyAt('08:30');        // Démarre les formations prévues pour aujourd'hui à 08h30
        //$schedule->command('notify:formation-start')->dailyAt('08:35'); // Informer les collègues du Démarre les formations prévues pour aujourd'hui à 08h35

        // Lancer la commande tous les jours à 08h00
        $schedule->command('missions:update-status')->dailyAt('08:00');
        $schedule->command('missions:update-status')->dailyAt('13:00');

        // Lancer la commande tous les jours à 17h00
        $schedule->command('missions:update-status')->dailyAt('17:00');

        /* $schedule->command('missions:send-reminders')
            ->everyMinute(); // recommandé pour précision */

        $schedule->command('missions:send-reminders')->dailyAt('08:00');
        $schedule->command('missions:send-reminders')->dailyAt('17:00');

        $schedule->command('activites:statuts')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
