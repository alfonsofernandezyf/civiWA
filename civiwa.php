<?php
require_once 'civiwa.civix.php';

/**
 * Implements hook_civicrm_searchTasks().
 */
function civiwa_civicrm_searchTasks($objectName, &$tasks) {
    if ($objectName === 'Contact') {
        $tasks[] = [
            'title' => ts('Enviar Mensaje de WhatsApp'),
            'class' => 'CRM_Civiwa_Task_SendWhatsApp',
            'result' => TRUE,
        ];
    }
}

/**
 * Implements hook_civicrm_post().
 */
function civiwa_civicrm_post($op, $objectName, $objectId, &$objectRef) {
    if ($objectName == 'Activity' && $op == 'create') {
        // Lógica para enviar mensajes de WhatsApp
    }
}
