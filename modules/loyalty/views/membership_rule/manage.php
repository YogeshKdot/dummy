<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">    
  <div class="col-md-12"> 
   <?php if (has_permission('loyalty', '', 'create') || is_admin()) { ?>
    <a href="#" onclick="new_mbs_rule(); return false;" class="btn btn-info pull-left mright10 display-block">
      <?php echo _l('new'); ?>
    </a>
  <?php } ?>

  <div class="col-md-3">
    <select name="client_group_filter[]" id="client_group_filter" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('client_group'); ?>" >
      <?php foreach($client_groups as $gr){ ?>
        <option value="<?php echo html_entity_decode($gr['id']); ?>"><?php echo html_entity_decode($gr['name']); ?></option>
      <?php } ?>
    </select> 
    <br>  
  </div>
  <div class="col-md-3">
   <select name="client_filter[]" id="client_filter" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('client'); ?>" >
     <?php foreach($clients as $cli){ ?>
      <option value="<?php echo html_entity_decode($cli['userid']); ?>"><?php echo html_entity_decode($cli['company']); ?></option>
    <?php } ?>
  </select> 
  <br>  
</div>

</div>
<div class="col-md-12">
 <hr>	
</div>

</div>
<div class="row">
  <div class="col-md-12" id="small-table">
   <?php render_datatable(array(
     _l('name'),
     _l('card'),
     _l('client_group'),
     _l('client'),
     _l('point_from'),
     _l('point_to'),
     _l('date_create'),
   ),'table_membership_rule'); ?>
 </div>
</div>

<div class="modal fade" id="mbs_rule" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('loyalty/membership_rule_form'),array('id'=>'membership_rule-form')); ?>
    <div class="modal-content modal_withd">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="add-title"><?php echo _l('add_membership_rule'); ?></span>
          <span class="edit-title"><?php echo _l('edit_membership_rule'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
       <div id="additional_mbs_rule"></div>
       <div class="row">
         <div class="col-md-12">
          <label for="name"><span class="text-danger">* </span><?php echo _l('name'); ?></label>
          <?php echo render_input('name','','','text',array('required' => 'true')); ?>
        </div>

        <div class="col-md-6 form-group">
          <label for="client_group"><?php echo _l('client_group'); ?></label>
          <select name="client_group" id="client_group" onchange="client_group_change(this); return false;" class="selectpicker form-control"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
            <option value=""></option>
            <?php foreach($client_groups as $gr){ ?>
             <option value="<?php echo html_entity_decode($gr['id']); ?>"><?php echo html_entity_decode($gr['name']); ?></option>
           <?php } ?>
         </select>
         <br>
       </div> 

       <div class="col-md-6 form-group">
        <label for="client"><?php echo _l('client'); ?></label>
        <select name="client[]" id="client" class="selectpicker form-control" multiple="true" data-actions-box="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
          <?php foreach($clients as $cli){ ?>
           <option value="<?php echo html_entity_decode($cli['userid']); ?>"><?php echo html_entity_decode($cli['company']); ?></option>
         <?php } ?>
       </select>
       <br>
     </div>   

     <div class="col-md-12 form-group">
      <label for="card"><span class="text-danger">* </span><?php echo _l('card'); ?></label>
      <select name="card" id="card" class="selectpicker" required  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
        <option value=""></option>
        <?php foreach($cards as $crd) { ?>
         <option value="<?php echo html_entity_decode($crd['id']); ?>"><?php echo html_entity_decode($crd['name']); ?></option>
       <?php } ?>
     </select>
     <br>
   </div>

   <div class="col-md-6">
     <label for="loyalty_point_from"><span class="text-danger">* </span><?php echo _l('point_from'); ?></label>
     <?php echo render_input('loyalty_point_from','','','number',array('required' => 'true')); ?>
   </div> 

   <div class="col-md-6">
    <label for="loyalty_point_to"><span class="text-danger">* </span><?php echo _l('point_to'); ?></label>
    <?php echo render_input('loyalty_point_to','','','number',array('required' => 'true')); ?>
  </div>
  
  <div class="col-md-12">
    <?php echo render_textarea('description','description') ?>
  </div>    

  <div id="type_care">
    
  </div>        
</div>
</div>
<div class="modal-footer">
  <button type=""class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  <button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
</div>
</div><!-- /.modal-content -->
<?php echo form_close(); ?>
</div><!-- /.modal-dialog -->
  </div><!-- /.modal -->