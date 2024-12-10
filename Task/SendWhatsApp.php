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
            $phone = $this->getPhoneNumber($contactId);
            if ($phone) {
                sendWhatsAppMessage($phone, $message);
            }
        }

        CRM_Core_Session::setStatus(ts('Mensajes enviados a los contactos seleccionados.'), ts('Éxito'), 'success');
    }

    private function getPhoneNumber($contactId) {
        $phones = civicrm_api3('Phone', 'get', [
            'contact_id' => $contactId,
            'sequential' => 1,
            'is_primary' => 1,
        ]);

        if ($phones['count'] > 0) {
            $phone = $phones['values'][0]['phone'];
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strpos($phone, '52') !== 0) {
                $phone = '52' . $phone;
            }
            return $phone;
        }
        return NULL;
    }
}
