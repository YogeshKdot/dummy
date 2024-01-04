<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
    <div class="row">
  
    <div class="panel_s col-md-12">
    <div class="panel-body">
    <div class="horizontal-scrollable-tabs">
        <nav>
            <ul class="nav nav-tabs m-bot-0" id="myTab" role="tablist">
            <?php
            $i = 0;
            foreach($tab as $group){
              ?>
              <li <?php if($i == 0){echo " class='active'"; } ?>>
              <a href="<?php echo admin_url('loyalty/configruration?group='.$group); ?>" data-group="<?php echo html_entity_decode($group); ?>">
               <?php echo _l($group); ?></a>
              </li>
              <?php $i++; } ?>
            </ul>
        </nav>
      </div>
    </div>  
  </div>
 
  <div class="col-md-12">
    <div class="panel_s">
     <div class="panel-body">

        <?php $this->load->view($tabs['view']); ?>
        
     </div>
  </div>
</div>
<div class="clearfix"></div>
</div>
<?php echo form_close(); ?>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>
</body>
</html>
