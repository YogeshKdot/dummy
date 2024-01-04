<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $redemp_rule = get_redemp_rule_client($client->userid, 'portal'); ?>

<?php if($redemp_rule != ''){ ?>
<div class="col-md-12">
<p class="text-info text-uppercase"><i class="fa fa-empire"></i><?php echo ' '._l('your_point').' '; ?><span class="label label-success"><?php echo client_loyalty_point($client->userid); ?></span></p>
</div>
<div class="col-md-8">
	<?php  
	$max_amount_received = 0;
	$loy_rule = get_rule_by_id($redemp_rule->loy_rule);
	if($loy_rule){ ?>
		<?php if($loy_rule->redeemp_type == 'full'){
			$val =  client_loyalty_point($client->userid);
			$val_to = client_loyalty_point($client->userid)*$redemp_rule->point_weight;
			$disabled = 'readonly';
			$min = $val;
		}elseif($loy_rule->redeemp_type == 'partial'){
			$disabled = '';
			$val = '';
			$val_to = '';
			$min = 0;
		}

		$max_amount_received = $loy_rule->max_amount_received;
		?>
	<?php } ?>
	
	<?php echo form_hidden('rate_percent', $max_amount_received); ?>

	<div class="col-md-6">
		<label for="redeem_from"><?php echo _l('redeem_from') ?></label>
		<div class="input-group" id="discount-total">
	     <input type="number" onchange="auto_redeem(this,'<?php echo html_entity_decode($redemp_rule->point_weight); ?>'); return false;" class="form-control text-right" name="redeem_from" value="<?php echo html_entity_decode($val); ?>" min="<?php echo html_entity_decode($min); ?>" max ="<?php echo client_loyalty_point($client->userid); ?>" <?php echo html_entity_decode($disabled); ?> >
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

	<?php $base_currency = get_base_currency_loy(); ?>
	<div class="col-md-5">
		<label for="redeem_to"><?php echo _l('redeem_to') ?></label>
		<div class="input-group" id="discount-total">
	     <input type="number" readonly class="form-control text-right" name="redeem_to" value="<?php echo html_entity_decode($val_to); ?>" >
	     <div class="input-group-addon">
	        <div class="dropdown">
	           <span class="discount-type-selected">
	            <?php 
	            if($base_currency){
	            	echo html_entity_decode($base_currency->name) ;
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
	<button type="button" onclick="redeem_order(); return false;" class="btn btn-warning mtop25" data-toggle="tooltip" data-placement="top" title="Redeem" ><i class="fa fa-refresh"></i></button>
</div>
<?php } ?>