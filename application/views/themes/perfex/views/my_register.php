<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-10 row col-md-offset-1 mbot15">
    <div class="col-md-6">
        <h1 class="tw-font-semibold register-heading text-right">
            <?php echo _l('clients_register_heading'); ?>
        </h1>
    </div>
    <div class="col-md-3 mtop15">
        <?php if (!is_language_disabled()) { ?>
        <div class="form-group">
            <select name="language" id="language" class="form-control selectpicker"
                onchange="change_contact_language(this)"
                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true">
                <?php $selected = (get_contact_language() != '') ? get_contact_language() : get_option('active_language'); ?>
                <?php foreach ($this->app->get_available_languages() as $availableLanguage) { ?>
                <option value="<?php echo $availableLanguage; ?>"
                    <?php echo ($availableLanguage == $selected) ? 'selected' : '' ?>>
                    <?php echo ucfirst($availableLanguage); ?>
                </option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>
    </div>
</div>
<div class="col-md-10 col-md-offset-1">
    <?php echo form_open('authentication/register', ['id' => 'register-form']); ?>
    <div class="panel_s">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="bold register-contact-info-heading"><?php echo _l('client_register_contact_info'); ?>
                    </h4>
                    <div class="form-group mtop15 register-firstname-group">
                        <label class="control-label" for="<?php echo $fields['firstname']; ?>">
                            <span class="text-danger">*</span> <?php echo _l('clients_firstname'); ?>
                        </label>
                        <input type="text" class="form-control" name="<?php echo $fields['firstname']; ?>"
                            id="<?php echo $fields['firstname']; ?>"
                            value="<?php echo set_value($fields['firstname']); ?>">
                        <?php echo form_error($fields['firstname']); ?>
                    </div>
                    <div class="form-group register-lastname-group">
                        <label class="control-label" for="<?php echo $fields['lastname']; ?>"><span
                                class="text-danger">*</span> <?php echo _l('clients_lastname'); ?></label>
                        <input type="text" class="form-control" name="<?php echo $fields['lastname']; ?>"
                            id="<?php echo $fields['lastname']; ?>"
                            value="<?php echo set_value($fields['lastname']); ?>">
                        <?php echo form_error($fields['lastname']); ?>
                    </div>
                    <div class="form-group register-email-group">
                        <label class="control-label" for="<?php echo $fields['email']; ?>"><span
                                class="text-danger">*</span> <?php echo _l('clients_email'); ?></label>
                        <input type="email" class="form-control" name="<?php echo $fields['email']; ?>"
                            id="<?php echo $fields['email']; ?>" value="<?php echo set_value($fields['email']); ?>">
                        <?php echo form_error($fields['email']); ?>
                    </div>
                    <div class="form-group register-contact-phone-group">
                        <label class="control-label"
                            for="contact_phonenumber"><?php echo _l('clients_phone'); ?></label>
                        <input type="text" class="form-control" name="contact_phonenumber" id="contact_phonenumber"
                            value="<?php echo set_value('contact_phonenumber'); ?>">
                    </div>
                    <div class="form-group register-website-group">
                        <label class="control-label" for="website"><?php echo _l('client_website'); ?></label>
                        <input type="text" class="form-control" name="website" id="website"
                            value="<?php echo set_value('website'); ?>">
                    </div>
                    <div class="form-group register-position-group">
                        <label class="control-label" for="title"><?php echo _l('contact_position'); ?></label>
                        <input type="text" class="form-control" name="title" id="title"
                            value="<?php echo set_value('title'); ?>">
                    </div>
                    <div class="form-group register-password-group">
                        <label class="control-label" for="password"><span class="text-danger">*</span>
                            <?php echo _l('clients_register_password'); ?></label>
                        <input type="password" class="form-control" name="password" id="password">
                        <?php echo form_error('password'); ?>
                    </div>
                    <div class="form-group register-password-repeat-group">
                        <label class="control-label" for="passwordr"><span class="text-danger">*</span>
                            <?php echo _l('clients_register_password_repeat'); ?></label>
                        <input type="password" class="form-control" name="passwordr" id="passwordr">
                        <?php echo form_error('passwordr'); ?>
                    </div>
                    <div class="form-group register-zip-group">
                        <label class="control-label" for="zip"><?php echo _l('clients_zip'); ?></label>
                        <input type="text" class="form-control" name="zip" id="zip"
                            value="<?php echo set_value('zip'); ?>">
                    </div>
                    <?php if (get_option('registration_accept_identity_confirmation') == '1'): ?>
                        <p class="bold" id="signatureLabel"><?php echo _l('signature'); ?></p>
                        <div class="signature-pad--body">
                          <canvas id="signature" height="130" width="550"></canvas>
                        </div>
                        <input type="text" style="width:1px; height:1px; border:0px;" tabindex="-1" name="signature" id="signatureInput">
                        <div class="dispay-block">
                          <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" data-action="clear"><?php echo _l('clear'); ?></button>
                          <button type="button" class="btn btn-default btn-xs" tabindex="-1" data-action="undo"><?php echo _l('undo'); ?></button>
                        </div>
                    <?php endif ?>
                    <div class="register-contact-custom-fields">
                        <?php echo render_custom_fields('contacts', '', ['show_on_client_portal' => 1]); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="bold register-company-info-heading"><?php echo _l('client_register_company_info'); ?>
                    </h4>
                    <div class="form-group mtop15 register-company-group">
                        <label class="control-label" for="<?php echo $fields['company']; ?>">
                            <?php if (get_option('company_is_required') == 1) { ?>
                            <span class="text-danger">*</span>
                            <?php } ?>
                            <?php echo _l('clients_company'); ?>
                            <!-- Custom modifications START -->
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Value shoud be only alphanumeric with spaces. If any spacial character inputed it will be removed automatically"></i>
                            <!-- Custom modifications END -->
                        </label>
                        <input type="text" class="form-control" name="<?php echo $fields['company']; ?>"
                            id="<?php echo $fields['company']; ?>" value="<?php echo set_value($fields['company']); ?>">
                        <?php echo form_error($fields['company']); ?>
                    </div>
                    <!-- Custom modifications: START -->
                    <?php if($this->session->userdata("selectedPlan")): ?>
                    <div class="form-group mtop15 register-tenant-group">
                        <label class="control-label" for="tenants_name">
                            <span class="text-danger">*</span>
                            <?php echo _l('tenants_name'); ?>
                            <span class="label label-info"><span id="display_subdomain">___</span>.<?php echo parse_url(base_url())['host'] ?></span>
                        </label>
                        <input type="text" class="form-control" name="tenants_name" id="tenants_name" value="<?php echo set_value('tenants_name'); ?>">
                            <?php echo form_error('tenants_name'); ?>
                    </div>
                    <div class="form-group mtop15 register-plan-group">
                        <label class="control-label" for="plan_name">
                            <span class="text-danger">*</span>
                            <?php echo _l('plan_name'); ?>
                            <!-- <span class="btn btn-danger btn-xs resetPlan"><i class="fa fa-times"></i> Remove</span> -->
                        </label>
                        <input type="text" class="form-control" name="plan_name" id="plan_name" value="<?php echo getSaasPlans($this->session->userdata("selectedPlan"))['plan_name']; ?>" disabled>
                            <?php echo form_error('plan_name'); ?>
                    </div>
                    <?php endif; ?>
                    <!-- Custom modifications: END -->
                    <?php if (get_option('company_requires_vat_number_field') == 1) { ?>
                    <div class="form-group register-vat-group">
                        <label class="control-label" for="vat"><?php echo _l('clients_vat'); ?></label>
                        <input type="text" class="form-control" name="vat" id="vat"
                            value="<?php echo set_value('vat'); ?>">
                    </div>
                    <?php } ?>
                    <div class="form-group register-company-phone-group">
                        <label class="control-label" for="phonenumber"><?php echo _l('clients_phone'); ?></label>
                        <input type="text" class="form-control" name="phonenumber" id="phonenumber"
                            value="<?php echo set_value('phonenumber'); ?>">
                    </div>
                    <div class="form-group register-country-group">
                        <label class="control-label" for="lastname"><?php echo _l('clients_country'); ?></label>
                        <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                            data-live-search="true" name="country" class="form-control" id="country">
                            <option value=""></option>
                            <?php foreach (get_all_countries() as $country) { ?>
                            <option value="<?php echo $country['country_id']; ?>" <?php if (get_option('customer_default_country') == $country['country_id']) {
    echo ' selected';
} ?> <?php echo set_select('country', $country['country_id']); ?>><?php echo $country['short_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group register-city-group">
                        <label class="control-label" for="city"><?php echo _l('clients_city'); ?></label>
                        <input type="text" class="form-control" name="city" id="city"
                            value="<?php echo set_value('city'); ?>">
                    </div>
                    <div class="form-group register-address-group">
                        <label class="control-label" for="address"><?php echo _l('clients_address'); ?></label>
                        <input type="text" class="form-control" name="address" id="address"
                            value="<?php echo set_value('address'); ?>">
                    </div>
                    <div class="form-group register-state-group">
                        <label class="control-label" for="state"><?php echo _l('clients_state'); ?></label>
                        <input type="text" class="form-control" name="state" id="state"
                            value="<?php echo set_value('state'); ?>">
                    </div>
                    <div class="register-company-custom-fields">
                        <?php echo render_custom_fields('customers', '', ['show_on_client_portal' => 1]); ?>
                    </div>
                </div>
                <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions') == 1) { ?>
                <div class="col-md-12 register-terms-and-conditions-wrapper">
                    <div class="text-center">
                        <div class="checkbox">
                            <input type="checkbox" name="accept_terms_and_conditions" id="accept_terms_and_conditions"
                                <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                            <label for="accept_terms_and_conditions">
                                <?php echo _l('gdpr_terms_agree', terms_url()); ?>
                            </label>
                        </div>
                        <?php echo form_error('accept_terms_and_conditions'); ?>
                    </div>
                </div>
                <?php } ?>

                <?php if ($honeypot) { ?>
                <label class="honey-element" for="firstname"></label>
                <input class="honey-element" autocomplete="off" type="text" id="firstname" name="firstname"
                    placeholder="Your first name here">
                <label class="honey-element" for="lastname"></label>
                <input class="honey-element" autocomplete="off" type="text" id="lastname" name="lastname"
                    placeholder="Your last name here">
                <label class="honey-element" for="email"></label>
                <input class="honey-element" autocomplete="off" type="email" id="email" name="email"
                    placeholder="Your e-mail here">
                <label class="honey-element" for="company"></label>
                <input class="honey-element" autocomplete="off" type="text" id="company" name="company"
                    placeholder="Your company here">
                <?php } ?>

                <?php if (show_recaptcha_in_customers_area()) { ?>
                <div class="col-md-12 register-recaptcha">
                    <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                    <?php echo form_error('g-recaptcha-response'); ?>
                </div>
                <?php } ?>
            </div>
            <?php if (!check_server_settings() && !empty(get_option('mysql_verification_message'))): ?>
                <div class="text-center">
                    <span class="text-danger">
                        <strong><?php echo _l('sorry') ?>! </strong><?php echo _l('you_cannot_register_at_the_moment') ?>
                    </span>
                </div>
            <?php endif ?>
        </div>
        <div class="panel-footer text-right">
            <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>"
                class="btn btn-primary" <?php echo (!check_server_settings() && !empty(get_option('mysql_verification_message'))) ? 'disabled' : '' ?>>
                <?php echo _l('clients_register_string'); ?>
            </button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<!-- Custom modifications by CIJAGANI: START -->
<?php if (!IS_TENANT): ?>
    <script type="text/javascript">
      var base_url = "<?php echo base_url(); ?>";

      $(document).on('keyup', '#tenants_name, #company', function(event) {
        let $this = $(this),
            value = $this.val().replace(/[^a-zA-Z0-9 ]/g, "").toLowerCase();

        $this.val(value);
        value = value.replace(/ /g,'');
        $("#tenants_name").val(value);
        $("#display_subdomain").html(value);
      });

      $(document).on('blur', '#tenants_name, #company', function(event) {
        let tenants_name = $("#tenants_name").val();

        $("button[type='submit']").prop('disabled', true);

        $.post(base_url + "saas/superadmin/validateTenantsName",{ tenants_name }, function(result) {
          if (result) {
            $("button[type='submit']").prop('disabled', false);
            return;
          }
          alert_float("danger","Tenant name already exist! Please try another name", 5000);
        }, "JSON");
      });

      $(document).on('click', '.resetPlan', function(event) {

        $.post(base_url + "saas/superadmin/resetCustomerPlan", function(result) {
            window.location.href = base_url+ "authentication/register";
        }, "JSON");
      });
    </script>
<?php endif ?>
<?php
  $this->app_scripts->theme('signature-pad','assets/plugins/signature-pad/signature_pad.min.js');
?>
<script>
  $(function(){
   SignaturePad.prototype.toDataURLAndRemoveBlanks = function() {
     var canvas = this._ctx.canvas;
       // First duplicate the canvas to not alter the original
       var croppedCanvas = document.createElement('canvas'),
       croppedCtx = croppedCanvas.getContext('2d');

       croppedCanvas.width = canvas.width;
       croppedCanvas.height = canvas.height;
       croppedCtx.drawImage(canvas, 0, 0);

       // Next do the actual cropping
       var w = croppedCanvas.width,
       h = croppedCanvas.height,
       pix = {
         x: [],
         y: []
       },
       imageData = croppedCtx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height),
       x, y, index;

       for (y = 0; y < h; y++) {
         for (x = 0; x < w; x++) {
           index = (y * w + x) * 4;
           if (imageData.data[index + 3] > 0) {
             pix.x.push(x);
             pix.y.push(y);

           }
         }
       }
       pix.x.sort(function(a, b) {
         return a - b
       });
       pix.y.sort(function(a, b) {
         return a - b
       });
       var n = pix.x.length - 1;

       w = pix.x[n] - pix.x[0];
       h = pix.y[n] - pix.y[0];
       var cut = croppedCtx.getImageData(pix.x[0], pix.y[0], w, h);

       croppedCanvas.width = w;
       croppedCanvas.height = h;
       croppedCtx.putImageData(cut, 0, 0);

       return croppedCanvas.toDataURL();
     };


     function signaturePadChanged() {

       var input = document.getElementById('signatureInput');
       var $signatureLabel = $('#signatureLabel');
       $signatureLabel.removeClass('text-danger');

       if (signaturePad.isEmpty()) {
         $signatureLabel.addClass('text-danger');
         input.value = '';
         return false;
       }

       $('#signatureInput-error').remove();
       var partBase64 = signaturePad.toDataURLAndRemoveBlanks();
       partBase64 = partBase64.split(',')[1];
       input.value = partBase64;
     }

     var canvas = document.getElementById("signature");
     var clearButton = wrapper.querySelector("[data-action=clear]");
     var undoButton = wrapper.querySelector("[data-action=undo]");
     var identityFormSubmit = document.getElementById('identityConfirmationForm');

     var signaturePad = new SignaturePad(canvas, {
      maxWidth: 2,
      onEnd:function(){
        signaturePadChanged();
      }
    });

     clearButton.addEventListener("click", function(event) {
       signaturePad.clear();
       signaturePadChanged();
     });

     undoButton.addEventListener("click", function(event) {
       var data = signaturePad.toData();
       if (data) {
           data.pop(); // remove the last dot or line
           signaturePad.fromData(data);
           signaturePadChanged();
         }
       });

     $('#register-form').submit(function() {
       signaturePadChanged();
     });
   });
 </script>
<!-- Custom modifications by CIJAGANI: END -->