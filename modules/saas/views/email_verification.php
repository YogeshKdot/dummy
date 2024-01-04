<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<div class="mtop40">
    <div class="col-md-4 col-md-offset-4 text-center">
        <h1 class="tw-font-semibold mbot20">
            <?php echo _l('find_my_tenant_login_url'); ?>
        </h1>
    </div>
    <div class="col-md-6 col-md-offset-3">
        <?php echo form_open($this->uri->uri_string(), ['class' => 'email-verification-form']); ?>
        <div class="panel_s">
            <div class="panel-body">
                <div class="mbot20">
                    <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                    <label class="form-label"><?php echo _l('confirm_your_email_address'); ?></label>
                    <input type="email" autofocus="true" class="form-control" name="email_address" id="email_address" placeholder="<?php echo _l('email'); ?>">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        <?php echo _l('send_and_submit'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>