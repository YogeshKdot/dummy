<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI       = & get_instance();
$start    = intval($CI->input->post('start'));
$length   = intval($CI->input->post('length'));
$draw     = intval($CI->input->post('draw'));
$cat_ids_arr = $CI->input->post('cat_ids_arr');
$sortBy = $CI->input->post('sortBy');
$aColumns = [
  db_prefix() . 'idea_hub_challenges.id',
  'user_id',
  'title',
  'cover_image',
  'description',
  'deadline',
  'status',
  'name'
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'idea_hub_challenges';
$where        = [];
$join = [
  'LEFT JOIN ' . db_prefix() . 'idea_hub_category ON ' . db_prefix() . 'idea_hub_category.id = ' . db_prefix() . 'idea_hub_challenges.category_id',
];
if(!$CI->input->post('include_archieved') && is_admin()){
    array_push($where, 'AND '.$sTable.'.status != "archived"');
}

if(!is_admin()){
	if($CI->input->post('include_archieved')){
		$arc = ' OR '.db_prefix() . 'idea_hub_challenges.status = "archived" ';
	}else{
		$arc = '';
	}
	array_push($where, 'AND (' . db_prefix() . 'idea_hub_challenges.status != "archived" '.$arc.')');
	if(!has_permission('idea_hub', '', 'view')){
		array_push($where, 'AND ' .db_prefix(). 'idea_hub_challenges.user_id = '.get_staff_user_id());
	}
}
if(!empty($cat_ids_arr)){
    array_push($where, 'AND '.$sTable.'.category_id IN('.$cat_ids_arr.')');
}

$result = prepare_grid_query($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'idea_hub_challenges.id',db_prefix() . 'idea_hub_category.name',db_prefix() . 'idea_hub_category.color']);

$output  = $result['output'];
$rResult = $result['rResult'];
$prevPage = (($draw - 1) < 0) ? 0 : ($draw-1);
$nextPage = $draw + 1;
$nxtStart = ($start +1 ) * $length;
$prevStart = ($start -1 ) * $length;
$this->load->library('pagination');

