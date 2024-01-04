<?php echo form_open(admin_url("status_manager/update_$type/{$status['id']}")); ?>

<div class="modal fade edit" id="_task_status_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?= $title; ?>
                    <?= $status['id']; ?>
                </h4>
            </div>
            <div class="modal-body pb-50">


                <?php echo render_input('name', 'status_name', $status['name'], 'text', ['autofocus' => true]); ?>

                <?php echo render_input('color', 'status_color', $status['color'], 'color', ['style' => 'width:5rem']); ?>

                <?php echo render_input('order', 'status_order', $status['order'], 'number'); ?>

                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="filter_default" id="filter_default" <?= $status['filter_default'] ? "checked" : "" ?>>
                    <label for="filter_default"><?php echo _l('filter_default'); ?></label>
                </div>


                <?php echo  $enableNotAssignedStaffIds ? render_select('notAssignedStaffIds[]', $staff, array('staffid', array('firstname', 'lastname')), _l('dont_have_staff'),  $status['notAssignedStaff'], ['multiple' => 1], [], '', '', false) : ''; ?>

                <?php echo render_select('avalibleStatusesForChange[]', $statuses, array('id', array('name')), _l('can_change_to'), $status['avalibleStatusesForChange'], ['multiple' => 1], [], '', '', false); ?>

                <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>


                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    init_color_pickers();
    init_selectpicker();

    $(function() {
        appValidateForm($('form'), {
            name: 'required',
            color: 'required',
            order: 'required',

        })
    });
</script>
</body>

</html>