<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetNotificationsRegion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-notifications-region';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\NotificationRegion::truncate();
        $this->info("Toutes les notifications ont été réinitialisées.");
    }
}
