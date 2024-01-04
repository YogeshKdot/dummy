<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                     <div class="_buttons">
                        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#product_category_modal"><?php echo _l('new_category'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="clearfix"></div>
                    <?php render_datatable([
                        _l('category_name'),
                        _l('category_description'),
                        _l('options'),
                        ], 'product-category'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('products/product_category_modal'); ?>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-product-category', window.location.href, [1], [1]);
   });
</script>
</body>
</html>
