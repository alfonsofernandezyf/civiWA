<?php

namespace CRM_Civiwaboxapp;

use GuzzleHttp\Client;
use CRM_Core_Error;

class WaboxApp {
    private $apiToken;
    private $phoneNumber;
    private $apiUrl = 'https://www.waboxapp.com/api/send/chat';

    public function __construct() {
        // Cargar las credenciales desde config.php
        $config = include __DIR__ . '/../../../config.php';
        $this->apiToken = $config['api_token'];
        $this->phoneNumber = $config['phone_number'];
    }

    public function sendMessage($recipient, $message, $metadata = []) {
        $client = new Client();

        // Preparar los datos del mensaje
        $payload = [
            'token' => $this->apiToken,
            'uid' => $this->phoneNumber,
            'to' => $recipient,
            'custom_uid' => uniqid(),
            'text' => $message,
        ];

        // Agregar metadatos adicionales
        if (!empty($metadata)) {
            $payload = array_merge($payload, $metadata);
        }

        CRM_Core_Error::debug_log_message("Enviando mensaje a {$recipient}");

        try {
            $response = $client->post($this->apiUrl, ['json' => $payload]);
            $result = json_decode($response->getBody(), true);

            // Registrar el resultado de la API
            CRM_Core_Error::debug_var('Respuesta de la API de Waboxapp', $result);

            return $result;
        } catch (\Exception $e) {
            CRM_Core_Error::debug_log_message("Error al enviar mensaje: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
