<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php  hooks()->do_action('post_redeem_head'); ?>
<div class="row col-md-12" id="div_pos_redeem">
	<div class="col-md-12">
		<p class="text-info text-uppercase"><i class="fa fa-empire"></i><?php echo ' '._l('loyalty_point').' '; ?><span class="label label-success" id="point_span"></span></p>
	</div>
	<?php echo form_hidden('weight'); ?>
	<?php echo form_hidden('rate_percent'); ?>
	<?php echo form_hidden('data_max'); ?>
	<div class="col-md-6">
		<label for="redeem_from"><?php echo _l('redeem_from') ?></label>
		<div class="input-group" id="discount-total">
			<input type="number" onchange="auto_redeem_pos(this); return false;" class="form-control text-right" name="redeem_from" value="" min="0">
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
			<input type="number" readonly class="form-control text-right" name="redeem_to" value="">
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
	<div class="col-md-1">
		<button type="button" onclick="redeem_pos_order(); return false;" class="btn btn-primary mtop25" data-toggle="tooltip" data-placement="top" title="Redeem" ><i class="fa fa-refresh"></i></button>
	</div>
</div>