<?php
namespace App\Mail;

use App\Models\Formulaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationInscriptionPchare extends Mailable
{
    use Queueable, SerializesModels;

    public $formulaire;

    /**
     * Create a new message instance.
     */
    public function __construct(Formulaire $formulaire)
    {
        $this->formulaire = $formulaire;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation Inscription Pchare',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.confirmationpcharge',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->subject('Confirmation de votre inscription ONFP')
            ->markdown('emails.confirmationpcharge');
    }
}
