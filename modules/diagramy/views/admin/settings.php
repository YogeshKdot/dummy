<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show options for call logs in Setup->Settings->Call Logs settings.
 */
$enabled = get_option('staff_members_create_inline_mindmap_group'); ?>
<div class="form-group">
    <label for="pusher_chat" class="control-label clearfix">
        <?php echo _l('mindmap_enable_group_option'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_mindmap_group" name="settings[staff_members_create_inline_mindmap_group]" value="1" <?php echo ('1' == $enabled) ? ' checked' : ''; ?>>
        <label for="y_opt_1_mindmap_group"><?php echo _l('settings_yes'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_mindmap_group" name="settings[staff_members_create_inline_mindmap_group]" value="0" <?php echo ('0' == $enabled) ? ' checked' : ''; ?>>
        <label for="y_opt_2_mindmap_group">
            <?php echo _l('settings_no'); ?>
        </label>
    </div>
</div>
<hr>
