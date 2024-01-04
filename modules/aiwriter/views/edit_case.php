<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-6 col-md-offset-3">
            <div class="panel_s">
               <div class="panel-body">
			   <h4><?php echo $title;?></h4>
			   <hr class="hr-panel-heading">
                   <?php echo form_open(admin_url('aiwriter/add_case/'.$case_info->id)); ?>
                   <?php echo render_input('usage_case', 'usage_case', $case_info->usage_case, 'text'); ?>
                   <div class="form-group select-placeholder">
                       <label for="is_default" class="control-label"><?php echo _l('is_default'); ?></label>
                       <select name="is_default" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value="1" <?php if($case_info->is_default == '1'){ echo 'selected'; } ?>><?php echo _l('yes'); ?></option>
                           <option value="0" <?php if($case_info->is_default == '0'){ echo 'selected'; } ?>><?php echo _l('no'); ?></option>
                       </select>
                   </div>
                   <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                   <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
