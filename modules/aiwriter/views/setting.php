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
			   <?php echo form_open(admin_url('aiwriter/save_setting'), ['autocomplete'=>'off', 'id'=>'verify-form']); ?>
                        <small class="pull-right"><?php echo _l('openai_api_hints'); ?> <a href="https://platform.openai.com/account/api-keys" target="_blank"><?php echo _l('click_here'); ?></a></small>
						<?php echo render_input('aiwriter_openai_api_key', _l('openai_api_key'), (get_option('aiwriter_demo_mode') == '1') ? '***************':get_option('aiwriter_openai_api_key'), 'text', ['required'=>true]); ?>

                        <?php echo render_input('aiwriter_openai_limit_text', _l('text_limit'), get_option('aiwriter_openai_limit_text'), 'number', ['required'=>true]); ?>
                        <div class="form-group select-placeholder">
                           <label for="aiwriter_allow_for_client" class="control-label"><?php echo _l('aiwriter_allow_for_client'); ?></label>
                              <select name="aiwriter_allow_for_client" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                               <option value="1" <?php if(get_option('aiwriter_allow_for_client') == '1'){ echo 'selected'; } ?>><?php echo _l('yes'); ?></option>
                               <option value="0" <?php if(get_option('aiwriter_allow_for_client') == '0'){ echo 'selected'; } ?>><?php echo _l('no'); ?></option>
                             </select>
                        </div>

                       <div class="form-group select-placeholder">
                           <label for="aiwriter_allow_for_client_without_login" class="control-label"><?php echo _l('aiwriter_allow_for_client_without_login'); ?></label>
                           <select name="aiwriter_allow_for_client_without_login" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                               <option value="1" <?php if(get_option('aiwriter_allow_for_client_without_login') == '1'){ echo 'selected'; } ?>><?php echo _l('yes'); ?></option>
                               <option value="0" <?php if(get_option('aiwriter_allow_for_client_without_login') == '0'){ echo 'selected'; } ?>><?php echo _l('no'); ?></option>
                           </select>
                       </div>

                       <div class="form-group select-placeholder">
                           <label for="aiwriter_autoreply_on_opening_ticket" class="control-label"><?php echo _l('aiwriter_autoreply_on_opening_ticket'); ?></label>
                           <select name="aiwriter_autoreply_on_opening_ticket" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                               <option value="1" <?php if(get_option('aiwriter_autoreply_on_opening_ticket') == '1'){ echo 'selected'; } ?>><?php echo _l('yes'); ?></option>
                               <option value="0" <?php if(get_option('aiwriter_autoreply_on_opening_ticket') == '0'){ echo 'selected'; } ?>><?php echo _l('no'); ?></option>
                           </select>
                       </div>

                       <div class="form-group select-placeholder">
                           <label for="aiwriter_autoreply_staffid" class="control-label"><?php echo _l('aiwriter_autoreply_staffid'); ?></label>

                           <select name="aiwriter_autoreply_staffid" class="selectpicker" id="aiwriter_autoreply_staffid" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                               <?php foreach($this->staff_model->get() as $s){ ?>
                                   <option value="<?php echo htmlspecialchars($s['staffid']); ?>"  <?php if(get_option('aiwriter_autoreply_staffid') == $s['staffid'] ){echo 'selected';} ?>> <?php echo htmlspecialchars($s['firstname']).' '.htmlspecialchars($s['lastname']); ?></option>
                               <?php }?>
                           </select>
                       </div>


                       <?php echo render_input('aiwriter_replay_from_name', _l('aiwriter_replay_from_name'), get_option('aiwriter_replay_from_name'), 'text', ['required'=>true]); ?>

                            <button id="submit" type="submit" class="btn btn-primary pull-right"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                   </div>
            </div>
         </div>
         <div class="col-md-6">
		 </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
   appValidateForm($('#verify-form'), {
        purchase_key: 'required'
    }, manage_verify_form);

   function manage_verify_form(form) {
      var data = $(form).serialize();
      var url = form.action;
      $("#submit").prop('disabled', true).prepend('<i class="fa fa-spinner fa-pulse"></i> ');
      $.post(url, data).done(function(response) {
         var response = $.parseJSON(response);
         if(!response.status){
            alert_float("danger",response.message);
         }
         if(response.status){
            alert_float("success",response.message);
            // document.location.reload(true);
         }
         $("#submit").prop('disabled', false).find('i').remove();
      });
   }
</script>