<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$isGridView = 0;
if ($this->session->has_userdata('cl_grid_view') && $this->session->userdata('cl_grid_view') == 'true') {
    $isGridView = 1;
}
?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_filters _hidden_inputs hidden">
                    <?php
                    echo form_hidden('my_call_logs');
                    foreach($staffs as $staff){
                        echo form_hidden('staffid_'.$staff['staffid']);
                    }
                    foreach($rel_types as $relTo){
                        echo form_hidden('rel_type_'.$relTo['key']);
                    }
                    foreach($leads as $lead){
                        echo form_hidden('lead_'.$lead['id']);
                    }
                    foreach($clcustomers as $client){
                        echo form_hidden('client_'.$client['userid']);
                    }

                    ?>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if(has_permission('call_logs','','create')){ ?>
                            <a href="<?php echo admin_url('call_logs/call_log'); ?>" class="btn btn-info pull-left display-block mright5"><?php echo _l('new_call_log'); ?></a>
                            <?php } ?>
                            <a href="<?php echo admin_url('call_logs/overview'); ?>" data-toggle="tooltip" title="<?php echo _l('cl_gantt_overview'); ?>" class="btn btn-default"><i class="fa fa-bar-chart" aria-hidden="true"></i> Overview</a>

                            <a href="<?php echo admin_url('call_logs/switch_grid/'.$switch_grid); ?>" class="btn btn-default hidden-xs">
                                <?php if($switch_grid == 1){ echo _l('cl_switch_to_list_view');}else{echo _l('cl_switch_to_grid_view');}; ?>
                            </a>
                            <div class="visible-xs">
                                <div class="clearfix"></div>
                            </div>
                            <?php if($isGridView ==0){ ?>
                            <div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                                    <li class="active"><a href="#" data-cview="all" onclick="dt_custom_view('','.table-call_logs',''); return false;"><?php echo _l('customers_sort_all'); ?></a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" data-cview="my_call_logs" onclick="dt_custom_view('my_call_logs','.table-call_logs','my_call_logs'); return false;">
                                            <?php echo _l('cl_assigned_to_me'); ?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <?php if(count($staffs) > 0){ ?>
                                        <li class="dropdown-submenu pull-left staffs">
                                            <a href="#" tabindex="-1"><?php echo _l('cl_filter_staff'); ?></a>
                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <?php foreach($staffs as $staff){ ?>
                                                    <li><a href="#" data-cview="staffid_<?php echo $staff['staffid']; ?>" onclick="dt_custom_view('staffid_<?php echo $staff['staffid']; ?>','.table-call_logs','staffid_<?php echo $staff['staffid']; ?>'); return false;"><?php echo $staff['firstname'].' '.$staff['lastname']; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <div class="clearfix"></div>
                                        <li class="divider"></li>
                                    <?php } ?>

                                    <?php if(count($rel_types) > 0){ ?>
                                        <li class="dropdown-submenu pull-left rel_types">
                                            <a href="#" tabindex="-1"><?php echo _l('cl_type'); ?></a>
                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <?php foreach($rel_types as $rel_type){ ?>
                                                    <li><a href="#" data-cview="rel_type_<?php echo $rel_type['key']; ?>" onclick="dt_custom_view('rel_type_<?php echo $rel_type['key']; ?>','.table-call_logs','rel_type_<?php echo $rel_type['key']; ?>'); return false;"><?php echo $rel_type['lang_key']; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <div class="clearfix"></div>
                                        <li class="divider"></li>
                                    <?php } ?>

                                    <?php if(count($leads) > 0){ ?>
                                        <li class="dropdown-submenu pull-left leads">
                                            <a href="#" tabindex="-1"><?php echo _l('cl_lead'); ?></a>
                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <?php foreach($leads as $lead){ ?>
                                                    <li><a href="#" data-cview="lead_<?php echo $lead['id']; ?>" onclick="dt_custom_view('lead_<?php echo $lead['id']; ?>','.table-call_logs','lead_<?php echo $lead['id']; ?>'); return false;"><?php echo $lead['name']; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <div class="clearfix"></div>
                                        <li class="divider"></li>
                                    <?php } ?>

                                    <?php if(count($clcustomers) > 0){ ?>
                                        <li class="dropdown-submenu pull-left cclients">
                                            <a href="#" tabindex="-1"><?php echo _l('cl_customer'); ?></a>
                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <?php foreach($clcustomers as $client){ ?>
                                                    <li><a href="#" data-cview="client_<?php echo $client['userid']; ?>" onclick="dt_custom_view('client_<?php echo $client['userid']; ?>','.table-call_logs','client_<?php echo $client['userid']; ?>'); return false;"><?php echo $client['company']; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <div class="clearfix"></div>
                                        <li class="divider"></li>
                                    <?php } ?>

                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <div class="clearfix mtop20"></div>
                        <?php if($this->session->has_userdata('cl_grid_view') && $this->session->userdata('cl_grid_view') == 'true') { ?>
                            <div class="grid-tab" id="grid-tab">
                                <div class="row">
                                    <div id="cl-grid-view" class="container-fluid">

                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <?php render_datatable(array(
                                _l('cl_type'),
                                _l('cl_purpose_of_call'),
                                _l('cl_caller'),
                                _l('cl_contact'),
                                _l('cl_start_time'),
                                _l('cl_end_time'),
                                _l('cl_duration'),
                                _l('cl_call_follow_up'),
                                _l('cl_is_important'),
                                _l('cl_is_completed'),
                            ),'call_logs'); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Call Log Modal-->
<div class="modal fade call_log-modal" id="call_log-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content data">

        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    var _lnth = 12;

    $(function(){
        var CustomersServerParams = {};
        $.each($('._hidden_inputs._filters input'),function(){
            CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
        });

        if(<?php echo $isGridView ?> == 0) {

            var tAPI = initDataTable('.table-call_logs', admin_url+'call_logs/table', [2, 3], [2, 3], CustomersServerParams);
        }else{
            loadGridView();

            $(document).off().on('click','a.paginate',function(e){
                e.preventDefault();
                console.log("$(this)", $(this).data('ci-pagination-page'))
                var pageno = $(this).data('ci-pagination-page');
                var formData = {
                    search: $("input#search").val(),
                    start: (pageno-1),
                    length: _lnth,
                    draw: 1
                }
                gridViewDataCall(formData, function (resposne) {
                    $('div#grid-tab').html(resposne)
                })
            });
        }
    });
</script>
</body>
</html>
