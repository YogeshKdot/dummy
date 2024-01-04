<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div class="row">
	<?php $base_currency = get_base_currency_pur(); ?>
	<div class="col-md-12">
		<div class="panel_s">
			<div class="panel-body">
				<h4><?php echo html_entity_decode($title) ?></h4>
				<hr class="mtop5">
				<table class="table dt-table">
			       <thead>
			       	<th><?php echo _l('invoice_no'); ?></th>
			         <th><?php echo _l('contract'); ?></th>
			          <th><?php echo _l('pur_order'); ?></th>
			          <th><?php echo _l('invoice_date'); ?></th>
			          <th><?php echo _l('invoice_amount'); ?></th>
			          <th><?php echo _l('tax_value'); ?></th>
			          <th><?php echo _l('total_included_tax'); ?></th>
			          <th><?php echo _l('payment_status'); ?></th>
			       </thead>
			      <tbody>
			         <?php foreach($invoices as $inv) { ?>
			         <tr class="inv_tr">
			         	<td class="inv_tr"><a href="<?php echo site_url('purchase/vendors_portal/invoice/'.$inv['id']); ?>"><?php echo html_entity_decode($inv['invoice_number']); ?></a></td>
			         	<td class="inv_tr"><?php echo get_pur_contract_number($inv['contract'],''); ?></td>
			         	<td class="inv_tr"><?php echo get_pur_order_subject($inv['pur_order']); ?></td>
			         	<td class="inv_tr"><?php echo '<span class="label label-info">'._d($inv['invoice_date']).'</span>'; ?></td>
			         	<td class="inv_tr"><?php echo app_format_money($inv['subtotal'],$base_currency->symbol); ?></td>
			         	<td class="inv_tr"><?php echo app_format_money($inv['tax'],$base_currency->symbol); ?></td>
			         	<td class="inv_tr"><?php echo app_format_money($inv['total'],$base_currency->symbol); ?></td>
			         	<td class="inv_tr"><?php 
			         	$class = '';
			            if($inv['payment_status'] == 'unpaid'){
			                $class = 'danger';
			            }elseif($inv['payment_status'] == 'paid'){
			                $class = 'success';
			            }elseif ($inv['payment_status'] == 'partially_paid') {
			                $class = 'warning';
			            }

			            echo  '<span class="label label-'.$class.' s-status invoice-status-3">'._l($inv['payment_status']).'</span>';

			         	?>
			         	</td>
			         </tr>
			         <?php } ?>
			      </tbody>
			   </table>	
			</div>
		</div>
	</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>