<?php

namespace App\Console\Commands;

use App\Mail\FinAgrementMail;
use App\Models\Commissionagrement;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFinagrementEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-finagrement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie un email aux opérateurs pour renouveler leur agrément';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limitDate = Carbon::today()->subYears(5);

        $commissions = Commissionagrement::whereDate('fin_commission', '<=', $limitDate)
            ->with('operateurs.user')
            ->get(); // ← get() pour récupérer toutes les commissions

        dd($commissions);

        foreach ($commissions as $commission) {

            foreach ($commission->operateurs as $operateur) {
                if ($operateur->statut_agrement === 'agréé') {
                    $operateur->update(['statut_agrement' => 'expiré']);
                    // Mail::to($operateur->user?->email)->send(new FinAgrementMail($operateur));
                }
            }
        }

        $this->info("Les e-mails de fin d'agrément ont été envoyés avec succès.");
    }
}
