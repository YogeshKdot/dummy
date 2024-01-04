<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin">
              <?php echo $title; ?>
            </h4>
            <hr class="hr-panel-heading" />
            <?php echo form_open($this->uri->uri_string()); ?>
            <div class="row">
              <div class="col-md-7">
                <div class="form-group select-placeholder">
                  <label for="clientid" class="control-label"><?php echo _l('invoice_select_customer'); ?></label>
                  <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search<?php if (isset($products) && empty($invoice->clientid)) {
    echo ' customer-removed';
} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                   <?php $selected = (isset($invoice) ? $invoice->clientid : '');
                   if ('' == $selected) {
                       $selected = ($customer_id ?? '');
                   }
                   if ('' != $selected) {
                       $rel_data = get_relation_data('customer', $selected);
                       $rel_val  = get_relation_values($rel_data, 'customer');
                       echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                   } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="product_row" id="product_row_0">
            <div class="row">
              <div class="col-md-7 col-sm-7">
               <?php echo render_select('product_items[0][product_id]', $products, ['id', 'product_name', 'quantity_number'], 'Product', !empty(set_value('product_id')) ? set_value('product_id') : $products['product_name'] ?? '', ['required'=>true, 'data-header'=>'Select a Product'], [], '', 'show-tick'); ?>
             </div>
             <div class="col-md-3 col-sm-3">
               <?php echo render_input('product_items[0][qty]', 'quantity', $products->quantity_number ?? '', 'number', ['required'=>true]); ?>
             </div>
             <div class="col-md-2 col-sm-2">
              <label>&nbsp;</label><br/>
              <button type="button" class="btn btn-sm btn-success add_row"><i class="fa fa-plus"></i> Add</button>
            </div>
          </div>
        </div>
        <div id="append_product_row"></div>
        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
  jQuery(document).ready( function () {
    "use strict";
    var product_row ="";
    init_order_required_label();
    $(".add_row").click( function(event) {
      event.preventDefault();
      var total_element = $(".product_row").length;
      var last_id = $(".product_row:last").attr('id').split("_");
      var next_id = Number(last_id[2]) + 1;
      product_row =`<div class="product_row" id="product_row_${next_id}">
        <div class="row">
        <div class="col-md-7">
        <?php echo render_select('product_items[0][product_id]', $products, ['id', 'product_name', 'quantity_number'], 'Product', !empty(set_value('product_id')) ? set_value('product_id') : $products['product_name'] ?? '', ['required'=>true, 'data-header'=>'Select a Product'], [], '', 'show-tick'); ?>
        </div>
        <div class="col-md-3">
        <?php echo render_input('product_items[][qty]', 'quantity', $products->quantity_number ?? '', 'number', ['required'=>true]); ?>
        </div>
        <div class="col-md-2 col-sm-2">
        <label>&nbsp;</label><br/>
        <button type="button" class="btn btn-sm btn-danger" onclick="remove_div(${next_id})"><i class="fa fa-times"></i></button>
        </div>
        </div>
      </div>`;
      $("#append_product_row").append(product_row);
      $(`#product_row_${next_id}`).find('select').prop('name',`product_items[${next_id}][product_id]`);
      $(`#product_row_${next_id}`).find('input[type="number"]').prop('name',`product_items[${next_id}][qty]`);
      $(`#product_row_${next_id}`).find('input[type="number"]').on('change', function () {
        if($(this).val() < 1 || !$.isNumeric($(this).val())) {
          alert_float("danger","Quantity Must Be Greater Than 0 ");
          $(this).val(1);
        }
      });
      init_selectpicker();
      init_order_required_label();
    });

    appValidateForm($('form'), {
      clientid        : "required",
    });
  });

  function init_order_required_label() {
    $(':input[required]').each(function(index, el) {
      label = $(this).parents('.form-group').find('label');
      if (label.length > 0 && label.find('.req').length === 0) {
        label.prepend('<small class="req"><span class=text-danger> * </<span></small>');
      }
    });
  }
  $('input[type="number"]').on('change', function () {
    if($(this).val() < 1 || !$.isNumeric($(this).val())) {
      alert_float("danger","Quantity Must Be Greater Than 0 ");
      $(this).val(1);
    }
  });
  function remove_div(id){
    $('#product_row_'+id).remove();
  }
</script>