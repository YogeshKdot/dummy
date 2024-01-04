<div class="panel-group no-margin">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo html_entity_decode($nodeId); ?>" aria-expanded="true">
        <span class="text-info glyphicon glyphicon-retweet"> </span><span class="text-info"> <?php echo _l('action'); ?></span>
      </h4>
    </div>
    <div id="collapse_node_<?php echo html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
      <div class="box" node-id="<?php echo html_entity_decode($nodeId); ?>">
        <?php $actions = [
            ['id' => 'change_segments','name' => _l('change_segments')],
            ['id' => 'change_stages','name' => _l('change_stages')],
            ['id' => 'change_points','name' => _l('change_points')],
            ['id' => 'point_action','name' => _l('point_action')],
            ['id' => 'delete_lead','name' => _l('delete_lead')],
            ['id' => 'remove_from_campaign','name' => _l('remove_from_campaign')],
            ['id' => 'convert_to_customer','name' => _l('convert_to_customer')],
          ]; ?>
          <?php echo render_select('action['.$nodeId.']',$actions, array('id', 'name'),'action', '', ['df-action' => ''], [], '', '', false); ?>
          <div class="div_action_change_segments">
            <?php echo render_select('segment['.$nodeId.']',$segments, array('id', 'name'),'segment', '', ['df-segment' => '']); ?>
          </div>
          <div class="div_action_change_stages hide">
            <?php echo render_select('stage['.$nodeId.']',$stages, array('id', 'name'),'stage', '', ['df-stage' => '']); ?>
          </div>
          <div class="div_action_point_action hide">
            <?php echo render_select('point_action['.$nodeId.']',$point_actions, array('id', 'name'),'point_action', '', ['df-point_action' => '']); ?>
          </div>
          <div class="div_action_change_points hide">
            <?php echo render_input('point['.$nodeId.']', 'point', '', 'number', ['df-point' => '']); ?>
          </div>
      </div>
    </div>
  </div>
</div>
