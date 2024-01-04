<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12>">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row _buttons">
                            <div class="col-md-8">
                                <h4 class=""><?php echo html_escape($title); ?></h4>
                            </div>
                            <div class="col-md-4">
                                <div class="btn-group pull-right">
                                    <?php
                                        $_data = '';

                                        $_data = '<a href="'.admin_url('zillapage/landingpages/builder/'. $item->code).'" class="btn btn-sm btn-success mright5">'._l('builder').'</a>';
                                        $_data .= '<a href="'.base_url('publish/'. $item->code).'" class="btn btn-sm btn-default" target="_blank">'._l('preview').'</a>';
                                        echo $_data;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />
                       <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'form_setting')) ;?>

                        <div class="horizontal-scrollable-tabs">
                            <div class="scroller scroller-left arrow-left"><i class="fa fa-angle-left"></i></div>
                            <div class="scroller scroller-right arrow-right"><i class="fa fa-angle-right"></i></div>
                            <div class="horizontal-tabs">
                                <ul class="nav nav-tabs nav-tabs-horizontal border-0" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_general" aria-controls="tab_general" role="tab" data-toggle="tab">
                                            <?php echo _l('general'); ?>
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tab_seo"  aria-controls="tab_seo" role="tab" data-toggle="tab">
                                            <?php echo _l('seo'); ?>
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tab_form_payment"  aria-controls="tab_form_payment" role="tab" data-toggle="tab">
                                            <?php echo _l('form'); ?>
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tab_custom_code"  aria-controls="tab_custom_code" role="tab" data-toggle="tab">
                                            <?php echo _l('custom_code'); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="tab_general">
                                        <?php $attrs = array('autofocus'=>true); ?>
                                        <?php $value = ($item->name); ?>
                                        <?php echo render_input('name','name',$value,'text',$attrs); ?>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" name="is_publish" id="is_publish" <?php if(isset($item)){if($item->is_publish == 1){echo 'checked';} } else {echo 'checked';} ?>>
                                            <label for="is_publish"><?php echo _l('publish'); ?></label>
                                        </div>
                                        <?php echo render_input('favicon','settings_general_favicon','','file'); ?>
                                        <?php if($item->favicon != ''){ ?>
                                            <img src="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $item->favicon); ?>" class="img img-responsive">
                                        <?php } ?>
                                    </div>
                                 
                                    <div role="tabpanel" class="tab-pane" id="tab_seo">

                                        <div class="alert alert-info">
                                             <span><?php echo _l('seo_description_note'); ?></span>
                                        </div>
                                        <?php $value = ($item->seo_title); ?>
                                        <?php echo render_input('seo_title','seo_title',$value,'text'); ?>
                                        <?php $value = ($item->seo_description); ?>
                                        <?php echo render_textarea('seo_description','seo_description',$value, ['rows' => 4]); ?>
                                        <?php $value = ($item->seo_keywords); ?>
                                        <?php echo render_textarea('seo_keywords','seo_keywords',$value, ['rows' => 4]); ?>

                                        <div class="alert alert-info">
                                             <span><?php echo _l('social_description_note'); ?></span>
                                        </div>
                                        <?php $value = ($item->social_title); ?>
                                        <?php echo render_input('social_title','social_title',$value,'text'); ?>
                                        <?php $value = ($item->social_description); ?>
                                        <?php echo render_textarea('social_description','social_description',$value, ['rows' => 4]); ?>

                                        <div class="form-group social_image">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <label for="favicon" class="control-label"><?php echo _l('social_image'); ?></label>
                                                    <small><?php echo _l('description_social_image'); ?></small>
                                                    <input type="file" name="social_image" class="form-control mbot10">
                                                    <?php if($item->social_image != ''){ ?>
                                                        <img src="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $item->social_image); ?>" class="img img-responsive">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="tab_form_payment">
                                         <div class="d-flex">
                                            <h4 class="title-tab-content pb-0"><?php echo _l('action_after_form_submission'); ?></h4>
                                        </div>
                                        
                                        <p class=""><?php echo _l('action_after_form_note'); ?></p>

                                        <div class="form-group row">
                                            <div class="col-md-8">
                                                <select name="type_form_submit" id="type_form_submit" class="form-control">
                                                    <option value="thank_you_page" <?php if($item->type_form_submit == 'thank_you_page') echo 'selected'; ?> >
                                                    <?php echo _l('go_to_default_thank_you_page'); ?></option>
                                                    <option value="url" <?php if($item->type_form_submit == 'url') echo 'selected'; ?> >
                                                        <?php echo _l('redirect_to_any_url'); ?></option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group row <?php if($item->type_form_submit == 'thank_you_page') echo 'd-none'; ?>" id="form_redirect_url">
                                            <div class="col-md-8">
                                                <label class="form-label"><?php echo _l('redirect_to'); ?>:</label>
                                                <input type="url" name="redirect_url" value="<?php echo $item->redirect_url ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-8">
                                            <?php
                                             $selected = '';
                                             foreach ($members as $staff) {
                                                 if (isset($item) && $item->responsible == $staff['staffid']) {
                                                     $selected = $staff['staffid'];
                                                 }
                                             }
                                             ?>
                                            <hr />
                                            <h4 class="title-tab-content pb-0"><?php echo _l('leads_import_assignee'); ?> & <?php echo _l('notification_settings'); ?></h4>
                                            <?php echo render_select('responsible', $members, array('staffid', array('firstname', 'lastname')), 'leads_import_assignee', $selected); ?>
                                            <div class="clearfix"></div>
                                            <div class="checkbox checkbox-primary">
                                              <input type="checkbox" name="notify_lead_imported" id="notify_lead_imported" <?php if (isset($item) && $item->notify_lead_imported == 1 || !isset($item)) {
                                              echo 'checked';
                                              } ?>>
                                              <label for="notify_lead_imported"><?php echo _l('leads_email_integration_notify_when_lead_imported'); ?></label>
                                            </div>
                                            <div class="select-notification-settings<?php if (isset($item) && $item->notify_lead_imported == '0') {
                                              echo ' hide';
                                              } ?>">
                                              <hr />
                                              <div class="radio radio-primary radio-inline">
                                                <input type="radio" name="notify_type" value="specific_staff" id="specific_staff" <?php if (isset($item) && $item->notify_type == 'specific_staff' || !isset($item)) {
                                                echo 'checked';
                                                } ?>>
                                                <label for="specific_staff"><?php echo _l('specific_staff_members'); ?></label>
                                              </div>
                                              <div class="radio radio-primary radio-inline">
                                                <input type="radio" name="notify_type" id="roles" value="roles" <?php if (isset($item) && $item->notify_type == 'roles') {
                                                echo 'checked';
                                                } ?>>
                                                <label for="roles"><?php echo _l('staff_with_roles'); ?></label>
                                              </div>
                                              <div class="radio radio-primary radio-inline">
                                                <input type="radio" name="notify_type" id="assigned" value="assigned" <?php if (isset($item) && $item->notify_type == 'assigned') {
                                                echo 'checked';
                                                } ?>>
                                                <label for="assigned"><?php echo _l('notify_assigned_user'); ?></label>
                                              </div>
                                              <div class="clearfix mtop15"></div>
                                              <div id="specific_staff_notify" class="<?php if (isset($item) && $item->notify_type != 'specific_staff') {
                                                echo 'hide';
                                                } ?>">
                                                <?php
                                                $selected = array();
                                                if (isset($item) && $item->notify_type == 'specific_staff') {
                                                $selected = unserialize($item->notify_ids);
                                                }
                                                ?>
                                                <?php echo render_select('notify_ids_staff[]', $members, array('staffid', array('firstname', 'lastname')), 'leads_email_integration_notify_staff', $selected, array('multiple'=>true)); ?>
                                              </div>
                                              <div id="role_notify" class="<?php if (isset($item) && $item->notify_type != 'roles' || !isset($item)) {
                                                echo 'hide';} ?>">
                                                <?php
                                                $selected = array();
                                                if (isset($item) && $item->notify_type == 'roles') {
                                                $selected = unserialize($item->notify_ids);
                                                }
                                                ?>
                                                <?php echo render_select('notify_ids_roles[]', $roles, array('roleid', array('name')), 'leads_email_integration_notify_roles', $selected, array('multiple'=>true)); ?>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="tab_custom_code">
                                        <div class="alert alert-info">
                                             <span><?php echo _l('custom_code_note'); ?></span>
                                        </div>

                                        <?php $value = ($item->custom_header); ?>
                                        <?php echo render_textarea('custom_header','custom_header',$value, ['rows' => 4]); ?>
                                        <?php $value = ($item->custom_footer); ?>
                                        <?php echo render_textarea('custom_footer','custom_footer',$value, ['rows' => 4]); ?>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/setting.js'); ?>"></script>
</body>
</html>