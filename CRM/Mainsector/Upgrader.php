<?php

/**
 * Collection of upgrade steps.
 */
class CRM_Mainsector_Upgrader extends CRM_Mainsector_Upgrader_Base {

  /**
   * Add column to table on install
   */
  public function install() {
    if (CRM_Core_DAO::checkTableExists('civicrm_contact_segment')) {
      if (!CRM_Core_DAO::checkFieldExists('civicrm_contact_segment', 'is_main')) {
        CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_contact_segment
          ADD COLUMN is_main TINYINT(3) NULL DEFAULT 0 AFTER is_active");
      }
    } else {
      throw new Exception('This extension requires table civicrm_contact_segment
      (extension org.civicoop.contactsegment), could not find this table!');
    }
  }

  /**
   * Upgrade 1001 - add views for expert
   * @return bool
   */
  public function upgrade_1001() {
    $this->ctx->log->info('Applying update 1001 (add expert views)');
    $this->executeSqlFile('sql/create_view_pum_expert_areas_expertise.sql');
    $this->executeSqlFile('sql/create_view_pum_expert_main_sector.sql');
    $this->executeSqlFile('sql/create_view_pum_expert_other_sector.sql');
    return TRUE;
  }
}
