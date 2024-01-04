<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a onclick="add_status('<?= $type ?>')" class="btn btn-info pull-left display-block"><?php echo _l("Add $type status"); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <div class="clearfix"></div>
                        <?php
                        $fields = array(
                            _l('id'),
                            _l('status_name'),
                            _l('status_color'),
                            _l('status_order'),
                            _l('status_default_filter'),
                            _l('status_can_change_to'),
                        );
                        if ($type == 'task') {
                            $fields[] = _l('status_dont_have_staff');
                        }
                        render_datatable($fields, 'task_statuses');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade task-modal-single" id="task-status-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog ">
            <div class="modal-content data">

            </div>
        </div>
    </div>

    <!--Add/edit task modal-->
    <div id="_task-status"></div>

</div>
<?php init_tail(); ?>


</body>

</html>