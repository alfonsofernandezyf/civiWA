<?php
// Cargar las credenciales desde el archivo de configuración
$config = include __DIR__ . '/config.php';

/**
 * Enviar un mensaje de WhatsApp usando Waboxapp.
 */
function sendWhatsAppMessage($phone, $message) {
    global $config;

    $apiToken = $config['waboxapp']['api_token'];
    $uid = $config['waboxapp']['uid'];
    $customUid = uniqid('msg_', true);

    $url = 'https://www.waboxapp.com/api/send/chat';
    $data = [
        'token' => $apiToken,
        'uid' => $uid,
        'to' => $phone,
        'custom_uid' => $customUid,
        'text' => $message,
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        error_log('Error enviando mensaje de WhatsApp: ' . print_r($data, true));
        return false;
    }

    error_log('Mensaje enviado correctamente: ' . $result);
    return $result;
}

/**
 * Hook: civicrm_post
 * Ejecuta acciones después de crear actividades.
 */
function civiwa_civicrm_post($op, $objectName, $objectId, &$objectRef) {
    if ($objectName == 'Activity' && $op == 'create') {
        $activity = civicrm_api3('Activity', 'get', ['id' => $objectId]);
        $activityType = $activity['values'][$objectId]['activity_type_id'];

        // Verificar si es una actividad de tipo "Mensaje WhatsApp"
        if ($activityType == 69) { // ID de "Mensaje WhatsApp"
            $targetContactId = $activity['values'][$objectId]['target_contact_id'][0];
            $contact = civicrm_api3('Contact', 'get', ['id' => $targetContactId]);
            $phone = $contact['values'][$targetContactId]['phone'];

            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strpos($phone, '52') !== 0) {
                $phone = '52' . $phone; // Agregar código de país si falta
            }

            $message = $activity['values'][$objectId]['subject'];
            sendWhatsAppMessage($phone, $message);
        }
    }
}

/**
 * Hook: civicrm_searchTasks
 * Agrega la acción "Enviar Mensaje de WhatsApp" a la lista de contactos.
 */
function civiwa_civicrm_searchTasks($objectName, &$tasks) {
    if ($objectName === 'Contact') {
        $tasks[] = [
            'title' => ts('Enviar Mensaje de WhatsApp'),
            'class' => 'CRM_CiviWA_Task_SendWhatsApp',
            'result' => TRUE,
        ];
    }
}
