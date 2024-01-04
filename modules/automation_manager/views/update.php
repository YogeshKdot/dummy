<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <?php echo form_open(admin_url('automation_manager/update/' . $automation['id'])); ?>

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"> <?= _l('Edit automation') ?> #<?= $automation['id'] ?> </h4>
                        <hr class="hr-panel-heading">
                        <?php echo render_input('name', 'name', $automation['name'], 'text', ['autofocus' => true]); ?>
                        <?php echo render_select('join', [['label' => _l('and'), 'value' => 'and'], ['label' => _l('or'), 'value' => 'or']], array('value', ['label']), _l('condition_join_type'), $automation['join'], [], [], '', '', false) ?>

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
                            <?= render_select('triggers[{{index}}][type]', $triggers, array('value', ['label']), _l('condition') . " {{index+1}}", '', ['data-object-id' => '{{index}}'], [], '', 'automationTriggerType') ?>
                            <div class="additional-params"></div>
                        </div>`
    let triggerCounter = -1

    const actionHtml = `<div class='action'>
                            <hr class="hr-panel-heading">
                            <?php echo render_select('actions[{{index}}][type]', $actions, array('value', ['label']), _l('action') . " {{index+1}}", '', ['data-object-id' => '{{index}}'], [], '', 'automationActionType') ?>
                            <div class="additional-params"></div>
                        </div>`
    let actionCounter = -1


    const additionalParametrs = {
        inactive: `<?= render_input('triggers[{{index}}][value]', _l('Days number'), '{{value}}', 'number', ['requred' => true, 'autofocus' => true, 'min' => 1]); ?>`,
        status: `<?= render_select('triggers[{{index}}][value]', $statuses, array('id', ['name']), _l('Status'), '', ['requred' => true], [], '', 'automationTriggerValue', false) ?>`,
        priority: `<?= render_select('triggers[{{index}}][value]', $priorities, array('id', ['name']), _l('Priority'), '', ['requred' => true],  [], '', 'automationTriggerValue', false) ?>`,
        custom_field: `<?= render_select('triggers[{{index}}][value]', $customFields, array('id', ['name']), _l('Custom field'), '', ['requred' => true],  [], '', 'automationTriggerValue', false) ?> <?= render_input('triggers[{{index}}][additional_argument]', _l('Custom field value') . ' &nbsp <i class="fa fa-info-circle" data-toggle="tooltip" data-title="' . _l("Supports operation: >, <, >=, <= and dynamic variables: \n {total_logged_time} - Total logged time") . '" title data-original-title></i>', '{{additional_argument}}', 'text', ['autofocus' => true, 'required' => true]); ?>`,


        change_status: `<?= render_select('actions[{{index}}][value]', $statuses, array('id', ['name']), _l('Status'), '', ['requred' => true],  [], '', 'automationActionValue', false) ?>`,
        add_comment: `<?= render_input('actions[{{index}}][value]', _l('Comment'), '{{value}}', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        add_timer: `<?= render_input('actions[{{index}}][value]', _l('Time'), '{{value}}', 'text', ['autofocus' => true, 'placeholder' => 'HH:MM', 'pattern' => "[0-9]{2}:[0-9]{2}", 'required' => true,],); ?>  <?= render_select('actions[{{index}}][additional_argument]', [...$staff, ['staffid' => false, 'full_name' => _l('Currently logged')]], array('staffid', ['full_name']), _l('Staff'), '', ['requred' => true],   [], '', 'automationActionAdditionalArgument', false) ?> <?= render_input('actions[{{index}}][additional_argument_2]', _l('Timer comment'), '{{additional_argument_2}}', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        change_priority: `<?= render_select('actions[{{index}}][value]', $priorities, array('id', ['name']), _l('Priority'), '', ['requred' => true],   [], '', 'automationActionValue', false) ?>`,
        set_assignee: `<?= render_select('actions[{{index}}][additional_argument]', [['label' => _l('Add'), 'value' => 'add'], ['label' => _l('replaceAll'), 'value' => 'replaceAll']], array('value', ['label']), _l('Type'), '', ['requred' => true],   [], '', 'automationActionAdditionalArgument', false) ?> <?= render_select('actions[{{index}}][value][]', $staff, array('staffid', ['full_name']), _l('Staff'), '', ['requred' => true, 'multiple' => true],   [], '', 'automationActionValue', false) ?>`,
        set_follower: `<?= render_select('actions[{{index}}][additional_argument]', [['label' => _l('Add'), 'value' => 'add'], ['label' => _l('replaceAll'), 'value' => 'replaceAll']], array('value', ['label']), _l('Type'),  '', [], [], '', 'automationActionAdditionalArgument', false) ?> <?= render_select('actions[{{index}}][value][]', $staff, array('staffid', ['full_name']), _l('Staff'), '', ['requred' => true, 'multiple' => true],  [], '', 'automationActionValue', false) ?>`,
        add_reminder: `<?= render_input('actions[{{index}}][value]', _l('Days after triggered'), '{{value}}', 'number', ['autofocus' => true, 'min' => 0, 'required' => true]); ?> <?= render_input('actions[{{index}}][additional_argument_2]', _l('Time'), '{{additional_argument_2}}', 'time', ['autofocus' => true, 'required' => true]); ?> <?= render_input('actions[{{index}}][additional_argument]', _l('Remainder text'), '{{additional_argument}}', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        set_custom_field: `<?= render_select('actions[{{index}}][value]', $customFields, array('id', ['name']), _l('Custom field'), '', ['requred' => true],  [], '', 'automationActionValue', false) ?> <?= render_input('actions[{{index}}][additional_argument]', _l('Custom field value'), '{{additional_argument}}', 'text', ['autofocus' => true, 'required' => true]); ?>  `,
        add_tag: `<?= render_select('actions[{{index}}][additional_argument]', [['label' => _l('Add'), 'value' => 'add'], ['label' => _l('Remove all and add'), 'value' => 'remove_all_and_add'], ['label' => _l('Remove'), 'value' => 'remove']], array('value', ['label']), _l('Type'), '', ['requred' => true],  [], '', 'automationActionAdditionalArgument', false) ?> <?= render_input('actions[{{index}}][value]', _l('Tag'), '{{value}}', 'text', ['autofocus' => true, 'required' => true]); ?>`,
        change_due_date: `<?= render_input('actions[{{index}}][value]', _l('Days'), '{{value}}', 'number', ['autofocus' => true, 'min' => -1000, 'max' => 1000, 'required' => true]); ?>`,

    }



    document.querySelector('#addTrigger').addEventListener('click', () => {
        triggerCounter++;
        let triggerElement = triggerHtml.replaceAll('{{index}}', triggerCounter).replaceAll('{{index+1}}', triggerCounter + 1).replaceAll('{{value}}', '')
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



    function initiateParams(cssSelector) {
        document.querySelectorAll(cssSelector).forEach(trigger => {
            trigger.querySelector('select').removeEventListener('change', addParamFieldToTrigger);
            trigger.querySelector('select').addEventListener('change', addParamFieldToTrigger)
        })
    }

    function addTriggeerWithData(type, value, additional_argument = '') {
        triggerCounter++;
        let triggerElement = triggerHtml.replaceAll('{{index}}', triggerCounter).replaceAll('{{index+1}}', triggerCounter + 1).replaceAll('{{value}}', type)
        document.querySelector('.triggers').insertAdjacentHTML('beforeend', triggerElement)

        let additionalParametr = additionalParametrs[type] ?? ''

        additionalParametr = additionalParametr.replaceAll('{{index}}', triggerCounter);
        additionalParametr = additionalParametr.replaceAll('{{index+1}}', triggerCounter + 1);
        additionalParametr = additionalParametr.replaceAll('{{value}}', value);
        additionalParametr = additionalParametr.replaceAll('{{additional_argument}}', additional_argument);

        document.querySelector('.trigger:last-child .additional-params').innerHTML = additionalParametr;

        document.querySelector(`.trigger:last-child select.automationTriggerType option[value="${type}"]`)?.setAttribute('selected', true)

        document.querySelector(`.trigger:last-child select.automationTriggerValue option[value="${value}"]`)?.setAttribute('selected', true)

        value?.split(',').forEach(elem => {
            document.querySelector(`.action:last-child select.automationActionValue option[value="${elem}"]`)?.setAttribute('selected', true)
        })

        document.querySelector(`.trigger:last-child select.automationTriggerAdditionalArgument option[value="${additional_argument}"]`)?.setAttribute('selected', true)

        init_selectpicker()
    }

    function addActionWithData(type, value, additional_argument = '', additional_argument_2 = '') {
        actionCounter++;
        let actionElement = actionHtml.replaceAll('{{index}}', actionCounter).replaceAll('{{index+1}}', actionCounter + 1)
        document.querySelector('.actions').insertAdjacentHTML('beforeend', actionElement)
        let additionalParametr = additionalParametrs[type] ?? ''

        additionalParametr = additionalParametr.replaceAll('{{index}}', actionCounter);
        additionalParametr = additionalParametr.replaceAll('{{index+1}}', actionCounter + 1);
        additionalParametr = additionalParametr.replaceAll('{{value}}', value);
        additionalParametr = additionalParametr.replaceAll('{{additional_argument}}', additional_argument);
        additionalParametr = additionalParametr.replaceAll('{{additional_argument_2}}', additional_argument_2);

        document.querySelector('.action:last-child .additional-params').innerHTML = additionalParametr;

        document.querySelector(`.action:last-child select.automationActionType option[value="${type}"]`)?.setAttribute('selected', true)

        value?.split(',').forEach(elem => {
            document.querySelector(`.action:last-child select.automationActionValue option[value="${elem}"]`)?.setAttribute('selected', true)
        })

        document.querySelector(`.action:last-child select.automationActionAdditionalArgument option[value="${additional_argument}"]`)?.setAttribute('selected', true)

        init_selectpicker()
    }

    <?php
    foreach ($automation['triggers'] as $trigger) {
        echo "addTriggeerWithData('{$trigger['type']}','{$trigger['value']}','{$trigger['additional_argument']}') \n";
    }

    foreach ($automation['actions'] as $action) {
        echo "addActionWithData('{$action['type']}','{$action['value']}','{$action['additional_argument']}','{$action['additional_argument_2']}') \n";
    }
    ?>

    initiateParams('.trigger')
    initiateParams('.action')

    function addParamFieldToTrigger(event) {
        let value = event.target.value
        let additionalParametr = additionalParametrs[value] ?? ''
        additionalParametr = additionalParametr.replaceAll('{{index}}', event.target.dataset.objectId);
        additionalParametr = additionalParametr.replaceAll('{{index+1}}', event.target.dataset.objectId + 1);
        additionalParametr = additionalParametr.replaceAll('{{value}}', '');
        additionalParametr = additionalParametr.replaceAll('{{additional_argument}}', '');
        additionalParametr = additionalParametr.replaceAll('{{additional_argument_2}}', '');

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