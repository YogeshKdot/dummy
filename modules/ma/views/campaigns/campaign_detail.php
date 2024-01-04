<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="customer-profile-group-heading"><?php echo _l('campaign'); ?></h4>
                  <h4 class="h4-color"><?php echo _l('general_infor'); ?></h4>
                  <hr class="hr-color">
                  <div class="row">
                    <div class="col-md-6">
                      <?php echo form_hidden('timezone', date_default_timezone_get()); ?>
                      <?php echo form_hidden('campaign_id',$campaign->id); ?>
                      <table class="table table-striped  no-margin">
                        <tbody>
                            <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('name'); ?></td>
                              <td><span style="color: <?php echo html_entity_decode($campaign->color); ?>"><?php echo html_entity_decode($campaign->name); ?></span></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('category'); ?></td>
                              <td><?php echo ma_get_category_name($campaign->category); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <?php $value = (($campaign->published == 1) ? _l('yes') : _l('no')); ?>
                              <?php $text_class = (($campaign->published == 1) ? 'text-success' : 'text-danger'); ?>
                              <td class="bold"><?php echo _l('published'); ?></td>
                              <td class="<?php echo html_entity_decode($text_class) ; ?>"><?php echo html_entity_decode($value) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('start_date'); ?></td>
                              <td><?php echo _d($campaign->start_date) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('end_date'); ?></td>
                              <td><?php echo _d($campaign->end_date) ; ?></td>
                           </tr>
                          </tbody>
                    </table>
                  </div>
                  <div class="col-md-6">
                      <table class="table table-striped  no-margin">
                        <tbody>
                          <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('datecreator'); ?></td>
                              <td><?php echo _dt($campaign->dateadded) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('addedfrom'); ?></td>
                              <td><?php echo get_staff_full_name($campaign->addedfrom) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('description'); ?></td>
                              <td><?php echo html_entity_decode($campaign->description) ; ?></td>
                           </tr>
                          </tbody>
                    </table>
                  </div>
                </div>
                <div class="horizontal-scrollable-tabs preview-tabs-top mtop25">
                  <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                    <div class="horizontal-tabs">
                      <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                          <li role="presentation" class="active">
                             <a href="#workflow" aria-controls="workflow" role="tab" id="tab_out_of_stock" data-toggle="tab">
                                <?php echo _l('workflow') ?>
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#actions" aria-controls="actions" role="tab" id="tab_out_of_stock" data-toggle="tab">
                                <?php echo _l('actions') ?>
                             </a>
                          </li>
                          <li role="presentation" >
                             <a href="#statistics" aria-controls="statistics" role="tab" id="tab_expiry_date" data-toggle="tab">
                                <?php echo _l('statistics'); ?>
                             </a>
                          </li>
                          <li role="presentation" >
                             <a href="#leads" aria-controls="leads" role="tab" id="tab_expiry_date" data-toggle="tab">
                                <?php echo _l('leads'); ?>
                             </a>
                          </li>
                      </ul>
                      </div>
                  </div>
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="workflow">
                      <div class="wrapper">
                        <div class="col-md-12">
                          <div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)">
                            <div class="btn-export" onclick="builder(); return false;"><?php echo _l('builder'); ?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="actions">
                      <div class="row mtop15">
                        <div class="col-md-4">
                          <div class="panel_s">
                            <div class="panel-heading">
                              <h4><?php echo _l('point_actions'); ?></h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped  no-margin">
                                <tbody>
                                  <?php foreach($point_actions as $action){ ?>
                                    <tr class="project-overview">
                                      <td width="30%"><span><?php echo html_entity_decode($action->name); ?></span></td>
                                      <td class="text-right"><?php echo html_entity_decode($action->total); ?></td>
                                   </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="panel_s">
                            <div class="panel-heading">
                              <h4><?php echo _l('emails'); ?></h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped  no-margin">
                                <tbody>
                                  <?php foreach($emails as $email){ ?>
                                    <tr class="project-overview">
                                      <td width="30%"><span style="color: <?php echo html_entity_decode($email->color); ?>"><?php echo html_entity_decode($email->name); ?></span></td>
                                      <td class="text-right"><?php echo html_entity_decode($email->total); ?></td>
                                   </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="panel_s">
                            <div class="panel-heading">
                              <h4><?php echo _l('segments'); ?></h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped  no-margin">
                                <tbody>
                                  <?php foreach($segments as $segment){ ?>
                                    <tr class="project-overview">
                                      <td width="30%"><span style="color: <?php echo html_entity_decode($segment->color); ?>"><?php echo html_entity_decode($segment->name); ?></span></td>
                                      <td class="text-right"><?php echo html_entity_decode($segment->total); ?></td>
                                   </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="panel_s">
                            <div class="panel-heading">
                              <h4><?php echo _l('sms'); ?></h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped  no-margin">
                                <tbody>
                                  <?php foreach($sms as $_sms){ ?>
                                    <tr class="project-overview">
                                      <td width="30%"><span><?php echo html_entity_decode($_sms->name); ?></span></td>
                                      <td class="text-right"><?php echo html_entity_decode($_sms->total); ?></td>
                                   </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="panel_s">
                            <div class="panel-heading">
                              <h4><?php echo _l('stages'); ?></h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped  no-margin">
                                <tbody>
                                  <?php foreach($stages as $stage){ ?>
                                    <tr class="project-overview">
                                      <td width="30%"><span style="color: <?php echo html_entity_decode($stage->color); ?>"><?php echo html_entity_decode($stage->name); ?></span></td>
                                      <td class="text-right"><?php echo html_entity_decode($stage->total); ?></td>
                                   </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="statistics">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="panel_s">
                            <div class="panel-body">
                              <div id="container_email"></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="panel_s">
                            <div class="panel-body">
                              <div id="container_text_message"></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="panel_s">
                            <div class="panel-body">
                              <div id="container_point_action"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="leads">
                     <?php
                      $table_data = array();
                      $_table_data = array(
                        '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="leads"><label></label></div>',
                        array(
                         'name'=>_l('the_number_sign'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-number')
                       ),
                        array(
                         'name'=>_l('leads_dt_name'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-name')
                       ),
                      );
                      if(is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
                        $_table_data[] = array(
                            'name'=>_l('gdpr_consent') .' ('._l('gdpr_short').')',
                            'th_attrs'=>array('id'=>'th-consent', 'class'=>'not-export')
                         );
                      }
                      $_table_data[] = array(
                       'name'=>_l('lead_company'),
                       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-company')
                      );
                      $_table_data[] =   array(
                       'name'=>_l('leads_dt_email'),
                       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-email')
                      );
                      $_table_data[] =  array(
                       'name'=>_l('leads_dt_phonenumber'),
                       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-phone')
                      );
                      $_table_data[] =  array(
                         'name'=>_l('leads_dt_lead_value'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-lead-value')
                        );
                      $_table_data[] =  array(
                       'name'=>_l('tags'),
                       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-tags')
                      );
                      $_table_data[] = array(
                       'name'=>_l('leads_dt_assigned'),
                       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-assigned')
                      );
                      $_table_data[] = array(
                       'name'=>_l('leads_dt_status'),
                       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-status')
                      );
                      $_table_data[] = array(
                       'name'=>_l('leads_source'),
                       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-source')
                      );
                      $_table_data[] = array(
                        'name'=>_l('point'),
                        'th_attrs'=>array('class'=>'toggleable','id'=>'th-point')
                      );
                      foreach($_table_data as $_t){
                       array_push($table_data,$_t);
                      }
                     
                      $table_data = hooks()->apply_filters('leads_table_columns', $table_data);
                      render_datatable($table_data,'leads-campaign',
                      array('customizable-table'),
                      array(
                       'id'=>'table-leads-campaign',
                       'data-last-order-identifier'=>'leads',
                       )); ?>
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
     
   </div>
</div>
<?php init_tail(); ?>
<?php require 'modules/ma/assets/js/campaigns/workflow_builder_js.php';?>
</body>
</html>
