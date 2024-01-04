<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="row">    
  <div class="col-md-12"> 
   <?php if (has_permission('loyalty', '', 'create') || is_admin()) { ?>
    <a href="<?php echo admin_url('loyalty/mbs_program'); ?>" class="btn btn-info pull-left mright10 display-block">
      <?php echo _l('new'); ?>
    </a>
  <?php } ?>

  
  <div class="col-md-3">
    <select name="discount_filter[]" id="discount_filter" class="selectpicker"  data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('discount_type'); ?>" >
     
      <option value="card_total"><?php echo _l('card_total'); ?></option>
      <option value="product_category"><?php echo _l('product_category'); ?></option>
      <option value="product"><?php echo _l('product_loy'); ?></option>
    </select>
    <br>  
  </div>
  <div class="col-md-3">
   <select name="membership_filter[]" id="membership_filter" class="selectpicker" data-live-search="true" multiple="" data-width="100%" data-none-selected-text="<?php echo _l('membership_rule'); ?>" >
     <?php foreach($memberships as $mem){ ?>
      <option value="<?php echo html_entity_decode($mem['id']); ?>"><?php echo html_entity_decode($mem['name']); ?></option>
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
     _l('program_name'),
     _l('voucher_code'),
     _l('discount_type'),
     _l('membership'),
     _l('point_from'),
     _l('point_to'),
     _l('start_date'),
     _l('end_date'),
   ),'table_membership_program'); ?>
 </div>
</div>

<div class="modal fade" id="mbs_program" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('loyalty/membership_program_form'),array('id'=>'membership_program-form')); ?>
    <div class="modal-content modal_withd">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="add-title"><?php echo _l('add_membership_program'); ?></span>
          <span class="edit-title"><?php echo _l('edit_membership_program'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
       <div id="additional_mbs_program"></div>
       <div class="row">

         <div class="col-md-6">
          
          <?php echo render_input('program_name','program_name','','text'); ?>
        </div>

        <div class="col-md-6">
         
          <?php echo render_input('voucher_code','voucher_code','','text'); ?>
        </div>

        <div class="col-md-12 form-group">
          <label for="discount"><?php echo _l('discount_type'); ?></label>
          <select name="discount" id="discount" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
            <option value=""></option>
            <option value="card_total"><?php echo _l('card_total'); ?></option>
            <option value="product_category"><?php echo _l('product_category'); ?></option>
            <option value="product"><?php echo _l('product_loy'); ?></option>
          </select>
          <br>
        </div>

        <div class="col-md-12 form-group">
          <label for="membership"><?php echo _l('membership_rule'); ?></label>
          <select name="membership[]" id="membership" class="selectpicker" data-live-search="true" multiple="" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
           <?php foreach($memberships as $mem){ ?>
            <option value="<?php echo html_entity_decode($mem['id']); ?>"><?php echo html_entity_decode($mem['name']); ?></option>
          <?php } ?>
        </select>
        <br>
      </div>
      <div class="col-md-6">
       
        <?php echo render_input('loyalty_point_from','point_from','','number'); ?>
      </div> 
      <div class="col-md-6">
        
        <?php echo render_input('loyalty_point_to','point_to','','number'); ?>
      </div> 

      <div class="col-md-6">
        <?php echo render_date_input('start_date','start_date'); ?>
      </div>
      <div class="col-md-6">
        <?php echo render_date_input('end_date','end_date'); ?>
      </div>
      
      <div class="col-md-12">
        <?php echo render_textarea('note','note') ?>
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

