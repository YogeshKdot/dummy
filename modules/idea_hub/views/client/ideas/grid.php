<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI     = & get_instance();
$start  = intval($CI->input->post('start'));
$length = intval($CI->input->post('length'));
$draw   = intval($CI->input->post('draw'));
$cat_ids_arr = $CI->input->post('cat_ids_arr');
$aColumns = [
  db_prefix().'idea_hub_ideas.id as idea_id',
  db_prefix().'idea_hub_ideas.user_id as user_id',
  db_prefix().'idea_hub_ideas.title as title',
  'image',
  db_prefix().'idea_hub_ideas.description as description',
  'deadline',
  'status_id',
  db_prefix().'idea_hub_ideas.added_at'
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'idea_hub_ideas';
$join = [
			'LEFT JOIN ' . db_prefix() . 'idea_hub_challenges ON ' . db_prefix() . 'idea_hub_ideas.challenge_id = ' . db_prefix() . 'idea_hub_challenges.id',
			'LEFT JOIN ' . db_prefix() . 'idea_hub_status ON ' . db_prefix() . 'idea_hub_ideas.status_id = ' . db_prefix() . 'idea_hub_status.id',
			'LEFT JOIN ' . db_prefix() . 'idea_hub_ideas_visibility ON ' . db_prefix() . 'idea_hub_ideas_visibility.idea_id = ' . db_prefix() . 'idea_hub_ideas.id',
		];
$where = [];
$login_user_id = get_client_user_id();
array_push($where, 'AND challenge_id = ' . $challenge_id);
array_push($where, 'AND ' . db_prefix() . 'idea_hub_ideas_visibility.customer_id = '.$login_user_id);
array_push($where, 'AND ' .db_prefix(). 'idea_hub_ideas.visibility ="custom"');
if(!empty($cat_ids_arr)) {
  array_push($where, 'AND '.db_prefix().'idea_hub_challenges.category_id IN ('.$cat_ids_arr.')');
}
$result = prepare_grid_query($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix().'idea_hub_status.name as status_name', db_prefix().'idea_hub_status.color as status_color']);
$output  = $result['output'];
$rResult = $result['rResult'];
$prevPage = (($draw - 1) < 0) ? 0 : ($draw-1);
$nextPage = $draw + 1;
$nxtStart = ($start +1 ) * $length;
$prevStart = ($start -1 ) * $length;
$this->load->library('pagination');
$config['base_url'] = base_url('idea_hub/client_ideas/ideas_grid/'.$challenge_id);
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
$CI->load->model('staff_model');
?>

<style type="text/css">
  .idea_ra span{padding: 6px 12px;color: white;font-weight: 400;font-size: 15px; border-radius: 2px;}
