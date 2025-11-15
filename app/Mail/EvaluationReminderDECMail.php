<?php
namespace App\Mail;

use App\Models\Formation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationReminderDECMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formation;
    public $label;

    /**
     * Create a new message instance.
     */
    public function __construct(Formation $formation, string $label)
    {
        $this->formation = $formation;
        $this->label     = $label;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Rappel : Évaluation prévue {$this->label}")
            ->view('emails.evaluations.reminder-html')
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
