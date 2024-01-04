<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="col-md-12">
							<h4 class="pull-left">
								<?php echo html_entity_decode($title); ?>
							</h4>
							<a href="<?php echo admin_url('fixed_equipment/licenses'); ?>" class="btn btn-default pull-right"><?php echo _l('fe_back'); ?></a>
						</div>
						<div class="clearfix"></div>
						<br>
						<div class="horizontal-scrollable-tabs mb-5">
							<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
							<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
							<div class="horizontal-tabs mb-4">
								<ul class="nav nav-tabs nav-tabs-horizontal">
									<li
									<?php if($tab == 'details'){echo " class='active'"; } ?>>
									<a href="<?php echo admin_url('fixed_equipment/detail_licenses/'.$id.'?tab=details'); ?>">
										<?php echo _l('fe_details'); ?>
									</a>
								</li>

								<li
								<?php if($tab == 'seat'){echo " class='active'"; } ?>>
								<a href="<?php echo admin_url('fixed_equipment/detail_licenses/'.$id.'?tab=seat'); ?>">
									<?php echo _l('fe_seat'); ?>
								</a>
							</li>

							<li
							<?php if($tab == 'history'){echo " class='active'"; } ?>>
							<a href="<?php echo admin_url('fixed_equipment/detail_licenses/'.$id.'?tab=history'); ?>">
								<?php echo _l('fe_history'); ?>
							</a>
						</li>

						<li
						<?php if($tab == 'files'){echo " class='active'"; } ?>>
						<a href="<?php echo admin_url('fixed_equipment/detail_licenses/'.$id.'?tab=files'); ?>">
							<?php echo _l('fe_files'); ?>
						</a>
					</li>
				</ul>
			</div>
			<?php $this->load->view('detail_licenses/'.$tab); ?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
</div>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>
</body>
</html>

