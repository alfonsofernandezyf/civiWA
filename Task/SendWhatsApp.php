<?php

class CRM_CiviWA_Task_SendWhatsApp extends CRM_Contact_Form_Task {
    public function preProcess() {
        parent::preProcess();
        $this->assign('selectedContactIDs', $this->_contactIds);
    }

    public function buildQuickForm() {
        $this->add('textarea', 'message', ts('WhatsApp Message'), ['rows' => 5, 'cols' => 50], TRUE);
        parent::buildQuickForm();
    }

    public function postProcess() {
        $params = $this->controller->exportValues();
        $message = $params['message'];
        $contactIds = $this->_contactIds;

        foreach ($contactIds as $contactId) {
            // Get the contact's phone number
            $contact = civicrm_api3('Contact', 'get', [
                'id' => $contactId,
                'sequential' => 1,
            ]);
            $phone = $contact['values'][0]['phone'];

            // Validate and format phone number
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strpos($phone, '52') !== 0) {
                $phone = '52' . $phone; // Add country code if missing
            }

            // Send WhatsApp message
            sendWhatsAppMessage($phone, $message);
        }

        CRM_Core_Session::setStatus(ts('WhatsApp messages sent to selected contacts.'), ts('Success'), 'success');
    }
}
