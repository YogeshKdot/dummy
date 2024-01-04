<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" id="" href="<?php echo module_dir_url('idea_hub','assets/css/customer.css');?>">
<link rel="stylesheet" type="text/css" id="jquery-comments-css" href="<?php echo base_url('assets/plugins/jquery-comments/css/jquery-comments.css');?>">
<div id="wrapper">
    <?php 
    $deadline = convert_to_days_hrs_min_sec(get_deadline_by_challenge_id($idea->challenge_id));
    $attachs = get_attachments_by_idea_id($idea->id);
    ?>
    <div class="content">
         <div class="row">
            <div class="col-md-12">
              <div class="panel_s">
                  <div class="panel-body ad_bottom_cl">
                    <div class="in_cl_maksd">
                       <div class="back_btn_swa">
                           <div class="challenge_btn_cl">
                               <a href="<?php echo site_url('idea_hub/client_ideas/'.$idea->challenge_id); ?>" class="btn btn-info pull-left display-block">
                                   <i class="fa fa-angle-left"></i> <?= _l('back');?>
                               </a>
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

                            <div class="points_cl">
                                <span class="point_count"><?= $ranking ? $ranking : '0' ?></span>
                                <p>Points</p>
                            </div>
                            <div class="wrap_img_sec_grid">
                               
                                <div class="rght_img_cl">
                                    <?php echo render_input('staffid','', get_staff_user_id(), 'hidden'); ?>
                                    <?php 
                                    if(!empty($idea->image))
                                        $type = getFileMimeType(FCPATH .'modules/idea_hub/uploads/ideas/'.$idea->image); 
                                    if(isset($idea->image) && !empty($idea->image) && $type == 'image'){ 
                                        ?>
                                        <img src="<?= base_url('modules/idea_hub/uploads/ideas/'.$idea->image); ?>" class="img-responsive" alt="Image">
                                    <?php } elseif(isset($idea->image) && !empty($idea->image) && $type == 'video') { ?>
                                        <video class="img-responsive user_k" autoplay muted>
                                            <source src="<?php echo base_url('modules/idea_hub/uploads/ideas/'.$idea->image); ?>" type="video/mp4">
                                              Your browser does not support the video tag.
                                          </video>
                                      <?php }else{ ?>
                                        <img src="<?php echo base_url('modules/idea_hub/assets/img/second_baner.jpg'); ?>">
                                    <?php } ?>
                                    <div class="name_ls_s countdown" data-challenge="2">
                                       <div class="timeing_heading">
                                        <p class="running_phase">
                                            Remaining time <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        </p>
                                        <p class="end_phase hidden">
                                            No time left <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        </p>
                                    </div>

                                    <div class="phase_running">

                                        <div class="w_timeing_d">
                                            <h3 class="days"><?= !empty($deadline) ? $deadline['days'] : '0';?> </h3>
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
                                  <?php $user_image = staff_profile_image($idea->user_id, [''], '', []); echo $user_image;?>
                              </div>
                              <div>
                                  <b><?= get_staff_full_name($idea->user_id); ?></b>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card_ih_text_data">
                   <div class="wrap_boa_da">
                    <h5>What is your initial challenge of this idea ?</h5>
                    <ul class="list-inline no_of_initial nav nav-pills" data-id="<?=$idea->id?>">
                        <li class="list-inline-itme ranking_btn <?= $rank && $rank=='1' ? 'active' : '' ?>" data-toggle="pill" style="background-color: #ee4b33;">
                            1
                        </li>
                        <li class="list-inline-item ranking_btn <?= $rank && $rank=='2' ? 'active' : '' ?>" data-toggle="pill" style="background-color: #ed7c04;">
                            2
                        </li>
                        <li class="list-inline-item ranking_btn <?= $rank && $rank=='3' ? 'active' : '' ?>" data-toggle="pill" style="background-color: #f3c902;">
                            3
                        </li>
                        <li class="list-inline-item ranking_btn <?= $rank && $rank=='4' ? 'active' : '' ?>" data-toggle="pill" style="background-color: #ece424;">
                            4
                        </li>
                        <li class="list-inline-item ranking_btn <?= $rank && $rank=='5' ? 'active' : '' ?>" data-toggle="pill" style="background-color: #d2ea22;">
                            5
                        </li>
                        <li class="list-inline-item ranking_btn <?= $rank && $rank=='6' ? 'active' : '' ?>" data-toggle="pill" style="background-color: #1acb6e;">
                            6
                        </li>
                        <li class="list-inline-item ranking_btn <?= $rank && $rank=='7' ? 'active' : '' ?>" data-toggle="pill" style="background-color: #11a658;">
                            7
                        </li>
                    </ul>
                </div>
                <div class="grid_dtails_data">
                    <h3><?=$idea->title;?></h3>
                    <ul class="list-inline"> 
                        <li class="list-inline-item">
                            <h4>Name</h4>
                            <p><?= get_staff_full_name($idea->user_id); ?></p>
                        </li>
                        <li class="list-inline-item">
                           <h4>Submitted</h4>
                           <p class="date_cl_n"><?= $idea->added_at; ?></p>
                       </li>
                       <li class="list-inline-item">
                        <?php $cate = get_category_by_challenge_id($idea->challenge_id);?>
                        <h4>Category</h4>
                        <label class="lable_new_cl" style="background-color: <?=$cate['color']?>;"><?=$cate['name']?></label>
                    </li>
                    <li class="list-inline-item">
                        <?php $stage = get_stage_by_id($idea->stage_id);?>
                        <h4>Stage</h4>
                        <label class="lable_new_cl" style="background-color: <?=$stage['color']?>;"><?=$stage['name']?></label>
                    </li>
                    <li class="list-inline-item">
                        <?php $status = get_status_by_id($idea->status_id);?>
                        <h4>Status</h4>
                        <label class="lable_new_cl" style="background-color: <?=$status['color']?>;"><?=$status['name']?></label>
                    </li>
                    <li class="list-inline-item">
                        <h4>Number of Votes</h4>
                        <p>
                            <b id="vote-count-p"><?= $vote_count; ?></b>
                        </p>
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
               <li class="nav-item active">
                   <a class="nav-link " data-toggle="pill" href="#description_tb"><i class="fa fa-align-left"></i> Description</a>
               </li>

               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#attachement_tb"><i class="fa fa-paperclip"></i> Attachement</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#business_tb"><i class="fa fa-briefcase" ></i> Business</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#impactgoal_tb"><i class="fa fa-life-ring"></i> Impact Goal</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#additional_info"><i class="fa fa-info-circle"></i> Additional Info</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#comment_tb"><i class="fa fa-commenting-o"></i> Comment</a>
               </li>
           </ul>

           <!-- Tab panes -->
           <div class="tab-content">
               <div id="description_tb" class=" tab-pane active">
                 <div class="grid_tab_data">
                     <?= $idea->description; ?>
                     <?php
                     $tags = tags_name_array_by_id($idea->id);
                     if(isset($tags) && !empty($tags)){ ?>
                         <div class="all_tag_dis">
                            <?php
                            foreach ($tags as $tag) {
                                echo "<label class='badge'>".$tag."</label>";
                            } ?>
                        </div>
                        <?php  
                    }
                    ?>

                 
            </div>
        </div>
        <div id="attachement_tb" class=" tab-pane fade">
          <div class="grid_tab_data">
             <h1>Attachement</h1>

             <ul class="list-inline attachement_file_list">
                <?php 
                if(isset($idea)){
                    if (isset($attachs) && !empty($attachs)) {
                        foreach ($attachs as $key) { 
                            $ext = get_file_extension($key['file_name']);
                            ?>
                            <li class="list-inline-item">
                                <div class="attachment_data_wrap">
                                   <a href="<?php echo base_url('modules/idea_hub/uploads/ideas/attachment/'.$key['file_name']); ?>" download>
                                    <img src="<?php echo base_url('modules/idea_hub/assets/img/'.$ext.'.png'); ?>" class="img-responsive" alt="Image">
                                    <p><?php echo $key['file_title']; ?> <i class="fa fa-cloud-download"></i></p>
                                </a>
                            </div>
                        </li>
                        <?php 
                    }   
                }
            }
            ?>
        </ul>

    </div>
