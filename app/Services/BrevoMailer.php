<?php

namespace App\Services;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Exception;

class BrevoMailer
{
    protected $api;

    public function __construct()
    {
        // Configuration de l'API avec la clé du .env
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
        $this->api = new TransactionalEmailsApi(new Client(), $config);
    }

    /**
     * Envoie un email via Brevo
     *
     * @param array $to ['email' => '', 'name' => '']
     * @param string $subject
     * @param string $htmlContent
     * @param array|null $attachments [['name' => '', 'content' => base64], ...]
     * @return mixed
     */
    /* public function sendEmail(array $to, string $subject, string $htmlContent, ?array $attachments = null)
    {
        $email = new SendSmtpEmail([
            'subject' => $subject,
            'sender' => [
                'name' => env('BREVO_SENDER_NAME', 'ONFP'),
                'email' => env('BREVO_SENDER_EMAIL', 'danilobadji@gmail.com'),
            ],
            'to' => [$to],
            'htmlContent' => $htmlContent,
            'attachment' => $attachments,
        ]);

        try {
            return $this->api->sendTransacEmail($email);
        } catch (Exception $e) {
            \Log::error('Erreur Brevo : ' . $e->getMessage());
            return false;
        }
    } */

    public function sendEmail($to, $subject, $htmlContent, $attachments = null)
    {
        $emailData = [
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'sender' => [
                'name' => env('BREVO_SENDER_NAME', 'SIGOF'),
                'email' => env('BREVO_SENDER_EMAIL')
            ],
            'to' => [
                [
                    'email' => $to['email'],
                    'name' => $to['name']
                ]
            ]
        ];

        if (!empty($attachments)) {
            $emailData['attachment'] = $attachments;
        }

        $email = new SendSmtpEmail($emailData);

        return $this->api->sendTransacEmail($email);
    }
}
