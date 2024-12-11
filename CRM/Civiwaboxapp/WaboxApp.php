<?php

namespace CRM_Civiwaboxapp;

use GuzzleHttp\Client;
use CRM_Core_Error;

class WaboxApp {
    private $apiToken;
    private $phoneNumber;

    public function __construct() {
        // Cargar las credenciales desde el archivo config.php
        $config = include __DIR__ . '/../../../config.php';
        $this->apiToken = $config['api_token'];
        $this->phoneNumber = $config['phone_number'];
    }

    public function sendMessage($to, $message) {
        $client = new Client();
        $url = "https://www.waboxapp.com/api/send/chat";

        CRM_Core_Error::debug_log_message("Sending message to: $to");
        CRM_Core_Error::debug_var("Message content", $message);

        try {
            $response = $client->post($url, [
                'json' => [
                    'token' => $this->apiToken,
                    'uid' => $this->phoneNumber,
                    'to' => $to,
                    'custom_uid' => uniqid(),
                    'text' => $message,
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            CRM_Core_Error::debug_var("API Response", $result);
            return $result;
        } catch (\Exception $e) {
            CRM_Core_Error::debug_log_message("Error sending message: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
