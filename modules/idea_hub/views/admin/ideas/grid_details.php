<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$deadline = convert_to_days_hrs_min_sec(get_deadline_by_challenge_id($idea->challenge_id));
$attachs = get_attachments_by_idea_id($idea->id);
?>
<?php init_head(); ?>
<div id="wrapper" class="idea_hub">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              <div class="panel_s">
                  <div class="panel-body ad_bottom_cl">
                    <div class="in_cl_maksd">
                       <div class="back_btn_swa">
                           <div class="challenge_btn_cl">
                               <a href="<?php echo admin_url('idea_hub/ideas/'.$idea->challenge_id); ?>" class="btn btn-info pull-left display-block">
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
                        <p><?= _l('points');?></p>
                    </div>     
                    <div class="wrap_img_sec_grid">
                        <div class="actn_edit">
                           <?php
                           if (is_admin() || (has_permission('idea_hub', '', 'delete') && get_staff_user_id() == $idea->user_id)) {
                              ?>
                              <div class="wrap_actn_b">
                                <a class="trash_btn_c _delete" href="<?= admin_url('idea_hub/delete_idea/'. $idea->id.'/'.$idea->challenge_id);?>">
                                    <span><i class="fa fa-trash-o" aria-hidden="true"></i> </span> <?= _l('delete');?> </a>
                                </div>
                            <?php }if (is_admin() || (has_permission('idea_hub', '', 'edit') && get_staff_user_id() == $idea->user_id)) {?>
                                <div class="wrap_actn_b">
                                    <a class="pencil_btn_c" href="<?= admin_url('idea_hub/idea/'. $idea->challenge_id .'/'. $idea->id);?>">
                                        <span><i class="fa fa-pencil" aria-hidden="true"></i></span> <?= _l('edit');?>
                                    </a>

                                </div>
                            <?php } ?> 
                        </div>
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
                            <div class="name_ls_s countdown" data-challenge="<?php echo $idea->challenge_id; ?>">
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
                                    <h3 class="days"><?= !empty($deadline) ? $deadline['days'] : '0';?> </h3>
                                    <h5>DAY</h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="hours">
                                        <?= !empty($deadline) ? $deadline['hrs'] : '0';?>
                                    </h3>
                                    <h5><?= _l('ih_hr') ?></h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="minutes"><?= !empty($deadline) ? $deadline['mins'] : '0';?></h3>
                                    <h5><?= _l('ih_min') ?></h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="seconds"><?= !empty($deadline) ? $deadline['secs'] : '0';?></h3>
                                    <h5><?= _l('ih_sec') ?></h5>
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
            <h5><?= _l('idea_detail_rank_title');?></h5>
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
                    <h4><?php echo _l('name'); ?></h4>
                    <p><?= get_staff_full_name($idea->user_id); ?></p>
                </li>
                <li class="list-inline-item">
                   <h4><?php echo _l('ih_submit_date'); ?></h4>
                   <p class="date_cl_n"><?= _d($idea->added_at); ?></p>
               </li>
               <li class="list-inline-item">
                <?php $cate = get_category_by_challenge_id($idea->challenge_id);?>
                <h4><?= _l('category'); ?></h4>
                <label class="lable_new_cl" style="background-color: <?=$cate['color']?>;"><?=$cate['name']?></label>
            </li>
            <li class="list-inline-item">
                <?php $stage = get_stage_by_id($idea->stage_id);?>
                <h4><?= _l('stage'); ?></h4>
                <label class="lable_new_cl" style="background-color: <?=$stage['color']?>;"><?=$stage['name']?></label>
            </li>
            <li class="list-inline-item">
                <?php $status = get_status_by_id($idea->status_id);?>
                <h4><?= _l('status'); ?></h4>
                <label class="lable_new_cl" style="background-color: <?=$status['color']?>;"><?=$status['name']?></label>
            </li>
            <li class="list-inline-item">
                <h4><?= _l('number_of_votes');?></h4>
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
                   <a class="nav-link " data-toggle="pill" href="#description_tb"><i class="fa fa-align-left"></i> <?= _l('description');?></a>
               </li>

               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#attachement_tb"><i class="fa fa-paperclip"></i> <?= _l('attachments');?></a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#business_tb"><i class="fa fa-briefcase" ></i> <?= _l('business');?></a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#impactgoal_tb"><i class="fa fa-life-ring"></i> <?= _l('impact_goal');?></a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#additional_info"><i class="fa fa-info-circle"></i> <?= _l('additional_info');?></a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" data-toggle="pill" href="#comment_tb"><i class="fa fa-commenting-o"></i> <?= _l('comments');?></a>
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
                                echo "<span class='badge'>".$tag."</span>";
                            } ?>
                        </div>
                        <?php  
                    }
                    ?>
            </div>
        </div>
        <div id="attachement_tb" class=" tab-pane fade">
          <div class="grid_tab_data">
             <h1><?= _l('attachments');?></h1>

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
          <h1><?= _l('business');?></h1>
          <?= $idea->bussiness_impact; ?>
      </div>
  </div>
  <div id="impactgoal_tb" class=" tab-pane fade">
   <div class="grid_tab_data">
      <h1><?= _l('impact_goal');?></h1>
      <?php echo $idea->goal; ?>
  </div>
