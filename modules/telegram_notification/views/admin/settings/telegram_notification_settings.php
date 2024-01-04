<?php
defined('BASEPATH') or exit('No direct script access allowed');


?>

<div class="horizontal-scrollable-tabs">
    <div class="scroller arrow-left" style="display: none;"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right" style="display: none;"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
            <li role="presentation" class="active">
                <a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a>
            </li>

        </ul>
    </div>
</div>

<div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="general">
        <div class="row">
            <div class="col-md-12">
                <h4><?php echo _l('settings_group_telegram_notification'); ?> Settings</h4>
                <p>These settings are required to enable <?php echo _l('settings_group_telegram_notification'); ?> - See Setup Documentation for more information</p>
                <hr />
                <?php echo render_yes_no_option("telegram_notification_enabled",_l('settings_group_telegram_notification_enable')) ?>
                <hr />
                <?php $attrs = (get_option('telegram_notification_token') != '' ? array() : array('autofocus' => true)); ?>
                <?php echo render_input('settings[telegram_notification_token]', 'settings_telegram_notification_token', get_option('telegram_notification_token'), 'text', $attrs); ?>
                <hr />
                <?php $attrs2 = (get_option('telegram_notification_channel_link') != '' ? array() : array('autofocus' => true)); ?>
                <?php echo render_input('settings[telegram_notification_channel_link]', 'settings_telegram_notification_channel_link', get_option('telegram_notification_channel_link'), 'text', $attrs2); ?>
                <hr />
                <hr />
             
           

            </div>
        </div>
    </div>





</div>