<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div class="content">
  <div id="wrapper" >
    <div class="content">
      <div class="panel">
        <div class="row">
          <div class="col-md-12">

            <div class="col-md-12">
              <h3 class="mtop15"><?php echo _l('fe_dashboard'); ?></h3>              
              <hr>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold"><?php
              $total1 = $this->fixed_equipment_model->count_total_assets('asset');
              echo html_entity_decode($total1); ?></h3>
              <strong class="text-danger"><?php echo _l('fe_total_assets'); ?></strong>
            </div>

            <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold"><?php
              $total2 = $this->fixed_equipment_model->count_total_assets('license');
              echo html_entity_decode($total2); ?></h3>
              <strong class="text-primary"><?php echo _l('fe_total_licenses'); ?></strong>
            </div>

            <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold"><?php
              $total3 = $this->fixed_equipment_model->count_total_assets('accessory');
              echo html_entity_decode($total3); ?></h3>
              <strong class="text-success"><?php echo _l('fe_total_accessories'); ?></strong>
            </div>

            <div class="col-md-3 col-xs-6">
              <h3 class="bold"><?php
              $total4 = $this->fixed_equipment_model->count_total_assets('consumable');
              echo html_entity_decode($total4); ?></h3>
              <strong class="text-default"><?php echo _l('fe_total_consumables'); ?></strong>
            </div>
            <div class="col-md-12">
              <div class="clearfix"></div>
              <hr>              
            </div>
          </div>         
        </div>
      </div>
      <div class="panel">
        <div class="row">
          <div class="col-md-6">
            <div class="col-md-12">
              <h4><?php echo _l('fe_assets_by_status'); ?></h4>
            </div>
            <br>
            <br>
            <div class="clearfix"></div>
            <figure class="highcharts-figure">
              <div id="container"></div>
              <p class="highcharts-description"></p>
            </figure>
          </div>

          <div class="col-md-6">
            <?php $this->load->view('dashboard/asset_categories.php'); ?>
          </div>

        </div>
      </div>

      <div class="panel">
        <div class="row">
          <div class="col-md-12">
            <?php $this->load->view('dashboard/activity.php'); ?>
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
require('modules/fixed_equipment/assets/js/dashboard_js.php');
?>