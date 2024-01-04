<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="history_send_code_table_rp" class="hide">
   <div class="row">
      <?php if(is_admin()){ ?>
      <div class="col-md-4">
         <div class="form-group">
            <label for="staff_sc"><?php echo _l('staff'); ?></label>
            <select name="staff_sc" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
               <?php foreach($staff as $s){ ?>
                <option value="<?php echo html_entity_decode($s['staffid']); ?>"><?php echo get_staff_full_name($s['staffid']); ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <?php } ?>
   <div class="clearfix"></div>
</div>
<table class="table table-history_send_code scroll-responsive">
   <thead>
      <tr>
         <th><?php echo _l('id'); ?></th>
         <th><?php echo _l('staff'); ?></th>
         <th><?php echo _l('type'); ?></th>
         <th><?php echo _l('status'); ?></th>
         <th><?php echo _l('note'); ?></th>
         <th><?php echo _l('time'); ?></th>
      </tr>
   </thead>
   <tbody></tbody>
</table>
</div>