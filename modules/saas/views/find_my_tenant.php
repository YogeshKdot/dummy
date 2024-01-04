<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<div class="mtop40">
    <div class="col-md-6 col-md-offset-3 text-center">
        <h1 class="tw-font-semibold mbot20">
            <?php echo _l('find_my_tenant'); ?>
        </h1>
    </div>
    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <?php echo form_open($this->uri->uri_string(), ['class' => 'find-tenant-form']); ?>
        <div class="panel_s">
            <div class="panel-body">

                <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

                <div class="input-group mbot20">
                    <input type="text" autofocus="true" class="form-control" name="tenants_name" id="tenants_name">
                    <span class="input-group-addon">.<?php echo getDomain(); ?></span>
                </div>

                <?php if (show_recaptcha_in_customers_area()) { ?>
                <div class="g-recaptcha tw-mb-4" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                <?php echo form_error('g-recaptcha-response'); ?>
                <?php } ?>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        <?php echo _l('customer_forgot_password_submit'); ?>
                    </button>
                </div>

                <p><?php echo _l('dont_know_your_tenants_login_url'); ?> ? <a href="<?php echo site_url(SUPERADMIN_MODULE.'/tenants/email_verification'); ?>"><?php echo _l('find_my_tenant_login'); ?></a></p>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>