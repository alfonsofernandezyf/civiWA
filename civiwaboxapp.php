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
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function civiwaboxapp_civicrm_enable(): void {
  _civiwaboxapp_civix_civicrm_enable();
}
