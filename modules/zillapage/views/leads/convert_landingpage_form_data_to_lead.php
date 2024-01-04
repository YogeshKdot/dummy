<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="convert_landingpage_form_data_to_lead_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document">
  <?php echo form_open('admin/zillapage/leads/convert_to_lead',array('id'=>'form_data_to_lead')); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
               <?php echo _l('convert_to_lead'); ?>
            </h4>
         </div>
         <div class="modal-body">
           <?php echo form_hidden('formdata_id',$lead->id); ?>
           <div class="row">
           		<div class="col-md-4">
           			<?php
			            $selected = '';
			            echo render_leads_status_select($statuses, $selected,'lead_add_edit_status');
			       ?>
           		</div>
           		<div class="col-md-4">
		            <?php
		               $selected = get_option('leads_default_source');
		               echo render_leads_source_select($sources, $selected,'lead_add_edit_source');
		            ?>
		         </div>
		         <div class="col-md-4">
		            <?php
		               $assigned_attrs = array();
		               $selected = get_staff_user_id();
		               echo render_select('assigned',$members,array('staffid',array('firstname','lastname')),'lead_add_edit_assigned',$selected,$assigned_attrs); ?>
		         </div>
           </div>
	       <div class="clearfix"></div>
            <hr class="mtop5 mbot10" />
           <div class="row">
             <div class="col-md-12">
                  <div class="form-group no-mbot" id="inputTagsWrapper">
                     <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
                     <input type="text" class="tagsinput" id="tags" name="tags" value="" data-role="tagsinput">
                  </div>
             </div>
           </div>
           <?php
              $field_values = json_decode($lead->field_values);
           ?>
           <div class="row">
           		<div class="col-md-6">
           			<?php echo render_input('name','lead_add_edit_name',getFieldFormData($field_values,'name')); ?>
           			<?php echo render_input('title','lead_title',getFieldFormData($field_values,'title')); ?>
           			<?php echo render_input('email','lead_add_edit_email',getFieldFormData($field_values,'email')); ?>
           			<?php echo render_input('website','lead_website',getFieldFormData($field_values,'website')); ?>
           			<?php echo render_input('phonenumber','lead_add_edit_phonenumber',getFieldFormData($field_values,'phonenumber')); ?>
           			<?php echo render_input('company','lead_company',getFieldFormData($field_values,'company')); ?>
           			
           		</div>
           		<div class="col-md-6">
           			<?php echo render_textarea('address','lead_address',getFieldFormData($field_values,'address'),array('rows'=>1,'style'=>'height:36px;font-size:100%;')); ?>

           			<?php echo render_input('city','lead_city',getFieldFormData($field_values,'city')); ?>
           			<?php echo render_input('state','lead_state',getFieldFormData($field_values,'state')); ?>
           			<?php
			           $countries= get_all_countries();
			           $customer_default_country = get_option('customer_default_country');
			           $selected =(getFieldFormData($field_values,'country') != 0 ? getFieldFormData($field_values,'country') : $customer_default_country);
			           echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'lead_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
			         ?>
			         <?php echo render_input('zip','lead_zip',getFieldFormData($field_values,'zip')); ?>
			         <?php if(!is_language_disabled()){ ?>
			            <div class="form-group">
			               <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?></label>
			               <select name="default_language" data-live-search="true" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
			                  <option value=""><?php echo _l('system_default_string'); ?></option>
			                  <?php foreach($this->app->get_available_languages() as $availableLanguage){
			                     $selected = '';
			                     if(isset($lead)){
			                       if($lead->default_language == $availableLanguage){
			                         $selected = 'selected';
			                      }
			                     }
			                     ?>
			                  <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>><?php echo ucfirst($availableLanguage); ?></option>
			                  <?php } ?>
			               </select>
			            </div>
			         <?php } ?>
           		</div>
           </div>
           
           <div class="row">
           	 <div class="col-md-12">
	            <?php echo render_textarea('description','lead_description',getFieldFormData($field_values,'description')); ?>
	            <div class="row">
	               <div class="col-md-12">
	                  <div class="lead-select-date-contacted hide">
	                     <?php echo render_datetime_input('custom_contact_date','lead_add_edit_datecontacted','',array('data-date-end-date'=>date('Y-m-d'))); ?>
	                  </div>
	                  <div class="checkbox-inline checkbox checkbox-primary">
	                  <input type="checkbox" name="is_public" id="lead_public">
	                  <label for="lead_public"><?php echo _l('lead_public'); ?></label>
	               </div>
	                <div class="checkbox-inline checkbox checkbox-primary">
	                     <input type="checkbox" name="contacted_today" id="contacted_today" checked>
	                     <label for="contacted_today"><?php echo _l('lead_add_edit_contacted_today'); ?></label>
	                </div>
	               </div>
	            </div>
	         </div>
           </div>
           
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo _l('close'); ?></button>
   <button type="submit" data-form="#lead_to_client_form" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info"><?php echo _l('submit'); ?></button>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
<script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/leads/js/form-data-to-lead.js'); ?>"></script>