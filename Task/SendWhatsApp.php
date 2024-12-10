<?php

class CRM_CiviWA_Task_SendWhatsApp extends CRM_Contact_Form_Task {
    public function preProcess() {
        parent::preProcess();
        $this->assign('selectedContactIDs', $this->_contactIds);
    }

    public function buildQuickForm() {
        $this->add('textarea', 'message', ts('Mensaje de WhatsApp'), ['rows' => 5, 'cols' => 50], TRUE);
        parent::buildQuickForm();
    }

    public function postProcess() {
        $params = $this->controller->exportValues();
        $message = $params['message'];
        $contactIds = $this->_contactIds;

        foreach ($contactIds as $contactId) {
            // Obtener el número de teléfono del contacto
            $phone = $this->getPhoneNumber($contactId);

            if ($phone) {
                // Enviar el mensaje de WhatsApp
                $this->sendWhatsAppMessage($phone, $message);
            }
        }

        CRM_Core_Session::setStatus(ts('Mensajes de WhatsApp enviados a los contactos seleccionados.'), ts('Éxito'), 'success');
    }

    private function getPhoneNumber($contactId) {
        // Obtener el número de teléfono principal del contacto
        $phones = civicrm_api3('Phone', 'get', [
            'contact_id' => $contactId,
            'sequential' => 1,
            'is_primary' => 1,
        ]);

        if ($phones['count'] > 0) {
            $phone = $phones['values'][0]['phone'];
            // Formatear el número de teléfono al formato internacional
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strpos($phone, '52') !== 0) {
                $phone = '52' . $phone; // Añadir código de país si falta (52 para México)
            }
            return $phone;
        }
        return NULL;
    }

    private function sendWhatsAppMessage($phone, $message) {
        $apiToken = 'TU_API_TOKEN_DE_WABOXAPP';
        $uid = 'TU_NUMERO_DE_WHATSAPP_CON_CODIGO_DE_PAIS';
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
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            // Manejar error
        }

        // Procesar la respuesta si es necesario
    }
}
