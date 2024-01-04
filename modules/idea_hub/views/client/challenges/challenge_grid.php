<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI       = & get_instance();
$start    = intval($CI->input->post('start'));
$length   = intval($CI->input->post('length'));
$draw     = intval($CI->input->post('draw'));
$cat_ids_arr = $CI->input->post('cat_ids_arr');
$aColumns = [
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
$where = [
  'WHERE ' . db_prefix() . 'idea_hub_challenges.status="active"',
];
if(!$CI->input->post('include_archieved')){
    array_push($where, 'AND '.$sTable.'.status != "archived"');
}
if(!empty($cat_ids_arr)){
    array_push($where, 'AND '.$sTable.'.category_id IN('.$cat_ids_arr.')');
}

$result = prepare_grid_query($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'idea_hub_challenges.id',db_prefix() . 'idea_hub_category.name',db_prefix() . 'idea_hub_category.color']);

$output  = $result['output'];
$rResult = $result['rResult'];
$prevPage = (($draw - 1) < 0) ? 0 : ($draw-1);
$nextPage = $draw + 1;
$nxtStart = ($start +1 ) * $length; //($draw <= 2)?$length:($draw - 1) * $length;
$prevStart = ($start -1 ) * $length; //(($draw - 1) >= 0)?(($draw - 1) * $length):0;
$this->load->library('pagination');

$config['base_url'] = base_url('idea_hub/client_challenges/');
$config['total_rows'] = $output['iTotalDisplayRecords'];
$config['per_page'] = $length;
$config['use_page_numbers'] = TRUE;
$config['full_tag_open'] = "<ul class='pagination pagination-sm pull-right' style='position:relative; top:-25px;'>";
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
$config["uri_segment"] = 4;
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
                        <span style="background-color: <?= $aRow['color']; ?>"><?= $aRow['name']; ?></span>
                    </h5>
                    
                    <div class="wrap_img_sec_grid">
                        
                        <div class="rght_img_cl">
                            <?php if(isset($aRow['cover_image']) && !empty($aRow['cover_image'])){ ?>
                                <img src="<?php echo base_url('modules/idea_hub/uploads/challenges/'.$aRow['cover_image']); ?>">
                            <?php }else{ ?>
                                <img src="<?php echo base_url('modules/idea_hub/assets/img/bg-img.jpg'); ?>">
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
                                    <h3 class="days">2 </h3>
                                    <h5>DAYS</h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="hours">
                                        18
                                    </h3>
                                    <h5>HOUR</h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="minutes">5</h3>
                                    <h5>MINS</h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="seconds">32</h3>
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
            
            <a href="<?=base_url('idea_hub/client_ideas/'.$aRow['id']);?>">
                <h4><?= $aRow['title']; ?></h4>
            </a>
            <p>
                <?= read_more(array('str'=>$aRow['description'], 'len'=>90),base_url('idea_hub/client_ideas/'.$aRow['id'])); ?>
            </p>
            <a href="<?php echo base_url('idea_hub/client_ideas/' . $aRow['id']); ?>" class="btn view_challenge_cl">
                <i class="fa fa-trophy menu-icon"></i> View challenge
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
   $(document).ready(function(){
      $(".countdown").each(function() {
        var theDaysBox = $(this).find(".days");
        var theHoursBox = $(this).find(".hours");
        var theMinsBox = $(this).find(".minutes");
        var theSecsBox = $(this).find(".seconds");
        var phase_running = $(this).find(".running_phase");
        var phase_ended = $(this).find(".end_phase");

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
              phase_running.addClass('hidden');
              phase_ended.removeClass('hidden');
          }
      }
  },
  1000);
    });

      $(".thumbs-up-div").on('click',function(e){
          if($(this).attr('data-voted') == false){
            var prevThumbVal = parseInt($(this).find('span').text());
            var challenge_id = $(this).attr('data-challenge_id');
            var _this = $(this);
            $.post(admin_url+"idea_hub/challengeVote",{'thumb_value':'up', 'challenge_id':challenge_id},function(resp){
              let data = JSON.parse(resp);
              _this.attr('data-voted','1');
              _this.find('i').css('color','#84f7a2');
              _this.find('i').css('cursor','');
              _this.find('span').text(data.up);
              _this.siblings('.thumbs-down-div').find('span').text(data.down);
              _this.siblings('.thumbs-down-div').find('i').css('color','');
              _this.siblings('.thumbs-down-div').attr('data-voted','0');
              _this.siblings('.thumbs-down-div').find('i').css('cursor','pointer');
          })
        }else{
            e.stopPropagation();
        }
    })
      $(".thumbs-down-div").on('click',function(e){
          if($(this).attr('data-voted') == false){
            var prevThumbVal = parseInt($(this).find('span').text());
            var challenge_id = $(this).attr('data-challenge_id');
            var _this = $(this);
            $.post(admin_url+"idea_hub/challengeVote",{'thumb_value':'down', 'challenge_id':challenge_id},function(resp){
              let data = JSON.parse(resp);
              _this.attr('data-voted','1');
              _this.find('i').css('color','#da3737');
              _this.find('i').css('cursor','');
              _this.find('span').text(data.down);
              _this.siblings('.thumbs-up-div').find('span').text(data.up);
              _this.siblings('.thumbs-up-div').find('i').css('color','');
              _this.siblings('.thumbs-up-div').attr('data-voted','0')
              _this.siblings('.thumbs-up-div').find('i').css('cursor','pointer');

          })
        }else{
            e.stopPropagation();
        }
    })
  });
</script>