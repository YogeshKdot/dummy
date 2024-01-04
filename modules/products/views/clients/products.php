<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="">
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-6">
        <?php echo render_select('product_categories', $product_categories, ['p_category_id', 'p_category_name'], '', '', ['multiple'=>true, 'data-none-selected-text'=>_l('products_categories'), 'multiple data-actions-box'=>'true'], [], '', '', false); ?>
        </div>
        <div class="col-md-8 col-sm-6 col-xs-6">
            <a href="<?php echo site_url('products/client/place_order'); ?>" class="btn btn-success pull-right"><i class="fa fa-shopping-cart"></i> View Cart and Checkout </a>
        </div>        
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12 text-center no_product hidden">
        <br><br><img src="<?php echo module_dir_url('products', 'uploads').'/no-product.png'; ?>" class="img1 img-responsive">
    </div>
    <div id="filter_html">
    </div>
</div>
<script type="text/javascript" src="<?php echo module_dir_url('products', 'assets/js/client_products.js'); ?>"></script>
