
<input type="hidden" name="staffid" value="<?php echo html_entity_decode($staffid); ?>">
<div class="horizontal-scrollable-tabs preview-tabs-top">
	<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
	<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
	<div class="horizontal-tabs">
		<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
			<li role="presentation" class="active">
				<a href="#asset" aria-controls="asset" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-align-justify"></span>&nbsp;<?php echo _l('fe_asset'); ?>
				</a>
			</li>
			<li role="presentation">
				<a href="#history" aria-controls="history" role="tab" data-toggle="tab">
					<span class="glyphicon glyphicon-pencil"></span>&nbsp;<?php echo _l('fe_history'); ?>
				</a>
			</li>
		</ul>
	</div>
</div>
<div class="tab-content mtop10">
	<div role="tabpanel" class="tab-pane active" id="asset">
		<div class="row">
			<div class="col-md-12">
				<table class="table table-staff_asset scroll-responsive">
					<thead>
						<tr>
							<th>ID</th>
							<th><?php echo  _l('fe_asset_name'); ?></th>
							<th><?php echo  _l('fe_image'); ?></th>
							<th><?php echo  _l('fe_serial'); ?></th>
							<th><?php echo  _l('fe_type'); ?></th>
							<th><?php echo  _l('fe_received_date'); ?></th>
							<th><?php echo  _l('fe_sign_status'); ?></th>
							<th><?php echo  _l('fe_sign_document'); ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane" id="history">
		<div class="row">
			<div class="col-md-12">
				<table class="table table-history scroll-responsive">
					<thead>
						<tr>
							<th><?php echo _l('fe_date'); ?></th>
							<th><?php echo _l('fe_admin'); ?></th>
							<th><?php echo _l('fe_action'); ?></th>
							<th><?php echo _l('fe_object'); ?></th>
							<th><?php echo _l('fe_notes'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>