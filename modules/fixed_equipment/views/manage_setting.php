<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-3">

        <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
          <li
          <?php if($tab == 'depreciations'){echo " class='active'"; } ?>>
          <a href="<?php echo admin_url('fixed_equipment/settings?tab=depreciations'); ?>">
            <?php echo _l('fe_depreciations'); ?>
          </a>
        </li>
        <li
        <?php if($tab == 'suppliers'){echo " class='active'"; } ?>>
        <a href="<?php echo admin_url('fixed_equipment/settings?tab=suppliers'); ?>">
          <?php echo _l('fe_suppliers'); ?>
        </a>
      </li>
      <li
      <?php if($tab == 'asset_manufacturers'){echo " class='active'"; } ?>>
      <a href="<?php echo admin_url('fixed_equipment/settings?tab=asset_manufacturers'); ?>">
        <?php echo _l('fe_asset_manufacturers'); ?>
      </a>
    </li>

    <li
    <?php if($tab == 'categories'){echo " class='active'"; } ?>>
    <a href="<?php echo admin_url('fixed_equipment/settings?tab=categories'); ?>">
      <?php echo _l('fe_categories'); ?>
    </a>
  </li>

  <li
  <?php if($tab == 'models'){echo " class='active'"; } ?>>
  <a href="<?php echo admin_url('fixed_equipment/settings?tab=models'); ?>">
    <?php echo _l('fe_models'); ?>
  </a>
</li>

<li
<?php if($tab == 'status_labels'){echo " class='active'"; } ?>>
<a href="<?php echo admin_url('fixed_equipment/settings?tab=status_labels'); ?>">
  <?php echo _l('fe_status_labels'); ?>
</a>
</li>

<li
<?php if($tab == 'approval_settings'){echo " class='active'"; } ?>>
<a href="<?php echo admin_url('fixed_equipment/settings?tab=approval_settings'); ?>">
  <?php echo _l('fe_approval_settings'); ?>
</a>
</li>
<li
<?php if($tab == 'custom_field'){echo " class='active'"; } ?>>
<a href="<?php echo admin_url('fixed_equipment/settings?tab=custom_field'); ?>">
  <?php echo _l('fe_custom_field'); ?>
</a>
</li>
<li
<?php if($tab == 'permission'){echo " class='active'"; } ?>>
<a href="<?php echo admin_url('fixed_equipment/settings?tab=permission'); ?>">
  <?php echo _l('fe_permission'); ?>
</a>
</li>
<li
<?php if($tab == 'other_setting'){echo " class='active'"; } ?>>
<a href="<?php echo admin_url('fixed_equipment/settings?tab=other_setting'); ?>">
  <?php echo _l('fe_other_setting'); ?>
</a>
</li>
</ul>
</div>

<div class="col-md-9">
  <div class="panel_s">
    <div class="panel-body">
      <?php $this->load->view('settings/'.$tab); ?>  
    </div>
  </div>
</div>


<div class="clearfix"></div>
</div>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>
</body>
</html>

