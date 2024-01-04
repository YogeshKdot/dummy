<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="customer-profile-group-heading"><?php echo _l('email'); ?></h4>
                  <h4 class="h4-color"><?php echo _l('general_infor'); ?></h4>
                  <hr class="hr-color">
                  <div class="row">
                    <div class="col-md-6">
                      <?php echo form_hidden('timezone', date_default_timezone_get()); ?>
                      <?php echo form_hidden('email_id', $email->id); ?>
                      <table class="table table-striped  no-margin">
                        <tbody>
                            <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('subject'); ?></td>
                              <td><span style="color: <?php echo html_entity_decode($email->color); ?>"><?php echo html_entity_decode($email->subject); ?></span></td>
                           </tr>
                            <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('internal_name'); ?></td>
                              <td><span><?php echo html_entity_decode($email->name); ?></span></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('category'); ?></td>
                              <td><?php echo ma_get_category_name($email->category); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('email_template'); ?></td>
                              <td><?php echo ma_get_email_template_name($email->email_template); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <?php $value = (($email->published == 1) ? _l('yes') : _l('no')); ?>
                              <?php $text_class = (($email->published == 1) ? 'text-success' : 'text-danger'); ?>
                              <td class="bold"><?php echo _l('published'); ?></td>
                              <td class="<?php echo html_entity_decode($text_class) ; ?>"><?php echo html_entity_decode($value) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('language'); ?></td>
                              <td><?php echo _l($email->language); ?></td>
                           </tr>
                          </tbody>
                    </table>
                  </div>
                  <div class="col-md-6">
                      <table class="table table-striped  no-margin">
                        <tbody>
                          <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('datecreator'); ?></td>
                              <td><?php echo _dt($email->dateadded) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('addedfrom'); ?></td>
                              <td><?php echo get_staff_full_name($email->addedfrom) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('description'); ?></td>
                              <td><?php echo html_entity_decode($email->description) ; ?></td>
                           </tr>
                          </tbody>
                    </table>
                  </div>
                </div>
                <h4 class="h4-color"><?php echo _l('advanced'); ?></h4>
                <hr class="hr-color">
                <div class="row">
                    <div class="col-md-6">
                      <table class="table table-striped  no-margin">
                        <tbody>
                            <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('from_name'); ?></td>
                              <td><span><?php echo html_entity_decode($email->from_name); ?></span></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('from_address'); ?></td>
                              <td><span><?php echo html_entity_decode($email->from_address); ?></span></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('reply_to_address'); ?></td>
                              <td><span><?php echo html_entity_decode($email->reply_to_address); ?></span></td>
                           </tr>
                          </tbody>
                    </table>
                  </div>
                  <div class="col-md-6">
                      <table class="table table-striped  no-margin">
                        <tbody>
                            <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('bcc_address'); ?></td>
                              <td><span><?php echo html_entity_decode($email->bcc_address); ?></span></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('attachment'); ?></td>
                              <td><span><?php echo ma_get_asset_name($email->attachment); ?></span></td>
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
                                <?php echo _l('preview') ?>
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#statistics" aria-controls="statistics" role="tab" id="tab_out_of_stock" data-toggle="tab">
                                <?php echo _l('statistics') ?>
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
                  <div class="tab-content mtop15">
                    <div role="tabpanel" class="tab-pane active" id="workflow">
                      <div class="wrapper">
                        <div class="row">
                        <div class="col-md-12">
                            <a href="#" data-toggle="modal" data-target="#send_example"
                            class="btn btn-success mbot20">
                            <?php echo _l('send_example'); ?>
                            </a>
                          <a class="btn btn-info mbot20" href="<?php echo admin_url('ma/email_design/'.$email->id); ?>"><?php echo _l('design'); ?></a>
                          <div id="EmailEditor"><?php echo json_decode($email->data_html); ?></div>
                        </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="statistics">
                     <div class="row">
                      <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                        <div class="panel_s">
                           <div class="panel-body">
                              <h3 class="text-muted _total">
                                 <?php echo count($lead_by_email); ?>
                              </h3>
                              <span class="text-warning"><?php echo _l('total_number_of_lead'); ?></span>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                        <div class="panel_s">
                           <div class="panel-body">
                              <h3 class="text-muted _total">
                                 <?php echo html_entity_decode($campaign_by_email['campaigns']); ?>
                              </h3>
                              <span class="text-success"><?php echo _l('number_of_active_campaigns'); ?></span>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                        <div class="panel_s">
                           <div class="panel-body">
                              <h3 class="text-muted _total">
                                 <?php echo html_entity_decode($campaign_by_email['old_campaigns']); ?>
                              </h3>
                              <span class="text-default"><?php echo _l('number_of_campaigns_participated'); ?></span>
                           </div>
                        </div>
                     </div>
                   </div>
                     <div class="row">
                        <div class="col-md-12">
                          <div class="panel_s">
                            <div class="panel-body">
                              <div id="container"></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="panel_s">
                            <div class="panel-body">
                              <div id="container_campaign" class="container_campaign"></div>
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
                              render_datatable($table_data,'leads-emails',
                              array('customizable-table'),
                              array(
                               'id'=>'table-leads-emails',
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
<div class="modal fade email-template" id="send_example" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('ma/send_example_email/'.$email->id), array('id' => 'send-example-email-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo _l('send_example'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('send_to_email', 'send_to_email','', 'email'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info"><?php echo _l('send'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
