<?php

/**
 * Class for ContactSegment API wrapper
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 1 Feb 2016
 * @license AGPL-3.0
 */
class CRM_Mainsector_ContactSegmentApiWrapper implements API_Wrapper {
  /**
   * Method to update request (required from abstract class)
   *
   * @param array $apiRequest
   * @return array $apiRequest
   */
  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Method to alter result (add is_main)
   *
   * @param array $apiRequest
   * @param array $result
   * @return array $result
   */
  public function toApiOutput($apiRequest, $result) {
    $apiRequest['action'] = strtolower($apiRequest['action']);
    switch ($apiRequest['action']) {
      case 'get':
        $this->getIsMain($apiRequest['params'], $result);
        break;
      case "create":
        $this->createIsMain($apiRequest['params'], $result);
        break;
    }
    return $result;
  }

  /**
   * Method to process the api create for is_main
   *
   * @param array $apiParams
   * @param array $result
   *
   */
  private function createIsMain($apiParams, &$result) {
    $isMain = isset($apiParams['is_main']) ? $apiParams['is_main'] : 0;
    if (!isset($apiParams['id']) && isset($result['id'])) {
      $contactSegmentId = $result['id'];
    } elseif (!isset($apiParams['id'])) {
      $contactSegmentId = $result['values'][0];
    } else {
      $contactSegmentId = $apiParams['id'];
    }

    $segmentId = CRM_Core_DAO::singleValueQuery("SELECT segment_id FROM `civicrm_contact_segment` WHERE id = %1 ", array(1=>array($contactSegmentId, 'Integer')));
    $segment = civicrm_api3('Segment', 'getsingle', array('id' => $segmentId));
    if (!empty($segment['parent_id'])) {
      $isMain = 0; // Reset is main when segment is an area of expertise.
    }

    $query = 'UPDATE civicrm_contact_segment SET is_main = %1 WHERE id = %2';
    $params = array(
      1 => array($isMain, 'Integer'),
      2 => array($contactSegmentId, 'Integer'));
    CRM_Core_DAO::executeQuery($query, $params);
    $result['values'] = civicrm_api3('ContactSegment', 'Getsingle', array('id' => $contactSegmentId));
    // check if sector coordinators need to be added to the expertapplication case
    if (class_exists('CRM_Mainsector_SectorCoordinator')) {
      $sectorCoordinator = new CRM_Mainsector_SectorCoordinator($result['values']);
      $sectorCoordinator->addToExpertApplicationCase();
    }
    if (function_exists('pum_portal_custom_civicrm_update_sector_community')) {
      pum_portal_custom_civicrm_update_sector_community($contactSegmentId, $isMain);
    }
  }

    /**
   * Method to process the api get request for is_main
   *
   * @param array $apiParams
   * @param array $result
   */
  private function getIsMain($apiParams, &$result) {
    if (isset($result['values'])) {
      foreach ($result['values'] as $key => $value) {
        $isMain = CRM_Core_DAO::singleValueQuery('SELECT is_main FROM civicrm_contact_segment WHERE id = %1',
          array(1 => array($value['id'], 'Integer')));
        $result['values'][$key]['is_main'] = $isMain;
        if (isset($apiParams['is_main'])) {
          if ($isMain != $apiParams['is_main']) {
            unset($result['values'][$key]);
          }
        }
      }
    }
  }
}