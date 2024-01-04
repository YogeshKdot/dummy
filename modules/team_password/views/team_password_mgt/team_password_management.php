<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
    <div class="row">  
      <div class="col-md-2">

            <div id="tree"></div> 
      </div>
	  <div class="col-md-10">
	    <div class="panel_s">
	     <div class="panel-body">

        <div class="horizontal-scrollable-tabs  mb-5">
      <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
      <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>

      <div class="horizontal-tabs mb-4">
        <ul class="nav nav-tabs nav-tabs-horizontal">
              <li <?php if($type == 'normal'){ echo 'class="active"'; } ?> >
              <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=normal'); ?>" data-group="profile">
               <i class="fa fa-user-circle menu-icon"></i><?php echo _l('normal'); ?></a>
              </li>
              <li <?php if($type == 'bank_account'){ echo 'class="active"'; } ?> >
              <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=bank_account'); ?>" data-group="contacts">
               <i class="fa fa-university menu-icon"></i><?php echo _l('bank_account'); ?></a>
              </li>
              <li <?php if($type == 'credit_card'){ echo 'class="active"'; } ?> >
              <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=credit_card'); ?>" data-group="notes">
               <i class="fa fa-credit-card menu-icon"></i><?php echo _l('credit_card'); ?></a>
              </li>
              <li <?php if($type == 'email'){ echo 'class="active"'; } ?> >
              <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=email'); ?>" data-group="reminders">
               <i class="fa fa-envelope menu-icon"></i><?php echo _l('email'); ?></a>
              </li>
              <li <?php if($type == 'server'){ echo 'class="active"'; } ?> >
              <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=server'); ?>" data-group="attachments">
               <i class="fa fa-server menu-icon"></i><?php echo _l('server'); ?></a>
              </li>
              <li class="<?php if($type == 'software_license'){ echo 'active'; } ?>" >
              <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=software_license'); ?>" data-group="attachments">
               <i class="fa fa-pagelines menu-icon"></i><?php echo _l('software_license'); ?></a>
              </li>

              <?php if(has_permission('team_password','','view') || is_admin()){ ?>
                <li class="<?php if($type == 'permission'){ echo 'active'; } ?>" >
                  <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=permission'); ?>" data-group="permission">
                  <i class="fa fa-check menu-icon"></i><?php echo _l('permission'); ?></a>
                </li>

                <li class="<?php if($type == 'share'){ echo 'active'; } ?>" >
                  <a href="<?php echo admin_url('team_password/team_password_mgt?cate='.$cate.'&type=share'); ?>" data-group="share">
                  <i class="fa fa-share-alt menu-icon"></i><?php echo _l('share'); ?></a>
                </li>
              <?php } ?>
            </ul>
        </div>
		    <div class="horizontal-scrollable-tabs  mb-5">
		      
		          <?php 
		              $this->load->view('team_password_mgt/'.$type);                      
		          ?>
        </div>
		    </div>
	     </div>
		</div>
		<div class="clearfix"></div>
	  </div>
	<?php echo form_close(); ?>
  <div class="btn-bottom-pusher"></div>
  </div>
 </div>
