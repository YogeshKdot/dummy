<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
 <?php $base_currency = get_base_currency_loy(); ?>
 <?php 
  	$base_crc = '';
  	if($base_currency){
  		$base_crc = $base_currency->name;
  	}
  ?>
<div class="row">
	<div class="col-md-12">
		<h3 id="greeting" class="no-mtop"></h3>
		<div class="panel_s">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-2">
							<?php if($contact->profile_image != NULL){ ?>
								<img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>" data-toggle="tooltip" data-title="<?php echo html_escape($client->company.' - '. $contact->firstname . ' ' .$contact->lastname); ?>" data-placement="bottom" class="client-profile-image-thumb">
							<?php } ?>

							<?php if($contact->profile_image == NULL){ ?>
								<img src="<?php echo site_url(LOYALTY_PATH . 'nul_image.jpg'); ?>" data-toggle="tooltip" data-title="<?php echo html_escape($client->company.' - '.$contact->firstname . ' ' .$contact->lastname); ?>" data-placement="bottom" class="client-profile-image-thumb">
							<?php } ?>
						</div>

						<div class="col-md-6">
							<?php $point = client_loyalty_point(get_client_user_id()); 
								  $next_rank = client_next_rank(get_client_user_id());
								  $rank = client_rank(get_client_user_id());
								  	if($rank){
								  		$card = $this->loyalty_model->get_card($rank->card);
									}else{
									  	$card = '';
									}
							?>
							<?php $next_rank_point = 0;
							$text = '';
							if($next_rank){
								if($rank){
									if($next_rank->id == $rank->id){
										$next_rank_point = $next_rank->loyalty_point_to;
									}else{
										$next_rank_point = $next_rank->loyalty_point_from;
									}
								}else{
									$next_rank_point = $next_rank->loyalty_point_from;
								}

								$nr_point = ($next_rank_point - $point) > 0 ? ($next_rank_point - $point): 0;
								
								$text = _l('loy_you_need').' '.$nr_point.' '._l('points_to_rank_up');
							}else{
								if($rank){
									$next_rank_point = $rank->loyalty_point_to;
								}else{
									$next_rank_point = $point;
								}
							} 

							$percent = 0;
							if($next_rank_point > 0){
								$percent = ($point/$next_rank_point)*100;
							}

							?>
							<table class="table ">
								<tbody>
									<tr>
										<td width="20%" class="bold"><p class="text-info text-uppercase"><i class="fa fa-empire"></i><?php echo ' '._l('point'); ?></p></td>
										<td><span class="label label-success"><?php echo client_loyalty_point(get_client_user_id()); ?></span></td>
									</tr>
									<tr>
										<td width="20%" class="bold"><p class="text-info text-uppercase"><i class="fa fa-sort-amount-desc"></i><?php echo ' '._l('rank'); ?></p></td>
										<td>
											<p class="">
												<?php echo client_membership(get_client_user_id()); ?> - <?php echo html_entity_decode($text); ?><span class="pull-right"><?php echo html_entity_decode($point); ?> / <?php echo html_entity_decode($next_rank_point); ?>
												</span>
											</p>

											<div class="progress no-margin progress-bar-mini">
							                  <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo html_entity_decode($point); ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($next_rank_point); ?>" style="width: <?php echo html_entity_decode($percent); ?>%;" data-percent="<?php echo html_entity_decode($percent); ?>">
							                  </div>
							               </div>
										</td>
									</tr>
									<tr>
										<td width="20%" class="bold"><p class="text-info text-uppercase"><i class="fa fa-arrow-right"></i><?php echo ' '._l('next_rank'); ?></p></td>
										<td>
										  <?php	 
										  	if($next_rank){
										  		echo html_entity_decode($next_rank->name);
										  	}else{
										  		echo client_membership(get_client_user_id());
										  	}
										  ?>
										</td>
									</tr>

								</tbody>
							</table>
						</div>

						<div class="col-md-4">
							<div class="picture-container pull-right">
                              <div class="picture pull-right">
                                  <img src="<?php if(($card != '') && isset($card->card_picture)){ echo site_url(LOYALTY_PATH . 'card_picture/'.$card->card_picture->rel_id.'/'.$card->card_picture->file_name);  }else{ echo site_url(LOYALTY_PATH . 'nul_image.jpg'); } ?>" class="picture-src" id="wizardPicturePreview" title="">
                              </div>
                              <div class="top-left">
                              	<?php if($card != '' && ($card->client_name == 1)){ ?>
                              		<span class="bold fsize20" style="color: <?php echo html_entity_decode($card->text_color); ?>"><?php echo html_entity_decode($client->company) ?></span><br>
                                <?php } ?>

                                <?php if($card != '' && ($card->company_name == 1)){ ?>
                              		<span class="bold fsize20" style="color: <?php echo html_entity_decode($card->text_color); ?>"><?php echo get_option('companyname'); ?></span>
                                <?php } ?>

                              </div>
							  <div class="bottom-left">
							  	<?php if($card != '' && ($card->membership == 1)){ ?>
                              		<span class="bold fsize20" style="color: <?php echo html_entity_decode($card->text_color); ?>"><?php echo client_membership(get_client_user_id()); ?></span>
                                <?php } ?></div>
							  </div>
							  <div class="top-right">
							  	<?php if($card != '' && ($card->subject_card == 1)){ ?>
                              		<span class=" bold fsize20" style="color: <?php echo html_entity_decode($card->text_color); ?>"><?php echo html_entity_decode($card->name) ?></span>
                                <?php } ?></div>
							  <div class="bottom-right">
							  	<?php if($card != '' && ($card->custom_field == 1)){ ?>
                              		<span class=" bold fsize20" style="color: <?php echo html_entity_decode($card->text_color); ?>"><?php echo html_entity_decode($card->custom_field_content) ?></span>
                                <?php } ?></div>
							  </div>
							  <div class="centered"></div>
                              
                          </div>
						</div>

						<div class="clearfix"></div>
					<div class="col-md-12">
						<br><br>
						<div class="horizontal-scrollable-tabs preview-tabs-top">
			             
			            <div class="horizontal-tabs">
			            <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">

			               <li role="presentation" class="active">
			                   <a href="#transation" aria-controls="transation" role="tab" data-toggle="tab" aria-controls="transation">
			                  	<i class="fa fa-backward"></i>&nbsp;<?php echo _l('transation'); ?>
			                   </a>
			                </li>
			                <li role="presentation">
			                   <a href="#entitlements" aria-controls="entitlements" role="tab" data-toggle="tab" aria-controls="entitlements">
			                   <i class="fa fa-usd"></i>&nbsp;<?php echo _l('loy_promotion'); ?>
			                   </a>
			                </li>
			                <li role="presentation">
			                   <a href="#redeem_log" aria-controls="redeem_log" role="tab" data-toggle="tab" aria-controls="redeem_log">
			                   <i class="fa fa-history"></i>&nbsp;<?php echo _l('redeem_log'); ?>
			                   </a>
			                </li>
			             </ul>
			             </div>
			        	</div>

			        	<div class="tab-content">
		                  	<div role="tabpanel" class="tab-pane active" id="transation">
								<table class="table dt-table">
									<thead>
										<th><?php echo _l('reference'); ?></th>
										<th><?php echo _l('invoice'); ?></th>
										<th><?php echo _l('point'); ?></th>
										<th><?php echo _l('type'); ?></th>
										<th><?php echo _l('time'); ?></th>
										<th><?php echo _l('note'); ?></th>
									</thead>
									<tbody>
										<?php $this->load->model('invoices_model');
										if(count($transations) > 0){ 
											foreach($transations as $tran){
												if(is_numeric($tran['invoice'])){
													$invoice = $this->invoices_model->get($tran['invoice']);
												}
												
											 ?>
												<tr>
													<td><?php echo _l($tran['reference']); ?></td>
													
													<?php if(isset($invoice)) { ?>
													<td><a href="<?php echo site_url('invoice/' . $tran['invoice'] . '/' . $invoice->hash); ?>" class="invoice-number"><?php echo format_invoice_number($tran['invoice']); ?></a></td>
													<?php } else { ?>
														<td></td>
													<?php } ?>
													<td><span class="label label-success"><?php echo html_entity_decode($tran['loyalty_point']); ?></span></td>
													<td><?php echo _l($tran['type']); ?></td>
													<td><span class="label label-info"><?php echo _dt($tran['date_create']); ?></span></td>
													<td>
														<?php
															$note = $tran['note'];
															if($tran['note'] == 'bonus_points_for_creating_new_accounts'){
																$note = _l($tran['note']);
															}elseif($tran['note'] == 'bonus_points_for_customers_birthday'){
																$note = _l('bonus_points_for_customers_birthday_client_portal');
															}
															echo html_entity_decode($note);
														 ?>
													</td>
												</tr>
										<?php } } ?>
									</tbody>
								</table>
							</div>

							<div role="tabpanel" class="tab-pane" id="entitlements">
								<div id="program_detail">
									
								</div>
								
								<table class="table dt-table">
									<thead>
										<th><?php echo _l('program_name'); ?></th>
										<th><?php echo _l('discount_type'); ?></th>
										<th><?php echo _l('voucher'); ?></th>
										<th><?php echo _l('start_date'); ?></th>
										<th><?php echo _l('end_date'); ?></th>

									</thead>
									<tbody>
										<?php if(count($programs) > 0){
											foreach($programs as $pg){
										 ?>
										 <tr>
										 	<td><a href="#" onclick="program_detail('<?php echo html_entity_decode($pg['id']); ?>'); return false;"><?php echo html_entity_decode($pg['program_name']); ?></a></td>
										 	<td><?php echo _l($pg['discount']); ?></td>
										 	<td>

										 		<?php 
										 		echo _l('voucher_code').': <strong class="text-danger">'.html_entity_decode($pg['voucher_code']).'</strong>';
										 		if($pg['formal'] == 1){
										 			echo '<br />'._l('loy_reduced_by_%').': '. app_format_money($pg['voucher_value'],'').'%';
										 		}else{
										 			echo '<br />'._l('loy_reduced_by_amount').': '.  app_format_money($pg['voucher_value'],'').' '.$base_crc ;
										 		} ?>
										 	</td>
										 	<td><span class="label label-info"><?php echo _d($pg['start_date']); ?></span></td>
										 	<td><span class="label label-info"><?php echo _d($pg['end_date']); ?></span></td>
										 </tr>

										<?php } } ?>
									</tbody>
								</table>
							</div>

							<div role="tabpanel" class="tab-pane" id="redeem_log">
				                  <div class="col-md-12">
					                  <div class="activity-feed">
					                  <?php foreach($rd_logs as $b){ ?>
					                  <div class="feed-item">
					                    <div class="date"><span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($b['time']); ?>">
					                      <?php echo time_ago($b['time']); ?>
					                    </span>
					                  </div>
					                    <div class="text">
					                     <p class="bold no-mbot">
					                      <a href="<?php echo site_url('invoice/'.$b['invoice'].'/'.get_inv_hash($b['invoice'])); ?>"></p>
					                      <?php echo format_invoice_number($b['invoice']); ?><br></a>
					                      <?php echo _l('redeem_from'); ?>: <?php echo html_entity_decode($b['redeep_from']).' points'.' - '; ?> <?php echo _l('to'); ?>: <?php echo app_format_money($b['redeep_to'],$base_crc); ?>
					                    </div>
					                  </div>
					                  <?php } ?>
					                </div>
					             </div>
							</div>

						</div>
					</div>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>

<?php require 'modules/loyalty/assets/js/home_portal_js.php';?>


