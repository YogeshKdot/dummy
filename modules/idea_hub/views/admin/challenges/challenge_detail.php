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
                                    <a href="<?php echo admin_url('idea_hub'); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-angle-left"></i> Back
                                    </a>
                                </div>
                                <div class="challenge_btn_cl">
                                    <a href="<?php echo admin_url('idea_hub/idea/'.$challenge_id); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-lightbulb-o menu-icon"></i> New idea
                                    </a>
                                </div>
                                
                                  <div class="challenge_btn_cl">
                                    <a href="<?php echo admin_url('idea_hub/ideas/'.$challenge_id); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-eye"></i> ideas
                                    </a>
                                </div>
                              </div>
                          </div>
                     </div>
                  </div>
              </div>   
            </div>
        </div>
        
        <div class="grid_panel details_grid_panel">
            <div class="row">
               <div class="col-lg-5 col_dif_1 col-sm-5">
                    <div class="panel_s">
                        <div class="panel-body panel-color ad_new_cl">
                            <div class="img_card_ih">
                               <div class="wrap_img_sec_grid">
                                <div class="actn_edit">
                                    <?php if(is_admin() || (has_permission('idea_hub', '', 'delete') && get_staff_user_id() == $challenge->user_id)){
                                    ?>
                                    <div class="wrap_actn_b">
                                        <a class="trash_btn_c _delete" href="<?= admin_url('idea_hub/delete_challenge/'. $challenge->id);?>">
                                            <span><i class="fa fa-trash-o" aria-hidden="true"></i> </span> <?= _l('delete');?>
                                        </a>
                                    </div>
                                     <?php } ?>
                                    <?php if(is_admin() || (has_permission('idea_hub', '', 'edit') && get_staff_user_id() == $challenge->user_id)){
                                    ?>
                                    <div class="wrap_actn_b">
                                        <a class="pencil_btn_c" href="<?= admin_url('idea_hub/challenge/' . $challenge->id);?>">
                                            <span><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                            <?= _l('edit');?>
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="rght_img_cl">
                                    <?php if(isset($challenge->cover_image) && !empty($challenge->cover_image)){ ?>
                                    <img src="<?php echo base_url('modules/idea_hub/uploads/challenges/'.$challenge->cover_image); ?>">
                                  <?php }else{ ?>
                                     <img src="<?php echo base_url('modules/idea_hub/assets/img/second_baner.jpg'); ?>">
                                  <?php } ?>
                                    <div class="name_ls_s countdown" data-challenge="2">
                                     <div class="timeing_heading">
                                            <p class="running_phase">
                                                <?= _l('remaining_time');?> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                            </p>
                                            <p class="end_phase hidden">
                                                <?= _l('no_left_time');?> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                            </p>
                                        </div>
                                       
                                    <div class="phase_running">
                                        <div class="w_timeing_d">
                                            <h3 class="days"><?= !empty($deadline) ? $deadline['days'] : '0';?></h3>
                                            <h5>DAY</h5>
                                        </div>
                                         <span class="dot_cl_ms">:</span>
                                        <div class="w_timeing_d">
                                            <h3 class="hours">
                                                <?= !empty($deadline) ? $deadline['hrs'] : '0';?>
                                            </h3>
                                            <h5>HOUR</h5>
                                        </div>
                                        <span class="dot_cl_ms">:</span>
                                        <div class="w_timeing_d">
                                            <h3 class="minutes"><?= !empty($deadline) ? $deadline['mins'] : '0';?></h3>
                                            <h5>MIN</h5>
                                        </div>
                                        <span class="dot_cl_ms">:</span>
                                        <div class="w_timeing_d">
                                            <h3 class="seconds"><?= !empty($deadline) ? $deadline['secs'] : '0';?></h3>
                                            <h5>SEC</h5>
                                        </div>
                                    </div>
                                </div>
                                    
                                </div>
                                <div class="user_img_name">
                                  <div>
                                      <?php $user_image = staff_profile_image($challenge->user_id, [''], '', []); echo $user_image;?>
                                  </div>
                                  <div>
                                      <b><?= get_staff_full_name($challenge->user_id); ?></b>
                                  </div>
                                </div>
                                </div>
                            </div>
                            <div class="card_ih_text_data">
                              
                                <div class="grid_dtails_data">
                                    <h3 class="d_m_ce_g"><?=$challenge->title;?></h3>
                                    <ul class="list-inline"> 
                                        <li class="list-inline-item">
                                            <h4>Name</h4>
                                            <p><?= get_staff_full_name($challenge->user_id); ?></p>
                                        </li>
                                        <li class="list-inline-item">
                                             <h4>Submitted</h4>
                                            <p class="date_cl_n"><?= $challenge->added_at; ?></p>
                                        </li>
                                        <li class="list-inline-item">
                                        	<?php $cat = get_category_by_challenge_id($challenge->id); ?>
                                            <h4>Category</h4>
                                            <label class="lable_new_cl" style="background-color: <?=$cat['color'];?>"><?=$cat['name'];?></label>
                                        </li>
                                        <li class="list-inline-item">
                                          
                                        </li>
                                        <li class="list-inline-item">
                                             <h4>Status</h4>
                                            <label class="lable_new_cl" style="background-color: #cadd2d;"><?= $challenge->status; ?></label>
                                        </li>
                                        <li class="list-inline-item">
                                            
                                        </li>
                                    </ul>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
              
                <div class="col-lg-7 col-sm-7 col_dif_2">
                    <div class="panel_s">
                        <div class="panel-body">

                         <div class="grid_view_datails_tab">
                              <ul class="nav nav-pills tab_header_d" role="tablist">
                                 <li class="nav-item active" style="width: 50%;text-align: center;">
                                     <a class="nav-link " data-toggle="pill" href="#description_tb"><i class="fa fa-align-left"></i> Description</a>
                                 </li>

                                 <li class="nav-item" style="width: 50%;text-align: center;">
                                     <a class="nav-link" data-toggle="pill" href="#attachement_tb"><i class="fa fa-paperclip"></i> Instruction</a>
                                 </li>
                              
                             </ul>

                             <!-- Tab panes -->
                             <div class="tab-content">
                                 <div id="description_tb" class=" tab-pane active">
                                   <div class="grid_tab_data">
                                       <h1>Description</h1>
                                        <p><?= $challenge->description; ?></p>
                                   </div>
                                 </div>
                                 <div id="attachement_tb" class=" tab-pane fade">
                                      <div class="grid_tab_data">
                                       <h1>Instruction</h1>
                                       <p><?= $challenge->instruction; ?></p>
                                   </div>
                                 </div>
                             </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    jQuery('.dropdown-menu.keep-open').on('click', function (e) {
	  e.stopPropagation();
	});

 "use strict";
 $(document).ready(function(){
  var theDaysBox = $(this).find(".days");
  var theHoursBox = $(this).find(".hours");
  var theMinsBox = $(this).find(".minutes");
  var theSecsBox = $(this).find(".seconds");
  var phase_running = $(this).find(".running_phase");
  var phase_ended = $(this).find(".end_phase");
  var challenge_id = $("#countdown").attr('data-challenge');
  var refreshId = setInterval(function() {
    var currentSeconds = theSecsBox.text();
    var currentMins = theMinsBox.text();
    var currentHours = theHoursBox.text();
    var currentDays = theDaysBox.text();
    if (currentSeconds == 0 && currentMins == 0 && currentHours == 0 && currentDays == 0) {
    } else if (currentSeconds == 0 && currentMins == 0 && currentHours == 0) {
      theDaysBox.html(currentDays - 1);
      theHoursBox.html("23");
      theMinsBox.html("59");
      theSecsBox.html("59");
    } else if (currentSeconds == 0 && currentMins == 0) {
      theHoursBox.html(currentHours - 1);
      theMinsBox.html("59");
      theSecsBox.html("59");
    } else if (currentSeconds == 0) {
      theMinsBox.html(currentMins - 1);
      theSecsBox.html("59");
    } else {
      if(parseInt(theSecsBox.text()) > 0){
        theSecsBox.html(currentSeconds - 1);
        phase_running.removeClass('hidden');
        phase_ended.addClass('hidden');
      }else{
        archivedchallenge(challenge_id);
        phase_running.addClass('hidden');
        phase_ended.removeClass('hidden');
      }
    }
  },
  1000);
});
</script>

</body>
</html>