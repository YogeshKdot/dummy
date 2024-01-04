<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<h4 class="pull-left">
									<?php echo html_entity_decode($title); ?>
								</h4>
								<a href="<?php echo admin_url('fixed_equipment/requested'); ?>" class="btn btn-default pull-right"><?php echo _l('fe_back'); ?></a>
								<div class="clearfix"></div>
								<hr>
							</div>
						</div>
						<br>
						<div class="horizontal-scrollable-tabs mb-5">
							<?php
							$manu_name = '';
							$manu_url = '';
							$manu_support_url = '';
							$manu_support_phone = '';
							$manu_support_email = '';

							$depreciation_name = '';
							$depreciation_term = '';

							if(isset($model)){
								$data_manu = $this->fixed_equipment_model->get_asset_manufacturers($model->manufacturer);
								if($data_manu){
									$manu_name = $data_manu->name;
									$manu_url = $data_manu->url;
									$manu_support_url = $data_manu->support_url;
									$manu_support_phone = $data_manu->support_phone;
									$manu_support_email = $data_manu->support_email;
								}

								$data_depreciation = $this->fixed_equipment_model->get_depreciations($model->depreciation);
								if($data_depreciation){
									$depreciation_name = $data_depreciation->name;
									$depreciation_term = $data_depreciation->term;
								}
							}
							?>

							<div class="row">
								<div class="col-md-4">
									<div class="row">
										<div class="col-md-12 text-center">
											<img class="img img-rounded mtop10 img-thumbnail" src="<?php echo html_entity_decode($this->fixed_equipment_model->get_image_items($model->id, 'models'));  ?>">          
										</div>
									</div>
									<br>
									<table class="table table-striped">
										<tbody>
											<tr>
												<td><?php 
												$status_name = '';
												if(is_numeric($asset->status)){
													$status_data = $this->fixed_equipment_model->get_status_labels($asset->status); 
													if($status_data){
														$status_name = $status_data->name;
													}
												}
												echo '<strong>'._l('fe_status').': </strong>'. $status_name; ?></td>
											</tr>
											<tr>
												<td><?php echo '<strong>Serial: </strong> '.$asset->series; ?></td>
											</tr>
											<tr>
												<td><?php echo '<strong>'._l('fe_model_no').': </strong>'.$model->model_no; ?></td>
											</tr>
											<tr>
												<td>
													<br>
													<img class="img img-thumbnail" width="160px" src="<?php echo fe_get_image_qrcode($asset->id);  ?>">         
													<br>
													<small><?php echo html_entity_decode($asset->qr_code);  ?></small> 
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								<div class="col-md-8">
									<table class="table table-striped">
										<tr>
											<td>
												<table>
													<tr>
														<td>
															<?php echo '<strong>'._l('fe_manufacturer').':&nbsp;</strong>'; ?> 
														</td>
														<td>
															<?php echo html_entity_decode($manu_name); ?> 
														</td>
													</tr>
													<tr>
														<td></td>
														<td class="ptop10"><strong><i class="fa fa-globe"></i></strong> <a href="<?php echo html_entity_decode($manu_url); ?>"><?php echo html_entity_decode($manu_url); ?></a></td>
													</tr>
													<tr>
														<td></td>
														<td class="ptop10"><strong><i class="fa fa-life-ring"></i></strong> <a href="<?php echo html_entity_decode($manu_support_url); ?>"><?php echo html_entity_decode($manu_support_url); ?></a></td>
													</tr>
													<tr>
														<td></td>
														<td class="ptop10"><strong><i class="fa fa-phone"></i></strong> <a href="tel:<?php echo html_entity_decode($manu_support_phone); ?>"><?php echo html_entity_decode($manu_support_phone); ?></a></td>
													</tr>
													<tr>
														<td></td>
														<td class="ptop10"><strong><i class="fa fa-envelope"></i></strong> <a href="mailto:<?php echo html_entity_decode($manu_support_email); ?>"><?php echo html_entity_decode($manu_support_email); ?></a></td>
													</tr>
												</table>
												<br>
											</td>
										</tr>
										<tr>
											<td><?php
											$category_name = '';
											if(is_numeric($model->category)){
												$data_category = $this->fixed_equipment_model->get_categories($model->category);
												if($data_category){
													$category_name = $data_category->category_name;
												}
											}
											echo '<strong>'._l('fe_category').': </strong>'.$category_name; ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_purchase_date').': </strong>'.$asset->date_buy; ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_purchase_cost').': </strong>'.app_format_money($asset->unit_price,''); ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_order_number').': </strong>'.$asset->order_number; ?></td>
										</tr>
										<tr>
											<td><?php
											$supplier_name = '';
											if(is_numeric($asset->supplier_id)){
												$data_supplier = $this->fixed_equipment_model->get_suppliers($asset->supplier_id);
												if($data_supplier){
													$supplier_name = $data_supplier->supplier_name;
												}
											}
											echo '<strong>'._l('fe_supplier').': </strong>'.$supplier_name; ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_depreciation').': </strong>'.$depreciation_name.' ('.$depreciation_term.' '._l('months').')'; ?></td>
										</tr>   
										<tr>
											<td><?php echo '<strong>'._l('fe_fully_depreciated').': </strong>'.$manu_name; ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_eol_rate').': </strong>'.(is_numeric($model->eol) ? $model->eol.' '._l('months') : ''); ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_eol_date').': </strong>'._d($model->date_creator); ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_notes').': </strong>'.$asset->description; ?></td>
										</tr>
										<tr>
											<td><?php
											$location_name = '';
											if(is_numeric($asset->asset_location)){
												$data_location = $this->fixed_equipment_model->get_locations($asset->asset_location);
												if($data_location){
													$location_name = $data_location->location_name;
												}
											}

											echo '<strong>'._l('fe_default_location').': </strong>'.$location_name; ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_created_at').': </strong>'.($asset->date_creator != '' ? _dt($asset->date_creator) : ''); ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_updated_at').': </strong>'.($asset->updated_at != '' ? _dt($asset->updated_at) : ''); ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_checkouts').': </strong>'.$this->fixed_equipment_model->count_log_detail($asset->id, 'checkout',0); ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_checkins').': </strong>'.$this->fixed_equipment_model->count_log_detail($asset->id, 'checkin'); ?></td>
										</tr>
										<tr>
											<td><?php echo '<strong>'._l('fe_requests').': </strong>'.$this->fixed_equipment_model->count_log_detail($asset->id, 'checkout', 1, 1); ?></td>
										</tr>
									</table>

									<div class="project-overview-right">
										<div class="project-overview-right">
											<?php
											if(count($data_approve) > 0){ ?>
												<div class="row">
													<div class="col-md-12 project-overview-expenses-finance">
														<?php 
														$has_deny = false;
														$current_approve = false;
														foreach ($data_approve as $value) {
															?>
															<div class="col-md-4 text-center">
																<p class="text-uppercase text-muted no-mtop bold"><?php	echo get_staff_full_name($value['staffid']); ?></p>

																<?php if($value['approve'] == 1){ 
																	?>
																	<img src="<?php echo site_url(FIXED_EQUIPMENT_PATH.'approve/approved.png'); ?>">
																	<br><br>
																	<p class="bold text-center"><?php echo html_entity_decode($value['note']); ?></p> 
																	<p class="bold text-center text-<?php if($value['approve'] == 1){ echo 'success'; }elseif($value['approve'] == 2){ echo 'danger'; } ?>"><?php echo _dt($value['date']); ?>
																<?php }elseif($value['approve'] == 2){ $has_deny = true;?>
																	<img src="<?php echo site_url(FIXED_EQUIPMENT_PATH.'approve/rejected.png'); ?>">
																	<br><br>
																	<p class="bold text-center"><?php echo html_entity_decode($value['note']); ?></p> 
																	<p class="bold text-center text-<?php if($value['approve'] == 1){ echo 'success'; }elseif($value['approve'] == 2){ echo 'danger'; } ?>"><?php echo _dt($value['date']); ?>
																<?php }else{
																	if($current_approve == false && $has_deny == false){ 
																		$current_approve = true;
																		if(get_staff_user_id() == $value['staffid']){ 
																			?>
																			<div class="row text-center" >
																				<a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?><span class="caret"></span></a>
																				<div class="dropdown-menu dropdown-menu-left">
																					<div class="col-md-12">
																						<?php echo render_textarea('reason', 'reason'); ?>
																						<div class="clearfix"></div>
																					</div>
																					<div class="col-md-12 text-center">
																						<a href="javascript:void(0)" data-loading-text="<?php echo _l('fe_waiting'); ?>" onclick="approve_request(<?php echo html_entity_decode($id); ?>);" class="btn btn-success"><?php echo _l('approve'); ?></a>
																						<a href="javascript:void(0)" data-loading-text="<?php echo _l('fe_waiting'); ?>" onclick="deny_request(<?php echo html_entity_decode($id); ?>);" class="btn btn-warning"><?php echo _l('deny'); ?></a>
																					</div>
																					<div class="clearfix"></div>
																					<br>
																					<div class="clearfix"></div>
																				</div>
																			</div>
																			<?php 
																		}
																	}
																} ?> 
															</p>
														</div>
														<?php
													} ?>
												</div>
											</div>
										<?php }else{
											if(isset($process)){
												if($process == 'choose'){
													$html = '<div class="row">';
													$html .= '<div class="col-md-9"><select name="approver" class="selectpicker" data-live-search="true" id="approver_c" data-width="100%" data-none-selected-text="'. _l('please_choose_approver').'" required> 
													<option value=""></option>'; 
													$current_user = get_staff_user_id();
													foreach($staffs as $staff){ 
														if($staff['staffid'] != $current_user){
															$html .= '<option value="'.$staff['staffid'].'">'.$staff['staff_identifi'].' - '.$staff['firstname'].' '.$staff['lastname'].'</option>';                  
														}
													}
													$html .= '</select></div>';
													$html .= '<div class="col-md-3"><a href="javascript:void(0)" onclick="choose_approver();" class="btn btn-success lead-top-btn lead-view">'._l('choose').'</a></div>';
													$html .= '</div>';
													echo html_entity_decode($html);
												}
											}
										} ?>
									</div>
								</div>


							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="btn-bottom-pusher"></div>
	</div>
</div>
<input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
<input type="hidden" name="select_approver_text" value="<?php echo _l('omni_please_choose_an_approver'); ?>">
<?php init_tail(); ?>
</body>
</html>

