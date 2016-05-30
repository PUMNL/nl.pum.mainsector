{* HEADER *}
<h3>{$actionLabel}&nbsp;{$headerLabel} for {$contactName}</h3>

<div class="crm-block crm-form-block">
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
  <div class="crm-section">
    <div class="label">{$form.contact_segment_role.label}</div>
    <div class="content">{$form.contact_segment_role.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.segment_parent.label}</div>
    <div class="content">{$form.segment_parent.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.segment_child.label}</div>
    <div class="content">{$form.segment_child.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.start_date.label}</div>
    {if $action eq 4}
      <div class="content">{$form.start_date.value|crmDate}</div>
    {else}
      <div class="content">{include file="CRM/common/jcalendar.tpl" elementName=start_date}</div>
    {/if}
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.end_date.label}</div>
    {if $action eq 4}
      <div class="content">{$form.end_date.value|crmDate}</div>
    {else}
      <div class="content">{include file="CRM/common/jcalendar.tpl" elementName=end_date}</div>
    {/if}
    <div class="clear"></div>
  </div>

  <div class="crm-section is-main-section">
    <div class="label">{$form.is_main.label}</div>
    <div class="content">{$form.is_main.html}</div>
    <div class="clear"></div>
  </div>
  {* FOOTER *}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>

{* show or hide parent select list *}
{literal}
  <script type="text/javascript">
    cj("#segment_parent").change(function() {
      var parentId = cj("#segment_parent").val();
      getSegmentChildren(parentId);
      showHideIsMain();
    });

    cj('#contact_segment_role').change(function() {
      showHideIsMain();
    });

    cj('#segment_child').change(function() {
      showHideIsMain();
    });

    function showHideIsMain() {
      var childId = cj('#segment_child').val();
      var parentId = cj("#segment_parent").val();
      var role = cj("#contact_segment_role").val();

      if (parentId && parentId > 0 && role == 'Expert' && childId == 0) {
        cj('.is-main-section').show();
      } else {
        cj('.is-main-section').hide();
      }
    }

    showHideIsMain();
  </script>
{/literal}

