<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <a href="#" onclick="enable_frontend(); return false;" class="btn btn-info">Enable for Clients Area</a>
                        <a href="#" onclick="disable_frontend(); return false;" class="btn btn-info">Disable for Clients Area</a>
                        <a href="#" onclick="enable_backend(); return false;" class="btn btn-info">Enable for Admin Area</a>
                        <a href="#" onclick="disable_backend(); return false;" class="btn btn-info">Disable for Admin Area</a>
                        <div class="form-group">
                        <br>
                        <h5>Currently enabled in Clients Area: <?php if(get_option('aio_support_frontend') == 1) { echo 'Yes'; } if(get_option('aio_support_frontend') == 0) { echo 'No'; } ?></h5>
                        <h5>Currently enabled in Admin Area: <?php if(get_option('aio_support_backend') == 1) { echo 'Yes'; } if(get_option('aio_support_backend') == 0) { echo 'No'; } ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4>AIO Support Contact Configuration</h4>
                        <h5>Please use your mobile phone in international format and no spaced, i.e: +336941636578.</h5>
                        <br>
                        <br>
                        <div class="form-group">
                            <label class="bold" for="support_contact_viber">Mobile phone for Viber: <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Enter your Viber's support phone number"></i></label>
                            <br><input name="support_contact_viber" id="support_contact_viber" class="form-control" value="<?php echo get_option('support_contact_viber'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="bold" for="support_contact_whatsapp">Mobile phone for WhatsApp: <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Enter your WhatsApp's support phone number"></i></label>
                            <br><input name="support_contact_whatsapp" id="support_contact_whatsapp" class="form-control" value="<?php echo get_option('support_contact_whatsapp'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="bold" for="support_contact_messenger_username">Facebook Messenger username: <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Enter your Facebook Messenger username"></i></label>
                            <input name="support_contact_messenger_username" id="support_contact_messenger_username" class="form-control" value="<?php echo get_option('support_contact_messenger_username'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="bold" for="support_contact_email_address">Support email address: <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Enter your support's email address"></i></label>
                            <input name="support_contact_email_address" id="support_contact_email_address" class="form-control" value="<?php echo get_option('support_contact_email_address'); ?>">
                        </div>
                        <br>
                        <a href="#" onclick="save_support_contact(); return false;" class="btn btn-info">Save Changes</a>
                        <br>
                        <br>
                        <br>
                        <span><i>Thank you for using our module!
                        <br>
                        If you face any issues, our team is always ready to help you at <a href="https://themesic.com/support" target="_blank"><b>Clients Area</b></a>
                        <br>
                        <br>
                        Rating our module <a href="https://codecanyon.net/downloads">here</a> will help us continue developing it!</i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url('modules/support_contact/assets/admin.js'); ?>"></script>
</body>
</html>