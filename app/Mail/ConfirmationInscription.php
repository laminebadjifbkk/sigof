<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationInscription extends Mailable
{
    use Queueable, SerializesModels;

    public $inscription;

    public function __construct($inscription)
    {
        $this->inscription = $inscription;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre participation ONFP')
                    ->view('emails.confirmation_inscription');
    }
}
