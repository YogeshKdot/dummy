<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div  class="row">
 <div class="col-md-12">
  <button class="btn btn-primary" onclick="add(); return false;"><?php echo _l('add'); ?></button>
</div>
<div class="clearfix"></div>
<br>
<div class="clearfix"></div>
<div  class="col-md-12">
  <table class="table table-locations scroll-responsive">
   <thead>
     <tr>
      <th><?php echo _l('id'); ?></th>
      <th><?php echo _l('fe_location_name'); ?></th>
      <th ><?php echo _l('fe_image'); ?></th>
      <th ><?php echo _l('fe_parent'); ?></th>
      <th ><?php echo _l('fe_assets'); ?></th>
      <th ><?php echo _l('fe_assets_assigned'); ?></th>
      <th ><?php echo _l('fe_currency') ?></th>
      <th ><?php echo _l('fe_address'); ?></th>
      <th ><?php echo _l('fe_city'); ?></th>
      <th ><?php echo _l('fe_state'); ?></th>
      <th ><?php echo _l('fe_postal_code'); ?></th>
      <th ><?php echo _l('fe_country'); ?></th>
      <th ><?php echo _l('fe_manager'); ?></th>
      <th ><?php echo _l('fe_created_at'); ?></th>
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
   <td></td>
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
     <span class="add-title"><?php echo _l('fe_add_locations'); ?></span>
     <span class="edit-title hide"><?php echo _l('fe_edit_locations'); ?></span>
   </h4>
 </div>
 <?php echo form_open_multipart(admin_url('fixed_equipment/add_locations'),array('id'=>'form_locations')); ?>              
 <div class="modal-body content">
  <?php $this->load->view('settings/includes/locations_modal_content'); ?>
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

