<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
   <div class="clearfix"></div><br>
   <div class="col-md-12">
    <h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo html_entity_decode($title).' - '.$normal->name; ?></h4>
   </div>
   <div class="col-md-12">
            <div class="wrapper_colors">
              <ul class="nav nav-tabs list_colors li1" id="myTab">
                <?php if(is_admin()){ ?>
                        <li <?php if(($tab == '')||($tab == 'general_info')){ echo 'class="active"'; } ?>>
                           <a href="<?php echo admin_url('team_password/view_normal/'.$id.'?tab=general_info'); ?>" aria-controls="medical_review" role="tab">
                              <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;<?php echo _l('general_info') ?>
                           </a>
                        </li>
                        <li <?php if($tab == 'permission'){ echo 'class="active"'; } ?>>
                           <a href="<?php echo admin_url('team_password/view_normal/'.$id.'?tab=permission'); ?>" aria-controls="diagnosis" role="tab"><i class="fa fa-check">
                                </i>&nbsp;<?php echo _l('permission') ?>
                           </a>
                        </li>
                        <li <?php if($tab == 'share'){ echo 'class="active"'; } ?>>
                           <a href="<?php echo admin_url('team_password/view_normal/'.$id.'?tab=share'); ?>" aria-controls="lab_tests_results" role="tab">
                            <i class="fa fa-share-alt"></i>&nbsp;<?php echo _l('share') ?>
                           </a>
                        </li>
                <?php }else{ 
                    if(get_permission('normal',$id,'r') == 1 && !get_permission('normal',$id,'w') == 1){ ?>
                        <li <?php if(($tab == '')||($tab == 'general_info')){ echo 'class="active"'; } ?>>
                           <a href="<?php echo admin_url('team_password/view_normal/'.$id.'?tab=general_info'); ?>" aria-controls="medical_review" role="tab">
                              <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;<?php echo _l('general_info') ?>
                           </a>
                        </li>
                  <?php  }elseif(get_permission('normal',$id,'w') == 1 || get_permission('normal',$id,'rw') == 1){ ?>
                        <li <?php if(($tab == '')||($tab == 'general_info')){ echo 'class="active"'; } ?>>
                           <a href="<?php echo admin_url('team_password/view_normal/'.$id.'?tab=general_info'); ?>" aria-controls="medical_review" role="tab">
                              <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;<?php echo _l('general_info') ?>
                           </a>
                        </li>
                        <li <?php if($tab == 'permission'){ echo 'class="active"'; } ?>>
                           <a href="<?php echo admin_url('team_password/view_normal/'.$id.'?tab=permission'); ?>" aria-controls="diagnosis" role="tab"><i class="fa fa-check">
                                </i>&nbsp;<?php echo _l('permission') ?>
                           </a>
                        </li>
                        <li <?php if($tab == 'share'){ echo 'class="active"'; } ?>>
                           <a href="<?php echo admin_url('team_password/view_normal/'.$id.'?tab=share'); ?>" aria-controls="lab_tests_results" role="tab">
                            <i class="fa fa-share-alt"></i>&nbsp;<?php echo _l('share') ?>
                           </a>
                        </li>
                <?php } ?>       
              <?php } ?>       
            </ul>
          </div>
     </div>
      <?php if(is_admin()){ 
             if(($tab == '')||($tab == 'general_info')){ 
                $this->load->view('team_password_mgt/include/normal_general_info');
              }
              if($tab == 'permission'){ 
                $this->load->view('team_password_mgt/include/normal_permission');
              }
              if($tab == 'share'){ 
                $this->load->view('team_password_mgt/include/normal_share');
              }        

       }else{ 
          if(get_permission('normal',$id,'r') == 1 && !get_permission('normal',$id,'w') == 1){ 
              $this->load->view('team_password_mgt/include/normal_general_info');
          }elseif(get_permission('normal',$id,'w') == 1 || get_permission('normal',$id,'rw') == 1){ 
                if(($tab == '')||($tab == 'general_info')){ 
                  $this->load->view('team_password_mgt/include/normal_general_info');
                }
                if($tab == 'permission'){ 
                  $this->load->view('team_password_mgt/include/normal_permission');
                }
                if($tab == 'share'){ 
                  $this->load->view('team_password_mgt/include/normal_share');
                } 
          }       
      } ?> 
      <div class="col-md-12">
        <a href="<?php echo admin_url('team_password/team_password_mgt?cate=all&type=normal'); ?>" class="btn pull-right"><?php echo _l('close'); ?></a>
      </div>
  </div>
 </div>
</div>

<input type="hidden" name="obj_id" value="<?php echo html_entity_decode($id); ?>">
<?php init_tail(); ?>
</body>
</html>

