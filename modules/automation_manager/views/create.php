<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <?php echo form_open(admin_url('automation_manager/store')); ?>

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"> <?= _l('Add new automation ') ?> </h4>
                        <hr class="hr-panel-heading">
                        <?php echo render_input('name', 'name', '', 'text', ['autofocus' => true]); ?>
                        <?php echo render_select('join', [['label' => _l('and'), 'value' => 'and'], ['label' => _l('or'), 'value' => 'or']], array('value', ['label']), _l('condition_join_type'), '', [], [], '', '', false) ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"> <?= _l('Add automation triggers') ?> </h4>
                        <div class="triggers">

                            <div class='trigger'>
                                <hr class="hr-panel-heading">
                                <?php echo render_select('triggers[0][type]', $triggers, array('value', ['label']), _l('condition') . " 1", '', ['data-object-id' => 0]) ?>
                                <div class="additional-params"></div>
                            </div>

                        </div>
                        <!-- <hr class="hr-panel-heading"> -->
                        <button class="btn btn-info pull-right" type="button" id="addTrigger"><?php echo _l('Add trigger'); ?></button>


                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"> <?= _l('Add automation actions') ?> </h4>
                        <div class="actions">

                            <div class='action'>
                                <hr class="hr-panel-heading">
                                <?php echo render_select('actions[0][type]', $actions, array('value', ['label']), _l('action') . " 1", '', ['data-object-id' => 0]) ?>
                                <div class="additional-params"></div>
                            </div>
                        </div>

                        <!-- <hr class="hr-panel-heading"> -->
                        <button class="btn btn-info pull-right" type="button" id="addAction"><?php echo _l('Add action'); ?></button>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>


</div>

