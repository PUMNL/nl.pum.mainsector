<?php
/**
 * Class to process Sector Coordinator for Expert Application
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 26 Apr 2016
 * @license AGPL-3.0
 */

class CRM_Mainsector_SectorCoordinator {
  private $_expertRoleValue = NULL;
  private $_sectorCoordinatorRelTypeId = NULL;
  private $_validCaseStatus = NULL;
  private $_validCaseType = NULL;
  private $_contactSegment = array();

  /**
   * CRM_Mainsector_SectorCoordinator constructor.
   *
   * @param $params
   * @throws Exception when relationship type Sector Coordinator not found
   */
  function __construct($params) {
    $this->_expertRoleValue = "Expert";
    $this->setRelationshipTypes();
    $this->setCaseStatus();
    $this->setCaseTypes();
    $this->_contactSegment = $params;
  }

  /**
   * Method to find all ExpertApplication cases for contact with valid status and no sector coordinator yet,
   * and add sector coordinator for those
   */
  public function addToExpertApplicationCase() {
    if ($this->_contactSegment['is_main']) {
      if ($this->_contactSegment['role_value'] == $this->_expertRoleValue) {
        $validCases = $this->getValidCases();
        $sectorCoordinatorId = CRM_Threepeas_BAO_PumCaseRelation::getSectorCoordinatorId($this->_contactSegment['contact_id']);
        if (!$this->_contactSegment['start_date']) {
          $startDate = date('Ymd');
        } else {
          $startDate = date('Ymd', strtotime($this->_contactSegment['start_date']));
        }
        foreach ($validCases as $validCaseId) {
          CRM_Threepeas_BAO_PumCaseRelation::createCaseRelation($validCaseId, $this->_contactSegment['contact_id'],
            $sectorCoordinatorId, $startDate, 'sector_coordinator');
        }
      }
    }
  }

  /**
   * Method to get all valid cases for contact
   *
   * @return array
   */
  private function getValidCases() {
    $result = array();
    try {
      $cases = civicrm_api3('Case', 'Get', array(
        'contact_id' => $this->_contactSegment['contact_id'],
        'case_type_id' => $this->_validCaseType,
        'status_id' => $this->_validCaseStatus,
        'is_deleted' => 0
      ));
      foreach ($cases['values'] as $case) {
        if (!$this->caseHasSectorCoordinator($case['id'])) {
          $result[] = $case['id'];
        }
      }
    } catch (CiviCRM_API3_Exception $ex) {}
    return $result;
  }

  /**
   * Method to determine if there is a sector coordinator for the case
   *
   * @param int $caseId
   * @return bool
   */
  private function caseHasSectorCoordinator($caseId) {
    try {
      $scCount = civicrm_api3('Relationship', 'Getcount', array(
        'relationship_type_id' => $this->_sectorCoordinatorRelTypeId,
        'case_id' => $caseId));
      if ($scCount > 0) {
        return TRUE;
      }
    } catch (CiviCRM_API3_Exception $ex) {}
    return FALSE;
  }

  /**
   * Method to set the valid case types
   *
   * @throws Exception when error from API
   */
  private function setCaseTypes() {
    try {
      $this->_validCaseType = civicrm_api3('OptionValue', 'Getvalue', array(
        'option_group_id' => 'case_type',
        'name' => 'ExpertApplication',
        'return' => 'value'
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find option value with name ExpertApplication in option group case_type in '.__METHOD__
        .', contact your system administrator. Error from API OptionValue Getvalue: '.$ex->getMessage());
    }
  }

  /**
   * Method to set the valid case status
   *
   * @throws Exception when error from API
   */
  private function setCaseStatus() {
    try {
      $this->_validCaseStatus = civicrm_api3('OptionValue', 'Getvalue', array(
        'option_group_id' => 'case_status',
        'name' => 'Assess CV',
        'return' => 'value'
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find option value with name Assess CV in option group case_status in '.__METHOD__
        .', contact your system administrator. Error from API OptionValue Getvalue: '.$ex->getMessage());
    }
  }

  /**
   * Method to set the relationship types
   *
   * @throws Exception when error from API
   */
  private function setRelationshipTypes() {
    try {
      $this->_sectorCoordinatorRelTypeId = civicrm_api3('RelationshipType', 'Getvalue', array(
        'name_a_b' => 'Sector Coordinator',
        'return' => 'id'
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find a relationship type with name_a_b Sector Coordinator in '.__METHOD__
        .', contact your system administrator. Error from API RelationshipType Getvalue: '.$ex->getMessage());
    }
  }
}

