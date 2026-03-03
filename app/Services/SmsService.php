<?php

namespace App\Services;

use SendinBlue\Client\Api\TransactionalSMSApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendTransacSms;
use GuzzleHttp\Client;

class SmsService
{
    protected $api;

    public function __construct()
    {
        // Configuration avec la clé API depuis le .env
        $config = Configuration::getDefaultConfiguration()->setApiKey(
            'api-key',
            env('BREVO_API_KEY')
        );

        $this->api = new TransactionalSMSApi(new Client(), $config);
    }

    /**
     * Envoyer un SMS
     *
     * @param string $to      Numéro du destinataire au format international (+221...)
     * @param string $message Contenu du SMS
     * @param string $sender  Nom de l'expéditeur (optionnel)
     * @return mixed
     */
    public function sendSms(string $to, string $message, string $sender = 'MonApp')
    {
        $sms = new SendTransacSms([
            'sender' => $sender,
            'recipient' => $to,
            'content' => $message,
        ]);

        try {
            return $this->api->sendTransacSms($sms);
        } catch (\Exception $e) {
            // Tu peux logger l'erreur pour le suivi
            \Log::error("Erreur envoi SMS : " . $e->getMessage());
            return false;
        }
    }
}