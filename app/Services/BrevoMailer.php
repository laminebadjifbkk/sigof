<?php

namespace App\Services;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoMailer
{
    protected $apiInstance;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
        $this->apiInstance = new TransactionalEmailsApi(
            new \GuzzleHttp\Client(),
            $config
        );
    }

    public function sendMail($to, $subject, $htmlContent)
    {
        $email = new SendSmtpEmail([
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'sender' => ['name' => env('APP_NAME'), 'email' => env('MAIL_FROM_ADDRESS')],
            'to' => [['email' => $to]],
        ]);

        return $this->apiInstance->sendTransacEmail($email);
    }
}