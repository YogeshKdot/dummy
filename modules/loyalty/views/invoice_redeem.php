<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php  hooks()->do_action('post_redeem_head'); ?>
<tr id="redeem_tr">
	<td>	
		<div class="row text-left" id="div_pos_redeem">
			<div class="col-md-6 pull-right">
				<?php echo render_input('voucher', 'loy_voucher'); ?>
			</div>
			<div class="col-md-12">
				<div class="col-md-6 pull-right">
					<p class="text-info text-uppercase"><i class="fa fa-empire"></i><?php echo ' '._l('loyalty_point').' '; ?><span class="label label-success" id="point_span"></span></p>
				</div>
			</div>
			<div id="pro_discount_div">
			</div>
			<?php echo form_hidden('weight'); ?>
			<?php echo form_hidden('rate_percent'); ?>
			<?php echo form_hidden('data_max'); ?>
			<?php echo form_hidden('program_discount', 0); ?>
			<?php echo form_hidden('redeem_discount', 0); ?>
			<?php echo form_hidden('voucher_value', 0); ?>


			<?php
			echo form_hidden('list_id_product', '');
			echo form_hidden('list_group_product', '');
			echo form_hidden('list_qty_product', '');
			echo form_hidden('list_prices_product', '');
			?>
			

			<?php $base_currency = get_base_currency_loy(); ?>
			<?php echo form_hidden('symbol', $base_currency->symbol); ?>
			<div class="col-md-3 pull-right">
				<label for="redeem_to"><?php echo _l('redeem_to') ?></label>
				<div class="input-group" id="discount-total">
					<input type="number" readonly class="form-control text-right" name="redeem_to" value="0">
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

			<div class="col-md-3 pull-right">
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

		</div>
	</td>
	<td><div class="row mtop40"><div class="col-md-12 mtop45 "><button type="button" id="redeem_btn" onclick="redeem_pos_order(); return false;" class="btn btn-primary mtop45" data-toggle="tooltip" data-placement="top" title="Redeem" ><i class="fa fa-refresh"></i></button></div></div></td>
</tr>
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