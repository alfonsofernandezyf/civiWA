<?php

namespace CRM_Civiwaboxapp\Provider;

use CRM_SMS_Provider;

class WaboxAppProvider extends CRM_SMS_Provider {
    public function send($recipients, $header, $message, $job = NULL) {
        $wabox = new \CRM_Civiwaboxapp\WaboxApp();
        $responses = [];

        foreach ($recipients as $recipient) {
            $responses[] = $wabox->sendMessage($recipient, $message, $header);
        }

        return $responses;
    }
}