</div>
<div id="business_tb" class=" tab-pane fade">
    <div class="grid_tab_data">
      <h1>Business</h1>
      <?= $idea->bussiness_impact; ?>
  </div>
</div>
<div id="impactgoal_tb" class=" tab-pane fade">
   <div class="grid_tab_data">
      <h1>Impact Goal</h1>
      <?php echo $idea->goal; ?>
  </div>
</div>
<div id="additional_info" class=" tab-pane fade">
   <div class="grid_tab_data">
      <h1>Additional Info</h1>
      <?= $idea->additional_info; ?>
  </div>
</div>
<div id="comment_tb" class=" tab-pane fade">
  <div class="grid_tab_data">
      <h1>Comment</h1>
      <div class="">
        <div id="idea-comments"></div>
    </div>
    <input type="hidden" name="idea_id" value="<?php echo $idea->id; ?>">
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
<!-- Modal -->
<div class="modal fade" id="ideaimg" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"><?=$idea->title;?></h4>
          </div>
          <div class="modal-body">
            <?php if(isset($idea->image) && !empty($idea->image) && $type == 'image'){ 
                ?>
                <img src="<?= base_url('modules/idea_hub/uploads/ideas/'.$idea->image); ?>" class="img-responsive" alt="Image">
            <?php } elseif(isset($idea->image) && !empty($idea->image) && $type == 'video') { ?>
                <video class="img-responsive user_k" autoplay muted>
                    <source src="<?php echo base_url('modules/idea_hub/uploads/ideas/'.$idea->image); ?>" type="video/mp4">
                      Your browser does not support the video tag.
                  </video>
              <?php }else{ ?>
                <img  src="<?php echo base_url('modules/idea_hub/assets/images/no-image.png'); ?>" class="img-responsive user_k" alt="Image">
            <?php } ?>
        </div>
    </div>
