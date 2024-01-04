   <table class="table table-asset-licenses scroll-responsive">
     <thead>
      <tr>
         <th><?php echo _l('fe_name'); ?></th>
         <th><?php echo _l('fe_product_key'); ?></th>
         <th><?php echo _l('fe_action'); ?></th>
      </tr>
   </thead>
   <tbody></tbody>
   <tfoot>
      <td></td>
      <td></td>
      <td></td>
   </tfoot>
</table>
<div class="modal fade" id="check_in" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
               <span class="add-title"><?php echo _l('fe_checkin'); ?></span>
            </h4>
         </div>
         <?php echo form_open(admin_url('fixed_equipment/check_in_license_detail_asset'),array('id'=>'check_in_assets-form')); ?>
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