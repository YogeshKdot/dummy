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
									<span class="assessid"><?php echo _d($mbs_program->date_create); ?></span>
									<h4><?php echo html_entity_decode($mbs_program->program_name); ?></h4>
									<p><?php echo html_entity_decode($mbs_program->note) ?></p>
									<div class="hira_assess_status">
										<div class="assess_left form-group"> 
											<?php 
											echo '<a href="'.admin_url('staff/profile/'.$mbs_program->add_from).'">' .get_staff_full_name($mbs_program->add_from).'</a>';
											?> 
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-10">
										<p class="assessid"><?php echo _l('apply_for_rank'); ?></p>
										<p>
										<?php 
											$rank_ids = explode(',', $mbs_program->membership);
											$list_rank = '';
											if(count($rank_ids) > 0){
												foreach($rank_ids as $key => $mbs_id){
													if(($key + 1) < count($rank_ids)){
														$list_rank .= get_membership_rule_name($mbs_id).', ';
													}else{
														$list_rank .= get_membership_rule_name($mbs_id);
													}
												}
											}
											echo html_entity_decode($list_rank);
										 ?>
										</p>
										</div>
										<div class="col-md-2 text-right">
										<?php if(is_admin() || has_permission('loyalty','','edit')){ ?>
											<a href="<?php echo admin_url('loyalty/mbs_program/'.$mbs_program->id); ?>" class="btn btn-primary"><?php echo _l('edit'); ?></a>
										<?php } ?>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<p class="assessid"><?php echo _l('point_from'); ?></p>
											<p><?php echo html_entity_decode($mbs_program->loyalty_point_from); ?></p>
										</div>

										<div class="col-md-6">
											<p class="assessid"><?php echo _l('point_to'); ?></p>
											<p><?php echo html_entity_decode($mbs_program->loyalty_point_to); ?></p>
										</div>
									</div>

								</div>
							</div>
							<div class="hiraclassification">
								<div class="classify_hira first col-md-3">
									<label class="text-info"><?php echo _l('discount_type'); ?></label>
									<div class="classify_value"><?php echo _l($mbs_program->discount) ?></div>
								</div>
								<div class="classify_hira col-md-9">
									<label class="text-info"><?php echo _l('program_detail'); ?></label>
									<?php if($mbs_program->discount_detail != ''){
										foreach($mbs_program->discount_detail as $detail){ ?>
										<p class="classify_value"><?php 
										if($detail['rel_type'] == 'product_category'){
											echo product_category_by_id($detail['rel_id']);
										}elseif($detail['rel_type'] == 'product'){
											echo product_by_id($detail['rel_id']);
										}
										 ?>
										
										<span class="pull-right"><?php echo html_entity_decode($detail['percent']).'%' ?></span></p>
									<?php }
									} ?>

									<?php if($mbs_program->discount == 'card_total'){ ?>
										<p class="classify_value"><?php echo _l('discount_percent').': '.html_entity_decode($mbs_program->discount_percent).'%'; ?>
									<?php } ?>
								</div>
							</div>
						</div>

						<div class="col-md-8 pleft0">
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
												<?php foreach($clients as $client_key => $cli){ ?>
													<?php if(in_array($mbs_program->id, program_ids_client($cli['userid']))  ){ ?>
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

						<div class="col-md-4 pright0">
							<div class="hiraheadsummary">
								<div class="hiraheadtop row">
									<div class="col-md-12">
										<p class="assessid"><?php echo _l('voucher_infor'); ?></p>
										<h4><?php echo _l('voucher_code').': '; ?><span class="text-danger"><?php echo html_entity_decode($mbs_program->voucher_code); ?></span></h4>
										<p><?php echo _l('voucher_value').': '; ?><span class="text-info"><?php echo html_entity_decode($mbs_program->voucher_value); ?></span></p>

										<?php $type_reduce = '';
											if($mbs_program->formal == 1){
												$type_reduce = 'loy_reduced_by_%';
											}else{
												$type_reduce = 'loy_reduced_by_amount';
											}
										 ?>
										<p><?php echo _l('type').': '; ?><span class="text-info"><?php echo _l($type_reduce); ?></span></p>
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
