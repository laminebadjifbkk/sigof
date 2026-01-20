<?php
/* namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationDemandeursMail extends Mailable
{
    use Queueable, SerializesModels;

    public $region;
    public $module;
    public $total;


    public function __construct($region, $module, $total)
    {
        $this->region = $region;
        $this->module = $module;
        $this->total  = $total;
    }


    public function build()
    {
        return $this->subject("⚠️ {$this->total} nouvelles demandes en {$this->module} pour la région de {$this->region}")
            ->view('emails.notif-vingt-demandeurs');
    }
} */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ModulesParRegionExport;

class NotificationDemandeursMail extends Mailable
{
    use Queueable, SerializesModels;

    public $donnees; // tableau [region => [[module, total], ...]]
    public $seuil;

    /**
     * Create a new message instance.
     *
     * @param array $donnees
     * @param int $seuil
     */
    public function __construct(array $donnees, int $seuil)
    {
        $this->donnees = $donnees;
        $this->seuil = $seuil;
    }

    /**
     * Build the message.
     */
    /* public function build()
    {
        return $this->subject("Modules ayant atteint {$this->seuil} demandes (Statut : Nouvelle & Conforme)")
            ->view('emails.notif-modules-par-region');
    } */
   
    public function build()
    {
        // Créer un chemin sur le disque local (storage/app/exports/)
        $chemin = 'exports/modules_par_region.xlsx';

        // Générer le fichier Excel
        Excel::store(new ModulesParRegionExport($this->donnees), $chemin, 'local');

        // Attacher le fichier depuis storage/app
        return $this->subject("Modules ayant atteint {$this->seuil} demandes")
            ->view('emails.notif-modules-par-region')
            ->attach(storage_path('app/' . $chemin), [
                'as'   => 'modules_par_region.xlsx',
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }
}
