<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
			   <h4><?php echo $title;?></h4>
			   <hr class="hr-panel-heading">
			   <?php echo form_open(admin_url('aiwriter/ajaxAiContent'), ['autocomplete'=>'off', 'id'=>'verify-form']); ?>
                   <div class="form-group select-placeholder">
                       <label for="usage_case" class="control-label"><?php echo _l('usage_case'); ?></label>
                       <select name="usage_case" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php foreach($this->aiwriter_model->get_usage_case_as_array() as $raw): ?>
                            <option value="<?php echo $raw['usage_case_key']; ?>" <?php if($raw['is_default'] =='1'): echo 'selected'; endif;?>><?php echo $raw['usage_case']; ?></option>
                           <?php endforeach; ?>
                       </select>
                   </div>
                   <?php echo render_input('primary_keyword', _l('primary_keyword'), '', 'text', ['placeholder'=>_l('how_to_earn_from_content'),'required'=>true],); ?>
                   <div class="form-group select-placeholder">
                       <label for="no_of_varient" class="control-label"><?php echo _l('no_of_varient'); ?></label>
                       <select name="no_of_varient" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value="1">1 <?php echo _l('variants'); ?></option>
                           <option value="2">2 <?php echo _l('variants'); ?></option>
                           <option value="3">3 <?php echo _l('variants'); ?></option>
                       </select>
                   </div>
                    <button id="submit" type="submit" class="btn btn-primary pull-right btn-block"><?php echo _l('write_for_me'); ?></button>
                  	<?php echo form_close(); ?>
               </div>
            </div>
         </div>
         <div class="col-md-6">
             <div class="panel_s">
                 <div class="panel-body">
                     <h4><?php echo _l('ai_generated_content');?></h4>
                     <hr class="hr-panel-heading">
                    <textarea id="aicontents" name="description" class="form-control" rows="12" placeholder="<?php echo _l('ai_generated_content_will_goes_here'); ?>" spellcheck="false"></textarea>
                 </div>
             </div>
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
             $('#aicontents').text(response.data);
             console.log(response);
            alert_float("success",response.message);
            // document.location.reload(true);
         }
         $("#submit").prop('disabled', false).find('i').remove();
      });
   }
</script>