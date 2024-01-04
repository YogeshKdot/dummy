<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div  class="row">
  <div class="col-md-3">
    <button class="btn btn-primary" onclick="add(); return false;"><?php echo _l('add'); ?></button>
  </div>
  <div class="col-md-3">
  </div>
  <div class="col-md-3">
  </div>
  <div class="col-md-3">
  </div>
  <div class="clearfix"></div>
  <br>
  <div class="clearfix"></div>
  <div  class="col-md-12">
    <table class="table table-customfield scroll-responsive">
     <thead>
       <tr>
        <th><?php echo _l('fe_name'); ?></th>
        <th><?php echo _l('fe_qty_fields'); ?></th>
        <th><?php echo _l('fe_used_by_models'); ?></th>
        <th><?php echo _l('fe_notes'); ?></th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
</div>

<div class="modal fade" id="add_fieldset" tabindex="-1" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
     <span class="add-title"><?php echo _l('fe_new_fieldset'); ?></span>
     <span class="edit-title hide"><?php echo _l('fe_edit_fieldset'); ?></span>
   </h4>
 </div>
 <?php echo form_open(admin_url('fixed_equipment/add_fieldset'),array('id'=>'add_fieldset-form')); ?>              
 <div class="modal-body content">
  <div class="row">
    <div class="col-md-12">
      <input type="hidden" name="id">
      <?php echo render_input('name', 'fe_name'); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php echo render_textarea('notes', 'fe_notes'); ?>
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

<div id="ic_file_data"></div>

