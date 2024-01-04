<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
  <div class="row">
   <div class="col-md-12">
    <div class="panel_s">
     <div class="panel-body">
      <div class="row">
       <div class="col-md-12">
        <h4 class="font-bold pull-left"><?php echo _l($title); ?></h4>
        <a class="btn btn-default pull-right no-margin" href="<?php echo admin_url('fixed_equipment/audit_managements'); ?>">
          <?php echo _l('fe_back'); ?>
        </a>
        <div class="clearfix"></div>
        <hr />
      </div>
    </div>
    <?php echo form_open(admin_url('fixed_equipment/create_audit_request'),array('id'=>'create_audit_request-form')) ?>

    <div class="row">
      <div class="col-md-6">
        <table class="table">
          <tbody>
            <tr>
              <td><?php echo _l('fe_audit_date'); ?>:</td>
              <td><?php echo _d(date('Y-m-d',strtotime($audit->audit_date))); ?></td>
            </tr>
            <tr>
              <td><?php echo _l('fe_auditor'); ?>:</td>
              <td><?php echo get_staff_full_name($audit->auditor); ?></td>
            </tr>
            <tr>
              <td><?php echo _l('fe_created_at'); ?>:</td>
              <td><?php echo _d($audit->date_creator); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-6">
       <table class="table">
        <tbody>
          <tr>
            <td><?php echo _l('fe_locations'); ?>:</td>
            <td><?php
            $location = '';
            $data_location = $this->fixed_equipment_model->get_locations($audit->asset_location);
            if($data_location){
              $location = $data_location->location_name;
            }
            echo html_entity_decode($location); 
            ?></td>
          </tr>
          <tr>
            <td><?php echo _l('fe_models'); ?>:</td>
            <td><?php
            $model_name = '';
            $data_model = $this->fixed_equipment_model->get_models($audit->model_id);
            if($data_model){
              $model_name = $data_model->model_name;
            }
            echo html_entity_decode($model_name); 
            ?></td>
          </tr>

          <tr>
            <td><?php echo _l('fe_checkin_checkout'); ?>:</td>
            <td><?php
            $status_name = '';
            if($audit->checkin_checkout_status == 1){
              $status_name = _l('fe_checkin');
            }
            elseif($audit->checkin_checkout_status == 2){
              $status_name = _l('fe_checkout');
            }
            echo html_entity_decode($status_name); 
            ?></td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <br>
      <div class="hot handsontable htColumnHeaders" id="example">
        <?php echo form_hidden('assets_detailt'); ?>
      </div>
    </div>
  </div>
  <br>
  <br>
  <div class="row">
    <div class="col-md-12">
      <div class="project-overview-right">
        <div class="project-overview-right">
          <?php
          if(count($data_approve) > 0){ ?>
            <div class="row">
              <div class="col-md-12 project-overview-expenses-finance">
                <?php 
                $has_deny = false;
                $current_approve = false;
                foreach ($data_approve as $value) {
                  ?>
                  <div class="col-md-4 text-center">
                    <p class="text-uppercase text-muted no-mtop bold"><?php echo get_staff_full_name($value['staffid']); ?></p>

                    <?php if($value['approve'] == 1){ 
                      ?>
                      <img src="<?php echo site_url(FIXED_EQUIPMENT_PATH.'approve/approved.png'); ?>">
                      <br><br>
                      <p class="bold text-center"><?php echo html_entity_decode($value['note']); ?></p> 
                      <p class="bold text-center text-<?php if($value['approve'] == 1){ echo 'success'; }elseif($value['approve'] == 2){ echo 'danger'; } ?>"><?php echo _dt($value['date']); ?>
                    <?php }elseif($value['approve'] == 2){ $has_deny = true;?>
                      <img src="<?php echo site_url(FIXED_EQUIPMENT_PATH.'approve/rejected.png'); ?>">
                      <br><br>
                      <p class="bold text-center"><?php echo html_entity_decode($value['note']); ?></p> 
                      <p class="bold text-center text-<?php if($value['approve'] == 1){ echo 'success'; }elseif($value['approve'] == 2){ echo 'danger'; } ?>"><?php echo _dt($value['date']); ?>
                    <?php }else{
                      if($current_approve == false && $has_deny == false){ 
                        $current_approve = true;
                        if(get_staff_user_id() == $value['staffid']){ 
                          ?>
                          <div class="row text-center" >
                            <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?><span class="caret"></span></a>
                            <div class="dropdown-menu dropdown-menu-left">
                              <div class="col-md-12">
                                <?php echo render_textarea('reason', 'reason'); ?>
                                <div class="clearfix"></div>
                              </div>
                              <div class="col-md-12 text-center">
                                <a href="javascript:void(0)" data-loading-text="<?php echo _l('fe_waiting'); ?>" onclick="approve_request(<?php echo html_entity_decode($id); ?>);" class="btn btn-success"><?php echo _l('approve'); ?></a>
                                <a href="javascript:void(0)" data-loading-text="<?php echo _l('fe_waiting'); ?>" onclick="deny_request(<?php echo html_entity_decode($id); ?>);" class="btn btn-warning"><?php echo _l('deny'); ?></a>
                              </div>
                              <div class="clearfix"></div>
                              <br>
                              <div class="clearfix"></div>
                            </div>
                          </div>
                          <?php 
                        }
                      }
                    } ?> 
                  </p>
                </div>
                <?php
              } ?>
            </div>
          </div>
        <?php }else{
          if(isset($process)){
            if($process == 'choose'){
              $html = '<div class="row">';
              $html .= '<div class="col-md-9"><select name="approver" class="selectpicker" data-live-search="true" id="approver_c" data-width="100%" data-none-selected-text="'. _l('fe_please_choose_approver').'"> 
              <option value=""></option>'; 
              $current_user = get_staff_user_id();
              foreach($staffs as $staff){ 
                if($staff['staffid'] != $current_user){
                  $html .= '<option value="'.$staff['staffid'].'">'.$staff['staff_identifi'].' - '.$staff['firstname'].' '.$staff['lastname'].'</option>';                  
                }
              }
              $html .= '</select></div>';
              $html .= '<div class="col-md-3"><a href="javascript:void(0)" onclick="choose_approver();" class="btn btn-success lead-top-btn lead-view">'._l('choose').'</a></div>';
              $html .= '</div>';
              echo html_entity_decode($html);
            }
          }
        } ?>
      </div>
    </div>
  </div>
</div>

<?php echo form_close(); ?>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="add_new_assets_maintenances" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
         <span class="add-title hide"><?php echo _l('fe_create_asset_maintenance'); ?></span>
         <span class="edit-title"><?php echo _l('fe_edit_asset_maintenance'); ?></span>
       </h4>
     </div>
     <?php echo form_open(admin_url('fixed_equipment/assets_maintenances?redirect='.urlencode('fixed_equipment/view_audit_request/'.$id).'&rel_type=audit&rel_id='.$id),array('id'=>'assets_maintenances-form')); ?>
     <div class="modal-body">
      <?php 
        $this->load->view('maintenance/maintenance_modal_content.php');
       ?>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
    </div>
    <?php echo form_close(); ?>                 
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<input type="hidden" name="choose_approver_text" value="<?php echo _l('fe_please_choose_approver'); ?>">
<input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
<?php init_tail(); ?>
<?php 
require('modules/fixed_equipment/assets/js/view_audit_request_js.php');
?>
</body>
</html>
