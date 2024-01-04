<div class="row">
<div class="col-md-3"> 
    <select name="staff_filter[]" id="staff_filter" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('staff'); ?>" >
    <?php foreach($staffs as $s){ ?>
		<option value="<?php echo html_entity_decode($s['staffid']); ?>"><?php echo html_entity_decode($s['firstname'].' '.$s['lastname']); ?></option>
	<?php } ?>
  	</select> 
    
</div>


<div class="col-md-3"> 
    <select name="type_filter[]" id="type_filter" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('types'); ?>" >
   	<option value="normal"><?php echo _l('normal'); ?></option>
   	<option value="bank_account"><?php echo _l('bank_account'); ?></option>
   	<option value="credit_card"><?php echo _l('credit_card'); ?></option>
   	<option value="email"><?php echo _l('email'); ?></option>
   	<option value="server"><?php echo _l('server'); ?></option>
   	<option value="software_license"><?php echo _l('software_license'); ?></option>
  	</select>  
</div>


<div class="clearfix"></div>
<hr />
<div class="clearfix"></div>
<div class="col-md-12">
	<?php render_datatable(array(
		_l('name'),
		_l('staff'),
		_l('read'),
		_l('write'),
		_l('type'),
		),'table_permission_rp'); ?>
</div>
</div>
