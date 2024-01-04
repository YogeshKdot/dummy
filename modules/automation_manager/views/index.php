<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="automation_manager/create" class="btn btn-info pull-left display-block"><?php echo _l('Add automation'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <div class="clearfix"></div>
                        <?php
                        render_datatable(array(
                            _l('id'),
                            _l('automation_name'),
                            _l('triggers_count'),
                            _l('actions_count'),
                            _l('last_triggered'),
                            _l('last_triggered_by'),
                        ), 'automations');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-automations', window.location.href + '/table');
    });
</script>
</body>

</html>