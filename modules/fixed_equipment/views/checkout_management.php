<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row panel_s mbot10">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <h4 class="pull-left">
              <?php echo html_entity_decode($title); ?>
            </h4>

            <div class="_buttons pull-right mtop4">
              <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs pull-right" onclick="toggle_small_view('.table-checkout_managements','#check_in_out_detail'); return false;" data-toggle="tooltip" title="<?php echo _l('estimates_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
            </div>
            <?php 
            if(is_admin() || has_permission('fixed_equipment_sign_manager', '', 'create')){ ?>
              <div class="_buttons pull-right mtop4 mright10">
                <a href="javascript:void(0)" class="btn btn-primary btn-with-tooltip hidden-xs pull-right" onclick="create_sign_document(); return false;" data-toggle="tooltip" title="<?php echo _l('fe_create_sign_document'); ?>"><?php echo _l('fe_create_sign_document'); ?></a>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>




    <div class="row">
      <div class="row">
        <div class="col-md-12" id="small-table">
          <div class="panel_s">
            <div class="panel-body">

              <div class="row" id="filter">
                <div class="col-md-6 col-lg-3">
                  <?php echo render_select('location_id', $locations, array('id', 'location_name'), 'fe_location') ?>
                </div>
                <?php 
                $selected_staff = '';
                $hide_staff_filter = '';
                if(!is_admin() && has_permission('fixed_equipment_sign_manager', '', 'view_own')){
                  $selected_staff = get_staff_user_id();
                  $hide_staff_filter = ' hide';
                }
                ?>
                <div class="col-md-6 col-lg-3<?php echo html_entity_decode($hide_staff_filter); ?>">
                  <?php

                  echo render_select('staff_id', $staffs, array('staffid', array('firstname', 'lastname')), 'staff', $selected_staff) ?>
                </div>
                <div class="col-md-6 col-lg-3">
                  <?php echo render_select('asset_id', $assets, array('id', 'assets_name'), 'fe_asset') ?>
                </div>
                <div class="col-md-6 col-lg-3">
                  <?php
                  $arr_check_type = [
                    ['id' => 'checkout', 'name' => _l('fe_checkout')],
                    ['id' => 'checkin', 'name' => _l('fe_checkin')]
                  ];
                  echo render_select('check_type', $arr_check_type, array('id', 'name'), 'fe_check_type') ?>
                </div>
                <div class="col-md-6 col-lg-3">
                  <?php echo render_date_input('from_date', 'fe_from_date') ?>
                </div>
                <div class="col-md-6 col-lg-3">
                  <?php echo render_date_input('to_date', 'fe_to_date') ?>
                </div>
                <div class="col-md-6 col-lg-3">
                  <?php echo render_select('sign_document', $sign_documents, array('id', 'reference'), 'fe_sign_document'); ?>
                </div>
                <div class="col-md-6 col-lg-3">

                </div>
              </div>
              <?php 
              $hide_first_column = 'hide';
              if(is_admin() || has_permission('fixed_equipment_sign_manager', '', 'create')){
                $hide_first_column = '';
                ?>
                <a href="#" onclick="bulk_sign(); return false;"  data-toggle="modal" data-table=".table-checkout_managements" data-target="#leads_bulk_actions" class=" hide bulk-actions-btn table-btn"><?php echo _l('fe_create_sign_document'); ?></a> 
              <?php } ?>
              <?php
              $table_data = array(
               '<input type="checkbox" id="mass_select_all" class="'.$hide_first_column.'" data-to-table="checkout_managements">',
               _l('id'),
               _l('fe_asset_name'),
               _l('fe_image'),
               _l('fe_serial'),
               _l('fe_type'),
               _l('fe_check_type'),
               _l('fe_check_in_out_date'),
               _l('fe_sign_document')
             );

              render_datatable($table_data,'checkout_managements',
                array('table-checkout_managements'),
                array(
                  'proposal_sm' => 'proposal_sm',
                  'id'=>'table-checkout_managements',
                )); ?>

              </div>
            </div>
          </div>

          <div class="col-md-7 small-table-right-col">
          <div class="panel_s">
            <div id="check_in_out_detail" class="hide panel-body">

            </div>
          </div>
          </div>


        </div>
      </div>


    </div>
  </div>


  <div class="modal create_sign_document_modal" id="create_sign_document_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><?php echo _l('fe_create_sign_document'); ?></h4>
        </div>
        <?php echo form_open(admin_url('fixed_equipment/add_sign_document'),array('id'=>'add_sign_document-form')); ?>
        <div class="modal-body">
          <?php echo render_select('staffid', $staffs, array('staffid', array('firstname', 'lastname')), '<small class="req text-danger">* </small>'._l('staff'), '', ['required' => true]) ?>
          <?php echo render_select('check_in_out_id[]', [], array('id', array('id', 'asset_name')), '<small class="req text-danger">* </small>'._l('fe_checkin_out'),'',['multiple' => true, 'required' => true]) ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <button type="submit" class="btn btn-info"><?php echo _l('create'); ?></button>
        </div>
        <?php echo form_close(); ?>                 
      </div>
    </div>
  </div>


  <?php 
  $this->load->view('includes/sign_document_modal.php');
  ?>



  <input type="hidden" name="check">
  <input type="hidden" name="please_select_at_least_one_item_from_the_list" value="<?php echo _l('please_select_at_least_one_item_from_the_list'); ?>">
  <?php init_tail(); ?>
