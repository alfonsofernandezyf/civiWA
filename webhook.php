<?php
// Webhook to receive WhatsApp messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['messages'])) {
        $message = $input['messages'][0]['text']['body'];
        $phone = $input['messages'][0]['from'];

        // Use CiviCRM API to find or create a contact
        $contact = civicrm_api3('Contact', 'get', [
            'phone' => $phone
        ]);

        if ($contact['count'] == 0) {
            $newContact = civicrm_api3('Contact', 'create', [
                'first_name' => 'Nuevo',
                'phone' => $phone
            ]);
            $contactId = $newContact['id'];
        } else {
            $contactId = $contact['id'];
        }

        // Log the WhatsApp message as an activity
        civicrm_api3('Activity', 'create', [
            'contact_id' => $contactId,
            'subject' => 'Mensaje de WhatsApp',
            'details' => $message,
            'activity_type_id' => 'Mensaje WhatsApp'
        ]);
    }

    http_response_code(200);
    echo 'OK';
} else {
    http_response_code(405);
    echo 'Method not allowed';
}
