<div class="col-md-12">
	<p class="bold"><?php echo _l('products_module_settings'); ?></p>
<?php render_yes_no_option('product_menu_disabled', 'product_menu_disabled', 'product_menu_disabled_tooltip'); ?>
</div>
<hr />
<div class="col-md-12">
	<i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('product_low_quantity_tooltip'); ?>"></i>
	<?php echo render_input('settings[product_low_quantity]', 'Low Quantity', get_option('product_low_quantity'), 'number', ['required'=>true]); ?>
</div>