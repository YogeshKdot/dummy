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
<br>
<div class="clearfix"></div>
<table class="table table-asset_checkout scroll-responsive">
    <thead>
        <tr>
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
            <th><?php echo  _l('fe_checkin_checkout'); ?></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div class="modal fade" id="check_in" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
           <span class="add-title"></span>
       </h4>
   </div>
   <?php echo form_open(admin_url('fixed_equipment/check_in_detail_assets'),array('id'=>'check_in_assets-form')); ?>
   <div class="modal-body">
    <input type="hidden" name="parent_id" value="<?php echo html_entity_decode($id) ?>">
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