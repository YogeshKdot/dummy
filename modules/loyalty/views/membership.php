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
              <li class="active">
              <a href="<?php echo admin_url('loyalty/membership?group=membership_rule'); ?>" data-group="membership_rule"><?php echo _l('membership_rule'); ?></a>
              </li>

              <li class="">
              <a href="<?php echo admin_url('loyalty/membership?group=membership_program'); ?>" data-group="membership_program"><?php echo _l('membership_program'); ?></a>
              </li>
            </ul>
        </nav>
      </div>
      <div class="clearfix mtop10"></div>
      <?php $this->load->view($group.'/manage'); ?>
    </div>  
  </div>

<div class="clearfix"></div>
</div>

</div>
</div>
<?php init_tail(); ?>
</body>
</html>
