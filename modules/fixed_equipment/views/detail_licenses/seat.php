<input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
<div  class="col-md-12">
   <table class="table table-seat scroll-responsive">
     <thead>
      <tr>
         <th><?php echo _l('fe_seat'); ?></th>
         <th><?php echo _l('fe_user'); ?></th>
         <th><?php echo _l('fe_asset'); ?></th>
         <th><?php echo _l('fe_location'); ?></th>
         <?php 
         if(is_admin() || has_permission('fixed_equipment_licenses', '', 'create')){           
           ?>
           <th><?php echo _l('fe_checkin_checkout'); ?></th>
        <?php } ?>
     </tr>
  </thead>
  <tbody></tbody>
</table>
</div>

<div class="modal fade" id="check_in" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
               <span class="add-title"><?php echo _l('fe_checkin'); ?></span>
            </h4>
         </div>
         <?php echo form_open(admin_url('fixed_equipment/check_in_license'),array('id'=>'check_in_assets-form')); ?>
         <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
            <input type="hidden" name="item_id" value="">
            <input type="hidden" name="type" value="checkin">
            <div class="row">
               <div class="col-md-12">
                  <?php echo render_input('asset_name','fe_software_name', '', 'text', array('readonly' => true)); ?>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <?php echo render_textarea('notes','fe_notes'); ?>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('fe_checkin'); ?></button>
         </div>
         <?php echo form_close(); ?>                 
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="check_out" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
               <span class="add-title"><?php echo _l('fe_checkout'); ?></span>
            </h4>
         </div>
         <?php echo form_open(admin_url('fixed_equipment/check_in_license'),array('id'=>'check_out_license-form')); ?>
         <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
            <input type="hidden" name="item_id" value="">
            <input type="hidden" name="type" value="checkout">
            <div class="row">
               <div class="col-md-12">
                  <?php echo render_input('asset_name','fe_software_name', '', 'text', array('readonly' => true)); ?>
               </div>
            </div>
            <div class="row mbot15">
               <div class="col-md-12">
                  <label for="location" class="control-label"><?php echo _l('fe_checkout_to'); ?></label>          
               </div>
               <div class="col-md-12">
                  <div class="pull-left">
                     <div class="checkbox">
                        <input type="radio" name="checkout_to" id="checkout_to_user" value="user" checked>
                        <label for="checkout_to_user"><?php echo _l('fe_staffs'); ?></label>
                     </div>    
                  </div>
                  <div class="pull-left">
                     <div class="checkbox">
                        <input type="radio" name="checkout_to" id="checkout_to_asset" value="asset">
                        <label for="checkout_to_asset"><?php echo _l('fe_asset'); ?></label>
                     </div>  
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 checkout_to_fr checkout_to_asset_fr hide">
                  <?php echo render_select('asset_id', $assets, array('id',array('series', 'assets_name')), 'fe_assets'); ?>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 checkout_to_fr checkout_to_staff_fr">
                  <?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_staffs'); ?>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <?php echo render_textarea('notes','fe_notes'); ?>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('fe_checkout'); ?></button>
         </div>
         <?php echo form_close(); ?>                 
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->