<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="row">
      <div class="col-md-12" id="block-add-edit-wrapper">
         <div class="row">
            <div class="col-md-8">
               <div class="panel_s">
                  <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'settings_form')) ;?>
                  <div class="panel-body">
                     <h4 class="no-margin">
                        <?php echo html_escape($title); ?>
                     </h4>
                     <hr class="hr-panel-heading" />
                     <?php $value = (isset($blockscss) ? $blockscss->value : ''); ?>
                     <?php echo render_textarea('blockscss','blockscss',$value, ['rows' => 10]); ?>
                   
                     <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
</body>
</html>
