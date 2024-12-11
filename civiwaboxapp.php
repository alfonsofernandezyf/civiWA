<?php

require_once 'civiwaboxapp.civix.php';

use CRM_Civiwaboxapp_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function civiwaboxapp_civicrm_config(&$config): void {
  _civiwaboxapp_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function civiwaboxapp_civicrm_install(): void {
  _civiwaboxapp_civix_civicrm_install();

  // Guarda las credenciales en la base de datos

}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function civiwaboxapp_civicrm_enable(): void {
  _civiwaboxapp_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_links().
 *
 * Adds a link to send WhatsApp messages from the contact screen.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_links/
 */
function civiwaboxapp_civicrm_links(&$links, $entity) {
  CRM_Core_Error::debug_log_message("Hook civicrm_links invoked for entity: $entity");

  // Asegúrate de que $links sea un array
  if (!is_array($links)) {
      $links = [];
  }

  // Agregar enlace solo para contactos
  if ($entity === 'Contact') {
      $links[] = [
          'title' => ts('Send WhatsApp'),
          'url' => CRM_Utils_System::url(
              'civicrm/civiwaboxapp/send',
              ['cid' => '%%id%%']
          ),
          'class' => 'action-item',
      ];
      CRM_Core_Error::debug_log_message("Added 'Send WhatsApp' link.");
  }
}

function civiwaboxapp_civicrm_pageRun($page) {
  if ($page instanceof CRM_Civiwaboxapp_Page_SendMessage) {
      CRM_Core_Resources::singleton()->addScriptFile('org.example.civiwaboxapp', 'templates/SendMessage.js');
  }
}

function civiwaboxapp_civicrm_alterProviderTypes(&$providerTypes) {
  $providerTypes['waboxapp'] = 'CRM_Civiwaboxapp_Provider_WaboxAppProvider';
}

