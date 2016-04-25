CREATE OR REPLACE VIEW pum_expert_other_sector AS
  SELECT ccs.id AS contact_segment_id, ccs.contact_id, ccs.start_date, ccs.end_date, ccs.is_active,
    seg.id AS segment_id, seg.label as other_sector
  FROM civicrm_contact_segment ccs JOIN civicrm_segment seg ON ccs.segment_id = seg.id
  WHERE ccs.role_value = "Expert" AND ccs.is_main = 0 AND seg.parent_id IS NULL;