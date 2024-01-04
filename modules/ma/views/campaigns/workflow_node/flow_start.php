<div class="panel-group no-margin">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo html_entity_decode($nodeId); ?>" aria-expanded="true">
        <span class="text-success glyphicon glyphicon-log-in"> </span><span class="text-success"> <?php echo _l('flow_start'); ?></span>
      </h4>
    </div>
    <div id="collapse_node_<?php echo html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
      <div class="box" node-id="<?php echo html_entity_decode($nodeId); ?>">
          <div class="form-group">
            <label for="lead_data_from"><?php echo _l('lead_data_from'); ?></label><br />
            <div class="radio radio-inline radio-primary">
              <input type="radio" name="lead_data_from[<?php echo html_entity_decode($nodeId); ?>]" id="lead_data_from_segment[<?php echo html_entity_decode($nodeId); ?>]" value="segment" checked df-lead_data_from>
              <label for="lead_data_from_segment[<?php echo html_entity_decode($nodeId); ?>]"><?php echo _l("segment"); ?></label>
            </div>
            <div class="radio radio-inline radio-primary">
              <input type="radio" name="lead_data_from[<?php echo html_entity_decode($nodeId); ?>]" id="lead_data_from_form[<?php echo html_entity_decode($nodeId); ?>]" value="form" df-lead_data_from>
              <label for="lead_data_from_form[<?php echo html_entity_decode($nodeId); ?>]"><?php echo _l("form"); ?></label>
            </div>
          </div>
          <div class="div_lead_data_from_segment">
            <?php echo render_select('segment['. $nodeId.']',$segments, array('id', 'name'),'segment', '', ['df-segment' => '']); ?>
          </div>
          <div class="div_lead_data_from_form hide">
            <?php echo render_select('form['. $nodeId.']',$forms, array('id', 'name'),'form', '', ['df-form' => '']); ?>
          </div>
        </div>
    </div>
  </div>
</div>
