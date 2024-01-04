<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row panel_s">
			<div class="panel-body">
				<div class="col-md-12">
					<h4 class="heading">
						<?php echo html_entity_decode($title); ?>
					</h4>
					<hr>

					<div class="row">
						<div class="col-md-3">
							<?php 
							if(is_admin() || has_permission('fixed_equipment_components', '', 'create')){
								?>
								<button class="btn btn-primary mtop15" onclick="add();"><?php echo _l('add'); ?></button>
								<a class="btn btn-warning mleft10 mtop15" href="<?php echo admin_url('fixed_equipment/bulk_upload/component'); ?>"><?php echo _l('fe_bulk_upload'); ?></a>

							<?php } ?>
						</div>

						<div class="col-md-3">
						</div>

						<div class="col-md-3">
							<?php echo render_select('category_filter', $categories, array('id', 'category_name'), 'fe_categories'); ?>
						</div>

						<div class="col-md-3">
							<?php echo render_select('location_filter', $locations, array('id', 'location_name'), 'fe_location'); ?>
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
								<th><?php echo  _l('fe_location'); ?></th>
								<th><?php echo  _l('fe_order_number'); ?></th>
								<th><?php echo  _l('fe_purchase_date'); ?></th>
								<th><?php echo  _l('fe_purchase_cost'); ?></th>
								<?php 
								if(is_admin() || has_permission('fixed_equipment_components', '', 'create')){
									?>
									<th><?php echo  _l('fe_checkin_checkout'); ?></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

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
			<?php echo form_open_multipart(admin_url('fixed_equipment/components'),array('id'=>'components-form', 'onsubmit'=>'return validateForm()')); ?>
			<div class="modal-body">
				<?php $this->load->view('includes/new_components_modal'); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
			<?php echo form_close(); ?>                 
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="check_out" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="add-title"><?php echo _l('fe_checkout'); ?></span>
				</h4>
			</div>
			<?php echo form_open(admin_url('fixed_equipment/check_in_components'),array('id'=>'check_out_components-form')); ?>
			<div class="modal-body">
				<input type="hidden" name="id" value="">
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

<?php init_tail(); ?>
</body>
</html>
