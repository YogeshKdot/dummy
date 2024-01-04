<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s " id="TableData">
          <div class="panel-body">
            <?php if (has_permission('products', '', 'create')) { ?>
              <?php echo _l('product_stock_list'); ?>
            </a>
            <?php } ?>
          </div>  
        </div>
        <div class="row">
          <div class="col-md-12" id="panel">
           <div class="panel_s">
              <div class="panel-body">
                <?php
                $table_data = [
                    _l('stock_status'),
                    _l('quantity'),
                    _l('product_name'),
                    _l('product_image'),
                    _l('products_categories'),
                    _l('invoice_item_add_edit_rate_currency'),
                  ];
                  render_datatable($table_data, ($class ?? 'products')); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <?php init_tail(); ?>
<script type="text/javascript">
  $(function(){
    initDataTable('.table-products', window.location.href,'undefined','undefined','');
  });     
</script>