$config['base_url'] = admin_url('idea_hub/');
$config['total_rows'] = $output['iTotalDisplayRecords'];
$config['per_page'] = $length;
$config['use_page_numbers'] = TRUE;
$config['full_tag_open'] = "<ul class='pagination pagination-sm pull-right'>";
$config['full_tag_close'] ="</ul>";
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='javascript:;'>";
$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
$config['next_tag_open'] = "<li>";
$config['next_tagl_close'] = "</li>";
$config['prev_tag_open'] = "<li>";
$config['prev_tagl_close'] = "</li>";
$config['first_tag_open'] = "<li>";
$config['first_tagl_close'] = "</li>";
$config['last_tag_open'] = "<li>";
$config['last_tagl_close'] = "</li>";
$config['attributes'] = array('class' => 'paginate');
$this->pagination->initialize($config);
?>
<div class="row">
	<?php
    if($output['iTotalDisplayRecords'] > 0){
       foreach($rResult as $aRow) {
        $deadline = convert_to_days_hrs_min_sec($aRow['deadline']); ?>
        <div class="col-lg-4 col-sm-6 m_landscape span4">
            <div class="panel_s">
                <div class="panel-body panel-color ad_new_cl">
                    <div class="img_card_ih">
                        <h5 class="cat_cl_grid">
                            <span style="background-color: <?= $aRow['color']; ?>" title="<?= _l('category'); ?>"><?= $aRow['name']; ?></span>
                        </h5>
                        <div class="wrap_img_sec_grid">
                            <div class="actn_edit">
                                <?php if(is_admin() || (has_permission('idea_hub', '', 'delete') && get_staff_user_id() == $aRow['user_id'])) {
                                    ?>
                                    <div class="wrap_actn_b">
                                        <a class="trash_btn_c _delete" href="<?= admin_url('idea_hub/delete_challenge/'. $aRow['id']);?>">
                                            <span><i class="fa fa-trash-o" aria-hidden="true"></i> </span> <?= _l('delete');?> </a>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if(is_admin() || (has_permission('idea_hub', '', 'edit') && get_staff_user_id() == $aRow['user_id'])) {
                                        ?>
                                        <div class="wrap_actn_b">
                                            <a class="pencil_btn_c" href="<?= admin_url('idea_hub/challenge/' . $aRow['id']);?>">
                                                <span><i class="fa fa-pencil" aria-hidden="true"></i></span> <?= _l('edit');?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="rght_img_cl">
                                    <?php if(isset($aRow['cover_image']) && !empty($aRow['cover_image'])){ ?>
                                        <img src="<?php echo base_url('modules/idea_hub/uploads/challenges/'.$aRow['cover_image']); ?>">
                                    <?php }else{ ?>
                                        <img src="<?php echo base_url('modules/idea_hub/assets/img/bg-img.jpg'); ?>">
                                    <?php } ?>

                                    <div class="name_ls_s countdown" data-challenge="<?php echo $aRow['id']; ?>" data-status="<?php echo $aRow['status']; ?>">
                                     <div class="timeing_heading">
                                        <p class="running_phase <?php echo $aRow['status'] == 'inactive' ? 'hidden' : ''?>">
                                            <?= _l('remaining_time');?> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        </p>
                                        <p class="end_phase <?php echo $aRow['status'] != 'inactive' ? 'hidden' : ''?>">
                                            <?= _l('no_left_time');?> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        </p>
                                    </div>

                                    <div class="phase_running">
                                        <div class="w_timeing_d">
                                            <h3 class="days"><?= !empty($deadline) ? $deadline['days'] : '0';?></h3>
                                            <h5>DAYS</h5>
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
                                            <h5>MINS</h5>
                                        </div>
                                        <span class="dot_cl_ms">:</span>
                                        <div class="w_timeing_d">
                                            <h3 class="seconds"><?= !empty($deadline) ? $deadline['secs'] : '0';?></h3>
                                            <h5>SECS</h5>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="user_img_name">
                                <div>
                                  <?php echo staff_profile_image($aRow['user_id'], array('img', 'img-responsive')); ?>
                              </div>
                              <div>
                                  <?php $oStaff = $CI->staff_model->get($aRow['user_id']); ?>
                                  <h5><?php echo $oStaff->firstname.' '. $oStaff->lastname; ?></h5>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card_ih_text_data">
                    <?php if($aRow['status'] == 'inactive' && get_staff_user_id() != $aRow['user_id']){?>
                        <div class="ch_watermark_status">
                            <p><?php echo $aRow['status'];?></p>
                        </div>
                    <?php } ?>
                    <a href="<?=admin_url('idea_hub/challenge_detail/'.$aRow['id']);?>">
                        <h4><?= $aRow['title']; ?></h4>
                    </a>
                    <p>
                        <?= read_more(array('str'=>$aRow['description'], 'len'=>90),admin_url('idea_hub/challenge_detail/'.$aRow['id'])); ?>
                    </p>
                    <a href="<?php echo admin_url('idea_hub/ideas/' . $aRow['id']); ?>" class="btn view_challenge_cl">
                        <i class="fa fa-trophy menu-icon"></i> <?= _l('view_challenge');?>
                    </a>
                    <?php 
                    $thumbs    = challenge_votes_count($aRow['id']); 
                    $my_thumbs = current_user_challenge_votes($aRow['id']);
                    ?>
                    <div class="icon_make_c">
                        <div>
                            <i class="fa fa-lightbulb-o"></i>
                            <span><?=ideas_count_by_challenge_id($aRow['id']);?></span>
                        </div>
                        <div class="thumbs-up-div" data-challenge_id="<?= $aRow['id']; ?>" data-voted="<?= $my_thumbs && $my_thumbs == 'up' ? 1 : 0 ;?>">
                            <i class="fa fa-thumbs-up" aria-hidden="true" style="<?= $my_thumbs && $my_thumbs == 'up' ? 'color:#84f7a2;' : 'cursor:pointer;' ;?>"></i>
                            <span><?= isset($thumbs) && $thumbs['up'] ? $thumbs['up'] : '0' ;?></span>
                        </div>
                        <div class="thumbs-down-div" data-challenge_id="<?= $aRow['id']; ?>" data-voted="<?= $my_thumbs && $my_thumbs == 'down' ? 1 : 0 ;?>">
                            <i class="fa fa-thumbs-down" aria-hidden="true" style="<?= $my_thumbs && $my_thumbs == 'down' ? 'color:#da3737;' : 'cursor:pointer;' ;?>"></i>
                            <span><?= isset($thumbs) && $thumbs['down'] ? $thumbs['down'] : '0' ;?></span>
                        </div>
                        <div>
                            <i class="fa fa-comment" aria-hidden="true"></i>
                            <span><?=ideas_comments_by_challenge_id($aRow['id']);?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php }else{ ?>
    <div class="col-md-12">
      <div class="cardbox text-center dataTables_empty" style="border: none">
        <p>No entries found</p>
    </div>
</div>
<?php } ?>
</div>
<div class="row">
  <div style='margin-top: 10px;' id='pagination'>
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>
<script type="text/javascript">
 "use strict";
 $(document).ready(function () {
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
    $(".thumbs-up-div").on("click", function (t) {
        if (0 == $(this).attr("data-voted")) {
            parseInt($(this).find("span").text());
            var i = $(this).attr("data-challenge_id"),
            s = $(this);
            $.post(admin_url + "idea_hub/challengeVote", { thumb_value: "up", challenge_id: i }, function (t) {
                let i = JSON.parse(t);
                s.attr("data-voted", "1"),
                s.find("i").css("color", "#84f7a2"),
                s.find("i").css("cursor", ""),
                s.find("span").text(i.up),
                s.siblings(".thumbs-down-div").find("span").text(i.down),
                s.siblings(".thumbs-down-div").find("i").css("color", ""),
                s.siblings(".thumbs-down-div").attr("data-voted", "0"),
                s.siblings(".thumbs-down-div").find("i").css("cursor", "pointer");
            });
        } else t.stopPropagation();
    });
    $(".thumbs-down-div").on("click", function (t) {
        if (0 == $(this).attr("data-voted")) {
            parseInt($(this).find("span").text());
            var i = $(this).attr("data-challenge_id"),
            s = $(this);
            $.post(admin_url + "idea_hub/challengeVote", { thumb_value: "down", challenge_id: i }, function (t) {
                let i = JSON.parse(t);
                s.attr("data-voted", "1"),
                s.find("i").css("color", "#da3737"),
                s.find("i").css("cursor", ""),
                s.find("span").text(i.down),
                s.siblings(".thumbs-up-div").find("span").text(i.up),
                s.siblings(".thumbs-up-div").find("i").css("color", ""),
                s.siblings(".thumbs-up-div").attr("data-voted", "0"),
                s.siblings(".thumbs-up-div").find("i").css("cursor", "pointer");
            });
        } else t.stopPropagation();
    });
});

</script>