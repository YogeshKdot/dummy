<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
        
        
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
			   <?php
                $table_data = [
                    _l('zoom'),
                    _l('zoom_meeting_id'),
					_l('zoom_timezone'),
                    _l('zoom_start_time'),
                    _l('zoom_duration'),
                    _l('zoom_agenda'),
                    _l('zoom_join_url'),
                    _l('zoom_delete_url'),
                  ];
                  render_datatable($table_data, ($class ?? 'zoom_list')); ?>
			   
               </div>
            </div>
         </div>
        <?php echo form_close(); ?>
      </div>
      <div class="btn-bottom-pusher"></div>
   </div>
</div>

 <?php init_tail(); ?>
<script type="text/javascript">
  $(function(){
    initDataTable('.table-zoom_list', window.location.href,'undefined','undefined','');
  });     
</script>

</body>
</html>

