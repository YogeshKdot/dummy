<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open_multipart((!isset($tab['update_url']) ? $this->uri->uri_string() . '?group=' . $tab['slug'] . ($this->input->get('tab') ? '&active_tab=' . $this->input->get('tab') : '') : $tab['update_url']), ['id' => 'settings-form', 'class' => isset($tab['update_url']) ? 'custom-update-url' : '']); ?>
        <div class="row">
            <?php if ($this->session->flashdata('debug')) { ?>
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        <?php echo $this->session->flashdata('debug'); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="btn-bottom-toolbar text-right">
                <button type="submit" class="btn btn-primary">
                    <?php echo _l('settings_save'); ?>
                </button>
            </div>
            <div class="col-md-12">
                <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
                    <?php echo $tab['name']; ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php hooks()->do_action('before_settings_group_view', $tab); ?>
                        <?php $this->load->view($tab['view']) ?>
                        <?php hooks()->do_action('after_settings_group_view', $tab); ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php echo form_close(); ?>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>
<script>
    $(function() {
        var slug = "<?php echo $tab['slug']; ?>";
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var settingsForm = $('#settings-form');

            if (settingsForm.hasClass('custom-update-url')) {
                return;
            }

            var tab = $(this).attr('href').slice(1);
            settingsForm.attr('action', '<?php echo site_url($this->uri->uri_string()); ?>?group=' + slug +
                              '&active_tab=' + tab);
        });

        $('input[name="settings[mail_engine]"]').on('change', function() {
            if ($(this).val() == 'codeigniter') {
                $('.protocol-microsoft').addClass('hide');
                $('.protocol-google').addClass('hide');

                if ($('input[name="settings[email_protocol]"]:checked').val() == 'microsoft') {
                    $('#smtp').prop('checked', true)
                    $('#microsoft').trigger('change')
                }

                if ($('input[name="settings[email_protocol]"]:checked').val() == 'google') {
                    $('#smtp').prop('checked', true)
                    $('#google').trigger('change')
                }
            } else {
                $('.protocol-microsoft').removeClass('hide');
                $('.protocol-google').removeClass('hide');
            }
        });

        $('input[name="settings[email_protocol]"]').on('change', function() {
            var $inputHost = $('input[name="settings[smtp_host]"]');
            var $inputPort = $('input[name="settings[smtp_port]"]');
            var $selectEnc = $('select[name="settings[smtp_encryption]"]');

            var resetFields = function() {
                if ($selectEnc.hasClass('_modified')) {
                    $selectEnc.selectpicker('val', '');
                    $selectEnc.removeClass('_modified');
                }

                if ($inputPort.hasClass('_modified')) {
                    $inputPort.val('');
                    $inputPort.removeClass('_modified');
                }

                if ($inputHost.hasClass('_modified')) {
                    $inputHost.val('');
                    $inputHost.removeClass('_modified');
                }
            }

            if ($(this).val() == 'mail') {
                $('.xoauth-hide').addClass('hide');
                $('.smtp-fields').addClass('hide');
                $('.xoauth-microsoft-show').addClass('hide');
                $('.xoauth-google-show').addClass('hide');
                resetFields();
            } else if ($(this).val() === 'microsoft' || $(this).val() === 'google') {
                $('.smtp-fields').removeClass('hide');
                $('.xoauth-hide').addClass('hide');
                $('.xoauth-microsoft-show').addClass('hide');
                $('.xoauth-google-show').addClass('hide');

                if($(this).val() === 'microsoft') {
                    $('.xoauth-microsoft-show').removeClass('hide');
                    if ($inputHost.val() == '') {
                        $inputHost.val('smtp.office365.com')
                        $inputHost.addClass('_modified');
                    }
                }

                if($(this).val() === 'google') {
                    $('.xoauth-google-show').removeClass('hide');
                    if ($inputHost.val() == '') {
                        $inputHost.val('smtp.gmail.com')
                        $inputHost.addClass('_modified');
                    }
                }

                if ($inputPort.val() == '') {
                    $inputPort.val('587')
                    $inputPort.addClass('_modified');
                    if ($selectEnc.selectpicker('val') == '') {
                        $selectEnc.selectpicker('val', 'tls');
                        $selectEnc.addClass('_modified');
                    }
                }
            } else {
                $('.smtp-fields').removeClass('hide');
                $('.xoauth-hide').removeClass('hide');
                $('.xoauth-microsoft-show').addClass('hide');
                $('.xoauth-google-show').addClass('hide');
                resetFields();
            }
        });

        $('.sms_gateway_active input').on('change', function() {
            if ($(this).val() == '1') {
                $('body .sms_gateway_active').not($(this).parents('.sms_gateway_active')[0]).find(
                                                                                                  'input[value="0"]').prop('checked', true);
            }
        });

        <?php if ($tab['slug'] == 'pusher') {
            if (get_option('desktop_notifications') == '1') {
                ?>
// Let's check if the browser supports notifications
                if (!("Notification" in window)) {
                    $('#pusherHelper').html(
                                            '<div class="alert alert-danger">Your browser does not support desktop notifications, please disable this option or use more modern browser.</div>'
                                            );
                } else if (Notification.permission == "denied") {
                    $('#pusherHelper').html(
                                            '<div class="alert alert-danger">Desktop notifications not allowed in browser settings, search on Google "How to allow desktop notifications for <?php echo $this->agent->browser(); ?>"</div>'
                                            );
                }
                <?php
            } ?>
            <?php if (get_option('pusher_realtime_notifications') == '0') {
                ?>
                $('input[name="settings[desktop_notifications]"]').prop('disabled', true);
                <?php
            } ?>
            <?php
        } ?>

        $('input[name="settings[pusher_realtime_notifications]"]').on('change', function() {
            if ($(this).val() == '1') {
                $('input[name="settings[desktop_notifications]"]').prop('disabled', false);
            } else {
                $('input[name="settings[desktop_notifications]"]').prop('disabled', true);
                $('input[name="settings[desktop_notifications]"][value="0"]').prop('checked', true);
            }
        });

        $('.test_email').on('click', function() {
            var email = $('input[name="test_email"]').val();
            if (email != '') {
                $(this).attr('disabled', true);
                $.post(admin_url + 'emails/sent_smtp_test_email', {
                    test_email: email
                }).done(function(data) {
                    window.location.reload();
                });
            }
        });

        $('#update_app').on('click', function(e) {
            e.preventDefault();
            $('input[name="settings[purchase_key]"]').parents('.form-group').removeClass('has-error');
            var purchase_key = $('input[name="settings[purchase_key]"]').val();
            var latest_version = $('input[name="latest_version"]').val();
            var upgrade_function = $('input[name="upgrade_function"]:checked').val();
            var update_errors;
            if (purchase_key != '') {
                var ubtn = $(this);
                ubtn.html('<?php echo _l('wait_text'); ?>');
                ubtn.addClass('disabled');
                $.post(admin_url + 'auto_update', {
                    purchase_key: purchase_key,
                    latest_version: latest_version,
                    auto_update: true,
                    upgrade_function: upgrade_function
                }).done(function() {
                    window.location.reload();
                }).fail(function(response) {
                    update_errors = JSON.parse(response.responseText);
                    $('#update_messages').html('<div class="alert alert-danger"></div>');
                    for (var i in update_errors) {
                        $('#update_messages .alert').append('<p>' + update_errors[i] + '</p>');
                    }
                    ubtn.removeClass('disabled');
                    ubtn.html($('.update_app_wrapper').data('original-text'));
                });
            } else {
                $('input[name="settings[purchase_key]"]').parents('.form-group').addClass('has-error');
            }
        });
    });

$('input[name="settings[reminder_for_completed_but_not_billed_tasks]"]').on('change', function() {
    if ($(this).val() == '1') {
        $('.staff_notify_completed_but_not_billed_tasks_fields').removeClass('hide');
    } else {
        $('.staff_notify_completed_but_not_billed_tasks_fields').addClass('hide');
    }
});
</script>
<?php hooks()->do_action('settings_group_end', $tab); ?>
</body>

</html>