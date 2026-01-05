<?php

namespace App\Mail;

use App\Models\ParcMission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouvelleMissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public ParcMission $mission;

    /**
     * Create a new message instance.
     */
    public function __construct(ParcMission $mission)
    {
        $this->mission = $mission;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->subject('Nouvelle mission créée')
                    ->view('emails.nouvelle-mission');
    }
}
