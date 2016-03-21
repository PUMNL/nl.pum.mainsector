CREATE OR REPLACE VIEW pum_expert_areas_expertise AS
  SELECT ccs.id AS contact_segment_id, ccs.contact_id, ccs.start_date, ccs.end_date, ccs.is_active,
    seg.id AS segment_id, seg.label as area_expertise, seg.parent_id AS expertise_parent,
    parent.label as parent_name
  FROM civicrm_contact_segment ccs
    JOIN civicrm_segment seg ON ccs.segment_id = seg.id
    JOIN civicrm_segment parent ON seg.parent_id = parent.id
  WHERE ccs.role_value = "Expert" AND seg.parent_id IS NOT NULL