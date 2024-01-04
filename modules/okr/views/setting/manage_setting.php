<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
	    	<div class="clearfix"></div>
		    	
		    	<div class="col-md-12">
				 	<div class="horizontal-scrollable-tabs preview-tabs-top">
					  <div class="horizontal-tabs">
					  	<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
					      <li role="presentation" class="tab_cart <?php if($tab == 'circulation'){ echo 'active'; } ?>">
					         <a href="<?php echo admin_url('okr/setting?tab=circulation'); ?>" aria-controls="tab_config" role="tab" aria-controls="tab_config">
					         		<?php echo _l('circulation'); ?>
					         </a>
					      </li>
					      <li role="presentation" class="tab_cart <?php if($tab == 'question'){ echo 'active'; } ?>">
					         <a href="<?php echo admin_url('okr/setting?tab=question'); ?>" aria-controls="tab1" role="tab" aria-controls="tab2">
					         		<?php echo _l('question'); ?>
					         </a>
					      </li>

					      <li role="presentation" class="tab_cart <?php if($tab == 'evaluation_criteria'){ echo 'active'; } ?>">
					         <a href="<?php echo admin_url('okr/setting?tab=evaluation_criteria'); ?>" aria-controls="tab1" role="tab" aria-controls="tab2">
					         		<?php echo _l('evaluation_criteria'); ?>
					         </a>
					      </li>
							
							<li role="presentation" class="tab_cart <?php if($tab == 'unit'){ echo 'active'; } ?>">
					         <a href="<?php echo admin_url('okr/setting?tab=unit'); ?>" aria-controls="tab1" role="tab" aria-controls="tab2">
					         		<?php echo _l('unit'); ?>
					         </a>
					      </li>
					      <li role="presentation" class="tab_cart <?php if($tab == 'category'){ echo 'active'; } ?>">
					         <a href="<?php echo admin_url('okr/setting?tab=category'); ?>" aria-controls="tab1" role="tab" aria-controls="tab2">
					         		<?php echo _l('category'); ?>
					         </a>
					      </li>		
					  	</ul>
					  </div>
					</div> 
					<?php $this->load->view('setting/'.$tab); ?>
				</div>
	  </div>
	</div>
  </div>
 </div>
</div>
<?php init_tail(); ?>
</body>
</html>
