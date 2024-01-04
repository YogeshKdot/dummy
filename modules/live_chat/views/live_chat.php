<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <a href="#" onclick="save_live_chat(); return false;" class="btn btn-info">Save Changes</a>
                        <a href="#" onclick="enable_live_chat(); return false;" class="btn btn-info" <?php if (get_option('live_chat') == 'enable') echo 'disabled';?>>Enable Live Chat Support</a>
                        <a href="#" onclick="disable_live_chat(); return false;" class="btn btn-info"<?php if (get_option('live_chat') == 'disable') echo 'disabled';?>>Disable Live Chat Support</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="bold" for="live_chat_clients_and_admin_area">Chat code for both Admin & Clients area (frontend & backend) <i class="fa fa-question-circle" data-toggle="tooltip" data-title="If you paste your code here, your live chat service will load in Admin area and Customers area aswell."></i></label>
                            <textarea name="live_chat_clients_and_admin_area" id="live_chat_clients_and_admin_area" rows="10" class="form-control"><?php echo clear_textarea_breaks(get_option('live_chat_clients_and_admin_area')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="bold" for="live_chat_admin_area">Chat code for Admin area only (backend) <i class="fa fa-question-circle" data-toggle="tooltip" data-title="If you paste your code here, your live chat service will load in Admin area only."></i></label>
                            <textarea name="live_chat_admin_area" id="live_chat_admin_area" rows="10" class="form-control"><?php echo clear_textarea_breaks(get_option('live_chat_admin_area')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="bold" for="live_chat_clients_area">Chat code for Clients area only (frontend) <i class="fa fa-question-circle" data-toggle="tooltip" data-title="If you paste your code here, your live chat service will load in Customers area only."></i></label>
                            <textarea name="live_chat_clients_area" id="live_chat_clients_area" rows="10" class="form-control"><?php echo clear_textarea_breaks(get_option('live_chat_clients_area')); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
   $(function() {
   });

   function save_live_chat() {
       $.post(admin_url + 'live_chat/save', {
            admin_area: $('#live_chat_admin_area').val(),
            clients_area: $('#live_chat_clients_area').val(),
            clients_and_admin: $('#live_chat_clients_and_admin_area').val(),
       }).done(function(response) {
            window.location = admin_url+'live_chat';
       });
   }
   
   function enable_live_chat() {
       $.post(admin_url + 'live_chat/enable', {
       }).done(function() {
            window.location = admin_url+'live_chat';
       });
   }
   
   function disable_live_chat() {
       $.post(admin_url + 'live_chat/disable', {
       }).done(function() {
            window.location = admin_url+'live_chat';
       });
   }
</script>

</body>
</html>