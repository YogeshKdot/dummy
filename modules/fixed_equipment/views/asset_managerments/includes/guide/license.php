						<?php 
						$file_header = array();
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_software_name');
						$file_header[] = _l('fe_product_key');
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_seats');
						$file_header[] = _l('fe_licensed_to_name');
						$file_header[] = _l('fe_licensed_to_email');
						$file_header[] = _l('fe_reassignable');
						$file_header[] = _l('fe_maintained');
						$file_header[] = _l('fe_order_number');
						$file_header[] = _l('fe_purchase_order_number');
						$file_header[] = _l('fe_purchase_cost');
						$file_header[] = _l('fe_purchase_date').' (mm/dd/YYYY)';
						$file_header[] = _l('fe_expiration_date');
						$file_header[] = _l('fe_termination_date');
						$file_header[] = _l('fe_purchase_date');
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_category_id');
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_manufacturer_id');
						$file_header[] = _l('fe_supplier_id');
						$file_header[] = _l('fe_depreciation_id');
						$file_header[] = _l('fe_notes');
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
								<?php echo '2. '._l('fe_in_field_reassignable_maintained_you_are_only_allowed_to_enter'); ?><br>
								<?php echo '3. '._l('fe_for_columns_like_category_id_manufacturer_id_supplier_id_depreciation_id'); ?><br>
							</p>
							<hr class="mtop10 mbot10">
							<p>
								<?php echo _l('fe_format_the_excel_file'); ?>
							</p>
						</div>