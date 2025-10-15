<?php
namespace App\Mail;

use App\Models\Formation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormationStartNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $formation;
    public $label;

    public function __construct(Formation $formation, $label)
    {
        $this->formation = $formation;
        $this->label     = $label;
    }

    /* public function build()
    {
        return $this->subject('📢 Démarrage de votre formation : ' . $this->formation->intitule)
            ->view('emails.formation_start');
    } */

    public function build()
    {
        /* return $this->subject("Démarrage formation {$this->label}") */
        return $this->subject("Démarrage formation : " . $this->formation->intitule)
            ->view('emails.formation_start')
            ->with([
                'formation' => $this->formation,
                'label'     => $this->label,
            ])
        /* ->attach(public_path('onfp.png'), [
                'as'   => 'logo.png',
                'mime' => 'image/png',
            ]) */;
    }
}
