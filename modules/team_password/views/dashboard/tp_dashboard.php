<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
  $this->load->model('team_password/team_password_model');
?>
<div id="wrapper">
  <div class="content">
    <div class="clearfix"></div>

        <div class="panel_s">
          <div class="panel-body">

    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6">
         <p class="text-dark text-uppercase bold"><?php echo _l('tp_dashboard');?></p>
      </div>
         <div class="col-md-3 pull-right">
         
         </div>
         <br>
         <hr class="mtop15" />    

         <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-6">
           <div class="top_stats_wrapper minheight85">
               <a class="text-success mbot15">
               <p class="text-uppercase mtop5 minheight35"><i class="hidden-sm fa fa-align-justify"></i> <?php echo _l('total_password'); ?>
               </p>
                  <span class="pull-right bold no-mtop fontsize24"><?php echo html_entity_decode($tp_count['total']); ?></span>
               </a>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar progress-bar-success no-percent-text not-dynamic width-100" role="progressbar" aria-valuenow="<?php echo html_entity_decode($tp_count['total']); ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($tp_count['total']); ?>" data-percent="100%">
                  </div>
               </div>
            </div>
         </div>

         <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-6">
           <div class="top_stats_wrapper minheight85">
               <a class="text mbot15">
               <p class="text-uppercase mtop5 minheight35"><i class="hidden-sm fa fa-user-circle"></i> <?php echo _l('normal_password'); ?>
               </p>
                  <span class="pull-right bold no-mtop fontsize24"><?php echo html_entity_decode($tp_count['normal']) ?></span>
               </a>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <?php 
                  $percentage = 0;
                  if($tp_count['total'] > 0){
                    $percentage = $tp_count['normal']/$tp_count['total']*100;
                  }
                  ?>
                  <div class="progress-bar progress-bar no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo html_entity_decode($tp_count['normal']); ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($tp_count['total']); ?>" style=" width: <?php echo html_entity_decode($percentage); ?>%" data-percent="<?php echo html_entity_decode($percentage); ?>%">
                  </div>
               </div>
            </div>
         </div>

         <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-6">
           <div class="top_stats_wrapper minheight85">
               <a class="text-warning mbot15">
               <p class="text-uppercase mtop5 minheight35"><i class="hidden-sm fa fa-university"></i> <?php echo _l('bank_account'); ?>
               </p>
                  <span class="pull-right bold no-mtop fontsize24"><?php echo html_entity_decode($tp_count['bank_account']); ?></span>
               </a>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                 <?php 
                  $percentage = 0;
                  if($tp_count['total'] > 0){
                    $percentage = $tp_count['bank_account']/$tp_count['total']*100;
                  }
                  ?>
                  <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo html_entity_decode($tp_count['bank_account']); ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($tp_count['total']); ?>" style="width: <?php echo html_entity_decode($percentage); ?>%" data-percent=" <?php echo html_entity_decode($percentage); ?>%">
                  </div>
               </div>
            </div>
         </div> 
        
        <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-6">
             <div class="top_stats_wrapper minheight85">
                 <a class="text-info mbot15">
                 <p class="text-uppercase mtop5 minheight35"><i class="hidden-sm fa fa-credit-card"></i> <?php echo _l('credit_card'); ?>
                 </p>
                    <span class="pull-right bold no-mtop fontsize24"><?php echo html_entity_decode($tp_count['credit_card']); ?></span>
                 </a>
                 <div class="clearfix"></div>
                 <div class="progress no-margin progress-bar-mini">
                  <?php 
                    $percentage = 0;
                    if($tp_count['total'] > 0){
                      $percentage = $tp_count['credit_card']/$tp_count['total']*100;
                    }
                    ?>
                    <div class="progress-bar progress-bar-info no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo html_entity_decode($tp_count['credit_card']) ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($tp_count['total']); ?>" style="width: <?php echo html_entity_decode($percentage); ?>%" data-percent=" <?php echo html_entity_decode($percentage); ?>%">
                    </div>
                 </div>
              </div>
           </div>

           <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-6">
             <div class="top_stats_wrapper minheight85">
                 <span class="text-default mbot15">
                 <p class="text-uppercase mtop5 minheight35"><i class="hidden-sm fa fa-envelope"></i> <?php echo _l('email'); ?>
                 </p>
                    <span class="pull-right bold no-mtop fontsize24"><?php echo html_entity_decode($tp_count['email']); ?></span>
                 </span>
                 <div class="clearfix"></div>
                 <div class="progress no-margin progress-bar-mini">
                  <?php 
                    $percentage = 0;
                    if($tp_count['total'] > 0){
                      $percentage = $tp_count['email']/$tp_count['total']*100;
                    }
                    ?>
                    <div class="progress-bar progress-bar-default no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo html_entity_decode($tp_count['email']) ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($tp_count['total']); ?>" style="width: <?php echo html_entity_decode($percentage); ?>%" data-percent=" <?php echo html_entity_decode($percentage); ?>%">
                    </div>
                 </div>
              </div>
           </div>

           <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-6">
             <div class="top_stats_wrapper minheight85">
                 <a class="text-warning mbot15">
                 <p class="text-uppercase mtop5 minheight35"><i class="hidden-sm fa fa-server"></i> <?php echo _l('server'); ?>
                 </p>
                    <span class="pull-right bold no-mtop fontsize24"><?php echo html_entity_decode($tp_count['server']); ?></span>
                 </a>
                 <div class="clearfix"></div>
                 <div class="progress no-margin progress-bar-mini">
                  <?php 
                    $percentage = 0;
                    if($tp_count['total'] > 0){
                      $percentage = $tp_count['server']/$tp_count['total']*100;
                    }
                    ?>
                    <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo html_entity_decode($tp_count['server']) ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($tp_count['total']); ?>" style="width: <?php echo html_entity_decode($percentage); ?>%" data-percent=" <?php echo html_entity_decode($percentage); ?>%">
                    </div>
                 </div>
              </div>
           </div>

           <div class="quick-stats-invoices col-xs-12 col-md-3 col-sm-6">
             <div class="top_stats_wrapper minheight85">
                 <a class="text-danger mbot15">
                 <p class="text-uppercase mtop5 minheight35"><i class="hidden-sm fa fa-pagelines"></i> <?php echo _l('software_license'); ?>
                 </p>
                    <span class="pull-right bold no-mtop fontsize24"><?php echo html_entity_decode($tp_count['software_license']); ?></span>
                 </a>
                 <div class="clearfix"></div>
                 <div class="progress no-margin progress-bar-mini">
                  <?php 
                    $percentage = 0;
                    if($tp_count['total'] > 0){
                      $percentage = $tp_count['software_license']/$tp_count['total']*100;
                    }
                    ?>
                    <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo html_entity_decode($tp_count['software_license']) ?>" aria-valuemin="0" aria-valuemax="<?php echo html_entity_decode($tp_count['total']); ?>" style="width: <?php echo html_entity_decode($percentage); ?>%" data-percent=" <?php echo html_entity_decode($percentage); ?>%">
                    </div>
                 </div>
              </div>
           </div>
            
          </div>
          <div class="col-md-6">
            <div id="password_by_cate" class="minwidth310">
            </div>
            <br>
          </div>

          <div class="col-md-6">
            <div id="share_by_type" class="minwidth310">
            </div>
            <br>
          </div>

          <div class="col-md-12">
            <hr>
          </div>

          <div class="col-md-6 border-right">
            <p class="text-dark text-uppercase bold"><?php echo _l('password_shared_to_you_recently');?></p>
            <table class="table dt-table">
              <thead>
                <th><?php echo _l('name') ?></th>
                <th><?php echo _l('read') ?></th>
                <th><?php echo _l('write') ?></th>
                <th><?php echo _l('type') ?></th>
              </thead>
              <tbody>
                <?php foreach($your_password_shared as $pw){ ?>
                  <tr>
                    <td>
                      <?php
                      echo '<a href="'.admin_url('team_password/view_'.$pw['type'].'/'.$pw['obj_id']).'">'. item_name_by_objid($pw['obj_id'],$pw['type']).'</a>';
                       ?>
                    </td>
                    <td><?php echo _l($pw['r']); ?></td>
                    <td><?php echo _l($pw['w']); ?></td>
                    <td><?php echo _l($pw['type']); ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <div class="col-md-6">

          <p class="text-dark text-uppercase bold"><?php echo _l('password_is_about_to_expire');?> <a href="<?php echo admin_url('team_password/report?tab=share_report&ef_time=unexpired'); ?>" class="pull-right" >...<?php echo _l('view_all'); ?></a></p>
          
           <table class="table dt-table">
             <thead>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('client'); ?></th>
                <th><?php echo _l('type'); ?></th>
                <th><?php echo _l('effective_time'); ?></th>
             </thead>
             <tbody>
               <?php foreach($password_expire as $pass){ ?>
                <tr>
                  <td>
                    <?php
                    echo '<a href="'.admin_url('team_password/view_'.$pass['type'].'/'.$pass['share_id']).'">'. item_name_by_objid($pass['share_id'],$pass['type']).'</a>';
                     ?>
                  </td>
                  <td>
                    <?php 
                      $name = '';
                       $client_name = '';
                        if($pass['client'] != ''){
                            $contact = $this->team_password_model->get_contact_by_email($pass['client']);
                        }else{
                            $contact = '';
                        }

                        if($contact != ''){
                          $name = $contact->lastname.' '.$contact->firstname;
                          $client_id = get_user_id_by_contact_id($contact->id);
                          $client_name = get_company_name($client_id);
                        }else{
                          $name = $pass['email'];
                        }

                        if($pass['client'] != ''){
                            echo html_entity_decode($client_name.' - '. $name);
                        }else{
                            echo html_entity_decode($name);
                        }
                    ?>
                  </td>
                  <td>
                    <?php echo _l($pass['type']); ?>
                  </td>
                  <td>
                    <?php echo _dt($pass['effective_time']); ?>
                  </td>
                </tr>

               <?php } ?>
             </tbody>
           </table>
          </div>

            </div>
        
        </div>
      </div>
   
  </div>
</div>
<?php init_tail(); ?>
<?php require('modules/team_password/assets/js/dashboard_js.php'); ?>