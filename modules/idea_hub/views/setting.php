<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show options for call logs in Setup->Settings->Call Logs settings
 */
$enabled = get_option('client_view_ih_menu'); ?>
<div class="form-group">
    <label for="pusher_chat" class="control-label clearfix">
        <?php echo _l('ih_client_view_enable_option'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_ih_client_view" name="settings[client_view_ih_menu]" value="1" <?= ($enabled == '1') ? ' checked' : '' ?>>
        <label for="y_opt_1_ih_client_view"><?php echo _l('settings_yes'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_ih_client_view" name="settings[client_view_ih_menu]" value="0" <?= ($enabled == '0') ? ' checked' : '' ?>>
        <label for="y_opt_2_ih_client_view">
            <?php echo _l('settings_no'); ?>
        </label>
    </div>
</div>