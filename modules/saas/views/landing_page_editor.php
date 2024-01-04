<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
    	<div class="panel_s">
    		<div class="panel-body">
    			<!-- Horizontal tabs : start -->
                <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                    <div class="horizontal-tabs">
                        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#legal_settings" aria-controls="legal_settings" role="tab" data-toggle="tab">
                                    <?php echo _l('legal_settings'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-danger" font-medium="">
                    <?php echo _l('this_feature_remove_in_next_version'); ?>
                </div>
                <!-- Horizontal tabs : over -->
                <?php echo form_open('', ['id' => 'legal-settings-form']); ?>
                <?php render_yes_no_option('gdpr_show_terms_of_use_in_footer', 'Show Terms Of Use in landing page footer'); ?>
                <hr />
                <?php render_yes_no_option('gdpr_show_terms_and_conditions_in_footer', 'Show Terms & Conditions in customers area footer'); ?>
                <hr />
                <?php render_yes_no_option('gdpr_show_privacy_policy_in_footer', 'Show Privacy Policy in landing page footer'); ?>
                <hr />
                <p class="">
                    <?php echo _l('terms_of_use'); ?>
                    <br />
                    <a href="<?php echo site_url('saas/terms-of-use'); ?>" target="_blank"><?php echo site_url('saas/terms-of-use'); ?></a>
                </p>
                <?php echo render_textarea('settings[terms_of_use]', '', get_option('terms_of_use'), [], [], '', 'tinymce'); ?>
                <hr />
                <a href="<?php echo admin_url('gdpr/index?page=informed'); ?>"><?php echo _l('terms_and_conditions'); ?></a>
                <hr />
                <a href="<?php echo admin_url('gdpr/index?page=informed'); ?>"><?php echo _l('privacy_policy'); ?></a>
                <button type="submit" class="btn btn-primary pull-right"><?php echo _l('save'); ?></button>
                <?php echo form_close(); ?>
    		</div>
    	</div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    $(function() {
        function save_legal_settings(form) {
            $.ajax({
                url: `${admin_url}saas/landing_page_editor`,
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
            })
            .done(function(res) {
                !empty(res.type)
                    ? alert_float(res.type, res.message)
                    : '';
            });
        }
        appValidateForm($('#legal-settings-form'), {}, save_legal_settings);
    });
</script>
