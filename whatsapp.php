<?php
// WhatsApp integration functions

function sendWhatsAppMessage($phone, $message) {
    $url = "https://graph.facebook.com/v15.0/YOUR_PHONE_NUMBER_ID/messages";
    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $phone,
        'type' => 'text',
        'text' => ['body' => $message]
    ];
    $headers = [
        'Authorization: Bearer YOUR_ACCESS_TOKEN',
        'Content-Type: application/json'
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

/**
 * Implementa hook_civicrm_searchTasks().
 * Añade una acción personalizada para enviar mensajes de WhatsApp desde los resultados de búsqueda de contactos.
 */
function civiwa_civicrm_searchTasks($objectName, &$tasks) {
    if ($objectName == 'Contact') {
        $tasks[] = [
            'title' => ts('Enviar Mensaje de WhatsApp'),
            'class' => 'CRM_CiviWA_Task_SendWhatsApp',
            'result' => TRUE,
        ];
    }
}
