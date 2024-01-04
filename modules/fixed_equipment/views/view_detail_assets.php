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
									<?php echo html_entity_decode($title); ?>
								</h4>
								<?php 
								$back_link = admin_url('fixed_equipment/assets');
								if(isset($redirect) && $redirect != ''){
									$back_link = admin_url('fixed_equipment/'.$redirect);
								}

								?>
								<a href="<?php echo html_entity_decode($back_link); ?>" class="btn btn-default pull-right"><?php echo _l('fe_back'); ?></a>
							</div>
						</div>
						<br>
						<div class="horizontal-scrollable-tabs  mb-5">
							<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
							<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
							<div class="horizontal-tabs mb-4">
								<ul class="nav nav-tabs nav-tabs-horizontal">
									<li
									<?php if($tab == 'details'){echo " class='active'"; } ?>>
									<a href="<?php echo admin_url('fixed_equipment/detail_asset/'.$id.'?tab=details'); ?>">
										<?php echo _l('fe_details'); ?>
									</a>
								</li>

								<li
								<?php if($tab == 'licenses'){echo " class='active'"; } ?>>
								<a href="<?php echo admin_url('fixed_equipment/detail_asset/'.$id.'?tab=licenses'); ?>">
									<?php echo _l('fe_licenses'); ?>
								</a>
							</li>

							<li
							<?php if($tab == 'components'){echo " class='active'"; } ?>>
							<a href="<?php echo admin_url('fixed_equipment/detail_asset/'.$id.'?tab=components'); ?>">
								<?php echo _l('fe_components'); ?>
							</a>
						</li>
						<li
						<?php if($tab == 'assets'){echo " class='active'"; } ?>>
						<a href="<?php echo admin_url('fixed_equipment/detail_asset/'.$id.'?tab=assets'); ?>">
							<?php echo _l('fe_assets'); ?>
						</a>
					</li>

					<li
					<?php if($tab == 'maintenances'){echo " class='active'"; } ?>>
					<a href="<?php echo admin_url('fixed_equipment/detail_asset/'.$id.'?tab=maintenances'); ?>">
						<?php echo _l('fe_maintenances'); ?>
					</a>
				</li>

				<li
				<?php if($tab == 'history'){echo " class='active'"; } ?>>
				<a href="<?php echo admin_url('fixed_equipment/detail_asset/'.$id.'?tab=history'); ?>">
					<?php echo _l('fe_history'); ?>
				</a>
			</li>

			<li
			<?php if($tab == 'files'){echo " class='active'"; } ?>>
			<a href="<?php echo admin_url('fixed_equipment/detail_asset/'.$id.'?tab=files'); ?>">
				<?php echo _l('fe_files'); ?>
			</a>
		</li>
	</ul>
</div>
<?php $this->load->view('detail_asset/'.$tab); ?>
</div>
</div>
</div>
<div class="clearfix"></div>
</div>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
<div id="new_version"></div>
<?php init_tail(); ?>
</body>
</html>

