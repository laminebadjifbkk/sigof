<?php
// app/Services/EmailService.php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Services\BrevoMailer;

class EmailService
{
    protected BrevoMailer $mailer;

    // On injecte BrevoMailer dans le service
    public function __construct(BrevoMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoie un email via Brevo
     *
     * @param array $to ['email'=>'', 'name'=>'']
     * @param string $subject
     * @param string $htmlContent
     * @param array|null $attachments [['name'=>'', 'content'=>base64], ...]
     * @return bool
     */
    public function send(array $to, string $subject, string $htmlContent, ?array $attachments = null): bool
    {
        try {
            $this->mailer->sendEmail($to, $subject, $htmlContent, $attachments);
            return true;
        } catch (Exception $e) {
            Log::error('Échec envoi email : ' . $e->getMessage());
            return false;
        }
    }
}
