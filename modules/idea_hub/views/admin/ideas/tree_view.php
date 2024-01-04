<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="idea_hub">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              <div class="panel_s">
                  <div class="panel-body ad_bottom_cl">
                     <div class="row">
                           <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_left">
                                <div class="challenge_btn_cl">
                                    <a href="<?php echo admin_url('idea_hub/idea/'.$challenge_id); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-lightbulb-o"></i> <?= _l('new_idea'); ?>
                                    </a>
                                </div>
                              </div>
                          </div>
                           <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_right">
                                 <div class="list_and_grid">
                                    <span>
                                    <a href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=tree" data-toggle="tooltip"  title="Tree View">
                                      <img src="<?php echo base_url('modules/idea_hub/assets/img/hierarchical-structure.png'); ?>">
                                    </a>
                                    </span>
                                    <span>
                                    <a href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=kanban" data-toggle="tooltip" title="Kanban Board">
                                      <img src="<?php echo base_url('modules/idea_hub/assets/img/kanban.png'); ?>">
                                    </a>
                                    </span>
                                    <span>
                                        <a data-toggle="tooltip" title="List View" href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=list">
                                        <img src="<?php echo base_url('modules/idea_hub/assets/img/list.png'); ?>">
                                        </a>
                                    </span>
                                    <span class="grid_list_bg">
                                        <a data-toggle="tooltip" title="Grid View" href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=grid">
                                        <img src="<?php echo base_url('modules/idea_hub/assets/img/menu.png'); ?>">
                                        </a>
                                    </span>
                                  </div>
                              </div>
                          </div>
                     </div>
                  </div>
              </div>   
            </div>
        </div>
         <div class="panel_s">
             <div class="panel-body">
                 <div class="all_wrap_tree">
                     <!--1-->
                     <div id="wrapper_tree">
                         <span style="background-color: #FF6F00;" class="label_tree toggleable" id="root_tree">
                            <?php if(isset($challenge->cover_image) && !empty($challenge->cover_image)){ ?>
                            <img src="<?php echo base_url('modules/idea_hub/uploads/challenges/'.$challenge->cover_image); ?>">
                          <?php }else{ ?>
                            <img src="<?php echo base_url('modules/idea_hub/assets/img/bg-img.jpg'); ?>">
                          <?php } ?>
                          
                         <?php echo $challenge->title; ?> 
                         <small data-toggle="tooltip" data-placement="top" title="No of child" class="root_num"><?php echo $ideas ? count($ideas):0; ?></small>
                         <small class="no_of_vite" data-toggle="tooltip" data-placement="top" title="No of votes">
                         <i class="las la-poll"></i> <?php echo isset($ivoteCount['total']) ? $ivoteCount['total']:0; ?></small>
                         </span>
                         <div class="branch lv1" id="branch1">
                            <?php 
                            if(!empty($ideas)){
                                foreach ($ideas as $key => $idea) { ?>
                                    <div class="entry_tree" >
                                        <span style="background-color: #FF6F00;" class="label_tree" id="cchild_<?php echo $idea['id']; ?>">
                                             <?php if(isset($idea['cover_image']) && !empty($idea['cover_image'])){ ?>
                                                <img src="<?php echo base_url('modules/idea_hub/uploads/challenges/'.$idea['cover_image']); ?>" class="img-responsive">
                                              <?php }else{ ?>
                                                <img src="<?php echo base_url('modules/idea_hub/assets/img/bg-img.jpg'); ?>" class="img-responsive">
                                              <?php } ?>
                                            
                                            <?php echo $idea['title']; ?>
                                            <small class="child_stuts_cl" data-toggle="tooltip" data-placement="right" title="<?php echo $idea['title']; ?>"><i class="las la-user-friends"></i>
                                            </small>
                                        </span>
                                    </div>
                            <?php } } ?>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
</script>
</body>
</html>