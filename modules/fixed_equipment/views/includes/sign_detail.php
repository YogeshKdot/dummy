<input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">

<div class="tab-content">

   <div role="tabpanel" class="tab-pane active" id="tab_invoice">
      <div id="invoice-preview">
         <div class="row">
            <div class="col-md-12">
               <?php 
               $count_signer = 0;
               foreach ($signers as $key => $value) {
                  if($value['ip_address'] != null && $value['date_of_signing'] != null && is_numeric($value['staff_id'])){ 
                     $count_signer++;
                     ?>
                     <div class="alert alert-success">
                        <?php echo _l('fe_this_document_is_signed_by'); ?> <b><?php echo html_entity_decode($value['firstname'].' '.$value['lastname']); ?></b> (<a href="mailto:<?php echo html_entity_decode($value['email']); ?>"><?php echo html_entity_decode($value['email']); ?></a>) <?php echo _l('fe_on'); ?> <b><?php echo _dt($value['date_of_signing']); ?></b> <?php echo _l('fe_from_ip_address'); ?> <b><?php echo html_entity_decode($value['ip_address']); ?></b>   
                     </div>
                  <? }} 
                  if(count($signers) != $count_signer && $sign_documents->status == 3){ ?>
                     <div class="alert alert-info">
                        <?php echo _l('fe_this_document_is_manually_marked_as_signed'); ?>
                     </div>
                  <? } ?>
               </div>
               <div class="col-md-12">
                  <div class="btn-group pull-right">
                     <a href="javascript:void(0)" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                     <ul class="dropdown-menu dropdown-menu-right">
                        <li class="hidden-xs"><a href="<?php echo admin_url('fixed_equipment/sign_detail_pdf/'.$id.'?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a></li>
                        <li class="hidden-xs"><a href="<?php echo admin_url('fixed_equipment/sign_detail_pdf/'.$id.'?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                        <li><a href="<?php echo admin_url('fixed_equipment/sign_detail_pdf/'.$id); ?>"><?php echo _l('download'); ?></a></li>
                        <li>
                           <a href="<?php echo admin_url('fixed_equipment/sign_detail_pdf/'.$id.'?print=true'); ?>" target="_blank">
                              <?php echo _l('print'); ?>
                           </a>
                        </li>
                     </ul>
                  </div>
                  <div class="btn-group pull-right">
                     <?php 
                     $approve_list = [
                        ['id' => 1, 'label' => _l('fe_not_yet_sign')],
                        ['id' => 2, 'label' => _l('fe_signing')],
                        ['id' => 3, 'label' => _l('fe_signed')]
                     ];
                     echo render_select('status', $approve_list, array('id', 'label'), '', $sign_documents->status, ['data-none-selected-text' => _l('fe_change_status_to').' '], [], 'dropdown bootstrap-select pull-right mright10 bs3'); 
                     ?>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-3 col-sm-3">
                  <h4 class="bold">
                     #<?php echo html_entity_decode($sign_documents->reference); ?>
                  </h4>
                 <?php get_company_logo(get_admin_uri().'/') ?>
              </div>
              <div class="col-sm-9 text-right">
               <?php 
               if(is_numeric($sign_documents->check_to_staff)){ ?>
                  <address>
                     <h4>
                        <?php echo get_staff_full_name($sign_documents->check_to_staff); ?>  
                     </h4>
                     <?php 
                     $staff_data = $this->staff_model->get($sign_documents->check_to_staff);
                     if($staff_data){
                        echo html_entity_decode($staff_data->email.'<br><h5>'.$staff_data->phonenumber.'</h5>');
                     }
                     ?>                 
                  </address>
               <?php } ?>                         
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="table-responsive">
                  <table class="table items items-preview invoice-items-preview" data-type="invoice">
                     <thead>
                        <tr>
                           <th class="description" width="40%" align="left"><?php echo _l('fe_item') ?></th>
                           <th class="description" width="30%" align="left"><?php echo _l('fe_asset_tag') ?></th>
                           <th align="right" width="30%"><?php echo _l('fe_check_in_out_date') ?></th>
                        </tr>
                     </thead>
                     <tbody class="ui-sortable">
                        <?php
                        $item_list_id = explode(',', $sign_documents->checkin_out_id);
                        foreach ($item_list_id as $key => $item_id) {
                           $data_check_in_out = $this->fixed_equipment_model->get_checkin_out_data($item_id);
                           if($data_check_in_out){
                              $asset_tag = '';
                              $asset_id = $data_check_in_out->item_id;
                              if($data_check_in_out->item_type == 'license'){
                                 $data_seats = $this->fixed_equipment_model->get_seats($data_check_in_out->item_id);
                                 if($data_seats){
                                    $asset_id = $data_seats->license_id;    
                                 }
                              }
                              $data_asset = $this->fixed_equipment_model->get_assets($asset_id);  
                              if($data_asset){
                                 $asset_tag = $data_asset->series;                                          
                              }                                                                           
                              ?>
                              <tr class="sortable" data-item-id="79">
                                 <td class="description" align="left;" width="33%"><strong><?php echo html_entity_decode($data_check_in_out->asset_name); ?></strong></td>
                                 <td class="description" align="left;" width="33%"><strong><?php echo html_entity_decode($asset_tag); ?></strong></td>
                                 <td align="right" width="9%"><?php echo _dt($data_check_in_out->date_creator); ?></td>
                              </tr>
                           <?php }} ?>
                        </tbody>
                     </table>      
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-12">
                  <hr>                  
               </div>
               <?php foreach ($signers as $key => $value) {
                  $full_name = '...';
                  if($value['firstname'] == null){
                     if(is_numeric($value['staff_id'])){
                        $full_name = get_staff_full_name($value['staff_id']);                        
                     }
                  }
                  else{
                     $full_name = $value['firstname'].' '.$value['lastname'];
                  }
                  ?>
                  <div class="col-md-6 mtop10 text-center">
                     <div>
                        <?php echo ($key == 0 ? _l('fe_creator_signature') : _l('fe_owner_signature')); ?>
                     </div>  

                     <?php if($value['staff_id'] == null || (is_numeric($value['staff_id']))){ 
                       $firstname = '';
                       $lastname = '';
                       $email = '';
                       if(is_numeric($value['staff_id'])){
                         $staff_data = $this->staff_model->get($value['staff_id']);
                         if($staff_data){
                           $firstname = $staff_data->firstname;
                           $lastname = $staff_data->lastname;
                           $email = $staff_data->email;
                        }
                     }
                     if($value['date_of_signing'] == null && ($value['staff_id'] == null || $value['staff_id'] == get_staff_user_id())){ ?>
                       <button class="btn btn-success mtop20" data-firstname="<?php echo html_entity_decode($firstname); ?>" data-lastname="<?php echo html_entity_decode($lastname); ?>" data-email="<?php echo html_entity_decode($email); ?>" onclick="staff_sign_document(this,<?php echo html_entity_decode($id.','.$value['id']); ?>)">
                        <?php echo _l('fe_sign') ?>
                     </button>
                  <?php }else{ 
                     $file_path  = site_url(FIXED_EQUIPMENT_IMAGE_UPLOADED_PATH.'sign_document/'.$value['id'].'/signature.png');
                     ?>
                     <img class="mtop10" height="60" src="<?php echo html_entity_decode($file_path) ?>" alt="">
                  <?php } ?>
               <?php }else{ ?>
                  <div class="clearfix mtop20">
                  <br>
                  <br>                     
                  <br>                     
                  </div>
               <?php } ?>
               <strong class="clearfix mtop20">
                  <?php echo html_entity_decode($full_name); ?>
               </strong>
            </div>
         <?php  } ?>
      </div>



   </div>
</div>
</div>
