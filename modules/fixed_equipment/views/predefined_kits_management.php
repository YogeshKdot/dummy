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
					<?php 
					if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'create')){
						?>
						<button class="btn btn-primary" onclick="add();"><?php echo _l('add'); ?></button>
						<div class="clearfix"></div>
						<br>
						<div class="clearfix"></div>
					<?php } ?>
					<table class="table table-predefined_kits scroll-responsive">
						<thead>
							<tr>
								<th>ID</th>
								<th><?php echo  _l('fe_name'); ?></th>
								<?php 
								if(is_admin() || has_permission('fixed_equipment_predefined_kits', '', 'create')){
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
<div class="modal fade" id="add_new_predefined_kits" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="edit-title hide"><?php echo _l('fe_edit_predefined_kit'); ?></span>
					<span class="add-title"><?php echo _l('fe_add_predefined_kit'); ?></span>
				</h4>
			</div>
			<?php echo form_open_multipart(admin_url('fixed_equipment/predefined_kits'),array('id'=>'predefined_kits-form', 'onsubmit'=>'return validateForm()')); ?>
			<div class="modal-body">
				<input type="hidden" value="predefined_kit" name="type">
				<input type="hidden" name="id" value="">
				<div class="row">
					<div class="col-md-12">
						<?php echo render_input('assets_name', 'fe_name') ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
			<?php echo form_close(); ?>                 
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="check_in" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="add-title"><?php echo _l('fe_checkin'); ?></span>
				</h4>
			</div>
			<?php echo form_open(admin_url('fixed_equipment/check_in_predefined_kits'),array('id'=>'check_in_assets-form')); ?>
			<div class="modal-body">
				<input type="hidden" name="id" value="">
				<input type="hidden" name="item_id" value="">
				<input type="hidden" name="type" value="checkin">
				<div class="row">
					<div class="col-md-12">
						<?php echo render_input('asset_name','fe_software_name', '', 'text', array('readonly' => true)); ?>
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

<div class="modal fade" id="check_out" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="add-title"><?php echo _l('fe_checkout'); ?></span>
				</h4>
			</div>
			<?php echo form_open(admin_url('fixed_equipment/check_in_predefined_kits'),array('id'=>'check_out_predefined_kits-form')); ?>
			<div class="modal-body">
				<input type="hidden" name="id" value="">
				<input type="hidden" name="item_id" value="">
				<input type="hidden" name="type" value="checkout">
				<div class="row">
					<div class="col-md-12">
						<?php echo render_input('asset_name','fe_software_name', '', 'text', array('readonly' => true)); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'fe_staffs'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php echo render_date_input('checkin_date','fe_checkout_date', ''); ?>
					</div>
					<div class="col-md-6">
						<?php echo render_date_input('expected_checkin_date','fe_expected_checkout_date', ''); ?>
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
				<div class="asset_list"></div>
			</div>
			<?php echo form_close(); ?>                 
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>
</body>
</html>
