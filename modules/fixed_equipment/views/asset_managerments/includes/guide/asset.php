						<?php 
						$file_header = array();
						$file_header[] = _l('fe_asset_name');
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_asset_tag');
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_model_id');
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_status_id');
						$file_header[] = _l('fe_supplier_id');
						$file_header[] = _l('fe_location_id');
						$file_header[] = _l('fe_purchase_date').' (mm/dd/YYYY)';
						$file_header[] = _l('fe_purchase_cost');
						$file_header[] = _l('fe_order_number');
						$file_header[] = _l('fe_warranty');
						$file_header[] = _l('fe_requestable');
						$file_header[] = _l('fe_description');
						?>					
						<div class="table-responsive no-dt">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<?php
										$total_fields = 0;

										for($i=0;$i<count($file_header);$i++){
											?>
											<th class="bold">
												<?php echo html_entity_decode($file_header[$i]) ?> 
											</th>
											<?php
											$total_fields++;
										}

										?>

									</tr>
								</thead>
								<tbody>
									<?php for($i = 0; $i<1;$i++){
										echo '<tr>';
										for($x = 0; $x<count($file_header);$x++){
											echo '<td>- </td>';
										}
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="alert alert-warning">
							<h4 class="alert-heading"><?php echo _l('fe_notes'); ?></h4>
							<p>
								<?php echo '1. '._l('fe_fields_marked_with_are_required_to_enter'); ?><br>
								<?php echo '2. '._l('fe_if_column_asset_name_is_left_blank_the_asset_name_will_be_the_name_of_the_model'); ?><br>
								<?php echo '3. '._l('fe_for_columns_like_model_id_status_id_supplier_id_location_id'); ?><br>
								<?php echo '4. '._l('fe_in_field_requestable_you_are_only_allowed_to_enter'); ?><br>
								<?php echo '5. '._l('fe_in_field_warranty_you_can_only_enter_the_number'); ?><br>
							</p>
							<hr class="mtop10 mbot10">
							<p>
								<?php echo _l('fe_format_the_excel_file'); ?>
							</p>
						</div>