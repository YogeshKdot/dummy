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
							if(is_admin() || has_permission('fixed_equipment_consumables', '', 'create')){
								?>
								<button class="btn btn-primary" onclick="add();"><?php echo _l('add'); ?></button>
								<a class="btn btn-warning mleft10" href="<?php echo admin_url('fixed_equipment/bulk_upload/consumable'); ?>"><?php echo _l('fe_bulk_upload'); ?></a>
							<?php } ?>
						</div>

						<div class="col-md-3">
							<?php echo render_select('manufacturer_filter', $manufacturers, array('id', 'name'), 'fe_manufacturer'); ?>
						</div>

						<div class="col-md-3">
							<?php echo render_select('category_filter', $categories, array('id', 'category_name'), 'fe_categories'); ?>
						</div>

						<div class="col-md-3">
							<?php echo render_select('location_filter', $locations, array('id', 'location_name'), 'fe_location'); ?>
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
								<th><?php echo  _l('fe_location'); ?></th>
								<th><?php echo  _l('fe_total'); ?></th>
								<th><?php echo  _l('fe_min_quantity'); ?></th>
								<th><?php echo  _l('fe_avail'); ?></th>
								<th><?php echo  _l('fe_purchase_cost'); ?></th>
								<?php 
								if(is_admin() || has_permission('fixed_equipment_consumables', '', 'create')){
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
			<?php echo form_open_multipart(admin_url('fixed_equipment/consumables'),array('id'=>'consumables-form', 'onsubmit'=>'return validateForm()')); ?>
			<div class="modal-body">
				<?php $this->load->view('includes/new_consumables_modal'); ?>
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
			<?php echo form_open(admin_url('fixed_equipment/check_in_consumables'),array('id'=>'check_out_consumables-form')); ?>
			<div class="modal-body">
				<input type="hidden" name="id" value="">
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

<?php init_tail(); ?>
</body>
</html>