</div>
<div id="additional_info" class=" tab-pane fade">
   <div class="grid_tab_data">
      <h1><?= _l('additional_info');?></h1>
      <?= $idea->additional_info; ?>
  </div>
</div>
<div id="comment_tb" class=" tab-pane fade">
  <div class="grid_tab_data">
      <h1><?= _l('comments');?></h1>
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
<?php init_tail(); ?>
<script>
    "use strict";
    jQuery('.dropdown-menu.keep-open').on('click', function (e) {
      e.stopPropagation();
  });
    $("li.ranking_btn").on("click", function(events, selector, data, handler) {
        var idea_id = $(this).parent('ul').attr('data-id');
        var thisObj = $(this);
        var _rank = events.target.innerText;
        $.post( admin_url + 'idea_hub/add_update_idea_rank/', {'rank':_rank, 'idea_id':idea_id}, function( res ) {
            let result = JSON.parse(res);
            $('.no_of_initial .active').removeClass('active');
            thisObj.addClass('active');
            $(".point_count").text(result.points);
            $("#vote-count-p").text(result.vote);
        });
    });
    var idea_id = $('input[name="idea_id"]').val();
    var discussion_user_profile_image_url = '';
    var current_user_is_admin = '';
    var get_project_discussions_language_array = '{"discussion_add_comment":"Add comment","discussion_newest":"Newest","discussion_oldest":"Oldest","discussion_attachments":"Attachments","discussion_send":"Send","discussion_reply":"Answer","discussion_edit":"Edit","discussion_edited":"Modified","discussion_you":"You","discussion_save":"Save","discussion_delete":"Delete","discussion_view_all_replies":"Show all replies","discussion_hide_replies":"Hide replies","discussion_no_comments":"No comments","discussion_no_attachments":"No attachments","discussion_attachments_drop":"Drag and drop to upload file"}';
    if(typeof(idea_id) != 'undefined'){
        discussion_comments('#idea-comments',idea_id,'regular');
    }

    function discussion_comments(selector,idea_id,discussion_type){
        var defaults = _get_jquery_comments_default_config({"discussion_add_comment":"Add comment","discussion_newest":"Newest","discussion_oldest":"Oldest","discussion_attachments":"Attachments","discussion_send":"Send","discussion_reply":"Answer","discussion_edit":"Edit","discussion_edited":"Modified","discussion_you":"You","discussion_save":"Save","discussion_delete":"Delete","discussion_view_all_replies":"Show all replies","discussion_hide_replies":"Hide replies","discussion_no_comments":"No comments","discussion_no_attachments":"No attachments","discussion_attachments_drop":"Drag and drop to upload file"});
        var options = {
            wysiwyg_editor: {
                opts: {
                    enable: true,
                    is_html: true,
                    container_id: 'editor-container',
                    comment_index: 0,
                },
                init: function (textarea, content) {
                    var comment_index = textarea.data('comment_index');
                    var editorConfig = _simple_editor_config();
                    editorConfig.setup = function(ed) {
                        textarea.data('wysiwyg_editor', ed);
                        ed.on('change', function() {
                          var value = ed.getContent();
                          if (value !== ed._lastChange) {
                            ed._lastChange = value;
                            textarea.trigger('change');
                        }
                    });
                        ed.on('keyup', function() {
                            var value = ed.getContent();
                            if (value !== ed._lastChange) {
                                ed._lastChange = value;
                                textarea.trigger('change');
                            }
                        });
                        ed.on('Focus', function (e) {
                            textarea.trigger('click');
                        });

                        ed.on('init', function() {
                            if (content) ed.setContent(content);
                        });
                    }
                    var editor = init_editor('#'+ this.get_container_id(comment_index), editorConfig)
                },
                get_container: function (textarea) {
                    if (!textarea.data('comment_index')) {
                        textarea.data('comment_index', ++this.opts.comment_index);
                    }
                    return $('<div/>', {
                        'id': this.get_container_id(this.opts.comment_index)
                    });
                },
                get_contents: function(editor) {
                  return editor.getContent();
              },
              on_post_comment: function(editor, evt) {
                 editor.setContent('');
             },
             get_container_id: function(comment_index) {
                var container_id = this.opts.container_id;
                if (comment_index) container_id = container_id + "-" + comment_index;
                return container_id;
            }
        },
        currentUserIsAdmin:current_user_is_admin,
        getComments: function(success, error) {
            $.get(admin_url + 'idea_hub/get_idea_comments/'+idea_id+'/'+discussion_type,function(response){
              success(response);
          },'json');
        },
        postComment: function(commentJSON, success, error) {
            $.ajax({
              type: 'post',
              url: admin_url + 'idea_hub/add_discussion_comment/'+idea_id+'/'+discussion_type,
              data: commentJSON,
              success: function(comment) {
                comment = JSON.parse(comment);
                success(comment)
            },
            error: error
        });
        },
        putComment: function(commentJSON, success, error) {
            $.ajax({
                type: 'post',
                url: admin_url + 'idea_hub/update_discussion_comment',
                data: commentJSON,
                success: function(comment) {
                    comment = JSON.parse(comment);
                    success(comment)
                },
                error: error
            });
        },
        deleteComment: function(commentJSON, success, error) {
            $.ajax({
                type: 'post',
                url: admin_url + 'idea_hub/delete_discussion_comment/'+commentJSON.id,
                success: success,
                error: error
            });
        },
        uploadAttachments: function(commentArray, success, error) {
            var responses = 0;
            var successfulUploads = [];
            var serverResponded = function() {
                responses++;
                if(responses == commentArray.length) {
                    if(successfulUploads.length == 0) {
                      error();
                  } else {
                    successfulUploads = JSON.parse(successfulUploads);
                    success(successfulUploads)
                }
            }
        }
        $(commentArray).each(function(index, commentJSON) {
            var formData = new FormData();
            if(commentJSON.file.size && commentJSON.file.size > app.max_php_ini_upload_size_bytes){
               alert_float('danger',"The uploaded file exceeds the upload_max_filesize directive in php.ini");
               serverResponded();
           }else{
            $(Object.keys(commentJSON)).each(function(index, key) {
                var value = commentJSON[key];
                if(value) formData.append(key, value);
            });
            if(typeof(csrfData) !== 'undefined') {
                formData.append(csrfData['token_name'], csrfData['hash']);
            }
            $.ajax({
                url: admin_url + 'idea_hub/add_discussion_comment/'+idea_id+'/'+discussion_type,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(commentJSON) {
                    successfulUploads.push(commentJSON);
                    serverResponded();
                },
                error: function(data) {
                 var error = JSON.parse(data.responseText);
                 alert_float('danger',error.message);
                 serverResponded();
             },
         });
        }
    });
    }
}
var settings = $.extend({}, defaults, options);
$(selector).comments(settings);
}
$(function(){
    $(".countdown").each(function() {
        var theDaysBox = $(this).find(".days");
        var theHoursBox = $(this).find(".hours");
        var theMinsBox = $(this).find(".minutes");
        var theSecsBox = $(this).find(".seconds");
        var phase_running = $(this).find(".running_phase");
        var phase_ended = $(this).find(".end_phase");
        var challenge_id = $(this).data('challenge');
        var challenge_status = $(this).data('status');
        var refreshId = '';
        var _this = $(this);
        if(challenge_status != 'inactive'){
            refreshId = setInterval(function() {
              var currentSeconds = theSecsBox.text();
              var currentMins = theMinsBox.text();
              var currentHours = theHoursBox.text();
              var currentDays = theDaysBox.text();
              if (currentSeconds == 0 && currentMins == 0 && currentHours == 0 && currentDays == 0) {
                $.post(admin_url+'idea_hub/deactivate_challenge/'+challenge_id,{status:'inactive'},function(response) {
                    if(response){
                        _this.data('status','inactive');
                        challenge_status = 'inactive';
                        phase_running.addClass('hidden');
                        phase_ended.removeClass('hidden');
                        clearInterval(refreshId);
                        window.location.href = admin_url+"idea_hub";
                    }else{
                        console.log(response);
                    }
                });
            } else if(currentSeconds == 0 && currentMins == 0 && currentHours == 0){
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
                  phase_running.addClass('hidden');
                  phase_ended.removeClass('hidden');
              }
          }
      },1000);
        }
    });
})
</script>
</body>
</html>