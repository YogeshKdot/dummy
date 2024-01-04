          
<div class="row">
  <div class="col-md-3">
    <?php 
    if(is_admin() || has_permission('fixed_equipment_maintenances', '', 'create')){
     ?>
     <br>
     <button class="btn btn-primary mtop15" onclick="add_maintenance();"><?php echo _l('add'); ?></button>
   <?php } ?>
 </div>

 <div class="col-md-3">
  <?php 
  $maintenances = [
    ['id' => 'maintenance', 'maintenance_name' => _l('fe_maintenance')],
    ['id' => 'repair', 'maintenance_name' => _l('fe_repair')],
    ['id' => 'upgrade', 'maintenance_name' => _l('fe_upgrade')],
    ['id' => 'pat_test', 'maintenance_name' => _l('fe_pat_test')],
    ['id' => 'calibration', 'maintenance_name' => _l('fe_calibration')],
    ['id' => 'software_support', 'maintenance_name' => _l('fe_software_support')],
    ['id' => 'hardware_support', 'maintenance_name' => _l('fe_hardware_support')]
  ];
  echo render_select('maintenance_type_filter', $maintenances, array('id', 'maintenance_name'), 'fe_maintenance_type');
  ?>
</div>

<div class="col-md-3">
  <?php echo render_date_input('from_date_filter', 'fe_from_date'); ?>
</div>

<div class="col-md-3">
  <?php echo render_date_input('to_date_filter', 'fe_to_date'); ?>
</div>
</div>

<div class="clearfix"></div>
<br>
<div class="clearfix"></div>
<table class="table table-assets_maintenances scroll-responsive">
 <thead>
   <tr>
    <th>ID</th>
    <th><?php echo  _l('fe_asset_name'); ?></th>
    <th><?php echo  _l('fe_serial'); ?></th>
    <th><?php echo  _l('fe_location'); ?></th>
    <th><?php echo  _l('fe_maintenance_type'); ?></th>
    <th><?php echo  _l('fe_title'); ?></th>
    <th><?php echo  _l('fe_start_date'); ?></th>
    <th><?php echo  _l('fe_completion_date'); ?></th>
    <th><?php echo  _l('fe_notes'); ?></th>
    <th><?php echo  _l('fe_warranty'); ?></th>
    <th><?php echo  _l('fe_cost'); ?></th>
  </tr>
</thead>
<tbody></tbody>
</table>
<div class="modal fade" id="add_new_assets_maintenances" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
         <span class="add-title hide"><?php echo _l('fe_create_asset_maintenance'); ?></span>
         <span class="edit-title"><?php echo _l('fe_edit_asset_maintenance'); ?></span>
       </h4>
     </div>
     <?php echo form_open(admin_url('fixed_equipment/assets_detail_maintenances'),array('id'=>'assets_maintenances-form')); ?>
     <div class="modal-body">
       <br>
       <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
       <input type="hidden" name="maintenance_id">
       <div class="row">
        <div class="col-md-12">
          <?php echo render_select('asset_id', $assets, array('id', array('series', 'assets_name')), 'fe_asset'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_select('supplier_id', $suppliers, array('id', 'supplier_name'), 'fe_supplier'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php
          $maintenances = [
            ['id' => 'maintenance', 'maintenance_name' => _l('fe_maintenance')],
            ['id' => 'repair', 'maintenance_name' => _l('fe_repair')],
            ['id' => 'upgrade', 'maintenance_name' => _l('fe_upgrade')],
            ['id' => 'pat_test', 'maintenance_name' => _l('fe_pat_test')],
            ['id' => 'calibration', 'maintenance_name' => _l('fe_calibration')],
            ['id' => 'software_support', 'maintenance_name' => _l('fe_software_support')],
            ['id' => 'hardware_support', 'maintenance_name' => _l('fe_hardware_support')]
          ];
          echo render_select('maintenance_type', $maintenances, array('id', 'maintenance_name'), 'fe_maintenance');
          ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php echo render_input('title', 'fe_title'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <?php echo render_date_input('start_date', 'fe_start_date'); ?>
        </div>
        <div class="col-md-6">
          <?php echo render_date_input('completion_date', 'fe_completion_date'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="checkbox mtop15">              
          <input type="checkbox" class="capability" name="warranty_improvement" value="1">
          <label><?php echo _l('fe_warranty_improvement'); ?></label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
       <div class="form-group">
        <label for="gst"><?php echo _l('fe_cost'); ?></label>            
        <div class="input-group">
          <span class="input-group-addon"><?php echo html_entity_decode($currency_name); ?></span>
          <input data-type="currency" class="form-control" name="cost" value="">
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php echo render_textarea('notes','fe_notes') ?>
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