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
									<?php echo _l('fe_custom_field'); ?>									
								</h4>
								<a href="<?php echo admin_url('fixed_equipment/settings?tab=custom_field'); ?>" class="btn btn-default pull-right"><?php echo _l('fe_back'); ?></a>
								<div class="clearfix">
								</div>
								<hr>									
							</div>
						</div>
						<button class="btn btn-primary" onclick="add();"><?php echo _l('add'); ?></button>
						<div class="clearfix"></div>
						<br>
						<div class="clearfix"></div>
						<table class="table table-customfield scroll-responsive">
							<thead>
								<tr>
									<th><?php echo  _l('fe_title'); ?></th>
									<th><?php echo  _l('fe_field_type'); ?></th>
									<th><?php echo  _l('fe_required'); ?></th>
									<th><?php echo  _l('fe_options'); ?></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>

					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="btn-bottom-pusher"></div>
	</div>
</div>

<div class="modal fade" id="add" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span class="add-title"><?php echo _l('fe_new_custom_field'); ?></span>
					<span class="edit-title hide"><?php echo _l('fe_edit_custom_field'); ?></span>
				</h4>
			</div>
			<?php echo form_open(admin_url('fixed_equipment/add_custom_field'),array('id'=>'add_custom_field-form')); ?>
			<div class="modal-body">
				<input type="hidden" name="id" value="">
				<input type="hidden" name="fieldset_id" value="<?php echo html_entity_decode($id); ?>">
				<div class="row">
					<div class="col-md-12">
						<?php echo render_input('title', 'fe_title', ''); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php
						$type_option = [
							['id' => 'textfield', 'label' => _l('fe_textfield')],
							['id' => 'numberfield', 'label' => _l('fe_numberfield')],
							['id' => 'textarea', 'label' => _l('fe_textarea')],
							['id' => 'select', 'label' => _l('fe_select')],
							['id' => 'multi_select', 'label' => _l('fe_multi_select')],
							['id' => 'checkbox', 'label' => _l('fe_checkbox')],
							['id' => 'radio_button', 'label' => _l('fe_radio_button')]
						];
						echo render_select('type', $type_option, array('id', 'label'), 'fe_field_type'); ?>
					</div>
				</div>
				<div class="list-option hide">
					<div class="row">
						<div class="col-md-10">
							<?php echo render_input('option[]','','','text',array('placeholder' => _l('fe_option'))); ?>
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-success add_new_row">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="checkbox checkbox-inline checkbox-primary">
							<input type="checkbox" name="required" id="required" value="1" checked>
							<label for="required"><?php echo _l('fe_required'); ?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('fe_submit'); ?></button>
			</div>
			<?php echo form_close(); ?>                 
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="new_version"></div>
<?php init_tail(); ?>
</body>
</html>

