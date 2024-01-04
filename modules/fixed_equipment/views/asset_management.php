<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row panel">
      <div class="col-md-12">
        <h4>
          <br>
          <?php echo html_entity_decode($title); ?>
          <hr>          
        </h4>

        <?php 
        if(has_permission('fixed_equipment_assets', '', 'create') || is_admin()){  ?>
          <button class="btn btn-primary" onclick="add();"><?php echo _l('add'); ?></button>
          <a class="btn btn-warning mleft10" href="<?php echo admin_url('fixed_equipment/bulk_upload/asset'); ?>"><?php echo _l('fe_bulk_upload'); ?></a>
          <div class="clearfix"></div>
          <br>
        <?php } ?>
        <div class="row">
          <div class="col-md-3">
            <?php echo render_select('model_filter', $models, array('id', 'model_name'), 'fe_model'); ?>
          </div>

          <div class="col-md-3">
            <?php echo render_select('status_filter', $status_labels, array('id', 'name'), 'fe_status'); ?>
          </div>

          <div class="col-md-3">
            <?php echo render_select('supplier_filter', $suppliers, array('id', 'supplier_name'), 'fe_supplier'); ?>
          </div>

          <div class="col-md-3">
            <?php echo render_select('location_filter', $locations, array('id', 'location_name'), 'fe_default_location'); ?>
          </div>
        </div>

        <div class="clearfix"></div>
          <a href="#" onclick="bulk_print(); return false;"  data-toggle="modal" data-table=".table-assets_management" data-target="#leads_bulk_actions" class=" hide bulk-actions-btn table-btn"><?php echo _l('fe_print_qrcode'); ?></a> 
        <table class="table table-assets_management scroll-responsive">
         <thead>
           <tr>
            <th><input type="checkbox" id="mass_select_all" data-to-table="checkout_managements"></th>
            <th>ID</th>
            <th><?php echo  _l('fe_asset_name'); ?></th>
            <th><?php echo  _l('fe_image'); ?></th>
            <th><?php echo  _l('fe_serial'); ?></th>
            <th><?php echo  _l('fe_model'); ?></th>
            <th><?php echo  _l('fe_model_no'); ?></th>
            <th><?php echo  _l('fe_category'); ?></th>
            <th><?php echo  _l('fe_status'); ?></th>
            <th><?php echo  _l('fe_checkout_to'); ?></th>
            <th><?php echo  _l('fe_location'); ?></th>
            <th><?php echo  _l('fe_default_location'); ?></th>
            <th><?php echo  _l('fe_manufacturer'); ?></th>
            <th><?php echo  _l('fe_supplier'); ?></th>
            <th><?php echo  _l('fe_purchase_date'); ?></th>
            <th><?php echo  _l('fe_purchase_cost'); ?></th>
            <th><?php echo  _l('fe_order_number'); ?></th>
            <th><?php echo  _l('fe_warranty'); ?></th>
            <th><?php echo  _l('fe_warranty_expires'); ?></th>
            <th><?php echo  _l('fe_notes'); ?></th>
            <th><?php echo  _l('fe_checkouts'); ?></th>
            <th><?php echo  _l('fe_checkins'); ?></th>
            <th><?php echo  _l('fe_requests'); ?></th>
            <th><?php echo  _l('fe_created_at'); ?></th>
            <th><?php echo  _l('fe_updated_at'); ?></th>
            <th><?php echo  _l('fe_checkout_date'); ?></th>
            <th><?php echo  _l('fe_expected_checkin_date'); ?></th>
            <th><?php echo  _l('fe_last_audit'); ?></th>
            <th><?php echo  _l('fe_next_audit_date'); ?></th>
            <?php 
            if(is_admin() || has_permission('fixed_equipment_assets', '', 'create')){
             ?>
             <th><?php echo  _l('fe_checkin_checkout'); ?></th>
           <?php } ?>
         </tr>
       </thead>
       <tbody></tbody>
     </table>



   </div>
 </div>
</div>
</div>

