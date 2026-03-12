<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class ActiviteEnRetardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activite;

    public function __construct($activite)
    {
        $this->activite = $activite;
    }

    public function build()
    {
        return $this->subject('Activité en retard')
            ->view('emails.activite_retard')
            ->with([
                'activite' => $this->activite
            ]);
    }
}