</style>
  <div id="ih-grid-view" class="container-fluid">
    <div class="row">
      <?php
      if($output['iTotalDisplayRecords'] > 0){
        foreach ($rResult as $aRow) {
          $deadline = convert_to_days_hrs_min_sec($aRow['deadline']);
          $hrefAttr = admin_url('idea_hub/idea_detail/' . $aRow['idea_id']);
            $idea_meta_data['ranking'] = $CI->idea_hub_model->get_idea_total_rank($aRow['idea_id']); 
           $idea_meta_data['comments'] = $CI->idea_hub_model->get_idea_comment_count($aRow['idea_id']); 
              $color = '';
              if($idea_meta_data['ranking'] >= 1 && $idea_meta_data['ranking'] <= 10){
               $color = '#ee4b33';
              }elseif ($idea_meta_data['ranking'] >= 11 && $idea_meta_data['ranking'] <= 20) {
                $color = '#ed7c04';
              }elseif ($idea_meta_data['ranking'] >= 21 && $idea_meta_data['ranking'] <= 30) {
                $color = '#f3c902';
              }elseif ($idea_meta_data['ranking'] >= 31 && $idea_meta_data['ranking'] <= 40) {
                $color = '#ece424';
              }elseif ($idea_meta_data['ranking'] >= 41 && $idea_meta_data['ranking'] <= 50) {
                $color = '#d2ea22';
              }elseif ($idea_meta_data['ranking'] >= 51 && $idea_meta_data['ranking'] <= 60) {
                $color = '#1acb6e';
              }elseif ($idea_meta_data['ranking'] >= 61 && $idea_meta_data['ranking'] <= 70) {
                $color = '#11a658';
              }else{
                $color = '#bfbfbf';
                $idea_meta_data['ranking'] = 0;
              }
             // echo '<pre>'; print_r($idea_meta_data); die;
              $oStaff = $CI->staff_model->get($aRow['user_id']);
                 
            ?>
          

        <div class="col-lg-4 col-sm-6 m_landscape span4">
            <div class="panel_s">
                <div class="panel-body panel-color ad_new_cl">
                    <div class="img_card_ih">
                      
                        <div class="points_cl">
                            <span><?= $idea_meta_data['ranking'];?></span>
                            <p>Points</p>
                        </div>
                      
                       <h5 class="cat_cl_grid">
                            <span style="background-color: <?= $aRow['status_color'];?> "><?= $aRow['status_name'];?></span>
                        </h5>
                        
                       <div class="wrap_img_sec_grid">
                        <div class="actn_edit">
                           
                        </div>
                        <div class="rght_img_cl">
                          <?php
                          if(isset($aRow['image']) && !empty($aRow['image']))
                          $type = getFileMimeType(FCPATH .'modules/idea_hub/uploads/ideas/'.$aRow['image']);
                          if(isset($aRow['image']) && !empty($aRow['image']) && $type == 'image'){ 
                            ?>
                            <img src="<?php echo base_url('modules/idea_hub/uploads/ideas/'.$aRow['image']); ?>" class="img-responsive user_k" alt="Image">
                          <?php } elseif(isset($aRow['image']) && !empty($aRow['image']) && $type == 'video') { ?>
                            <video class="img-responsive user_k" autoplay muted>
                              <source src="<?php echo base_url('modules/idea_hub/uploads/ideas/'.$aRow['image']); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                              </video>
                            <?php }else{ ?>
                            <img src="<?php echo base_url('modules/idea_hub/assets/img/bg-img');?>.jpg">
                            <?php } ?>
                            <div class="name_ls_s countdown" data-challenge="2">
                             <div class="timeing_heading">
                                    <p class="running_phase">
                                        <?= _l('remaining_time'); ?> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    </p>
                                    <p class="end_phase hidden">
                                        <?= _l('no_left_time'); ?> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    </p>
                                </div>
                               
                            <div class="phase_running">
                               
                                <div class="w_timeing_d">
                                    <h3 class="days"><?= !empty($deadline) ? $deadline['days'] : '0';?> </h3>
                                    <h5><?= _l('ih_days'); ?></h5>
                                </div>
                                 <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="hours">
                                        <?= !empty($deadline) ? $deadline['hrs'] : '0';?>
                                    </h3>
                                    <h5><?= _l('ih_days'); ?></h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="minutes"><?= !empty($deadline) ? $deadline['mins'] : '0';?></h3>
                                    <h5><?= _l('ih_min'); ?></h5>
                                </div>
                                <span class="dot_cl_ms">:</span>
                                <div class="w_timeing_d">
                                    <h3 class="seconds"><?= !empty($deadline) ? $deadline['secs'] : '0';?></h3>
                                    <h5><?= _l('ih_sec'); ?></h5>
                                </div>
                            </div>
                        </div>
                            
                        </div>
                        <div class="user_img_name">
                          <div>
                              <?php echo staff_profile_image($aRow['user_id'], array('img', 'img-responsive')); ?>
                          </div>
                          <div>
                              <b><?php echo $oStaff->firstname.' '. $oStaff->lastname; ?></b>
                          </div>
                        </div>
                        </div>
                    </div>
                    <div class="card_ih_text_data">
                        
                        <a href="<?php echo base_url('idea_hub/idea_detail/'.$aRow['idea_id']); ?>">
                            <h4><?= $aRow['title'];?></h4>
                        </a>
                        <p>
                            <?= generate_read_more_link(array('str'=>$aRow['description'], 'len'=>110), $hrefAttr); ?>
                        </p>
                        <a href="<?php echo base_url('idea_hub/client_ideas/idea_detail/'.$aRow['idea_id']); ?>" class="btn view_challenge_cl">
                            <i class="fa fa-trophy menu-icon"></i> <?= _l('view_idea');?>
                        </a>
                        <div class="icon_make_c new_pro_cl">
                            <div>
                                <i class="fa fa-comment" aria-hidden="true"></i>
                                <span><?= $idea_meta_data['comments'];?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
           <?php } ?>
  </div>
<?php 
} else { ?>
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
  $(document).ready(function(){
    $(".countdown").each(function() {
      var theDaysBox = $(this).find(".days");
      var theHoursBox = $(this).find(".hours");
      var theMinsBox = $(this).find(".minutes");
      var theSecsBox = $(this).find(".seconds");
      var phase_running = $(this).find(".running_phase");
      var phase_ended = $(this).find(".end_phase");
      var challenge_id = $(this).attr('data-challenge');
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
  });
</script>