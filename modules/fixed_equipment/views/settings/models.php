<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div  class="row">
  <div class="col-md-3">
    <br>
    <button class="btn btn-primary mtop10" onclick="add(); return false;"><?php echo _l('add'); ?></button>
  </div>
  <div class="col-md-3">
    <?php echo render_select('manufacturer_filter[]', $manufacturers, array('id', 'name'), 'fe_manufacturer','',array('multiple' => true)); ?>
  </div>
  <div class="col-md-3">
    <?php echo render_select('category_filter[]', $categories, array('id', 'category_name'), 'fe_category','',array('multiple' => true)); ?>
  </div>
  <div class="col-md-3">
    <?php echo render_select('depreciation_filter[]', $depreciations, array('id', 'name'), 'fe_depreciation','',array('multiple' => true)); ?>
  </div>
  <div class="clearfix"></div>
  <br>
  <div class="clearfix"></div>
  <div  class="col-md-12">
    <table class="table table-models scroll-responsive">
     <thead>
       <tr>
        <th><?php echo _l('id'); ?></th>
        <th><?php echo _l('fe_name'); ?></th>
        <th><?php echo _l('fe_image'); ?></th>
        <th><?php echo _l('fe_manufacturer'); ?></th>
        <th><?php echo _l('fe_model_no'); ?></th>
        <th><?php echo _l('fe_assets'); ?></th>
        <th><?php echo _l('fe_depreciation'); ?></th>
        <th><?php echo _l('fe_category'); ?></th>
        <th>EOL</th>
        <th><?php echo _l('fe_notes'); ?></th>
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
     <span class="add-title"><?php echo _l('fe_add_model'); ?></span>
     <span class="edit-title hide"><?php echo _l('fe_edit_model'); ?></span>
   </h4>
 </div>
 <?php echo form_open_multipart(admin_url('fixed_equipment/add_models'),array('id'=>'form_models')); ?>              
 <div class="modal-body content">
  <?php $this->load->view('settings/includes/models_modal_content'); ?>
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

