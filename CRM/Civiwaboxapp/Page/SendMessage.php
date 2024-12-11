<?php

namespace CRM_Civiwaboxapp_Page;

use CRM_Core_Page;
use CRM_Civiwaboxapp\WaboxApp;

class SendMessage extends CRM_Core_Page {
    public function run() {
        $cid = CRM_Utils_Request::retrieve('cid', 'Integer');
        $contact = civicrm_api3('Contact', 'get', ['id' => $cid]);

        if (!empty($_POST['message'])) {
            $message = $_POST['message'];
            $phoneNumber = $contact['values'][$cid]['phone'];

            $wabox = new WaboxApp();
            $result = $wabox->sendMessage($phoneNumber, $message);

            if ($result['success']) {
                CRM_Core_Session::setStatus(ts('Message sent successfully!'), '', 'success');
            } else {
                CRM_Core_Session::setStatus(ts('Error sending message: ' . $result['message']), '', 'error');
            }
        }

        $this->assign('contact', $contact);
        parent::run();
    }
}
