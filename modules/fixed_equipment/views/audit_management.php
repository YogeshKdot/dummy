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
        if(is_admin() || has_permission('fixed_equipment_audit', '', 'create')){
         ?>
         <a href="<?php echo admin_url('fixed_equipment/audit_request'); ?>" class="btn btn-primary" onclick="add();"><?php echo _l('add'); ?></a>
         <div class="clearfix"></div>
         <br>
       <?php } ?>
       <div class="row">
        <?php 
          if(has_permission('fixed_equipment_audit', '', 'view') || is_admin()){ ?>
            <div class="col-md-3">
              <?php echo render_select('auditor_filter[]', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_auditor','',array('multiple' => true, 'data-actions-box' => true),[],'','',false); ?>
            </div>
        <?php } ?>

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
          <?php echo render_date_input('audit_from_date_filter', 'fe_audit_from_date'); ?>
        </div>

        <div class="col-md-3">
          <?php echo render_date_input('audit_to_date_filter', 'fe_audit_to_date'); ?>
        </div>
      </div> 


      <div class="clearfix"></div>
      <table class="table table-audit_management scroll-responsive">
       <thead>
         <tr>
          <th><?php echo  _l('fe_title'); ?></th>
          <th><?php echo  _l('fe_auditor'); ?></th>
          <th><?php echo  _l('fe_audit_date'); ?></th>
          <th><?php echo  _l('fe_status'); ?></th>
          <th><?php echo  _l('fe_created_at'); ?></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>



  </div>
</div>
</div>
</div>

<?php init_tail(); ?>
</body>
</html>
