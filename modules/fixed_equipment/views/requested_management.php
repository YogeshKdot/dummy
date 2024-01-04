<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row panel">
      <div class="col-md-12">
        <h4>
          <br>
          <?php echo html_entity_decode($title); ?>
          <hr>          
        </h4>
        <?php 
        if(is_admin() || has_permission('fixed_equipment_requested', '', 'create')){
         ?>
         <button class="btn btn-primary" onclick="add();"><?php echo _l('add'); ?></button>
         <div class="clearfix"></div>
         <br>
       <?php } ?>
       <div class="row">
        <?php 
        if(has_permission('fixed_equipment_requested', '', 'view') || is_admin()){ ?>
          <div class="col-md-3">
            <?php echo render_select('checkout_for_filter[]', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_checkout_for','',array('multiple' => true, 'data-actions-box' => true),[],'','',false); ?>
          </div>
        <?php }  ?>

        <div class="col-md-3">
          <?php
          $status_approve = [
            ['id' => 3, 'label' => _l('fe_new')],
            ['id' => 1, 'label' => _l('fe_approved')],
            ['id' => 2, 'label' => _l('fe_rejected')],
          ];
          echo render_select('status_filter', $status_approve, array('id', 'label'), 'fe_status'); ?>
        </div>

        <div class="col-md-3">
          <?php echo render_date_input('create_from_date_filter', 'fe_create_from_date'); ?>
        </div>

        <div class="col-md-3">
          <?php echo render_date_input('create_to_date_filter', 'fe_create_to_date'); ?>
        </div>
      </div> 
      <br>
      <div class="clearfix"></div>
      <table class="table table-request scroll-responsive">
       <thead>
         <tr>
          <th><?php echo  _l('fe_title'); ?></th>
          <th><?php echo  _l('fe_assets'); ?></th>
          <th><?php echo  _l('fe_image'); ?></th>
          <th><?php echo  _l('fe_serial'); ?></th>
          <th><?php echo  _l('fe_checkout_for'); ?></th>
          <th><?php echo  _l('fe_notes'); ?></th>
          <th><?php echo  _l('fe_created_at'); ?></th>
          <th><?php echo  _l('fe_status'); ?></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
</div>
</div>

<div class="modal fade" id="add_new_request" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="add-title"><?php echo _l('fe_new_request'); ?></span>
        </h4>
      </div>
      <?php echo form_open(admin_url('fixed_equipment/add_new_request'),array('id'=>'add_new_request-form')); ?>
      <div class="modal-body">
        <input type="hidden" name="checkout_to" value="user">
        <input type="hidden" name="type" value="checkout">
        <input type="hidden" name="requestable" value="1">
        <div class="row">
          <div class="col-md-12">
            <?php echo render_input('request_title', 'fe_title'); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php echo render_select('item_id', $assets, array('id',array('series', 'assets_name')), 'fe_assets'); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_checkout_for'); ?>
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
        <button type="submit" class="btn btn-info"><?php echo _l('fe_send_request'); ?></button>
      </div>
      <?php echo form_close(); ?>                 
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
</body>
</html>
