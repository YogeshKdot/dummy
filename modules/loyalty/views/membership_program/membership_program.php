<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="panel_s mbot10">
				<div class="panel-body">
					<div class="col-md-12">
						<h4><?php echo html_entity_decode($title); ?></h4>
					</div>

					
					<?php echo form_open(admin_url('loyalty/membership_program_form'),array('id'=>'membership_program-form')); ?>
					<div class="col-md-12">
						<div class="horizontal-scrollable-tabs preview-tabs-top">
							<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
							<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
							<div class="horizontal-tabs">
								<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
									<li role="presentation" class="active">
										<a href="#program_infor" aria-controls="program_infor" role="tab" data-toggle="tab" aria-controls="program_infor">
											<?php echo _l('program_infor'); ?>
										</a>
									</li>
									<li role="presentation">
										<a href="#voucher_infor" aria-controls="voucher_infor" role="tab" data-toggle="tab" aria-controls="voucher_infor">
											<?php echo _l('voucher_infor'); ?>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="program_infor">	
							<div class="col-md-12">
								<?php echo  form_hidden('id', (isset($mbs_program) ? $mbs_program->id : '') ); ?>    
								<?php $program_name = (isset($mbs_program) ? $mbs_program->program_name : ''); 
								echo render_input('program_name','program_name',$program_name,'text'); ?>
							</div>

							<div class="col-md-12 form-group">
								<label for="discount"><?php echo _l('scope_of_application'); ?></label>
								<select name="discount" id="discount" class="selectpicker" onchange="discount_change(this); return false;"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
									<option value=""></option>
									<option value="card_total" <?php if(isset($mbs_program) && $mbs_program->discount == 'card_total'){ echo 'selected';} ?> ><?php echo _l('card_total'); ?></option>
									<option value="product_category"  <?php if(isset($mbs_program) && $mbs_program->discount == 'product_category'){ echo 'selected';} ?> ><?php echo _l('product_category'); ?></option>
									<option value="product" <?php if(isset($mbs_program) && $mbs_program->discount == 'product'){ echo 'selected';} ?> ><?php echo _l('product_loy'); ?></option>
								</select>
								<br>
							</div>

							<div id="product_category_rule_div" class="hide">
								<div class="col-md-12 new-kpi-al">
									<div class="col-md-7 padright0 padleft0">
										<label class="control-label get_id_row" value ="0" ><span class="text-danger">* </span><?php echo _l('product_category'); ?></label>
									</div>
									<div class="col-md-4 padright0">
										<label class="control-label"><span class="text-danger">* </span><?php echo _l('discount_percent') ?></label>
									</div>
									<?php if(isset($mbs_program)) { ?>
										<?php if($mbs_program->discount == 'product_category' && count($mbs_program->discount_detail) > 0) { ?>
											<?php $count = 0;
											foreach($mbs_program->discount_detail as $rule_dt){ ?>
												<div id ="new_kpi" class="row padbot5" >
													<div class="col-md-7 padright0">
														<select name="product_category[<?php echo html_entity_decode($count); ?>]" class="selectpicker" data-live-search="true" id="product_category[<?php echo html_entity_decode($count); ?>]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
															<option value=""></option>
															<?php foreach ($item_groups as $jp){ ?>
																<option value="<?php echo html_entity_decode($jp['id']); ?>" <?php if($jp['id'] == $rule_dt['rel_id']){ echo 'selected';} ?>><?php echo html_entity_decode($jp['name']); ?></option>
															<?php } ?>
														</select>
													</div>

													<div class="col-md-4 padright0">

														<input type="number" id="percent_cate[<?php echo html_entity_decode($count); ?>]" name="percent_cate[<?php echo html_entity_decode($count); ?>]" class="form-control" value="<?php echo html_entity_decode($rule_dt['percent']) ?>" aria-invalid="false" >
													</div>
													<div class="col-md-1" name="button_add_kpi addbtn_style" >
														<button name="add" class="btn <?php if($count == 0){ echo 'new_kpi btn-success'; }else{ echo 'remove_kpi btn-danger';} ?> pull-right" data-ticket="true" type="button"><i class="fa fa-<?php if($count == 0){ echo 'plus'; }else{ echo 'remove';} ?>"></i></button>
													</div>
												</div>
												<?php 
												$count++;
											} }else{
												?>
												<div id ="new_kpi" class="row padbot5">
													<div class="col-md-7 padright0">
														<select name="product_category[0]" class="selectpicker" data-live-search="true" id="product_category[0]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
															<option value=""></option>
															<?php foreach ($item_groups as $jp){ ?>
																<option value="<?php echo html_entity_decode($jp['id']); ?>"><?php echo html_entity_decode($jp['name']); ?></option>
															<?php } ?>
														</select>
													</div>

													<div class="col-md-4 padright0" >
														<input type="number" id="percent_cate[0]" name="percent_cate[0]" class="form-control" value="" aria-invalid="false" >
													</div>
													<div class="col-md-1" name="button_add_kpi addbtn_style" >
														<button name="add" class="btn new_kpi btn-success pull-right" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
													</div>
												</div>
											<?php } ?>
										<?php }else{ ?>

											<div id ="new_kpi" class="row padbot5">
												<div class="col-md-7 padright0">
													<select name="product_category[0]" class="selectpicker" data-live-search="true" id="product_category[0]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
														<option value=""></option>
														<?php foreach ($item_groups as $jp){ ?>
															<option value="<?php echo html_entity_decode($jp['id']); ?>"><?php echo html_entity_decode($jp['name']); ?></option>
														<?php } ?>
													</select>
												</div>

												<div class="col-md-4 padright0">
													<input type="number" id="percent_cate[0]" name="percent_cate[0]" class="form-control" value="" aria-invalid="false" >
												</div>
												<div class="col-md-1" name="button_add_kpi addbtn_style">
													<button name="add" class="btn new_kpi btn-success pull-right"  data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
												</div>
											</div>

										<?php } ?>

									</div>
								</div>

								<div id="product_rule_div" class="hide">
									<div class="col-md-12 new-product-rule">
										<div class="col-md-7 padleft0 padright0">
											<label class="control-label get_id_row" value ="0" ><span class="text-danger">* </span><?php echo _l('product_loy'); ?></label>
										</div>
										<div class="col-md-4 padright0" >
											<label class="control-label"><span class="text-danger">* </span><?php echo _l('discount_percent') ?></label>
										</div>

										<?php if(isset($mbs_program)) { ?>
											<?php if($mbs_program->discount == 'product' && count($mbs_program->discount_detail) > 0) { ?>
												<?php $count1 = 0;
												foreach($mbs_program->discount_detail as $rule_pd){ ?>	

													<div id ="new_rule" class="row padbot5">
														<div class="col-md-7 padright0" >
															<select name="product[<?php echo html_entity_decode($count1); ?>]" class="selectpicker" data-live-search="true" id="product[<?php echo html_entity_decode($count1); ?>]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
																<option value=""></option>
																<?php foreach ($items as $jp){ ?>
																	<option value="<?php echo html_entity_decode($jp['itemid']); ?>" <?php if($jp['itemid'] == $rule_pd['rel_id']){ echo 'selected';} ?> ><?php echo html_entity_decode($jp['description']); ?></option>
																<?php } ?>
															</select>
														</div>

														<div class="col-md-4 padright0" >

															<input type="number" id="percent_product[<?php echo html_entity_decode($count1); ?>]" name="percent_product[<?php echo html_entity_decode($count1); ?>]" class="form-control" value="<?php echo html_entity_decode($rule_pd['percent']); ?>" aria-invalid="false" >
														</div>
														<div class="col-md-1" name="button_add_kpi addbtn_style" >
															<button name="add" class="btn <?php if($count1 == 0){ echo 'new_rule btn-success';}else{ echo 'remove_rule btn-danger'; } ?> pull-right" data-ticket="true" type="button"><i class="fa fa-<?php if($count1 == 0){ echo 'plus';}else{ echo 'remove'; } ?>"></i></button>
														</div>
													</div>

													<?php $count1++; } ?> <?php }else{ ?>
														<div id ="new_rule" class="row padbot5" >
															<div class="col-md-7 padright0">
																<select name="product[0]" class="selectpicker" data-live-search="true" id="product[0]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
																	<option value=""></option>
																	<?php foreach ($items as $jp){ ?>
																		<option value="<?php echo html_entity_decode($jp['itemid']); ?>"><?php echo html_entity_decode($jp['description']); ?></option>
																	<?php } ?>
																</select>
															</div>

															<div class="col-md-4 padright0" >

																<input type="number" id="percent_product[0]" name="percent_product[0]" class="form-control" value="" aria-invalid="false" >
															</div>
															<div class="col-md-1" name="button_add_kpi addbtn_style" >
																<button name="add" class="btn new_rule btn-success pull-right" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
															</div>
														</div>
													<?php } }else{ ?>

														<div id ="new_rule" class="row padbot5" >
															<div class="col-md-7 padright0" >
																<select name="product[0]" class="selectpicker" data-live-search="true" id="product[0]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
																	<option value=""></option>
																	<?php foreach ($items as $jp){ ?>
																		<option value="<?php echo html_entity_decode($jp['itemid']); ?>"><?php echo html_entity_decode($jp['description']); ?></option>
																	<?php } ?>
																</select>
															</div>

															<div class="col-md-4 padright0" >

																<input type="number" id="percent_product[0]" name="percent_product[0]" class="form-control" value="" aria-invalid="false" >
															</div>
															<div class="col-md-1" name="button_add_kpi addbtn_style" >
																<button name="add" class="btn new_rule btn-success pull-right" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
															</div>
														</div>
													<?php } ?>
												</div>
											</div>


											<div id="card_total_rule_div">
												<div class="col-md-12">
													<?php $discount_percent = (isset($mbs_program) ? $mbs_program->discount_percent : '');
													echo render_input('discount_percent','discount_percent',$discount_percent,'number'); ?>
												</div>



											</div>

											<div class="col-md-12 form-group">
												<br>
												<?php $membership = (isset($mbs_program) ? explode(',', $mbs_program->membership) : ''); ?>

												<label for="membership"><?php echo _l('membership_rule'); ?></label>
												<select name="membership[]" id="membership" class="selectpicker" data-live-search="true" multiple="" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
													<?php foreach($memberships as $mem){ ?>
														<option value="<?php echo html_entity_decode($mem['id']); ?>" <?php if(isset($mbs_program) && in_array($mem['id'], $membership)){ echo 'selected'; } ?> ><?php echo html_entity_decode($mem['name']); ?></option>
													<?php } ?>
												</select>
												<br>
											</div>
											<div class="col-md-6">

												<?php $loyalty_point_from = (isset($mbs_program) ? $mbs_program->loyalty_point_from : '');
												echo render_input('loyalty_point_from', 'point_from', $loyalty_point_from, 'number'); ?>
											</div> 
											<div class="col-md-6">

												<?php $loyalty_point_to = (isset($mbs_program) ? $mbs_program->loyalty_point_to : '');
												echo render_input('loyalty_point_to','point_to',$loyalty_point_to,'number'); ?>
											</div> 

											<div class="col-md-6">
												<?php $start_date = (isset($mbs_program) ? _d($mbs_program->start_date) : '');
												echo render_date_input('start_date','start_date',$start_date); ?>
											</div>
											<div class="col-md-6">
												<?php $end_date = (isset($mbs_program) ? _d($mbs_program->end_date) : '');
												echo render_date_input('end_date','end_date',$end_date); ?>
											</div>

											<div class="col-md-12">
												<?php $note = (isset($mbs_program) ? $mbs_program->note : '');
												echo render_textarea('note','note',$note) ?>
											</div>

										</div>
										<div role="tabpanel" class="tab-pane" id="voucher_infor">
											<div class="col-md-6">
												<?php $voucher_code = (isset($mbs_program) ? $mbs_program->voucher_code : ''); 
												echo render_input('voucher_code','voucher_code',$voucher_code,'text'); ?>
											</div>

											<div class="col-md-6">
												<?php $voucher_value = (isset($mbs_program) ? $mbs_program->voucher_value : ''); 
												echo render_input('voucher_value','voucher_value',$voucher_value,'text', array('data-type' => 'currency')); ?>
											</div>

											<div class="col-md-6 form-group">
									            <label class="control-label" for="type"><?php echo _l('type'); ?></label>
									            <select class="selectpicker display-block" data-width="100%" name="formal" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
									            	<option value=""></option>
									                <option value="1" <?php if(isset($mbs_program) && $mbs_program->formal == 1){ echo "selected"; } if(!isset($mbs_program)){ echo "selected"; } ?>><?php echo _l('loy_coupon').' ('._l('loy_reduced_by_%').')'; ?></option>
									                <option value="2" <?php if(isset($mbs_program) && $mbs_program->formal == 2){ echo "selected"; } ?> ><?php echo _l('loy_voucher').' ('._l('loy_reduced_by_amount').')'; ?></option>
									            </select>
											</div>

											<div class="col-md-6">
												<?php $minium_purchase = (isset($mbs_program) ? $mbs_program->minium_purchase : ''); ?>
												<label for="minium_purchase"><?php echo _l('minium_purchase') ?></label>
												<div class="input-group" id="discount-total">
													<input type="text" class="form-control text-right" data-type="currency" name="minium_purchase" value="<?php echo html_entity_decode($minium_purchase); ?>">
													<div class="input-group-addon">
														<div class="dropdown">
															<span class="discount-type-selected">
																<?php echo html_entity_decode($base_currency->name) ;?>
															</span>
														</div>
													</div>
												</div>
												<br>
											</div>										
											
										</div>
									</div>
									<div class="col-md-12">
										<button id="sm_btn" type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
									</div>


									<?php echo form_close(); ?>
								</div>

							</div>
						</div>
					</div>
				</div>

				<?php init_tail(); ?>
			</body>
			</html>
			