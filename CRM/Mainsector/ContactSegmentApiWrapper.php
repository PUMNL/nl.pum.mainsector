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
    switch ($apiRequest['action']) {
      case 'get':
        if (isset($result['values'])) {
          foreach ($result['values'] as $key => $value) {
            $isMain = CRM_Core_DAO::singleValueQuery('SELECT is_main FROM civicrm_contact_segment WHERE id = %1',
              array(1 => array($value['id'], 'Integer')));
            $result['values'][$key]['is_main'] = $isMain;
          }
        }
        break;
      case "create":
        if (isset($apiRequest['params']['is_main'])) {
          if (!isset($apiRequest['params']['id'])) {
            $contactSegmentId = $result['values'][0];
          } else {
            $contactSegmentId = $apiRequest['params']['id'];
          }
          $query = 'UPDATE civicrm_contact_segment SET is_main = %1 WHERE id = %2';
          $params = array(
            1 => array($apiRequest['params']['is_main'], 'Integer'),
            2 => array($contactSegmentId, 'Integer'));
          CRM_Core_DAO::executeQuery($query, $params);
          $result['values'] = civicrm_api3('ContactSegment', 'Getsingle', array('id' => $contactSegmentId));
          break;
        }
    }
    return $result;
  }
}