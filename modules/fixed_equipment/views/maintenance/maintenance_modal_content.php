   <br>
       <input type="hidden" name="id">
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
          echo render_select('maintenance_type', $maintenances, array('id', 'maintenance_name'), 'fe_maintenance'); ?>
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