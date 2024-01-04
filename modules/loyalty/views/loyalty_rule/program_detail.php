<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="panel_s">
			<div class="panel-body">
				<div class="row">
					<div class="hirasummary_left col-md-12">
						<div class="hiraheadsummary">
							<div class="hiraheadtop row">
								<div class="col-md-6 border-right">
									<span class="assessid"><?php echo _d($loyalty_program->date_create); ?></span>
									<h4><?php echo html_entity_decode($loyalty_program->subject); ?></h4>
									<p><?php echo html_entity_decode($loyalty_program->note) ?></p>
									<div class="hira_assess_status">
										<div class="assess_left form-group"> 
											<?php 
											echo '<a href="'.admin_url('staff/profile/'.$loyalty_program->add_from).'">' .get_staff_full_name($loyalty_program->add_from).'</a>';
											?> 
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-10">
										<p class="assessid"><?php echo _l('min_poin_to_redeem'); ?></p>
										<p>
										<?php echo html_entity_decode($loyalty_program->min_poin_to_redeem); ?>
										</p>
										</div>
										<div class="col-md-2 text-right">
										<?php if(is_admin() || has_permission('loyalty','','edit')){ ?>
											<a href="<?php echo admin_url('loyalty/create_loyalty_rule/'.$loyalty_program->id); ?>" class="btn btn-primary"><?php echo _l('edit'); ?></a>
										<?php } ?>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<p class="assessid"><?php echo _l('start_date'); ?></p>
											<p><?php echo _d($loyalty_program->start_date); ?></p>
										</div>

										<div class="col-md-6">
											<p class="assessid"><?php echo _l('end_date'); ?></p>
											<p><?php echo _d($loyalty_program->end_date); ?></p>
										</div>
									</div>

								</div>
							</div>
							<div class="hiraclassification">
								<div class="classify_hira first col-md-3">
									<label class="text-info"><?php echo _l('rule_base'); ?></label>
									<div class="classify_value"><?php echo _l($loyalty_program->rule_base) ?></div>
									<p class="mtop15"><?php
										if($loyalty_program->rule_base == 'product'){
											echo _l('rule_product_note');
										}elseif($loyalty_program->rule_base == 'product_category'){
											echo _l('rule_product_category_note');
										}else{
											echo _l('rule_card_total_note');
										}
									 ?></p>
								</div>
								<div class="classify_hira col-md-9">
									<label class="text-info"><?php echo _l('program_detail'); ?></label>
									<?php if($loyalty_program->rule_detail != ''){
										foreach($loyalty_program->rule_detail as $detail){ ?>
										<p class="classify_value"><?php 
										if($detail['rel_type'] == 'product'){
											echo product_by_id($detail['rel_id']);
										}elseif($detail['rel_type'] == 'product_category'){
											echo product_category_by_id($detail['rel_id']);
										} ?>
										<span class="pull-right"><?php echo _l('poin_awarded').': '.html_entity_decode($detail['loyalty_point']); ?></span></p>
									<?php }
									} ?>

									<?php if($loyalty_program->rule_base == 'card_total'){ ?>
										<p class="classify_value"><?php echo _l('poin_awarded').': '; ?> <span class="text-info"><?php echo html_entity_decode($loyalty_program->poin_awarded); ?></span><?php echo ' '._l('purchase_value_text_label').': '; ?><span class="text-info"><?php echo html_entity_decode($loyalty_program->purchase_value).' '.$base_currency->name; ?></span><p>
									<?php } ?>
								</div>
							</div>
						</div>

						<div class="col-md-12 pleft0 pright0">
							<div class="hiraheadsummary">
								<div class="hiraheadtop row">
									<div class="col-md-12">
										<p class="assessid"><?php echo _l('qualified_customers'); ?></p>
										<hr>
										<table class="table dt-table">
											<thead>
												<th><?php echo _l('customer'); ?></th>
												<th><?php echo _l('membership'); ?></th>
												<th><?php echo _l('loyalty_point'); ?></th>
											</thead>
											<tbody>
												<?php $client_arr = explode(',', $loyalty_program->client);
												foreach($clients as $client_key => $cli){ ?>
													<?php if(($cli['loy_point'] >= $loyalty_program->min_poin_to_redeem) && in_array($cli['userid'], $client_arr)) { ?>
														<tr>
															<td><?php echo '<a href="'.admin_url('clients/client/'.$cli['userid']).'" >'.$cli['company'].'</a>'; ?></td>
															<td><?php echo client_membership($cli['userid']); ?></td>
															<td><span class="label label-success"><?php echo client_loyalty_point($cli['userid']); ?></span></td>
														</tr>
													<?php } ?>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php init_tail(); ?>
</body>
</html>
