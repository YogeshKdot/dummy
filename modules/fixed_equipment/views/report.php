<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" >
 <div class="content">
  <div class="row">
   <div class="col-md-12">

    <div class="panel_s">
     <div class="panel-body">
       <div class="row">
         <div class="col-md-4 border-right">
          <h4 class="no-margin font-medium"><i class="fa fa-balance-scale" aria-hidden="true"></i> <?php echo _l('fe_report_by_table'); ?></h4>
          <hr />
          <p><a href="#" class="font-medium" onclick="init_report(this,'activity'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('fe_activity'); ?></a></p>
          <p><a href="#" class="font-medium" onclick="init_report(this,'unaccepted_assets'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('fe_unaccepted_assets'); ?></a></p>
        </div>
        <div class="col-md-4 border-right">
          <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('charts_based_report'); ?></h4>
        </div>
        <div class="col-md-4">
          <div class="form-group hide" id="report-time">
            <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
            <select class="selectpicker" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
             <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
             <option value="this_month"><?php echo _l('this_month'); ?></option>
             <option value="1"><?php echo _l('last_month'); ?></option>
             <option value="this_year"><?php echo _l('this_year'); ?></option>
             <option value="last_year"><?php echo _l('last_year'); ?></option>
             <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
             <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
             <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
             <option value="custom"><?php echo _l('period_datepicker'); ?></option>
           </select>
           <div class="clearfix"></div>
           <br>
           <div class="clearfix"></div>
           <div id="date-range" class="hide mbot15">
            <div class="row">
             <div class="col-md-6">
              <?php echo render_date_input('report-from','fe_from_date'); ?>
            </div>
            <div class="col-md-6">
              <?php echo render_date_input('report-to','fe_to_date'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <br>
  <div class="row">
    <div class="col-md-12 report_title text-center">
      <h4>
      </h4>
      <hr>
    </div>
  </div>

  <br>

  <!-- Filter -->
  <div class="row filter_fr filter1 hide">
    <?php 
    if(has_permission('fixed_equipment_report', '', 'view') || is_admin()){
     ?>
     <div class="col-md-3">
      <?php echo render_select('checkout_for_filter[]', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_checkout_for','',array('multiple' => true, 'data-actions-box' => true),[],'','',false); ?>
    </div>
  <?php } ?>
  <div class="col-md-3">
  </div>
  <div class="col-md-3">
  </div>
  <div class="col-md-3"></div>
</div> 

<div class="row filter_fr filter2 hide">
  <div class="col-md-3">
    <?php 
    $list_type = [
      ['id' => 'assets', 'label' => _l('fe_assets')],
      ['id' => 'licenses', 'label' => _l('fe_licenses')],
      ['id' => 'accessories', 'label' => _l('fe_accessories')],
      ['id' => 'consumables', 'label' => _l('fe_consumables')],
      ['id' => 'components', 'label' => _l('fe_components')],
      ['id' => 'predefined_kits', 'label' => _l('fe_predefined_kits')],
    ];
    echo render_select('type_filter', $list_type, array('id', 'label'), 'fe_type',''); ?>
  </div>
  <div class="col-md-3">
  </div>
  <div class="col-md-3">
  </div>
  <div class="col-md-3"></div>
</div> 
<!-- End Filter -->

<br>
<?php $this->load->view('report/activity.php'); ?>
<?php $this->load->view('report/unaccepted_assets.php'); ?>
<?php $this->load->view('report/inventory_report.php'); ?>
</div>      
</div>

</div>
</div>
</div>
</div>

<?php init_tail(); ?>
</body>
</html>
<?php 
require('modules/fixed_equipment/assets/js/report_js.php');
?>