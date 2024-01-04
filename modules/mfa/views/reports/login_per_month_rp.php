<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="login_per_month_rp" class="hide">
   <div class="row">
   	<?php if(is_admin()){ ?>
   	<div class="col-md-4 form-group" id ="staff_login_div">
	    <label for="staff_login"><?php echo _l('staff'); ?></label>
	    <select name="staff_login" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
	       <?php foreach($staff as $s){ ?>
	        <option value="<?php echo html_entity_decode($s['staffid']); ?>"><?php echo get_staff_full_name($s['staffid']); ?></option>
	       <?php } ?>
	    </select>
	 </div>
	<?php } ?>
     <figure class="highcharts-figure col-md-12">
       <div id="container_login_per_month_rp"></div>
       
      </figure>
   </div>
</div>