<?php

namespace App\Mail;

use App\Models\OnfpActiviteNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OnfpNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OnfpActiviteNotification $notification
    ) {
    }

    public function build()
    {
        return $this
            ->subject($this->notification->titre)
            ->view('emails.onfp.notification')
            ->with([
                'titre' => $this->notification->titre,
                'message' => $this->notification->message,
                'activite' => $this->notification->activite,
                'priorite' => $this->notification->priorite,
            ]);
    }
}
