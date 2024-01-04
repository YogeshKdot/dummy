<?php defined('BASEPATH') or exit('No direct script access allowed');
$where = ('challenge_id ='.$challenge_id);
if(is_admin()){
    $where .=(' AND (visibility = "public" OR ' .db_prefix(). 'idea_hub_ideas.user_id = '.get_staff_user_id().')');
}else{
	if(!has_permission('idea_hub', '', 'view')){
		$where .=(' AND (visibility = "public" OR ' .db_prefix(). 'idea_hub_ideas.user_id = '.get_staff_user_id().')');
	}else{
		$where .=(' AND (visibility = "public" OR visibility = "custom")');
	}
}

foreach ($stages as $stage) {
  $total_pages = ceil($this->idea_hub_model->do_kanban_query($stage['id'],$this->input->get('search'),1,true,$where)/get_option('tasks_kanban_limit'));
  ?>
<ul class="kan-ban-col tasks-kanban" data-col-stage-id="<?php echo $stage['id']; ?>" data-total-pages="<?php echo $total_pages; ?>">
	<li class="kan-ban-col-wrapper">
	  	<div class="border-right panel_s">
		    <div class="panel-heading-bg" style="background:<?php echo $stage['color']; ?>;border-color:<?php echo $stage['color']; ?>;color:#fff; ?>" data-status-id="<?php echo $stage['id']; ?>">
		      	<div class="kan-ban-step-indicator<?php if($stage['id'] == Tasks_model::STATUS_COMPLETE){ echo ' kan-ban-step-indicator-full'; } ?>"></div>
				<?php
				$ideas = $this->idea_hub_model->do_kanban_query($stage['id'],$this->input->get('search'),1,false,$where);
				$total_ideas = count($ideas);
				?>
				<span class="heading">
					<?php echo $stage['name']; ?>
				</span>
		    	<a href="#" onclick="return false;" class="pull-right color-white"></a>
		  	</div>
			<div class="kan-ban-content-wrapper">
				<div class="kan-ban-content">
					<ul class="status tasks-status sortable relative" data-task-stage-id="<?php echo $stage['id']; ?>">
						<?php

						foreach ($ideas as $idea) {
						if ($idea['stage_id'] == $stage['id']) {
						$this->load->view('idea_hub/admin/ideas/kanban_card',array('idea'=>$idea,'status'=>$stage['id']));
						} } ?>
						<?php if($total_ideas > 0 ){ ?>
						<li class="text-center not-sortable kanban-load-more" data-load-status="<?php echo $stage['id']; ?>">
							<a href="#" class="btn btn-default btn-block<?php if($total_pages <= 1){echo ' disabled';} ?>" data-page="1" onclick="kanban_load_more(<?php echo $stage['id']; ?>,this,'idea_hub/idea_kanban_load_more',265,360); return false;";>
							<?php echo _l('load_more'); ?>
							</a>
						</li>
						<?php } ?>
						<li class="text-center not-sortable mtop30 kanban-empty<?php if($total_ideas > 0){echo ' hide';} ?>">
							<h4>
								<i class="fa fa-circle-o-notch" aria-hidden="true"></i><br /><br />
								<?php echo _l('no_ideas_found'); ?>
							</h4>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</li>
</ul>
<?php } ?>