<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div  class="row">
  <div class="col-md-12">
    <div class="panel-body">
      <?php echo form_open(admin_url('fixed_equipment/other_setting'),array('id'=>'other_setting-form')); ?>
      <?php
      $googlemap_api_key = '';
      $api_key = get_option('fe_googlemap_api_key');
      if($api_key){
        $googlemap_api_key = $api_key;
      } 
      echo render_input('fe_googlemap_api_key', 'fe_googlemap_api_key', $googlemap_api_key) ?>
      <div class="row">
        <div class="col-md-12">
          <button class="btn btn-primary pull-right">
            <?php echo _l('fe_save'); ?>
          </button>
        </div>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