</div>
<div class="modal fade" id="share" tabindex="-1" role="dialog">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">
                  <span class="add-title"><?php echo _l('add_share'); ?></span>
                  <span class="update-title"><?php echo _l('update_share'); ?></span>
              </h4>
          </div>
      <?php echo form_open(admin_url('team_password/add_share_by_cate/'.$cate.'/'.$type),array('id'=>'share')); ?>             
          <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <?php echo form_hidden('shareid',''); ?>
                    <div class="checkbox">              
                      <input type="checkbox" class="capability" name="not_in_the_system" onchange="open_frame(this);" value="on">
                      <label><?php echo _l('not_in_the_system'); ?></label>
                    </div>
            </div>
                  <div class="col-md-12 client_fr">               
                    <div class="form-group">
                          <label for="creator" class="control-label"><?php echo _l('client'); ?></label>
                          <select name="client" class="selectpicker" id="client" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
                            <option value="" ></option>
                                <?php foreach($contact as $s){ ?>
                                  <option value="<?php echo html_entity_decode($s['email']); ?>" ><?php echo get_company_name(get_user_id_by_contact_id($s['id'])).' - '.html_entity_decode($s['lastname']).' '.html_entity_decode($s['firstname']); ?></option>
                             <?php } ?>
                            </select> 
                      </div> 
                </div>
                <div class="col-md-12 email_fr hide">
                  <?php echo render_input('email','email'); ?>
                </div>
                <div class="col-md-12">
                  <?php echo render_datetime_input('effective_time','effective_time','',array('required' => true)); ?>
                </div>
                <div class="col-md-12">
                    <label for="creator" class="control-label"><?php echo html_entity_decode(_l('permission')); ?></label>
                    <div class="col-md-12">
                    <div class="checkbox">              
                      <input type="checkbox" class="capability" name="read" value="on">
                      <label><?php echo html_entity_decode(_l('read')); ?></label>
                </div>
                <div class="checkbox">              
                      <input type="checkbox" class="capability" name="write" value="on">
                      <label><?php echo html_entity_decode(_l('write')); ?></label>
                </div>
            </div>
                  </div>
                  <div class="clearfix"></div>
            </div>
          </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
          <?php echo form_close(); ?>                 
        </div>
      </div>
  </div>

 <div class="modal fade" id="permission" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">
                  <span class="add-title"><?php echo _l('add_permission'); ?></span>
                  <span class="update-title"><?php echo _l('update_permission'); ?></span>
              </h4>
          </div>
      <?php echo form_open(admin_url('team_password/add_permission_by_cate/'.$cate.'/'.$type),array('id'=>'permission')); ?>             
          <div class="modal-body">
          <div class="row">
                  <div class="col-md-12">
                    <?php echo form_hidden('id',''); ?>
                    <div class="form-group">
                          <label for="creator" class="control-label"><?php echo html_entity_decode(_l('staff')); ?></label>
                          <select name="staff[]" class="selectpicker" id="patient_id" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" multiple required > 
                            
                                <?php foreach($staffs as $s){ ?>
                                  <option value="<?php echo html_entity_decode($s['staffid']); ?>" ><?php echo html_entity_decode($s['lastname']).' '.html_entity_decode($s['firstname']); ?></option>
                             <?php } ?>
                            </select> 
                      </div> 
                </div>
                  <div class="col-md-12">
                    <label for="creator" class="control-label"><?php echo html_entity_decode(_l('permission')); ?></label>
                    <div class="col-md-12">
                    <div class="checkbox">              
                      <input type="checkbox" class="capability" name="read" value="on">
                      <label><?php echo html_entity_decode(_l('read')); ?></label>
                </div>
                <div class="checkbox">              
                      <input type="checkbox" class="capability" name="write" value="on">
                      <label><?php echo html_entity_decode(_l('write')); ?></label>
                </div>
            </div>
                  </div>


                  <div class="clearfix"></div>
            </div>
          </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_entity_decode(_l('close')); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
          <?php echo form_close(); ?>                 
        </div>
      </div>
  </div>

<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/team_password/assets/js/team_password_mgt_js.php';?>
<?php if($type == 'normal'){ ?>
  <?php require 'modules/team_password/assets/js/normal/normal_js.php';?>
<?php } ?>

<?php if($type == 'bank_account'){ ?>
  <?php require 'modules/team_password/assets/js/bank_account/bank_account_js.php';?>
<?php } ?>

<?php if($type == 'credit_card'){ ?>
  <?php require 'modules/team_password/assets/js/credit_card/credit_card_js.php';?>
<?php } ?>

<?php if($type == 'email'){ ?>
  <?php require 'modules/team_password/assets/js/email/email_js.php';?>
<?php } ?>

<?php if($type == 'server'){ ?>
  <?php require 'modules/team_password/assets/js/server/server_js.php';?>
<?php } ?>

<?php if($type == 'software_license'){ ?>
  <?php require 'modules/team_password/assets/js/software_license/software_license_js.php';?>
<?php } ?>