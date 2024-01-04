<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="convert_landingpage_lead_to_customer_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document">
      <?php echo form_open('admin/zillapage/leads/convert_to_customer',array('id'=>'lead_to_client_form')); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
               <?php echo _l('lead_convert_to_client'); ?>
            </h4>
         </div>
         <div class="modal-body">
            <?php echo form_hidden('formdata_id',$lead->id); ?>
            <?php
              $field_values = json_decode($lead->field_values);
           ?>
           <?php echo render_input('firstname','lead_convert_to_client_firstname', getFieldFormData($field_values,'firstname')); ?>
           <?php echo render_input('lastname','lead_convert_to_client_lastname',getFieldFormData($field_values,'lastname')); ?>
           <?php echo render_input('title','contact_position',getFieldFormData($field_values,'title')); ?>
           <?php echo render_input('email','lead_convert_to_email',getFieldFormData($field_values,'email')); ?>
           <?php echo render_input('company','lead_company',getFieldFormData($field_values,'company')); ?>
           <?php echo render_input('phonenumber','lead_convert_to_client_phone',getFieldFormData($field_values,'phonenumber')); ?>
           <?php echo render_input('website','client_website',getFieldFormData($field_values,'website')); ?>
           <?php echo render_textarea('address','client_address',getFieldFormData($field_values,'address')); ?>
           <?php echo render_input('city','client_city',getFieldFormData($field_values,'city')); ?>
           <?php echo render_input('state','client_state',getFieldFormData($field_values,'state')); ?>
           <?php
           $countries= get_all_countries();
           $customer_default_country = get_option('customer_default_country');
           $selected =(getFieldFormData($field_values,'country') != 0 ? getFieldFormData($field_values,'country') : $customer_default_country);
           echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
           ?>
           <?php echo render_input('zip','clients_zip',getFieldFormData($field_values,'zip')); ?>
         
   <?php echo form_hidden('original_lead_email',getFieldFormData($field_values,'email')); ?>

   <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
   <input  type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1"/>
   <input  type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1"/>

   <div class="client_password_set_wrapper">
      <label for="password" class="control-label"><?php echo _l('client_password'); ?></label>
      <div class="input-group">
         <input type="password" class="form-control password" name="password" autocomplete="off">
         <span class="input-group-addon">
            <a href="#password" class="show_password" onclick="showPassword('password');return false;"><i class="fa fa-eye"></i></a>
         </span>
         <span class="input-group-addon">
            <a href="#" class="generate_password" onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
         </span>
      </div>
   </div>
   <?php if(is_email_template_active('contact-set-password')){ ?>
   <div class="checkbox checkbox-primary">
      <input type="checkbox" name="send_set_password_email" id="send_set_password_email">
      <label for="send_set_password_email">
         <?php echo _l( 'client_send_set_password_email'); ?>
      </label>
   </div>
   <?php } ?>
   <?php if(is_email_template_active('new-client-created')){ ?>
   <div class="checkbox checkbox-primary">
      <input type="checkbox" name="donotsendwelcomeemail" id="donotsendwelcomeemail">
      <label for="donotsendwelcomeemail"><?php echo _l('client_do_not_send_welcome_email'); ?></label>
   </div>
   <?php } ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo _l('close'); ?></button>
   <button type="submit" data-form="#lead_to_client_form" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info"><?php echo _l('submit'); ?></button>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
<script>
   validate_lead_convert_to_client_form();
   init_selectpicker();
</script>
