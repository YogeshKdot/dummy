<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="panel_s">
         <div class="panel-body">
            <div id="EmailEditor" class="EmailEditor"></div>
            <hr>
            <h4 class="no-margin">
               <?php echo _l('available_merge_fields'); ?>
            </h4>
            <hr class="hr-panel-heading" />
            <div class="row">
               <div class="col-md-12">
                  <div class="row available_merge_fields_container">
                     <?php
                        $mergeLooped = array();

                        foreach($available_merge_fields as $field){
                            foreach($field as $key => $val){
                              if($key != 'leads' && $key != 'other'){
                                 continue;
                              }
                              
                              echo '<div class="col-md-6 merge_fields_col">';
                              echo '<h5 class="bold">'.ucfirst($key).'</h5>';

                             foreach($val as $_field){
                               if(count($_field['available']) == 0) {
                                   // Fake data to simulate foreach loop and check the templates key for the available slugs
                                 $_field['available'][] = '1';
                             }
                             foreach($_field['available'] as $_available){
                                 if($_field['key'] == '{dark_logo_image_with_url}' || $_field['key'] == '{logo_image_with_url}' || $_field['key'] == '{logo_url}'){
                                    continue;
                                 }

                                 if(!in_array($_field['name'], $mergeLooped)){
                                    $mergeLooped[] = $_field['name'];
                                    echo '<p>'.$_field['name'];
                                    echo '<span class="pull-right">';
                                    echo html_entity_decode($_field['key']);
                                    echo '&nbsp;&nbsp;&nbsp;</span>';
                                    echo '</p>';
                                 }
                             }
                           }
                           echo '</div>';
                           }
                        }
                        ?>
                  </div>
               </div>
            </div>
            <?php echo form_hidden('csrf_token_hash', $this->security->get_csrf_hash()); ?>
            <?php echo form_open(admin_url('ma/email_template_design_save'),array('id'=>'email-template-form','autocomplete'=>'off')); ?>
            <?php echo form_hidden('email_template_id',(isset($email_template) ? $email_template->id : '') ); ?>
            <?php echo form_hidden('data_design',(isset($email_template) ? $email_template->data_design : '')); ?>
            <?php echo form_hidden('data_html',(isset($email_template) ? $email_template->data_html : '')); ?>
            <?php echo form_close(); ?>
            <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
               <a href="#" onclick="save_template(); return false;" class="btn btn-info mbot15"><?php echo _l('submit'); ?></a>
           </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>

</body>
</html>