<div class="modal fade" id="add_new_assets" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
         <span class="edit-title hide"><?php echo _l('fe_edit_asset'); ?></span>
         <span class="add-title"><?php echo _l('fe_add_asset'); ?></span>
       </h4>
     </div>
     <?php echo form_open_multipart(admin_url('fixed_equipment/add_assets'),array('id'=>'assets-form', 'onsubmit'=>'return validateForm()')); ?>
     <div class="modal-body">
      <?php $this->load->view('includes/new_asset_modal'); ?>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
    </div>
    <?php echo form_close(); ?>                 
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="check_in" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
         <span class="add-title"></span>
       </h4>
     </div>
     <?php echo form_open(admin_url('fixed_equipment/check_in_assets'),array('id'=>'check_in_assets-form')); ?>
     <div class="modal-body">
      <input type="hidden" name="item_id" value="">
      <input type="hidden" name="type" value="checkin">
      <div class="row">
        <div class="col-md-12">
          <?php echo render_input('model', 'fe_model', '', 'text', array('readonly' => true)); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_input('asset_name','fe_asset_name'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_select('status', $status_labels, array('id', 'name'), 'fe_status'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_select('location_id', $locations, array('id', 'location_name'), 'fe_locations'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_date_input('checkin_date','fe_checkin_date'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_textarea('notes','fe_notes'); ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      <button type="submit" class="btn btn-info"><?php echo _l('fe_checkin'); ?></button>
    </div>
    <?php echo form_close(); ?>                 
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="check_out" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
         <span class="add-title"></span>
       </h4>
     </div>
     <?php echo form_open(admin_url('fixed_equipment/check_in_assets'),array('id'=>'check_out_assets-form')); ?>
     <div class="modal-body">
      <input type="hidden" name="item_id" value="">
      <input type="hidden" name="type" value="checkout">
      <div class="row">
        <div class="col-md-12">
          <?php echo render_input('model', 'fe_model', '', 'text', array('readonly' => true)); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_input('asset_name','fe_asset_name'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_select('status', $status_label_checkout, array('id', 'name'), 'fe_status'); ?>
        </div>
      </div>

      <div class="row mbot15">
        <div class="col-md-12">
          <label for="location" class="control-label"><?php echo _l('fe_checkout_to'); ?></label>          
        </div>
        <div class="col-md-12">

          <div class="pull-left">
            <div class="checkbox">
              <input type="radio" name="checkout_to" id="checkout_to_user" value="user" checked>
              <label for="checkout_to_user"><?php echo _l('fe_staffs'); ?></label>
            </div>    
          </div>
          <div class="pull-left">
            <div class="checkbox">
              <input type="radio" name="checkout_to" id="checkout_to_asset" value="asset">
              <label for="checkout_to_asset"><?php echo _l('fe_asset'); ?></label>
            </div>  
          </div>
          <div class="pull-left">
            <div class="checkbox">
              <input type="radio" name="checkout_to" id="checkout_to_location" value="location">
              <label for="checkout_to_location"><?php echo _l('fe_location'); ?></label>
            </div> 
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 checkout_to_fr checkout_to_location_fr hide">
          <?php echo render_select('location_id', $locations, array('id', 'location_name'), 'fe_locations'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 checkout_to_fr checkout_to_asset_fr hide">
          <?php echo render_select('asset_id', $assets, array('id',array('series', 'assets_name')), 'fe_assets'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 checkout_to_fr checkout_to_staff_fr">
          <?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_staffs'); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <?php echo render_date_input('checkin_date','fe_checkout_date'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_date_input('expected_checkin_date','fe_expected_checkin_date'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_textarea('notes','fe_notes'); ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      <button type="submit" class="btn btn-info"><?php echo _l('fe_checkout'); ?></button>
    </div>
    <?php echo form_close(); ?>                 
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type="hidden" name="check">
<?php init_tail(); ?>
<?php 
require('modules/fixed_equipment/assets/js/asset_management_js.php');
?>
</body>
</html>
