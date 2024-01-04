<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<li data-idea-id="<?php echo $idea['idea_id']; ?>" >
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 task-name flex_row">
				<?php if($idea['cover_type'] == 'image'){ ?>
					<img src="<?php echo base_url('modules/idea_hub/uploads/ideas/'.$idea['image']); ?>" class="img-responsive hwidth30">
				<?php }else{ ?>
					<img src="<?php echo base_url('modules/idea_hub/uploads/ideas/v_thumbnails/'.$idea['video_thumbnail']); ?>" class="img-responsive hwidth30">
				<?php } ?>
				<a href="<?php echo admin_url('idea_hub/idea_detail/' . $idea['idea_id']); ?>">
					<span class="inline-block full-width mtop10 mbot10"><?php echo $idea['title']; ?></span>
				</a>
			</div>
			<?php if(isset($idea) && !empty($idea['description'])){ ?>
			  <div class="col-md-12 text-muted" style="padding-left: 25px;">
			   <?php echo substr($idea['description'], 0, 35).'...'; ?>
			 </div>
			<?php } ?>
			<div class="col-md-12 text-muted">
				<span class="mright5 inline-block text-muted" data-toggle="tooltip" data-title="<?php echo _l('submitter'); ?>">
				<?php echo $idea['firstname'].' '.$idea['lastname'];?>(<?= get_category_by_challenge_id($idea['challenge_id'])['name']; ?>)
				</span>
			</div>
		</div>
	</div>
</li>