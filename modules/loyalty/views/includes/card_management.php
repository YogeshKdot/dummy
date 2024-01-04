<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="_buttons">
	<a href="<?php echo admin_url('loyalty/create_card'); ?>" class="btn btn-info pull-left"><?php echo _l('new_card'); ?></a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
	<thead>
		<th><?php echo _l('name'); ?></th>
		<th><?php echo _l('add_from'); ?></th>
		<th><?php echo _l('date_create'); ?></th>
		<th><?php echo _l('options'); ?></th>
	</thead>
	<tbody>
		<?php foreach($cards as $c){ ?>
			<tr>
				<td><?php echo html_entity_decode($c['name']); ?></td>
				<td><?php echo get_staff_full_name($c['add_from']); ?></td>
				<td><span class="label label-success"><?php echo _d($c['date_create']); ?></span></td>
				<td>
					<?php if(has_permission('loyalty','','edit') || is_admin()){ ?>
					<a href="<?php echo admin_url('loyalty/create_card/'.$c['id']); ?>" class="btn btn-icon btn-primary"><i class="fa fa-edit"></i></a>
					<?php } ?>

					<?php if(has_permission('loyalty','','delete') || is_admin()){ ?>
						<a href="<?php echo admin_url('loyalty/delete_card/'.$c['id']); ?>" class="btn btn-icon btn-danger"><i class="fa fa-remove"></i></a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>



