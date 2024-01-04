<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-4">
				<div class="col-md-12 panel">
					<br>
					<h4>
						<?php echo html_entity_decode($title); ?>
					</h4>
					<hr>
					<div class="text-center">
						<img class="img img-rounded mtop10 img-thumbnail" src="<?php echo html_entity_decode($this->fixed_equipment_model->get_image_items($id, 'locations'));  ?>">          
					</div>    
					<?php 
					$location_name = '';
					$parent = '';
					$manager = '';
					$address = '';
					$city = '';
					$state = '';
					$zip = '';
					$country =  get_option('customer_default_country');

					$location_currency = '';
					$currency_attr = array('data-show-subtext'=>true);
					$currency_attr = apply_filters_deprecated('invoice_currency_disabled', [$currency_attr], '2.3.0', 'invoice_currency_attributes');

					foreach($currencies as $currency){
						if($currency['isdefault'] == 1){
							$currency_attr['data-base'] = $currency['id'];
						}
						if(isset($invoice)){
							if($currency['id'] == $invoice->currency){
								$location_currency = $currency['id'];
							}
						} else {
							if($currency['isdefault'] == 1){
								$location_currency = $currency['id'];
							}
						}
					}
					if(isset($location)){
						$id = $location->id;
						$location_name = $location->location_name;
						$parent = $location->parent;
						$manager = $location->manager;
						$address = $location->address;
						$city = $location->city;
						$state = $location->state;
						$country =  $location->country;
						$location_currency = $location->location_currency;
						$zip = $location->zip;
					}
					?>
					<table class="table">
						<tbody>
							<?php
							$full_address = '';
							if(is_numeric($parent) && $parent != 0){ ?>
								<tr>
									<td><strong><?php echo _l('fe_parent'); ?></strong></td>
									<td><?php
									$data_parent = $this->fixed_equipment_model->get_locations($parent);
									if($data_parent){
										$parent = $data_parent->location_name;
									}
									echo html_entity_decode($parent); 
									?></td>
								</tr>
							<?php } ?>
							<?php if(is_numeric($manager) && $manager != ''){ ?>
								<tr>
									<td><strong><?php echo _l('fe_manager'); ?></strong></td>
									<td><?php echo get_staff_full_name($manager); ?></td>
								</tr>
							<?php } ?>
							<?php if($address != ''){
								$full_address .= $address.', ';
								?>
								<tr>
									<td><strong><?php echo _l('fe_address'); ?></strong></td>
									<td><?php echo html_entity_decode($address); ?></td>
								</tr>
							<?php } ?>
							<?php if($state != ''){
								$full_address .= $state.', ';
								?>
								<tr>
									<td><strong><?php echo _l('fe_state'); ?></strong></td>
									<td><?php echo html_entity_decode($state); ?></td>
								</tr>
							<?php } ?>
							<?php if($city != ''){
								$full_address .= $city.', ';
								?>
								<tr>
									<td><strong><?php echo _l('fe_city'); ?></strong></td>
									<td><?php echo html_entity_decode($city); ?></td>
								</tr>
							<?php } ?>
							<?php if(is_numeric($country) && $country != ''){
								$country_name = get_country_name($country);
								$full_address .= $country_name.', ';
								?>
								<tr>
									<td><strong><?php echo _l('fe_country'); ?></strong></td>
									<td><?php echo html_entity_decode($country_name); ?></td>
								</tr>
							<?php } ?>
							<?php if($zip != ''){ ?>
								<tr>
									<td><strong><?php echo _l('fe_zip'); ?></strong></td>
									<td><?php echo html_entity_decode($zip); ?></td>
								</tr>
							<?php } ?>
							<?php if($location_currency != ''){ ?>
								<tr>
									<td><strong><?php echo _l('fe_location_currency'); ?></strong></td>
									<td><?php 
									$currency_name = '';
									$this->load->model('currencies_model');
									$data_currenrcy = $this->currencies_model->get($location_currency); 
									if($data_currenrcy){
										$currency_name = $data_currenrcy->name;
									}
									echo html_entity_decode($currency_name); 
									?></td>
								</tr>
							<?php } ?>
							<tbody>
							</table>

							<!-- Map -->
							<?php 
							if($full_address != ''){
								$full_address = rtrim($full_address,', ');
								$data_codinate = $this->fixed_equipment_model->get_coordinate($full_address);
								if($data_codinate != ''){ 
									$data_codinate = json_decode($data_codinate);
									$lat = $data_codinate->lat;
									$lng = $data_codinate->lng;
									if($lat != '' && $lng != ''){
										?>
										<input type="hidden" name="lat" value="<?php echo html_entity_decode($lat); ?>">
										<input type="hidden" name="lng" value="<?php echo html_entity_decode($lng); ?>">
									<?php } } } ?>
									<div id="map"></div>
									<!-- End Map -->

									<br>
								</div>
							</div>
							<div class="col-md-8">
								<div class="col-md-12 panel">
									<div class="row">
										<div class="col-md-12 ptop10">
											<h4 class="pull-left mtop15"><?php echo _l('fe_asset'); ?></h4>
											<?php 
											$back_link = admin_url('fixed_equipment/locations');
											if(isset($redirect) && $redirect != ''){
												$back_link = admin_url('fixed_equipment/'.$redirect);
											}

											?>
											<a href="<?php echo html_entity_decode($back_link); ?>" class="btn btn-default pull-right mtop6"><?php echo _l('fe_back'); ?></a>
											<br>
											<br>
											<hr>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3"></div>
										<div class="col-md-3">
											<?php echo render_select('model_filter', $models, array('id', 'model_name'), 'fe_model'); ?>
										</div>

										<div class="col-md-3">
											<?php echo render_select('status_filter', $status_labels, array('id', 'name'), 'fe_status'); ?>
										</div>

										<div class="col-md-3">
											<?php echo render_select('supplier_filter', $suppliers, array('id', 'supplier_name'), 'fe_supplier'); ?>
										</div>
									</div>
									<table class="table table-assets_management scroll-responsive">
										<thead>
											<tr>
												<th>ID</th>
												<th><?php echo  _l('fe_asset_name'); ?></th>
												<th><?php echo  _l('fe_image'); ?></th>
												<th><?php echo  _l('fe_serial'); ?></th>
												<th><?php echo  _l('fe_model'); ?></th>
												<th><?php echo  _l('fe_model_no'); ?></th>
												<th><?php echo  _l('fe_category'); ?></th>
												<th><?php echo  _l('fe_status'); ?></th>
												<th><?php echo  _l('fe_checkout_to'); ?></th>
												<th><?php echo  _l('fe_location'); ?></th>
												<th><?php echo  _l('fe_default_location'); ?></th>
												<th><?php echo  _l('fe_manufacturer'); ?></th>
												<th><?php echo  _l('fe_supplier'); ?></th>
												<th><?php echo  _l('fe_purchase_date'); ?></th>
												<th><?php echo  _l('fe_purchase_cost'); ?></th>
												<th><?php echo  _l('fe_order_number'); ?></th>
												<th><?php echo  _l('fe_warranty'); ?></th>
												<th><?php echo  _l('fe_warranty_expires'); ?></th>
												<th><?php echo  _l('fe_notes'); ?></th>
												<th><?php echo  _l('fe_checkouts'); ?></th>
												<th><?php echo  _l('fe_checkins'); ?></th>
												<th><?php echo  _l('fe_requests'); ?></th>
												<th><?php echo  _l('fe_created_at'); ?></th>
												<th><?php echo  _l('fe_updated_at'); ?></th>
												<th><?php echo  _l('fe_checkout_date'); ?></th>
												<th><?php echo  _l('fe_expected_checkin_date'); ?></th>
												<th><?php echo  _l('fe_last_audit'); ?></th>
												<th><?php echo  _l('fe_next_audit_date'); ?></th>
												<?php 
												if(is_admin() || has_permission('fixed_equipment_assets', '', 'view')){
													?>
													<th><?php echo  _l('fe_checkin_checkout'); ?></th>
												<?php } ?>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<!-- Accessories -->
								<div class="col-md-12 panel">
									<br>
									<h4><?php echo _l('fe_accessories'); ?></h4>
									<hr>
									<div class="row">
										<div class="col-md-3"></div>
										<div class="col-md-3"></div>
										<div class="col-md-3">
											<?php echo render_select('manufacturer_filter', $manufacturers, array('id', 'name'), 'fe_manufacturer'); ?>
										</div>

										<div class="col-md-3">
											<?php echo render_select('category_filter', $accessories_categories, array('id', 'category_name'), 'fe_categories'); ?>
										</div>
									</div>
									<table class="table table-accessories scroll-responsive">
										<thead>
											<tr>
												<th>ID</th>
												<th><?php echo  _l('fe_image'); ?></th>
												<th><?php echo  _l('fe_name'); ?></th>
												<th><?php echo  _l('fe_category'); ?></th>
												<th><?php echo  _l('fe_model_no'); ?></th>
												<th><?php echo  _l('fe_manufacturer'); ?></th>
												<th><?php echo  _l('fe_total'); ?></th>
												<th><?php echo  _l('fe_min_quantity'); ?></th>
												<th><?php echo  _l('fe_avail'); ?></th>
												<th><?php echo  _l('fe_purchase_cost'); ?></th>
												<?php 
												if(is_admin() || has_permission('fixed_equipment_accessories', '', 'view')){
													?>
													<th><?php echo  _l('fe_checkin_checkout'); ?></th>
												<?php } ?>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<!-- End accessories -->

								<!-- Consumable -->
								<div class="col-md-12 panel">
									<br>
									<h4><?php echo _l('fe_consumable'); ?></h4>
									<hr>
									<div class="row">
										<div class="col-md-3"></div>
										<div class="col-md-3"></div>
										<div class="col-md-3">
											<?php echo render_select('consumable_manufacturer_filter', $manufacturers, array('id', 'name'), 'fe_manufacturer'); ?>
										</div>
										<div class="col-md-3">
											<?php echo render_select('consumable_category_filter', $consumable_categories, array('id', 'category_name'), 'fe_categories'); ?>
										</div>
									</div>
									<table class="table table-consumables scroll-responsive">
										<thead>
											<tr>
												<th>ID</th>
												<th><?php echo  _l('fe_image'); ?></th>
												<th><?php echo  _l('fe_name'); ?></th>
												<th><?php echo  _l('fe_category'); ?></th>
												<th><?php echo  _l('fe_model_no'); ?></th>
												<th><?php echo  _l('fe_manufacturer'); ?></th>
												<th><?php echo  _l('fe_total'); ?></th>
												<th><?php echo  _l('fe_min_quantity'); ?></th>
												<th><?php echo  _l('fe_avail'); ?></th>
												<th><?php echo  _l('fe_purchase_cost'); ?></th>
												<?php 
												if(is_admin() || has_permission('fixed_equipment_consumables', '', 'view')){
													?>
													<th><?php echo  _l('fe_checkin_checkout'); ?></th>
												<?php } ?>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<!-- End consumable -->
								<!-- Component -->
								<div class="col-md-12 panel">
									<br>
									<h4><?php echo _l('fe_component'); ?></h4>
									<hr>
									<div class="row">
										<div class="col-md-3">
										</div>

										<div class="col-md-3">
										</div>

										<div class="col-md-3">
										</div>

										<div class="col-md-3">
											<?php echo render_select('component_category_filter', $component_categories, array('id', 'category_name'), 'fe_categories'); ?>
										</div>
									</div>
									<table class="table table-components scroll-responsive">
										<thead>
											<tr>
												<th>ID</th>
												<th><?php echo  _l('fe_name'); ?></th>
												<th><?php echo  _l('fe_serial'); ?></th>
												<th><?php echo  _l('fe_category'); ?></th>
												<th><?php echo  _l('fe_total'); ?></th>
												<th><?php echo  _l('fe_remaining'); ?></th>
												<th><?php echo  _l('fe_min_quantity'); ?></th>
												<th><?php echo  _l('fe_order_number'); ?></th>
												<th><?php echo  _l('fe_purchase_date'); ?></th>
												<th><?php echo  _l('fe_purchase_cost'); ?></th>
												<?php 
												if(is_admin() || has_permission('fixed_equipment_components', '', 'view')){
													?>
													<th><?php echo  _l('fe_checkin_checkout'); ?></th>
												<?php } ?>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<!-- End component -->

							</div>

						</div>
					</div>
				</div>

				<!-- CheckIn checkOut asset -->
				<div class="modal fade" id="add_new_assets" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="edit-title hide"><?php echo _l('fe_edit_asset'); ?></span>
									<span class="add-title"><?php echo _l('fe_add_asset'); ?></span>
								</h4>
							</div>
							<?php echo form_open_multipart(admin_url('fixed_equipment/update_asset_location'),array('id'=>'assets-form', 'onsubmit'=>'return validateForm()')); ?>
							<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<div class="modal fade" id="check_in_asset" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="add-title"></span>
								</h4>
							</div>
							<?php echo form_open(admin_url('fixed_equipment/check_in_assets_location'),array('id'=>'check_in_assets-form')); ?>
							<div class="modal-body">
								<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
								<input type="hidden" name="item_id" value="">
								<input type="hidden" name="type" value="checkin">
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('model', 'fe_model', '', 'text', array('readonly' => true)); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('asset_name','fe_asset_name'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_select('status', $status_labels, array('id', 'name'), 'fe_status'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_select('location_id', $locations, array('id', 'location_name'), 'fe_locations'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_date_input('checkin_date','fe_checkin_date'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea('notes','fe_notes'); ?>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('fe_checkin'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

				<div class="modal fade" id="check_out_asset" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="add-title"></span>
								</h4>
							</div>
							<?php echo form_open(admin_url('fixed_equipment/check_in_assets_location'),array('id'=>'check_out_assets-form')); ?>
							<div class="modal-body">
								<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
								<input type="hidden" name="item_id" value="">
								<input type="hidden" name="type" value="checkout">
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('model', 'fe_model', '', 'text', array('readonly' => true)); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('asset_name','fe_asset_name'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_select('status', $status_label_checkout, array('id', 'name'), 'fe_status'); ?>
									</div>
								</div>

								<div class="row mbot15">
									<div class="col-md-12">
										<label for="location" class="control-label"><?php echo _l('fe_checkout_to'); ?></label>          
									</div>
									<div class="col-md-12">

										<div class="pull-left">
											<div class="checkbox">
												<input type="radio" name="checkout_to" id="checkout_to_user" value="user" checked>
												<label for="checkout_to_user"><?php echo _l('fe_staffs'); ?></label>
											</div>    
										</div>
										<div class="pull-left">
											<div class="checkbox">
												<input type="radio" name="checkout_to" id="checkout_to_asset" value="asset">
												<label for="checkout_to_asset"><?php echo _l('fe_asset'); ?></label>
											</div>  
										</div>
										<div class="pull-left">
											<div class="checkbox">
												<input type="radio" name="checkout_to" id="checkout_to_location" value="location">
												<label for="checkout_to_location"><?php echo _l('fe_location'); ?></label>
											</div> 
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 checkout_to_fr checkout_to_location_fr hide">
										<?php echo render_select('location_id', $locations, array('id', 'location_name'), 'fe_locations'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 checkout_to_fr checkout_to_asset_fr hide">
										<?php echo render_select('asset_id', $assets, array('id',array('series', 'assets_name')), 'fe_assets'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 checkout_to_fr checkout_to_staff_fr">
										<?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_staffs'); ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<?php echo render_date_input('checkin_date','fe_checkout_date'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_date_input('expected_checkin_date','fe_expected_checkin_date'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea('notes','fe_notes'); ?>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('fe_checkout'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- End CheckIn checkOut asset -->


				<!-- Accessories -->
				<div class="modal fade" id="add_new_accessories" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="edit-title hide"><?php echo _l('fe_edit_accessories'); ?></span>
									<span class="add-title"><?php echo _l('fe_add_accessories'); ?></span>
								</h4>
							</div>
							<?php echo form_open_multipart(admin_url('fixed_equipment/update_accessories_location'),array('id'=>'accessories-form', 'onsubmit'=>'return validateForm()')); ?>
							<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<div class="modal fade" id="check_out_accessory" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="add-title"><?php echo _l('fe_checkout'); ?></span>
								</h4>
							</div>
							<?php echo form_open(admin_url('fixed_equipment/check_in_accessories_location'),array('id'=>'check_out_accessories-form')); ?>
							<div class="modal-body">
								<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
								<input type="hidden" name="item_id" value="">
								<input type="hidden" name="type" value="checkout">
								<input type="hidden" name="status" value="2">	
								<input type="hidden" name="checkout_to" value="user">
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('asset_name','fe_accessory_name', '', 'text', array('readonly' => true)); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_staffs'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea('notes','fe_notes'); ?>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('fe_checkout'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- End accessories -->

				<!-- Consumable -->
				<div class="modal fade" id="add_new_consumables" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="edit-title hide"><?php echo _l('fe_edit_consumables'); ?></span>
									<span class="add-title"><?php echo _l('fe_add_consumables'); ?></span>
								</h4>
							</div>
							<?php echo form_open_multipart(admin_url('fixed_equipment/update_consumables_location'),array('id'=>'consumables-form', 'onsubmit'=>'return validateForm()')); ?>
							<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<div class="modal fade" id="check_out_consumable" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="add-title"><?php echo _l('fe_checkout'); ?></span>
								</h4>
							</div>
							<?php echo form_open(admin_url('fixed_equipment/check_in_consumables_location'),array('id'=>'check_out_consumables-form')); ?>
							<div class="modal-body">
								<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
								<input type="hidden" name="item_id" value="">
								<input type="hidden" name="type" value="checkout">
								<input type="hidden" name="status" value="2">	
								<input type="hidden" name="checkout_to" value="user">
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('asset_name','fe_accessory_name', '', 'text', array('readonly' => true)); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_staffs'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea('notes','fe_notes'); ?>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('fe_checkout'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- End Consumable -->

				<!-- Component -->
				<div class="modal fade" id="add_new_components" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="edit-title hide"><?php echo _l('fe_edit_component'); ?></span>
									<span class="add-title"><?php echo _l('fe_add_component'); ?></span>
								</h4>
							</div>
							<?php echo form_open_multipart(admin_url('fixed_equipment/update_components_location'),array('id'=>'components-form', 'onsubmit'=>'return validateForm()')); ?>
							<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<div class="modal fade" id="check_out_component" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<span class="add-title"><?php echo _l('fe_checkout'); ?></span>
								</h4>
							</div>
							<?php echo form_open(admin_url('fixed_equipment/check_in_components_location'),array('id'=>'check_out_components-form')); ?>
							<div class="modal-body">
								<input type="hidden" name="location" value="<?php echo html_entity_decode($id); ?>">
								<input type="hidden" name="item_id" value="">
								<input type="hidden" name="type" value="checkout">
								<input type="hidden" name="status" value="2">	
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('asset_name','fe_software_name', '', 'text', array('readonly' => true)); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_select('asset_id', $assets, array('id',array('series', 'assets_name')), 'fe_assets'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_input('quantity','fe_quantity', '1', 'number', array('min' => 1)); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<?php echo render_textarea('notes','fe_notes'); ?>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
								<button type="submit" class="btn btn-info"><?php echo _l('fe_checkout'); ?></button>
							</div>
							<?php echo form_close(); ?>                 
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- End Component -->





				<input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
				<?php init_tail(); ?>
			</body>
			</html>
