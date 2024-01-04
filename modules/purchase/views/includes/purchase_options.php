<div class="col-md-12">

  <div class="col-md-6">
    <div class="form-group">
      <div class="checkbox checkbox-primary">
        <input onchange="purchase_order_setting(this); return false" type="checkbox" id="purchase_order_setting" name="purchase_setting[purchase_order_setting]" <?php if(get_purchase_option('purchase_order_setting') == 1 ){ echo 'checked';} ?> value="purchase_order_setting">
        <label for="purchase_order_setting"><?php echo _l('create_purchase_order_non_create_purchase_request_quotation'); ?>

        <a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('purchase_order_tooltip'); ?>"></i></a>
        </label>
      </div>
    </div>
    <div class="form-group">
      <div class="checkbox checkbox-primary">
        <input onchange="item_by_vendor(this); return false" type="checkbox" id="item_by_vendor" name="purchase_setting[item_by_vendor]" <?php if(get_purchase_option('item_by_vendor') == 1 ){ echo 'checked';} ?> value="item_by_vendor">
        <label for="item_by_vendor"><?php echo _l('load_item_by_vendor'); ?>

        </label>
      </div>
    </div>

    <div class="form-group">
      <div class="checkbox checkbox-primary">
        <input onchange="po_only_prefix_and_number(this); return false" type="checkbox" id="po_only_prefix_and_number" name="purchase_setting[po_only_prefix_and_number]" <?php if(get_option('po_only_prefix_and_number') == 1 ){ echo 'checked';} ?> value="po_only_prefix_and_number">
        <label for="po_only_prefix_and_number"><?php echo _l('po_only_prefix_and_number'); ?>

        </label>
      </div>
    </div>
</div>
<div class="col-md-6">
  <div class="checkbox checkbox-primary">
    <input onchange="show_tax_column(this); return false" type="checkbox" id="show_purchase_tax_column" name="purchase_setting[show_purchase_tax_column]" <?php if(get_option('show_purchase_tax_column') == 1 ){ echo 'checked';} ?> value="show_purchase_tax_column">
    <label for="show_purchase_tax_column"><?php echo _l('show_purchase_tax_column'); ?>

    </label>
  </div>

  <div class="form-group">
    <div class="checkbox checkbox-primary">
      <input onchange="show_item_cf_on_pdf(this); return false" type="checkbox" id="show_item_cf_on_pdf" name="purchase_setting[show_item_cf_on_pdf]" <?php if(get_option('show_item_cf_on_pdf') == 1 ){ echo 'checked';} ?> value="show_item_cf_on_pdf">
      <label for="show_item_cf_on_pdf"><?php echo _l('show_item_cf_on_pdf'); ?>

      </label>
    </div>
  </div>
</div>

 

  <?php echo form_open_multipart(admin_url('purchase/reset_data'), array('id'=>'reset_data')); ?>
  <div class="_buttons">
      <?php if (is_admin()) { ?>
          <div class="row">
              <div class="col-md-12">
                  <button type="button" class="btn btn-danger intext-btn" onclick="reset_data(this); return false;" ><?php echo _l('reset_data'); ?></button>
                  <a href="#" class="input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('reset_data_title_pur'); ?>"></i></a>
              </div>
          </div>
      <?php } ?>
  </div>
  <?php echo form_close(); ?>
</div>