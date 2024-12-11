<?php
// Archivo principal: civiwa.php
require_once 'civiwa.civix.php'; // Incluye soporte para Civix (si estás usando Civix)

function civiwa_civicrm_searchTasks($objectName, &$tasks) {
    error_log("Hook civicrm_searchTasks ejecutado para $objectName."); // Depuración
    if ($objectName === 'Contact') {
        $tasks[] = [
            'title' => ts('Enviar Mensaje de WhatsApp'),
            'class' => 'CRM_CiviWA_Task_SendWhatsApp',
            'result' => TRUE,
        ];
        error_log("Acción 'Enviar Mensaje de WhatsApp' añadida."); // Depuración
    }
}
