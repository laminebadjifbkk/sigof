<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationDemandeursMail extends Mailable
{
    use Queueable, SerializesModels;

    public $region;
    public $module;
    public $total;

    /**
     * Create a new message instance.
     */
    public function __construct($region, $module, $total)
    {
        $this->region = $region;
        $this->module = $module;
        $this->total  = $total;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("⚠️ {$this->total} nouvelles demandes en {$this->module} pour la région de {$this->region}")
            ->view('emails.notif-vingt-demandeurs');
    }
}
