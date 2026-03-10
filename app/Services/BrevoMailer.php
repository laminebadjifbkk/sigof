<?php

namespace App\Services;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class BrevoMailer
{
    protected TransactionalEmailsApi $api;
    protected string $senderName;
    protected string $senderEmail;

    public function __construct()
    {
        $apiKey = config('brevo.api_key');
        $this->senderName  = config('brevo.sender_name', 'SIGOF');
        $this->senderEmail = config('brevo.sender_email', 'no-reply@onfp.sn');

        if (!$apiKey) {
            throw new Exception('Clé API Brevo manquante. Vérifiez config/brevo.php et .env');
        }

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        $this->api = new TransactionalEmailsApi(new Client(), $config);
    }

    public function sendEmail(array $to, string $subject, string $htmlContent, ?array $attachments = null)
    {
        $emailData = [
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'sender' => [
                'name' => $this->senderName,
                'email' => $this->senderEmail,
            ],
            'to' => [
                [
                    'email' => $to['email'],
                    'name' => $to['name'],
                ]
            ],
        ];

        if (!empty($attachments)) {
            $emailData['attachment'] = $attachments;
        }

        $email = new SendSmtpEmail($emailData);

        try {
            return $this->api->sendTransacEmail($email);
        } catch (Exception $e) {
            Log::error('Erreur envoi mail Brevo : ' . $e->getMessage());
            throw $e;
        }
    }
}
