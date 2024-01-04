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
        <a href="<?php echo admin_url('fixed_equipment/add_assets'); ?>" class="btn btn-primary"><?php echo _l('add'); ?></a>
        <div class="clearfix"></div>
        <br>
        <div class="clearfix"></div>
        <?php
        $table_data = array(
          'ID',
          _l('fe_asset_name'),
          _l('fe_image'),
          _l('fe_asset_tag'),
          _l('fe_serial'),
          _l('fe_model'),
          _l('fe_model_no'),
          _l('fe_category'),
          _l('fe_status'),
          _l('fe_checkout_to'),
          _l('fe_location'),
          _l('fe_default_location'),
          _l('fe_manufacturer'),
          _l('fe_supplier'),
          _l('fe_purchase_date'),
          _l('fe_purchase_cost'),
          _l('fe_order_number'),
          _l('fe_warranty'),
          _l('fe_warranty_expires'),
          _l('fe_notes'),
          _l('fe_checkouts'),
          _l('fe_checkins'),
          _l('fe_created_at'),
          _l('fe_updated_at'),
          _l('fe_checkout_date'),
          _l('fe_expected_checkin_date'),
          _l('fe_last_audit'),
          _l('fe_next_audit_date'));

        $custom_fields = get_custom_fields('fixed_equipment');
        foreach($custom_fields as $field){
         array_push($table_data,$field['name']);
       }
       array_push($table_data, _l('fe_checkin_checkout'));

       render_datatable($table_data,'view_model',[], [
        'data-last-order-identifier' => 'view_model-relation',
        'data-default-order'         => get_table_last_order('view_model-relation'),
      ]);
      ?>
    </div>
  </div>
</div>
</div>
<?php init_tail(); ?>
</body>
</html>