</div>
</div>
<script src="<?php echo base_url('assets/plugins/jquery-comments/js/jquery-comments.js');?>"></script>
<script>
    "use_strict";
    var idea_id=$('input[name="idea_id"]').val(),discussion_type="regular",user_id=$("input[name='clientid']").val(),userProfilePic=$("input[name='clientpic']").val();$(function(){$("#idea-comments").comments({profilePictureURL:userProfilePic,currentUserId:user_id,roundProfilePictures:!0,textareaRows:1,enableAttachments:!0,enableHashtags:!0,enablePinging:!1,enableUpvoting:!1,scrollContainer:$(window),searchUsers:function(e,t,i){setTimeout(function(){t(usersArray.filter(function(t){var i=-1!=t.fullname.toLowerCase().indexOf(e.toLowerCase()),n=1!=t.id;return i&&n}))},500)},getComments:function(e,t){setTimeout(function(){$.get(site_url+"idea_hub/client_ideas/get_discussion_comments/"+idea_id+"/"+discussion_type,function(t){e(t)},"json")},500)},postComment:function(e,t,i){$.ajax({type:"post",url:site_url+"idea_hub/client_ideas/add_discussion_comment/"+idea_id+"/"+discussion_type,data:e,success:function(e){e=JSON.parse(e),t(e)},error:i})},putComment:function(e,t,i){$.ajax({type:"post",url:site_url+"idea_hub/client_ideas/update_discussion_comment",data:e,success:function(e){e=JSON.parse(e),t(e)},error:i})},deleteComment:function(e,t,i){$.ajax({type:"post",url:site_url+"idea_hub/client_ideas/delete_discussion_comment/"+e.id,success:t,error:i})},validateAttachments:function(e,t){setTimeout(function(){t(e)},500)},uploadAttachments:function(e,t,i){var n=0,a=[],s=function(){++n==e.length&&(0==a.length?i():(a=JSON.parse(a),t(a)))};$(e).each(function(e,t){var i=new FormData;t.file.size&&t.file.size>app.max_php_ini_upload_size_bytes?(alert_float("danger","file exeed maximum size"),s()):($(Object.keys(t)).each(function(e,n){var a=t[n];a&&i.append(n,a)}),"undefined"!=typeof csrfData&&i.append(csrfData.token_name,csrfData.hash),$.ajax({url:site_url+"idea_hub/client_ideas/add_discussion_comment/"+idea_id+"/"+discussion_type,type:"POST",data:i,cache:!1,contentType:!1,processData:!1,success:function(e){a.push(e),s()},error:function(e){var t=JSON.parse(e.responseText);alert_float("danger",t.message),s()}}))})}});var e=$(this).find(".days"),t=$(this).find(".hours"),i=$(this).find(".minutes"),n=$(this).find(".seconds"),a=$(this).find(".running_phase"),s=$(this).find(".end_phase"),o=$("#countdown").attr("data-challenge");setInterval(function(){var d=n.text(),r=i.text(),c=t.text(),u=e.text();0==d&&0==r&&0==c&&0==u||(0==d&&0==r&&0==c?(e.html(u-1),t.html("23"),i.html("59"),n.html("59")):0==d&&0==r?(t.html(c-1),i.html("59"),n.html("59")):0==d?(i.html(r-1),n.html("59")):parseInt(n.text())>0?(n.html(d-1),a.removeClass("hidden"),s.addClass("hidden")):(archivedchallenge(o),a.addClass("hidden"),s.removeClass("hidden")))},1e3);$("li.ranking_btn").on("click",function(e,t,i,n){var a=$(this).parent("ul").attr("data-id"),s=$(this),o=e.target.innerText;$.post(site_url+"idea_hub/client_ideas/add_update_idea_rank/",{rank:o,idea_id:a},function(e){let t=JSON.parse(e);$(".no_of_initial .active").removeClass("active"),s.addClass("active"),$(".point_count").text(t.points),$("#vote-count-p").text(t.vote)})})});
</script>
</body>
</html>