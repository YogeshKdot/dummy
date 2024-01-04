<div class="row">
<div class="col-md-3"> 
   <select name="contact_filter[]" class="selectpicker" id="contact_filter" multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('client'); ?>"> 

	      <?php foreach($contact as $s){ ?>
	        <option value="<?php echo html_entity_decode($s['email']); ?>" ><?php echo get_company_name(get_user_id_by_contact_id($s['id'])).' - '. html_entity_decode($s['lastname']).' '.html_entity_decode($s['firstname']); ?></option>
	   <?php } ?>
	  </select> 
    
</div>


<div class="col-md-3"> 
    <select name="type_sh_filter[]" id="type_sh_filter" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('types'); ?>" >
   	<option value="normal"><?php echo _l('normal'); ?></option>
   	<option value="bank_account"><?php echo _l('bank_account'); ?></option>
   	<option value="credit_card"><?php echo _l('credit_card'); ?></option>
   	<option value="email"><?php echo _l('email'); ?></option>
   	<option value="server"><?php echo _l('server'); ?></option>
   	<option value="software_license"><?php echo _l('software_license'); ?></option>
  	</select>  
</div>

<div class="col-md-3"> 
    <select name="effective_time" id="effective_time" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('effective_time'); ?>" >
    	<option value=""></option>
	   	<option value="expired" <?php if(isset($ef_time) && $ef_time == 'expired'){ echo 'selected'; } ?>><?php echo _l('expired'); ?></option>
	   	<option value="unexpired" <?php if(isset($ef_time) && $ef_time == 'unexpired'){ echo 'selected'; } ?>><?php echo _l('unexpired'); ?></option>
  	</select>  
</div>


<div class="clearfix"></div>
<hr />
<div class="clearfix"></div>
<div class="col-md-12">
	<?php render_datatable(array(
		_l('name'),
		_l('client'),
		_l('effective_time'),
		_l('read'),
		_l('write'),
		_l('type'),
		),'table_share_rp'); ?>
</div>
</div>
