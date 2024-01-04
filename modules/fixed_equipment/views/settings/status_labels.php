<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div  class="row">
 <div class="col-md-12">
  <button class="btn btn-primary" onclick="add(); return false;"><?php echo _l('add'); ?></button>
</div>
<div class="clearfix"></div>
<br>
<div class="clearfix"></div>
<div  class="col-md-12">
  <table class="table table-status_labels scroll-responsive">
   <thead>
     <tr>
      <th><?php echo _l('id'); ?></th>
      <th><?php echo _l('fe_name'); ?></th>
      <th ><?php echo _l('fe_status_type'); ?></th>
      <th ><?php echo _l('fe_assets'); ?></th>
      <th ><?php echo _l('fe_chart_color'); ?></th>
      <th ><?php echo _l('fe_default_label'); ?></th>
    </tr>
  </thead>
  <tbody></tbody>
  <tfoot>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
 </tfoot>
</table>
</div>
</div>

<div class="modal fade" id="add" tabindex="-1" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
     <span class="add-title"><?php echo _l('fe_add_status_labels'); ?></span>
     <span class="edit-title hide"><?php echo _l('fe_edit_status_labels'); ?></span>
   </h4>
 </div>
 <?php echo form_open(admin_url('fixed_equipment/add_status_labels'),array('id'=>'form_status_labels')); ?>              
 <div class="modal-body">
   <div class="row">

     <input type="hidden" name="id" value="">  
     <div class="col-md-12">
      <?php echo render_input('name','fe_name'); ?>
    </div>
    <div class="col-md-6">
      <?php
      $status_type_list = [
        ['id' => 'deployable', 'label' => _l('fe_deployable')],
        ['id' => 'pending', 'label' => _l('fe_pending')],
        ['id' => 'undeployable', 'label' => _l('fe_undeployable')],
        ['id' => 'archived', 'label' => _l('fe_archived')]
      ];
      echo render_select('status_type', $status_type_list, array('id', 'label'),'fe_status_type', ''); ?>
    </div>
    <div class="col-md-6">
      <?php echo render_input('chart_color','fe_chart_color', '', 'color'); ?>
    </div>
    <div class="col-md-12">
      <?php echo render_textarea('note','fe_note'); ?>
    </div>
    <div class="col-md-12">
      <div class="checkbox">              
        <input type="checkbox" class="capability" name="default_label" value="1">
        <label><?php echo _l('fe_default_label'); ?></label>
      </div>
    </div>

    <div class="col-md-12">
      <h4><?php echo _l('fe_about_status_labels') ?></h4>
      <div class="alert">
        <p><?php echo _l('fe_status_labels_are_used_to_describe_the_various'); ?></p>
      </div>
      <div class="alert alert-success hide_frame hide_deployable hide">
        <p><i class="fa fa-circle text-green"></i> <strong><?php echo _l('fe_deployable'); ?></strong>:  <?php echo _l('fe_these_assets_can_be_checked_out'); ?> <i class="fa fa-circle text-blue"></i> <strong><?php echo _l('fe_deployed'); ?></strong>.</p>
      </div>

      <div class="alert alert-warning hide_frame hide_pending hide">
        <p><i class="fa fa-circle text-orange"></i> <strong><?php echo _l('fe_pending'); ?></strong>: <?php echo _l('fe_these_assets_can_not_yet_be_assigned_to_anyone') ?>.</p>
      </div>

      <div class="alert alert-danger hide_frame hide_undeployable hide">
        <p><i class="fa fa-times text-red"></i> <strong><?php echo _l('fe_undeployable'); ?></strong>: <?php echo _l('fe_these_assets_cannot_be_assigned_to_anyone'); ?>.</p>
      </div>

      <div class="alert alert-danger hide_frame hide_archived hide">
        <p><i class="fa fa-times text-red"></i> <strong><?php echo _l('fe_archived'); ?></strong>: <?php echo _l('fe_these_assets_cannot_be_checked_out') ?>.</p>
      </div>
    </div>



  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
</div>
<?php echo form_close(); ?>                   
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
