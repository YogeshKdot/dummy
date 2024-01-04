<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s ">
					<div class="panel-body">
						<h4><?php echo _l($title); ?></h4>			
						<?php echo form_open( admin_url('loyalty/loyalty_rule_form'),array('id'=>'loyalty_rule-form')); ?>
						<?php echo form_hidden('id', (isset($loyalty_rule) ? $loyalty_rule->id : '')); ?>
						<div class="modal-body">
							<div class="row">
								<div class="horizontal-scrollable-tabs preview-tabs-top">
									<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
									<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
									<div class="horizontal-tabs">
										<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
											<li role="presentation" class="active">
												<a href="#general_infor" aria-controls="general_infor" role="tab" data-toggle="tab" aria-controls="general_infor">
													<span class="glyphicon glyphicon-align-justify"></span>&nbsp;<?php echo _l('loyalty_general_infor'); ?>
												</a>
											</li>
											<li role="presentation">
												<a href="#poin_calculation" aria-controls="poin_calculation" role="tab" data-toggle="tab" aria-controls="poin_calculation">
													<i class="fa fa-calendar"></i>&nbsp;<?php echo _l('poin_calculation'); ?>
												</a>
											</li>
											<li role="presentation">
												<a href="#redeemp_calculation" aria-controls="redeemp_calculation" role="tab" data-toggle="tab" aria-controls="redeemp_calculation">
													<i class="fa fa-address-card-o"></i>&nbsp;<?php echo _l('redeemp_calculation'); ?>
												</a>
											</li>

										</ul>
									</div>
								</div>

								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="general_infor">
										<div class="col-md-10">
											<label for="subject"><span class="text-danger">* </span><?php echo _l('name'); ?></label>
											<?php $subject = (isset($loyalty_rule) ? $loyalty_rule->subject : '');
											echo render_input('subject','',$subject,'text',array('required' => 'true')); ?>
										</div>

										<div class="col-md-2 padtop25">
											<div class="form-group">
												<div class="checkbox checkbox-primary">
													<input type="checkbox" id="enable" name="enable" <?php if(isset($loyalty_rule) && $loyalty_rule->enable == 1){ echo 'checked';} ?> value="1" >
													<label for="enable"><?php echo _l('loy_enable'); ?>
												</label>
											</div>
										</div>
									</div>

									<div class="col-md-6 form-group">
								        <label for="client_group"><?php echo _l('client_group'); ?></label>
								        <select name="client_group" id="client_group" onchange="client_group_change(this); return false;" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
								            <option value=""></option>
								            <?php foreach($client_groups as $gr){ ?>
								             <option value="<?php echo html_entity_decode($gr['id']); ?>" <?php if(isset($loyalty_rule) && $loyalty_rule->client_group == $gr['id']){ echo 'selected'; } ?> ><?php echo html_entity_decode($gr['name']); ?></option>
								           <?php } ?>
								        </select>
								    </div> 

								    <div class="col-md-6 form-group">
								    	<?php $clients_ed = (isset($loyalty_rule) ? explode(',',$loyalty_rule->client) : []); ?>
								        <label for="client"><?php echo _l('client'); ?></label>
								        <select name="client[]" id="client" class="selectpicker" multiple="true"  data-live-search="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
								          <?php foreach($clients as $cli){ ?>
								           <option value="<?php echo html_entity_decode($cli['userid']); ?>" <?php if(isset($loyalty_rule) && in_array($cli['userid'], $clients_ed)){ echo 'selected'; } ?> ><?php echo html_entity_decode($cli['company']); ?></option>
								         <?php } ?>
								       </select>
								    </div>

									<div class="col-md-6">
										<label for="start_date"><span class="text-danger">* </span><?php echo _l('start_date'); ?></label>
										<?php $start_date = (isset($loyalty_rule) ? _d($loyalty_rule->start_date) : '');
										echo render_date_input('start_date','',$start_date,array('required' => 'true')); ?>
									</div>

									<div class="col-md-6">
										<label for="end_date"><span class="text-danger">* </span><?php echo _l('end_date'); ?></label>
										<?php $end_date = (isset($loyalty_rule) ? _d($loyalty_rule->end_date) : '');
										echo render_date_input('end_date','',$end_date,array('required' => 'true')); ?>
									</div>
									<div class="col-md-12">
										<?php $note = (isset($loyalty_rule) ? $loyalty_rule->note : '');
										echo render_textarea('note','module_description',$note); ?>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane" id="poin_calculation">

									<div class="col-md-12 mtop5">
										<p class="bold p_style"><?php echo _l('loy_when_paying_invoice'); ?></p>
        								<hr class="hr_style"/>
									</div>

									<div class="col-md-6">
										<label for="rule_base"><span class="text-danger">* </span><?php echo _l('rule_base'); ?></label>
										<select name="rule_base" id="rule_base" onchange="rule_base_change(this); return false;" class="selectpicker" data-width="100%" required data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
											<option value=""></option>
											<option value="card_total" <?php if(isset($loyalty_rule) && $loyalty_rule->rule_base == 'card_total'){ echo 'selected'; } ?>><?php echo _l('card_total'); ?></option>
											<option value="product_category" <?php if(isset($loyalty_rule) && $loyalty_rule->rule_base == 'product_category'){ echo 'selected'; } ?>><?php echo _l('product_category'); ?></option>
											<option value="product"  <?php if(isset($loyalty_rule) && $loyalty_rule->rule_base == 'product'){ echo 'selected'; } ?>><?php echo _l('product_loy'); ?></option>
										</select> 
										<br>  
									</div>

									<div class="col-md-6">
										<?php $minium_purchase = (isset($loyalty_rule) ? $loyalty_rule->minium_purchase : ''); ?>
										<label for="minium_purchase"><span class="text-danger">* </span><?php echo _l('minium_purchase') ?></label>
										<div class="input-group" id="discount-total">
											<input type="text" class="form-control text-right" data-type="currency" required name="minium_purchase" value="<?php echo html_entity_decode($minium_purchase); ?>">
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

									<div id="product_category_rule_div" class="hide">
										<div class="col-md-12 new-kpi-al">
											<div class="col-md-7 padright0 padleft0">
												<label class="control-label get_id_row" value ="0" ><span class="text-danger">* </span><?php echo _l('product_category'); ?></label>
											</div>
											<div class="col-md-4 padright0">
												<label class="control-label"><span class="text-danger">* </span><?php echo _l('loyalty_point') ?></label>
											</div>
											<?php if(isset($loyalty_rule)) { ?>
												<?php if($loyalty_rule->rule_base == 'product_category' && count($loyalty_rule->rule_detail) > 0) { ?>
													<?php 
													foreach($loyalty_rule->rule_detail as $key => $rule_dt){ ?>
														<div id ="new_kpi" class="row padbot5" >
															<div class="col-md-7 padright0">
																<select name="product_category[<?php echo html_entity_decode($key); ?>]" class="selectpicker" data-live-search="true" id="product_category[<?php echo html_entity_decode($key); ?>]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
																	<option value=""></option>
																	<?php foreach ($item_groups as $jp){ ?>
																		<option value="<?php echo html_entity_decode($jp['id']); ?>" <?php if($jp['id'] == $rule_dt['rel_id']){ echo 'selected';} ?>><?php echo html_entity_decode($jp['name']); ?></option>
																	<?php } ?>
																</select>
															</div>

															<div class="col-md-4 padright0">

																<input type="number" id="point[<?php echo html_entity_decode($key); ?>]" name="point[<?php echo html_entity_decode($key); ?>]" class="form-control" value="<?php echo html_entity_decode($rule_dt['loyalty_point']) ?>" aria-invalid="false" >
															</div>
															<div class="col-md-1" name="button_add_kpi addbtn_style" >
																<button name="add" class="btn <?php if($key == 0){ echo 'new_kpi btn-success'; }else{ echo 'remove_kpi btn-danger';} ?> pull-right" data-ticket="true" type="button"><i class="fa fa-<?php if($key == 0){ echo 'plus'; }else{ echo 'remove';} ?>"></i></button>
															</div>
														</div>
														<?php 

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
																<input type="number" id="point[0]" name="point[0]" class="form-control" value="" aria-invalid="false" >
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
															<input type="number" id="point[0]" name="point[0]" class="form-control" value="" aria-invalid="false" >
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
													<label class="control-label"><span class="text-danger">* </span><?php echo _l('loyalty_point') ?></label>
												</div>

												<?php if(isset($loyalty_rule)) { ?>
													<?php if($loyalty_rule->rule_base == 'product' && count($loyalty_rule->rule_detail) > 0) { ?>
														<?php 
														foreach($loyalty_rule->rule_detail as $key1 => $rule_pd){ ?>	

															<div id ="new_rule" class="row padbot5">
																<div class="col-md-7 padright0" >
																	<select name="product[<?php echo html_entity_decode($key1); ?>]" class="selectpicker" data-live-search="true" id="product[<?php echo html_entity_decode($key1); ?>]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
																		<option value=""></option>
																		<?php foreach ($items as $jp){ ?>
																			<option value="<?php echo html_entity_decode($jp['itemid']); ?>" <?php if($jp['itemid'] == $rule_pd['rel_id']){ echo 'selected';} ?> ><?php echo html_entity_decode($jp['description']); ?></option>
																		<?php } ?>
																	</select>
																</div>

																<div class="col-md-4 padright0" >

																	<input type="number" id="point_product[<?php echo html_entity_decode($key1); ?>]" name="point_product[<?php echo html_entity_decode($key1); ?>]" class="form-control" value="<?php echo html_entity_decode($rule_pd['loyalty_point']); ?>" aria-invalid="false" >
																</div>
																<div class="col-md-1" name="button_add_kpi addbtn_style" >
																	<button name="add" class="btn <?php if($key1 == 0){ echo 'new_rule btn-success';}else{ echo 'remove_rule btn-danger'; } ?> pull-right" data-ticket="true" type="button"><i class="fa fa-<?php if($key1 == 0){ echo 'plus';}else{ echo 'remove'; } ?>"></i></button>
																</div>
															</div>

														<?php } ?> <?php }else{ ?>
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

																	<input type="number" id="point_product[0]" name="point_product[0]" class="form-control" value="" aria-invalid="false" >
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

																	<input type="number" id="point_product[0]" name="point_product[0]" class="form-control" value="" aria-invalid="false" >
																</div>
																<div class="col-md-1" name="button_add_kpi addbtn_style" >
																	<button name="add" class="btn new_rule btn-success pull-right" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
																</div>
															</div>
														<?php } ?>
													</div>
												</div>


												<div id="card_total_rule_div">
													<div class="col-md-6">
														<?php $poin_awarded = (isset($loyalty_rule) ? $loyalty_rule->poin_awarded : '');
														echo render_input('poin_awarded','poin_awarded_text_label',$poin_awarded,'number'); ?>
													</div>

													<div class="col-md-6">
														<?php $purchase_value = (isset($loyalty_rule) ? $loyalty_rule->purchase_value : ''); ?>
														<label for="purchase_value"><?php echo _l('purchase_value_text_label') ?></label>
														<div class="input-group" id="discount-total">
															<input type="text" class="form-control text-right" data-type="currency" name="purchase_value" value="<?php echo html_entity_decode($purchase_value); ?>">
															<div class="input-group-addon">
																<div class="dropdown">
																	<span class="discount-type-selected">
																		<?php echo html_entity_decode($base_currency->name) ;?>
																	</span>
																</div>
															</div>
														</div>
													</div>

												</div>

												<div class="col-md-12 mtop10">
													<p class="bold p_style"><?php echo _l('loy_other_actions'); ?></p>
			        								<hr class="hr_style"/>
												</div>

												<div class="col-md-6">
													<label for="create_account_point"><?php echo _l('create_account_point').' '; ?><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php echo _l('create_account_point_note'); ?>"></i></label>
													<?php $create_account_point = (isset($loyalty_rule) ? $loyalty_rule->create_account_point : '');
													echo render_input('create_account_point', '', $create_account_point, 'number'); ?>
												</div>

												<div class="col-md-6">
													<label for="birthday_point"><?php echo _l('birthday_point').' '; ?><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php echo _l('birthday_point_note'); ?>"></i></label>
													<?php $birthday_point = (isset($loyalty_rule) ? $loyalty_rule->birthday_point : '');
													echo render_input('birthday_point', '', $birthday_point, 'number'); ?>
												</div>

											</div>

											<div role="tabpanel" class="tab-pane" id="redeemp_calculation">
												<div class="col-md-2 padleft0 form-group">
													<label for="redeemp_type"><span class="text-danger">* </span><?php echo _l('redeemp_type'); ?></label>
													<select name="redeemp_type" id="redeemp_type" class="selectpicker" data-width="100%" required data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
														<option value=""></option>
														<option value="full" <?php if(isset($loyalty_rule) && $loyalty_rule->redeemp_type == 'full'){ echo 'selected'; } ?>><?php echo _l('full'); ?></option>
														<option value="partial" <?php if(isset($loyalty_rule) && $loyalty_rule->redeemp_type == 'partial'){ echo 'selected'; } ?>><?php echo _l('partial'); ?></option>
													</select>   
												</div>
												<div class="col-md-2">
													<?php $min_poin_to_redeem = (isset($loyalty_rule) ? $loyalty_rule->min_poin_to_redeem : 0);
													echo render_input('min_poin_to_redeem','min_poin_to_redeem',$min_poin_to_redeem,'number'); ?>
												</div>

												<div class="col-md-2">
													<?php $max_amount_received = (isset($loyalty_rule) ? $loyalty_rule->max_amount_received : 100); ?>
													<label for="max_amount_received"><span class="text-danger">* </span><?php echo _l('max_amount_received').' '; ?><i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo _l('tooltip_max_amount_received'); ?>" ></i></label>
													<div class="input-group" id="discount-total">
														<input type="number" class="form-control text-right" required name="max_amount_received" min="0" max="100" value="<?php echo html_entity_decode($max_amount_received); ?>">
														<div class="input-group-addon">
															<div class="dropdown">
																<span class="discount-type-selected">
																	<?php echo '%';?>
																</span>
															</div>
														</div>
													</div>
													<br>
												</div>

												<div class="col-md-3 padtop25">
													<div class="form-group">
														<div class="checkbox checkbox-primary">
															<input type="checkbox" id="redeem_portal" name="redeem_portal" <?php if(isset($loyalty_rule) && $loyalty_rule->redeem_portal == 1){ echo 'checked';} ?> value="1" >
															<label for="redeem_portal"><?php echo _l('redeem_in_portal'); ?>
														</label>
													</div>
												</div>
											</div>
												<div class="col-md-3 padtop25">
													<div class="form-group">
														<div class="checkbox checkbox-primary">
															<input type="checkbox" id="redeem_pos" name="redeem_pos" <?php if(isset($loyalty_rule) && $loyalty_rule->redeem_pos == 1){ echo 'checked';} ?> value="1" >
															<label for="redeem_pos"><?php echo _l('redeem_in_pos'); ?>
														</label>
													</div>
												</div>
											</div>
											<div class="col-md-12 new-redemp-calcu">
												<div class="row">
												<div class="col-md-3 padleft0">
													<label class="control-label get_id_row" value ="0" ><span class="text-danger">* </span><?php echo _l('rule_name'); ?></label>
												</div>
												<div class="col-md-2 padright0">
													<label class="control-label"><span class="text-danger">* </span><?php echo _l('point_from') ?></label>
												</div>
												<div class="col-md-2 padright0" >
													<label class="control-label"><span class="text-danger">* </span><?php echo _l('point_to') ?></label>
												</div>
												<div class="col-md-2 padright0" >
													<label class="control-label"><span class="text-danger">* </span><?php echo _l('point_weight') ?></label>
												</div>
												<div class="col-md-2 padright0">
													<label class="control-label"><span class="text-danger">* </span><?php echo _l('loy_status') ?></label>
												</div>
											    </div>

												<?php if(isset($loyalty_rule) && count($loyalty_rule->redemp_detail) > 0) { ?>

													<?php 
													foreach($loyalty_rule->redemp_detail as $key2 => $rd){ ?>
														<div id ="new_redemp" class="row padbot5">
															<div class="col-md-3 padleft0 padright0">
																<input type="text" id="rule_name[<?php echo html_entity_decode($key2); ?>]" name="rule_name[<?php echo html_entity_decode($key2); ?>]" class="form-control" value="<?php echo html_entity_decode($rd['rule_name']); ?>" aria-invalid="false" >
															</div>

															<div class="col-md-2 padright0">
																<input type="number" id="point_from[<?php echo html_entity_decode($key2); ?>]" name="point_from[<?php echo html_entity_decode($key2); ?>]" class="form-control" value="<?php echo html_entity_decode($rd['point_from']); ?>" aria-invalid="false" >
															</div>

															<div class="col-md-2 padright0" >
																<input type="number" id="point_to[<?php echo html_entity_decode($key2); ?>]" name="point_to[<?php echo html_entity_decode($key2); ?>]" class="form-control" value="<?php echo html_entity_decode($rd['point_to']); ?>" aria-invalid="false" >
															</div>

															<div class="col-md-2 padright0">
																<input type="number" id="point_weight[<?php echo html_entity_decode($key2); ?>]" name="point_weight[<?php echo html_entity_decode($key2); ?>]" class="form-control" value="<?php echo html_entity_decode($rd['point_weight']); ?>" step="0.01" >
															</div>

															<div class="col-md-2 padright0">
																<select name="status[<?php echo html_entity_decode($key2); ?>]" class="selectpicker" data-live-search="true" id="status[<?php echo html_entity_decode($key2); ?>]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
																	<option value=""></option>
																	<option value="enable" <?php if($rd['status'] == 'enable'){ echo 'selected'; } ?>><?php echo _l('loy_enable'); ?></option>
																	<option value="disable" <?php if($rd['status'] == 'disable'){ echo 'selected'; } ?> ><?php echo _l('loy_disable'); ?></option>
																</select>
															</div>

															<div class="col-md-1 addbtn_style" name="button_add_kpi" >
																<button name="add" class="btn <?php if($key2 == 0){ echo 'new_redemp btn-success';}else{ echo 'remove_redemp btn-danger'; } ?> pull-right"  data-ticket="true" type="button"><i class="fa fa-<?php if($key2 == 0){ echo 'plus';}else{ echo 'remove'; } ?>"></i></button>
															</div>
														</div>
													<?php  } ?>
												<?php }else{ ?>

													<div id ="new_redemp" class="row padbot5">
														<div class="col-md-3 padleft0 padright0">
															<input type="text" id="rule_name[0]" name="rule_name[0]" class="form-control" value="" aria-invalid="false" >
														</div>

														<div class="col-md-2 padright0">
															<input type="number" id="point_from[0]" name="point_from[0]" class="form-control" value="" aria-invalid="false" >
														</div>

														<div class="col-md-2 padright0">
															<input type="number" id="point_to[0]" name="point_to[0]" class="form-control" value="" aria-invalid="false" >
														</div>

														<div class="col-md-2 padright0">
															<input type="number" id="point_weight[0]" name="point_weight[0]" class="form-control" value="" step="0.01">
														</div>

														<div class="col-md-2 padright0">
															<select name="status[0]" class="selectpicker" data-live-search="true" id="status[0]" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" > 
																<option value=""></option>
																<option value="enable"><?php echo _l('loy_enable'); ?></option>
																<option value="disable"><?php echo _l('loy_disable'); ?></option>
															</select>
														</div>

														<div class="col-md-1 addbtn_style" name="button_add_kpi">
															<button name="add" class="btn new_redemp btn-success pull-right" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>

									</div>
									<div class="col-md-12 padleft0 padright0">
										<hr>
										<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
										<?php echo form_close(); ?>
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
