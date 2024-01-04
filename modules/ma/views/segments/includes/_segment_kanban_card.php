<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<li data-segment-id="<?php echo html_entity_decode($segment['id']); ?>" class="task<?php if(has_permission('tasks','','create') || has_permission('tasks','','edit')){echo ' sortable';} ?>">
    <div class="panel-body border-top-2 border-top-solid" style="border-top-color: <?php echo html_entity_decode($campaign['color']); ?> !important;">
    <div class="row">
      <div class="col-md-12 task-name">
    <a href="<?php echo admin_url('ma/segment_detail/'.$segment['id']); ?>" class="task_milestone pull-left"><span class="inline-block full-width mtop10"><?php echo html_entity_decode($segment['name']); ?></span></a>
    </div>
    <div class="col-md-6 text-muted mtop10">
      <?php 
      if($segment['published'] == 1){ ?>
        <span class="text-success"><?php echo _l('published'); ?></span>
      <?php } ?>
     </div>
    <div class="col-md-6 text-right text-muted mtop10">
      <span class="inline-block text-muted" data-toggle="tooltip" data-title="<?php echo _l('total_lead'); ?>">
       <i class="fa fa-tty"></i>
       <?php echo count($this->ma_model->get_lead_by_segment($segment['id'])); ?>
     </span>
   </div>
  </div>
</div>
</li>
