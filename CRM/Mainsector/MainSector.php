<?php

/**
 * Class to process hooks for MainSector
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 1 Feb 2016
 * @license AGPL-3.0
 */
class CRM_Mainsector_MainSector {

  /**
   * Method to implement hook civicrm_buildForm for Main Sector
   * @param $formName
   * @param $form
   */
  public static function buildForm($formName, &$form) {
    if ($formName == "CRM_Contactsegment_Form_ContactSegment") {
      $form->add('checkbox', 'is_main', ts('Main Sector?'));
      $action = $form->getVar('_action');
      if ($action != CRM_Core_Action::ADD) {
        $contactSegmentId = $form->getVar('_contactSegmentId');
        $query = 'SELECT is_main FROM civicrm_contact_segment WHERE id = %1';
        $defaults['is_main'] = CRM_Core_DAO::singleValueQuery($query,
          array(1 => array($contactSegmentId, 'Integer')));
        $form->setDefaults($defaults);
      }
    }
  }

  /**
   * Method to implement hook civicrm_alterTemplateFile for Main Sector
   *
   * @param $formName
   * @param $form
   * @param $context
   * @param $tplName
   */
  public static function alterTemplateFile($formName, &$form, $context, &$tplName) {
    // add is_main column to page
    if ($formName == "CRM_Contactsegment_Page_ContactSegment") {
      $tplName = 'CRM/Mainsector/Page/ContactSegment.tpl';
    }
    // only show is main on form for Expert role and Sector OR add mode
    // todo : jQuery to remove is_main if any other role than Expert is selected or if area of expertise is selected
    if ($formName == "CRM_Contactsegment_Form_ContactSegment") {
      if (!isset($form->_defaultValues['segment_child'])) {
        $action = $form->getVar('_action');
        if ($form->_defaultValues['contact_segment_role'] == "Expert" || $action == CRM_Core_Action::ADD) {
          $tplName = 'CRM/Mainsector/Form/ContactSegment.tpl';
        }
      }
    }
  }

  /**
   * Method to implement hook civicrm_postProcess for Main Sector
   * @param $formName
   * @param $form
   */
  public static function postProcess($formName, &$form) {
    if ($formName == "CRM_Contactsegment_Form_ContactSegment") {
      $submitValues = $form->getVar('_submitValues');
      if (isset($submitValues['contact_segment_role']) && isset($submitValues['segment_child'])) {
        if ($submitValues['contact_segment_role'] == 'Expert' && empty($submitValues['segment_child'])) {
          if (!isset($submitValues['is_main'])) {
            $submitValues['is_main'] = 0;
          }
          $apiParams = array(
            'contact_id' => $submitValues['contact_id'],
            'role_value' => 'Expert',
            'segment_id' => $submitValues['segment_parent'],
            'is_main' => $submitValues['is_main'],
          );
          if ($submitValues['start_date']) {
            $apiParams['start_date'] = $submitValues['start_date'];
          }
          if ($submitValues['end_date']) {
            $apiParams['end_date'] = $submitValues['end_date'];
          }
          // if action is add, then retrieve the contact segment just created
          $action = $form->getVar('_action');
          if ($action == CRM_Core_Action::ADD) {
            $existing = civicrm_api3('ContactSegment', 'Get', array(
              'contact_id' => $submitValues['contact_id'],
              'segment_id' => $submitValues['segment_parent'],
              'role_value' => 'Expert'
            ));
            foreach ($existing['values'] as $existingContactSegment) {
              $apiParams['id'] = $existingContactSegment['id'];
            }
          }
          if (isset($submitValues['contact_segment_id']) && !empty($submitValues['contact_segment_id'])) {
            $apiParams['id'] = $submitValues['contact_segment_id'];
          }
          civicrm_api3('ContactSegment', 'create', $apiParams);
        }
      }
    }
  }

  /**
   * Method to implement hook civicrm_validateForm for Main Sector
   * @param $form
   * @param $fields
   * @param $files
   * @param $form
   * @param $errors
   */
  public static function validateForm($form, $fields, $files, $form, &$errors) {
    // error if contact already has another main sector (only for role Expert and Sector)
    if (isset($fields['contact_segment_role']) && isset($fields['segment_child']) && isset($fields['is_main'])) {
      if ($fields['contact_segment_role'] == 'Expert' && empty($fields['segment_child']) && $fields['is_main'] == 1) {
        $query = 'SELECT COUNT(*) AS countMain FROM civicrm_contact_segment
          WHERE is_main = %1 AND contact_id = %2 AND role_value = %3 AND segment_id != %4';
        $params = array(
          1 => array(1, 'Integer'),
          2 => array($fields['contact_id'], 'Integer'),
          3 => array('Expert', 'String'),
          4 => array($fields['segment_parent'], 'Integer')
        );
        $countMain = CRM_Core_DAO::singleValueQuery($query, $params);
        if ($countMain > 0) {
          $errors['is_main'] = ts('This contact already has a Main Sector with role Expert.
            If you want to change the main sector you have to un-select the other active one first!');
        }
      }
    }
  }
}