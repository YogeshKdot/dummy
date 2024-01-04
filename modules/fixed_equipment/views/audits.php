<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
  <div class="row">
   <div class="col-md-12">
    <div class="panel_s">
     <div class="panel-body">
      <div class="row">
       <div class="col-md-12">
        <h4 class="font-bold pull-left"><?php echo _l($title); ?></h4>
        <a class="btn btn-default pull-right no-margin" href="<?php echo admin_url('fixed_equipment/audit_managements'); ?>">
          <?php echo _l('fe_back'); ?>
        </a>
        <div class="clearfix"></div>
        <hr />
      </div>
    </div>
    <?php echo form_open(admin_url('fixed_equipment/create_audit_request'),array('id'=>'create_audit_request-form')) ?>

    <div class="row">
      <div class="col-md-6">
        <?php echo render_input('title', 'fe_title'); ?>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            <?php echo render_date_input('audit_date', 'fe_audit_date', _d(date('Y-m-d'))); ?>            
          </div>
          <div class="col-md-6">
            <?php echo render_select('auditor', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_auditor', get_staff_user_id()); ?>            
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <?php echo render_select('asset_location', $locations, array('id', 'location_name'), 'fe_locations'); ?>        
      </div>
      <div class="col-md-3">
        <?php echo render_select('model_id', $models, array('id', 'model_name'), 'fe_model'); ?>
      </div>
      <div class="col-md-3">
        <?php echo render_select('asset_id[]', $assets, array('id',array('series', 'assets_name')), 'fe_assets', '',array('multiple' => true, 'data-actions-box' => true),[],'','',false); ?>
      </div>
      <div class="col-md-3">
        <?php
        $list_status_checkin_checkout = [
          ['id' => 1, 'label' => _l('fe_checkin')],
          ['id' => 2, 'label' => _l('fe_checkout')]
        ];
        echo render_select('checkin_checkout_status', $list_status_checkin_checkout, array('id', 'label'), 'fe_checkin_checkout'); ?>
      </div>
    </div>


    <div class="row">
      <div class="col-md-12">
        <br>
        <div class="hot handsontable htColumnHeaders" id="example">
          <?php echo form_hidden('assets_detailt'); ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <hr>
        <button class="btn btn-primary pull-right" id="submit"><?php echo _l('fe_submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<?php 
require('modules/fixed_equipment/assets/js/audit_request_js.php');
?>
</body>
</html>
