<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use RealRashid\SweetAlert\Facades\Alert;

class NotificationRejetIndividuelle extends Mailable
{
    use Queueable, SerializesModels;

    public $mailMessage;
    public $subject;
    public $toEmail;
    public $toUserName;

    /**
     * Create a new message instance.
     */
    public function __construct($message, $subject, $toEmail, $toUserName)
    {
        $this->mailMessage = $message;
        $this->subject     = $subject;
        $this->toEmail     = $toEmail;
        $this->toUserName  = $toUserName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('lamine.badji@onfp.sn', 'ONFP | Notification concernant votre demande de formation'),
            replyTo: [
                new Address('lamine.badji@onfp.sn', 'ONFP | Réponse concernant votre nouvelle demande de formation'),
            ],
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        /* Alert::success("E-mail envoyés !", "La notification de validation été envoyée avec succès."); */
        Alert::success('Succès !', 'La demande a été rejetée avec succès !, un mail a été envoyé au demandeur.');
        /* Alert::success('E-mail envoyés !', 'La notification de rejet a été envoyée avec succès.'); */
        return new Content(
            view: 'individuelles.mailinfo',
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
}
