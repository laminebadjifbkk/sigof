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

    /* public function build()
    {
        return $this->subject('Confirmation de votre participation ONFP')
                    ->view('emails.confirmation_inscription');
    } */
    public function build()
    {
        return $this->subject('Confirmation de votre participation ONFP')
            ->view('emails.confirmation_inscription')
            ->attach(public_path('Termes_de_reference_protected.pdf'), [
                'as'   => 'TDR.pdf', // nom du fichier tel qu'il apparaîtra dans le mail
                'mime' => 'application/pdf',
            ]);
    }
}
