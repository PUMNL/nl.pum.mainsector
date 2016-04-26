<?php

require_once 'mainsector.civix.php';
/**
 * Implements hook_civicrm_apiWrappers()
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_apiWrappers
 */
function mainsector_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if ($apiRequest['entity'] == 'ContactSegment') {
    $wrappers[] = new CRM_Mainsector_ContactSegmentApiWrapper();
  }
}
/**
 * Implements hook_civicrm_alterTemplateFile().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterTemplateFile
 */
function mainsector_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName) {
  CRM_Mainsector_MainSector::alterTemplateFile($formName, $form, $context, $tplName);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buidForm
 */
function mainsector_civicrm_buildForm($formName, &$form) {
  CRM_Mainsector_MainSector::buildForm($formName, $form);
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function mainsector_civicrm_postProcess($formName, &$form) {
  CRM_Mainsector_MainSector::postProcess($formName, $form);
}

/**
 * Implements hook_civicrm_validateForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_validateForm
 */
function mainsector_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  CRM_Mainsector_MainSector::validateForm($form, $fields, $files, $form, $errors);
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function mainsector_civicrm_config(&$config) {
  _mainsector_civix_civicrm_config($config);
}


/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function mainsector_civicrm_xmlMenu(&$files) {
  _mainsector_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 * check if extension org.civicoop.contactsegment is installed
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function mainsector_civicrm_install() {
  if (_mainsector_checkContactSegmentInstalled() == FALSE) {
    throw new Exception(ts('Could not install extension nl.pum.mainsector,
      required extension org.civicoop.contactsegment not installed or disabled'));
  }
  _mainsector_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function mainsector_civicrm_uninstall() {
  _mainsector_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function mainsector_civicrm_enable() {
  if (_mainsector_checkContactSegmentInstalled() == FALSE) {
    throw new Exception(ts('Could not enable extension nl.pum.mainsector,
      required extension org.civicoop.contactsegment not installed or disabled'));
  }
  _mainsector_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function mainsector_civicrm_disable() {
  _mainsector_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function mainsector_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _mainsector_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function mainsector_civicrm_managed(&$entities) {
  _mainsector_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function mainsector_civicrm_caseTypes(&$caseTypes) {
  _mainsector_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function mainsector_civicrm_angularModules(&$angularModules) {
_mainsector_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function mainsector_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _mainsector_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Function to check if extension org.civicoop.contactsegment is installed
 */
function _mainsector_checkContactSegmentInstalled() {
  $foundExtension = FALSE;
  try {
    $installedExtensions = civicrm_api3('Extension', 'Get', array());
    foreach ($installedExtensions['values'] as $extension) {
      if ($extension['key'] = 'org.civicoop.contactsegment' && $extension['status'] == 'installed') {
        $foundExtension = TRUE;
      }
    }
  } catch (CiviCRM_API3_Exception $ex) {
    throw new Exception(ts('Could not get any extensions in mainsector.php function _checkContactSegmentInstalled,
      error from API Extension Get: '.$ex->getMessage()));
  }
  return $foundExtension;
}
