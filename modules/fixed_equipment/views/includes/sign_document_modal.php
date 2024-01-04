<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" tabindex="-1" role="dialog" id="identityConfirmationModal">
 <div class="modal-dialog" role="document">
  <div class="modal-content">
   <?php echo form_open(admin_url('fixed_equipment/staff_sign_document'), array('id'=>'identityConfirmationForm','class'=>'form-horizontal')); ?>
   <input type="hidden" name="id" value="">
   <input type="hidden" name="document_id" value="">
   
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo _l('signature'); ?> &amp; <?php echo _l('confirmation_of_identity'); ?></h4>
  </div>
  <div class="modal-body">
    <div id="identity_fields">
     <div class="form-group">
      <label for="firstname" class="control-label col-sm-2">
        <span class="text-left inline-block full-width">
          <?php echo _l('client_firstname'); ?>
        </span>
      </label>
      <div class="col-sm-10">
       <input type="text" name="firstname" id="firstname" class="form-control" required="true" value="">
     </div>
   </div>
   <div class="form-group">
    <label for="lastname" class="control-label col-sm-2">
      <span class="text-left inline-block full-width">
        <?php echo _l('client_lastname'); ?>
      </span>
    </label>
    <div class="col-sm-10">
     <input type="text" name="lastname" id="lastname" class="form-control" required="true" value="">
   </div>
 </div>
 <div class="form-group">
  <label for="email" class="control-label col-sm-2">
    <span class="text-left inline-block full-width">
      <?php echo _l('client_email'); ?>
    </span>
  </label>
  <div class="col-sm-10">
   <input type="email" name="email" id="email" class="form-control" required="true" value="">
 </div>
</div>
<p class="bold" id="signatureLabel"><?php echo _l('signature'); ?></p>
<div class="signature-pad--body">
  <canvas id="signature" height="130" width="550"></canvas>
</div>
<input type="text" class="hide" tabindex="-1" name="signature" id="signatureInput">
<div class="dispay-block mtop10">
  <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" data-action="clear"><?php echo _l('clear'); ?></button>
  <button type="button" class="btn btn-default btn-xs" tabindex="-1" data-action="undo"><?php echo _l('undo'); ?></button>
</div>
</div>
</div>
<div class="modal-footer">
  <p class="text-left text-muted e-sign-legal-text">
   <?php echo _l(get_option('e_sign_legal_text'),'', false); ?>
 </p>
 <hr />
 <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('cancel'); ?></button>
 <button type="submit" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" data-form="#identityConfirmationForm" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
</div>
<?php echo form_close(); ?>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php
  $this->app_scripts->theme('signature-pad','assets/plugins/signature-pad/signature_pad.min.js');
?>
