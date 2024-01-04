<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open('loyalty/loyalty_setting',array('id'=>'loyalty_setting-form')); ?>
<div class="form-group">
  <div class="checkbox checkbox-primary">
    <input type="checkbox" id="loyalty_setting" name="loyalty_setting" <?php if(get_option('loyalty_setting') == 1 ){ echo 'checked';} ?> value="loyalty_setting">
    <label for="loyalty_setting"><?php echo _l('enable_loyalty'); ?>

    </label>
  </div>
</div>

<div class="modal-footer">
	
	<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
	<?php echo form_close(); ?>
</div>
<div class="clearfix"></div>