<?php init_tail(); ?>
<script>
    const triggerHtml = `<div class='trigger'>
                            <hr class="hr-panel-heading">
                            <?= render_select('triggers[{{index}}][type]', $triggers, array('value', ['label']), _l('condition') . " {{index+1}}", '', ['data-object-id' => '{{index}}']) ?>
                            <div class="additional-params"></div>
                        </div>`
    let triggerCounter = 0

    const actionHtml = `<div class='action'>
                            <hr class="hr-panel-heading">
                            <?php echo render_select('actions[{{index}}][type]', $actions, array('value', ['label']), _l('action') . " {{index+1}}", '', ['data-object-id' => '{{index}}']) ?>
                            <div class="additional-params"></div>
                        </div>`
    let actionCounter = 0


    const additionalParametrs = {
        inactive: `<?= render_input('triggers[{{index}}][value]', _l('Days number'), '', 'number', ['requred' => true, 'autofocus' => true, 'min' => 1]); ?>`,
        status: `<?= render_select('triggers[{{index}}][value]', $statuses, array('id', ['name']), _l('Status'), '', ['requred' => true], [], '', '', false) ?>`,
        priority: `<?= render_select('triggers[{{index}}][value]', $priorities, array('id', ['name']), _l('Priority'), '', ['requred' => true], [], '', '', false) ?>`,
        custom_field: `<?= render_select('triggers[{{index}}][value]', $customFields, array('id', ['name']), _l('Custom field'), '', ['requred' => true], [], '', '', false) ?> <?= render_input('triggers[{{index}}][additional_argument]', _l('Custom field value') . ' &nbsp <i class="fa fa-info-circle" data-toggle="tooltip" data-title="' . _l("Supports operation: >, <, >=, <= and dynamic variables: \n {total_logged_time} - Total logged time") . '" title data-original-title></i>', '', 'text', ['autofocus' => true, 'required' => true]); ?>  `,


        change_status: `<?= render_select('actions[{{index}}][value]', $statuses, array('id', ['name']), _l('Status'), '', ['requred' => true], [], '', '', false) ?>`,
        add_comment: `<?= render_input('actions[{{index}}][value]', _l('Comment'), '', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        add_timer: `<?= render_input('actions[{{index}}][value]', _l('Time'), '', 'text', ['autofocus' => true, 'placeholder' => 'HH:MM', 'pattern' => "[0-9]{2}:[0-9]{2}", 'required' => true]); ?>  <?= render_select('actions[{{index}}][additional_argument]', [...$staff, ['staffid' => false, 'full_name' => _l('Currently logged')]], array('staffid', ['full_name']), _l('Staff'), '', ['requred' => true], [], '', '', false) ?> <?= render_input('actions[{{index}}][additional_argument_2]', _l('Timer comment'), '', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        change_priority: `<?= render_select('actions[{{index}}][value]', $priorities, array('id', ['name']), _l('Priority'), '', ['requred' => true], [], '', '', false) ?>`,
        set_assignee: `<?= render_select('actions[{{index}}][additional_argument]', [['label' => _l('Add'), 'value' => 'add'], ['label' => _l('replaceAll'), 'value' => 'replaceAll']], array('value', ['label']), _l('Type'), '', ['requred' => true], [], '', '', false) ?> <?= render_select('actions[{{index}}][value][]', $staff, array('staffid', ['full_name']), _l('Staff'), '', ['requred' => true, 'multiple' => true], [], '', '', false) ?>`,
        set_follower: `<?= render_select('actions[{{index}}][additional_argument]', [['label' => _l('Add'), 'value' => 'add'], ['label' => _l('replaceAll'), 'value' => 'replaceAll']], array('value', ['label']), _l('Type'), '', [], [], '', '', false) ?> <?= render_select('actions[{{index}}][value][]', $staff, array('staffid', ['full_name']), _l('Staff'), '', ['requred' => true, 'multiple' => true], [], '', '', false) ?>`,
        add_reminder: `<?= render_input('actions[{{index}}][value]', _l('Days after triggered'), '1', 'number', ['autofocus' => true, 'min' => 0, 'required' => true]); ?> <?= render_input('actions[{{index}}][value_2]', _l('Time'), '', 'time', ['autofocus' => true, 'required' => true]); ?> <?= render_input('actions[{{index}}][additional_argument]', _l('Remainder text'), '', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        set_custom_field: `<?= render_select('actions[{{index}}][value]', $customFields, array('id', ['name']), _l('Custom field'), '', ['requred' => true], [], '', '', false) ?> <?= render_input('actions[{{index}}][additional_argument]', _l('Custom field value'), '', 'text', ['autofocus' => true, 'required' => true]); ?>  `,
        add_tag: `<?= render_select('actions[{{index}}][additional_argument]', [['label' => _l('Add'), 'value' => 'add'], ['label' => _l('Remove all and add'), 'value' => 'remove_all_and_add'], ['label' => _l('Remove'), 'value' => 'remove']], array('value', ['label']), _l('Type'), '', ['requred' => true], [], '', '', false) ?> <?= render_input('actions[{{index}}][value]', _l('Tag'), '', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        change_due_date: `<?= render_input('actions[{{index}}][value]', _l('Days number'), '', 'number', ['autofocus' => true, 'min' => -1000, 'max' => 1000, 'required' => true]); ?>`,
    }


    document.querySelector('#addTrigger').addEventListener('click', () => {
        triggerCounter++;
        let triggerElement = triggerHtml.replaceAll('{{index}}', triggerCounter).replaceAll('{{index+1}}', triggerCounter + 1)
        document.querySelector('.triggers').insertAdjacentHTML('beforeend', triggerElement)
        init_selectpicker()
        initiateParams('.trigger')
    })

    document.querySelector('#addAction').addEventListener('click', () => {
        actionCounter++;
        let actionElement = actionHtml.replaceAll('{{index}}', actionCounter).replaceAll('{{index+1}}', actionCounter + 1)
        document.querySelector('.actions').insertAdjacentHTML('beforeend', actionElement)
        init_selectpicker()
        initiateParams('.action')
    })

    initiateParams('.trigger')
    initiateParams('.action')

    function initiateParams(cssSelector) {
        document.querySelectorAll(cssSelector).forEach(trigger => {
            trigger.querySelector('select').removeEventListener('change', addParamFieldToTrigger);
            trigger.querySelector('select').addEventListener('change', addParamFieldToTrigger)
        })
    }


    function addParamFieldToTrigger(event) {
        let value = event.target.value
        let additionalParametr = additionalParametrs[value] ?? ''
        additionalParametr = additionalParametr.replaceAll('{{index}}', event.target.dataset.objectId);
        additionalParametr = additionalParametr.replaceAll('{{index+1}}', event.target.dataset.objectId + 1);

        let eventPath = event.path || event.composedPath()
        eventPath[3].querySelector('.additional-params').innerHTML = additionalParametr

        init_selectpicker()
    }

    $(function() {
        appValidateForm($('form'), {
            name: 'required',
            join: 'required',
            'triggers[0][type]': 'required',
            'actions[0][type]': 'required',

        })
    });
</script>

</body>

</html>