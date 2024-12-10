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

// Hook for sending WhatsApp messages based on activity creation
function civiwa_civicrm_post($op, $objectName, $objectId, &$objectRef) {
    if ($objectName == 'Activity' && $op == 'create') {
        // Check if the activity type is 69 (Mensaje WhatsApp)
        $activity = civicrm_api3('Activity', 'get', ['id' => $objectId]);
        $activityType = $activity['values'][$objectId]['activity_type_id'];

        if ($activityType == 69) {
            // Get the target contact's phone number
            $targetContactId = $activity['values'][$objectId]['target_contact_id'][0];
            $contact = civicrm_api3('Contact', 'get', ['id' => $targetContactId]);
            $phone = $contact['values'][$targetContactId]['phone'];

            // Validate phone number
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strpos($phone, '52') !== 0) {
                $phone = '52' . $phone; // Add country code if missing
            }

            // Send the WhatsApp message
            $message = $activity['values'][$objectId]['subject']; // Example: Use the subject as the message body
            sendWhatsAppMessage($phone, $message);
        }
    }
}
