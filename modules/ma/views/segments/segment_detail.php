<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                <h4 class="customer-profile-group-heading"><?php echo _l('segment'); ?></h4>
                  <h4 class="h4-color"><?php echo _l('general_infor'); ?></h4>
                  <hr class="hr-color">
                  <div class="row">
                    <div class="col-md-6">
                      <?php echo form_hidden('timezone', date_default_timezone_get()); ?>
                      <?php echo form_hidden('segment_id',$segment->id); ?>
                      <table class="table table-striped no-margin">
                        <tbody>
                            <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('name'); ?></td>
                              <td><span style="color: <?php echo html_entity_decode($segment->color); ?>"><?php echo html_entity_decode($segment->name); ?></span></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('category'); ?></td>
                              <td><?php echo ma_get_category_name($segment->category); ?></td>
                           </tr>
                           <tr class="project-overview">
                              <?php $value = (($segment->public_segment == 1) ? _l('yes') : _l('no')); ?>
                              <?php $text_class = (($segment->public_segment == 1) ? 'text-success' : 'text-danger'); ?>
                              <td class="bold"><?php echo _l('public_segment'); ?></td>
                              <td class="<?php echo html_entity_decode($text_class) ; ?>"><?php echo html_entity_decode($value) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <?php $value = (($segment->published == 1) ? _l('yes') : _l('no')); ?>
                              <?php $text_class = (($segment->published == 1) ? 'text-success' : 'text-danger'); ?>
                              <td class="bold"><?php echo _l('published'); ?></td>
                              <td class="<?php echo html_entity_decode($text_class) ; ?>"><?php echo html_entity_decode($value) ; ?></td>
                           </tr>
                          </tbody>
                    </table>
                  </div>
                  <div class="col-md-6">
                      <table class="table table-striped  no-margin">
                        <tbody>
                          <tr class="project-overview">
                              <td class="bold" width="30%"><?php echo _l('datecreator'); ?></td>
                              <td><?php echo _dt($segment->dateadded) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('addedfrom'); ?></td>
                              <td><?php echo get_staff_full_name($segment->addedfrom) ; ?></td>
                           </tr>
                           <tr class="project-overview">
                              <td class="bold"><?php echo _l('description'); ?></td>
                              <td><?php echo html_entity_decode($segment->description) ; ?></td>
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
                             <a href="#statistics" aria-controls="statistics" role="tab" id="tab_out_of_stock" data-toggle="tab">
                                <?php echo _l('statistics') ?>
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#leads" aria-controls="leads" role="tab" id="tab_out_of_stock" data-toggle="tab">
                                <?php echo _l('leads') ?>
                             </a>
                          </li>
                          <li role="presentation" >
                             <a href="#filters" aria-controls="filters" role="tab" id="tab_expiry_date" data-toggle="tab">
                                <?php echo _l('filters'); ?>
                             </a>
                          </li>
                      </ul>
                      </div>
                  </div>
                  <div class="tab-content">
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
                              render_datatable($table_data,'leads-segment',
                              array('customizable-table'),
                              array(
                               'id'=>'table-leads-segment',
                               'data-last-order-identifier'=>'leads',
                               )); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="statistics">
                      <div class="row">
                      <div class="col-lg-4 col-xs-12 col-md-12 total-column mtop25">
                        <div class="panel_s">
                           <div class="panel-body">
                              <h3 class="text-muted _total">
                                 <?php echo count($lead_by_segment); ?>
                              </h3>
                              <span class="text-warning"><?php echo _l('total_number_of_lead'); ?></span>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-xs-12 col-md-12 total-column mtop25">
                        <div class="panel_s">
                           <div class="panel-body">
                              <h3 class="text-muted _total">
                                 <?php echo html_entity_decode($campaign_by_segment['campaigns']); ?>
                              </h3>
                              <span class="text-success"><?php echo _l('number_of_active_campaigns'); ?></span>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-xs-12 col-md-12 total-column mtop25">
                        <div class="panel_s">
                           <div class="panel-body">
                              <h3 class="text-muted _total">
                                 <?php echo html_entity_decode($campaign_by_segment['old_campaigns']); ?>
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
                              <div id="container_segment"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="panel_s">
                            <div class="panel-body">
                              <div id="container_segment_campaign"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="filters">
                      <?php $types = [ 
                        1 => ['id' => 'name', 'name' => _l('name')],
                        2 => ['id' => 'position', 'name' => _l('position')],
                        3 => ['id' => 'email', 'name' => _l('email')],
                        4 => ['id' => 'website', 'name' => _l('lead_website')],
                        5 => ['id' => 'phone', 'name' => _l('phone')],
                        7 => ['id' => 'lead_value', 'name' => _l('lead_value')],
                        6 => ['id' => 'company', 'name' => _l('company')],
                        8 => ['id' => 'address', 'name' => _l('lead_address')],
                        9 => ['id' => 'city', 'name' => _l('city')],
                        10 => ['id' => 'state', 'name' => _l('state')],
                        11 => ['id' => 'country', 'name' => _l('country')],
                        12 => ['id' => 'zip_code', 'name' => _l('zip_code')],
                        13 => ['id' => 'language', 'name' => _l('language')],
                        13 => ['id' => 'tag', 'name' => _l('tag')],
                      ]; ?>
                      <?php $follow_1 = [ 
                        1 => ['id' => 'and', 'name' => _l('ma_and')],
                        2 => ['id' => 'or', 'name' => _l('ma_or')],
                      ]; ?>

                        <?php $follow_2 = [ 
                          1 => ['id' => 'equals', 'name' => _l('equals')],
                          2 => ['id' => 'not_equal', 'name' => _l('not_equal')],
                          3 => ['id' => 'greater_than', 'name' => _l('greater_than')],
                          4 => ['id' => 'greater_than_or_equal', 'name' => _l('greater_than_or_equal')],
                          5 => ['id' => 'less_than', 'name' => _l('less_than')],
                          6 => ['id' => 'less_than_or_equal', 'name' => _l('less_than_or_equal')],
                          7 => ['id' => 'empty', 'name' => _l('empty')],
                          8 => ['id' => 'not_empty', 'name' => _l('not_empty')],
                          9 => ['id' => 'like', 'name' => _l('like')],
                          10 => ['id' => 'not_like', 'name' => _l('not_like')],
                        ]; ?>
                      <?php foreach($segment->filters as $key => $filter){ ?>
                <div id="item_approve" class="border mtop10 padding-10">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="select-placeholder form-group">
                          <label for="sub_type_1[<?php echo html_entity_decode($key); ?>]"></label>
                          <select name="sub_type_1[<?php echo html_entity_decode($key); ?>]" id="sub_type_1[<?php echo html_entity_decode($key); ?>]" data-index="<?php echo html_entity_decode($key); ?>" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true" disabled>
                            <?php foreach($follow_1 as $val){
                                $selected = '';
                             if($val['id'] == $filter['sub_type_1']){
                                $selected = 'selected';
                              }
                              ?>
                              <option value="<?php echo html_entity_decode($val['id']); ?>" <?php echo html_entity_decode($selected); ?>>
                               <?php echo html_entity_decode($val['name']); ?>
                             </option>
                           <?php } ?>
                         </select>
                       </div> 
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="select-placeholder form-group">
                          <label for="type[<?php echo html_entity_decode($key); ?>]"></label>
                          <select name="type[<?php echo html_entity_decode($key); ?>]" id="type[<?php echo html_entity_decode($key); ?>]" data-index="<?php echo html_entity_decode($key); ?>" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true" disabled>
                            <?php foreach($types as $val){
                                $selected = '';
                             if($val['id'] == $filter['type']){
                                $selected = 'selected';
                              }
                              ?>
                              <option value="<?php echo html_entity_decode($val['id']); ?>" <?php echo html_entity_decode($selected); ?>>
                               <?php echo html_entity_decode($val['name']); ?>
                             </option>
                           <?php } ?>
                         </select>
                       </div> 
                    </div>
                   <div class="col-md-3">                            
                      <div class="select-placeholder form-group">
                        <label for="sub_type_2[<?php echo html_entity_decode($key); ?>]"></label>
                        <select name="sub_type_2[<?php echo html_entity_decode($key); ?>]" id="sub_type_2[<?php echo html_entity_decode($key); ?>]" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-hide-disabled="true" data-live-search="true" disabled>
                          <?php foreach($follow_2 as $val){
                                $selected = '';
                           if($val['id'] == $filter['sub_type_2']){
                            $selected = 'selected';
                          }
                          ?>
                          <option value="<?php echo html_entity_decode($val['id']); ?>" <?php echo html_entity_decode($selected); ?>>
                             <?php echo html_entity_decode($val['name']); ?>
                           </option>
                         <?php } ?>
                        </select>
                      </div> 
                   </div>
                   <div class="col-md-3">                            
                      <div class="form-group" app-field-wrapper="name">
                        <label for="value[<?php echo html_entity_decode($key); ?>]" class="control-label"></label>
                        <input type="value" id="value[<?php echo html_entity_decode($key); ?>]" name="value[<?php echo html_entity_decode($key); ?>]" class="form-control" value="<?php echo html_entity_decode($filter['value']); ?>" disabled>
                      </div>
                   </div>
                  
                </div>
                </div>
              <?php } ?>
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
     
   </div>
</div>
<?php init_tail(); ?>

</body>
</html>
