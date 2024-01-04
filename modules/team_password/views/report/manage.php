<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
   <div class="clearfix"></div><br>
   <div class="col-md-12">
    <h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo html_entity_decode($title); ?></h4>
   </div>
   <div class="col-md-12">
            <div class="wrapper_colors">
              <ul class="nav nav-tabs list_colors li1" id="myTab">
                  <li <?php if(($tab == '')||($tab == 'permission_report')){ echo 'class="active"'; } ?>>
                     <a href="<?php echo admin_url('team_password/report?tab=permission_report'); ?>" aria-controls="medical_review" role="tab">
                        <i class="fa fa-check" aria-hidden="true"></i>&nbsp;<?php echo _l('permission_statistical') ?>
                     </a>
                  </li>

                  <li <?php if($tab == 'share_report'){ echo 'class="active"'; } ?>>
                     <a href="<?php echo admin_url('team_password/report?tab=share_report'); ?>" aria-controls="share_report" role="tab"><i class="fa fa-share-alt">
                          </i>&nbsp;<?php echo _l('share_statistical') ?>
                     </a>
                  </li>
                 
                </ul>
          </div>
     </div>
      <?php

        if(($tab == '')||($tab == 'permission_report')){ 
          $this->load->view('report/permission_report');
        }
        if($tab == 'share_report'){ 
          $this->load->view('report/share_report');
        }

       ?>
  </div>
 </div>
</div>

<?php init_tail(); ?>
</body>
</html>

