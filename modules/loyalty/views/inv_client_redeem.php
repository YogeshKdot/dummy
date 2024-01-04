<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $redemp_rule = get_redemp_rule_client_inv($invoice->clientid); ?>
<div class="col-md-6 col-md-offset-6">
<?php $base_currency = get_base_currency_loy(); ?>

<?php 
$items_ids = [];
$item_group_ids = [];
$list_qty = [];
$list_price = [];
if(count($invoice->items) > 0){
	foreach($invoice->items as $item){
		$items_ids[] = get_item_id_by_item_name($item['description'], $item['long_description']);
		$item_group_ids[] = get_group_by_item_name($item['description'], $item['long_description']);
		$list_qty[] = $item['qty'];
		$list_price[] = $item['rate']; 
	}
}

$list_id = implode(',', $items_ids);
$list_group_id = implode(',', $item_group_ids);  
$list_qty = implode(',', $list_qty);  
$list_price = implode(',', $list_price);  

echo form_hidden('list_id_product', $list_id);
echo form_hidden('list_group_product', $list_group_id);
echo form_hidden('list_qty_product', $list_qty);
echo form_hidden('list_prices_product', $list_price);
?>

<?php echo form_open(site_url('loyalty/loyalty_portal/redeem_inv')); ?>

<?php echo render_input('voucher', 'loy_voucher'); ?>
<?php echo form_hidden('clientid', $invoice->clientid); ?>
<?php echo form_hidden('symbol', $base_currency->symbol); ?>
<?php echo form_hidden('voucher_value', ''); ?>
<?php echo form_hidden('program_discount', ''); ?>
<?php echo form_hidden('redeem_val', ''); ?>
<?php if($redemp_rule != ''){ ?>

<p class="text-info text-uppercase"><i class="fa fa-empire"></i><?php echo ' '._l('your_point').' '; ?><span class="label label-success"><?php echo client_loyalty_point($invoice->clientid); ?></span></p>

	<?php echo form_hidden('total', $invoice->total); ?>
	<?php echo form_hidden('discount_total', $invoice->discount_total); ?>
	<?php  $loy_rule = get_rule_by_id($redemp_rule->loy_rule);
	if($loy_rule){ ?>
		<?php 
		$max_amount_received = $loy_rule->max_amount_received;
		if($loy_rule->redeemp_type == 'full'){
			$val =  client_loyalty_point($invoice->clientid);
			$val_to = client_loyalty_point($invoice->clientid)*$redemp_rule->point_weight;
			$max = ($invoice->total * $max_amount_received)/100;
			if($val_to < $max){
				$val_to = $val_to;
			}else{
				$val_to = $max;
			}

			$disabled = 'readonly';
			$min = $val;
		}elseif($loy_rule->redeemp_type == 'partial'){
			$disabled = '';
			$val = '';
			$val_to = '';
			$min = 0;
		}
		?>
	<?php } ?>
	
	<?php echo form_hidden('rate_percent', $max_amount_received); ?>

	<?php echo form_hidden('inv_id', $invoice->id); ?>
	<?php echo form_hidden('new_discount', 0); ?>
	<?php echo form_hidden('new_total', 0); ?>
	<div class="col-md-6">
		<label for="redeem_from"><?php echo _l('redeem_from') ?></label>
		<div class="input-group" id="discount-total">
	     <input type="number" onchange="auto_redeem(this,'<?php echo html_entity_decode($redemp_rule->point_weight); ?>'); return false;" class="form-control text-right" name="redeem_from" value="<?php echo html_entity_decode($val); ?>" min="<?php echo html_entity_decode($min); ?>" max ="<?php echo client_loyalty_point($invoice->clientid); ?>" <?php echo html_entity_decode($disabled); ?> >
	     <div class="input-group-addon">
	        <div class="dropdown">
	           <span class="discount-type-selected">
	            <?php 
	           	 echo 'POINT';
	            ?>
	           </span>
	        </div>
	     </div>
	  	</div>
	  	<br>
	</div>

	
	<div class="col-md-5">
		<label for="redeem_to"><?php echo _l('redeem_to') ?></label>
		<div class="input-group" id="discount-total">
	     <input type="number" readonly class="form-control text-right" name="redeem_to" value="<?php echo html_entity_decode($val_to); ?>" >
	     <div class="input-group-addon">
	        <div class="dropdown">
	           <span class="discount-type-selected">
	            <?php 
	            if($base_currency){
	            	echo html_entity_decode($base_currency->symbol) ;
	        	}else{
	        		echo '';
	        	}
	            ?>
	           </span>
	        </div>
	     </div>
	  	</div>
	  	<br>
	</div>
	<button type="button" onclick="redeem_order(this); return false;" class="btn btn-icon btn-warning mtop25" data-symbol="<?php echo html_entity_decode($base_currency->symbol); ?>" data-toggle="tooltip" data-placement="top" title="Redeem" ><i class="fa fa-refresh"></i></button>

	<button type="submit" class="btn btn-info pull-right redeem_inv_btn" disabled><?php echo _l('loy_confirm') ?></button>
	<?php echo form_close(); ?>
<?php } ?>
</div>

<div class="modal fade" id="discount_detail_md" tabindex="-1" role="dialog">
  <div class="modal-dialog">
   
    <div class="modal-content modal_withd">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="add-title"><?php echo _l('loy_discount_detail'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div id="discount_detail_div">
            	
            </div>  
		</div>
      </div>
	<div class="modal-footer">
	  <button type=""class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->