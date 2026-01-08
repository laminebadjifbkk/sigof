<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MissionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mission;
    public $type;
    public $employee;

    public function __construct($mission, $type, $employee)
    {
        $this->mission = $mission;
        $this->type = $type;
        $this->employee = $employee;
    }

    public function build()
    {
        return $this->subject(
            "Rappel mission ({$this->type}) – {$this->mission->reference}"
        )
            ->view('emails.mission_reminder');
    }
}
