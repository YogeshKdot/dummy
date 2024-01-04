<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php if (empty(getClientPlan($client->userid))) { ?>
     <?php if (has_permission('customers', '', 'create')) { ?>
    <?php echo form_open(admin_url(SUPERADMIN_MODULE.'/plans/assign_plan_to_client_create_tenant/'.$client->userid), ['id' => 'assign_plan_to_client_create_tenant']); ?>
        <div class="alert alert-danger">
            <?php echo _l('tenant_assign_message'); ?>
        </div>
        <div class="form-group select-placeholder" id="userContacts">
            <label for="contactid"><?php echo _l('contact'); ?></label>
            <select name="contactid" required="true" id="contactid" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                <?php if (isset($contact)) { ?>
                    <option value="<?php echo $contact['id']; ?>" selected>
                        <?php echo $contact['firstname'].' '.$contact['lastname']; ?>
                    </option>
                <?php } ?>
                <option value=""></option>
            </select>
            <?php echo form_hidden('userid'); ?>
        </div>
        <?php echo render_select('tenant_plan', getSaasPlans(), ['id', 'plan_name', 'price'], _l('tenant_plan')); ?>
        <div class="form-group" app-field-wrapper="tenants_name">
            <label for="tenants_name" class="control-label">
                Tenants Name
                <span class="label label-info"><span id="display_subdomain">___</span>.<?php echo parse_url(base_url())['host']; ?></span>
            </label>
            <input type="text" id="tenants_name" name="tenants_name" class="form-control" value="<?php echo $client->company; ?>">
        </div>
        <div class="pull-right">
            <?php if (!check_server_settings()): ?>
                <span class="label label-warning mright5">
                    <a href="<?php echo admin_url('settings?group=saas') ?>"><?php echo _l('click_here') ?></a>&nbsp;
                    <?php echo _l('set_mysql_server_settings_properly') ?>
                </span>
            <?php else: ?>
                <button type="submit" id="submit" class="btn-tr btn btn-info transaction-submit"><?php echo _l('submit'); ?></button>
            <?php endif ?>
        </div>
        <?php echo form_close(); ?>
    <?php } else { ?>
        <div class="alert alert-danger">
            <?php echo _l('not_permission_to_assign_plan_to_clients'); ?>
        </div>
    <?php } ?>
<?php } ?>

<script>
    var userid = "<?php echo $client->userid; ?>";
</script>
<script defer src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/js/client_js.js').'?v='.$this->app_scripts->core_version(); ?>"></script>