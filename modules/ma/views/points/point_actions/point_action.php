<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="panel_s">
         <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'point-action-form')) ;?>
         <div class="panel-body">
            <div class="clearfix"></div>
            <h4 class="no-margin"><?php echo html_entity_decode($title); ?></h4>
            <hr class="hr-panel-heading" />
            <div class="btn-bottom-toolbar text-right">
               <a href="<?php echo admin_url('ma/points?group=point_actions'); ?>" class="btn btn-default"><?php echo _l('back'); ?></a>
               <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <div class="row">
            <div class="col-md-6">
               <?php $value = (isset($point_action) ? $point_action->name : ''); ?>
               <?php echo render_input('name','name',$value); ?>
               <?php $value = (isset($point_action) ? $point_action->category : ''); ?>
               <?php echo render_select('category',$category, array('id', 'name'),'category',$value); ?>
               <div class="form-group">
                 <?php
                   $selected = (isset($point_action) ? $point_action->published : ''); 
                   ?>
                 <label for="published"><?php echo _l('published'); ?></label><br />
                 <div class="radio radio-inline radio-primary">
                   <input type="radio" name="published" id="published_yes" value="1" <?php if($selected == '1'|| $selected == ''){echo 'checked';} ?>>
                   <label for="published_yes"><?php echo _l("yes"); ?></label>
                 </div>
                 <div class="radio radio-inline radio-primary">
                   <input type="radio" name="published" id="published_no" value="0" <?php if($selected == '0'){echo 'checked';} ?>>
                   <label for="published_no"><?php echo _l("no"); ?></label>
                 </div>
               </div>
               <?php
                $description = (isset($point_action) ? $point_action->description : ''); 
                ?>
               <p class="bold"><?php echo _l('dt_expense_description'); ?></p>
               <?php echo render_textarea('description','',$description,array(),array(),'','tinymce'); ?>
            </div>
            <div class="col-md-6">
               <?php $value = (isset($point_action) ? $point_action->change_points : ''); ?>
               <?php echo render_input('change_points','change_points',$value, 'number'); ?>
               <?php 
                  $actions = [
                     ['id' => 'downloads_an_asset', 'name' => _l('downloads_an_asset')],
                     ['id' => 'is_sent_an_email', 'name' => _l('is_sent_an_email')],
                     ['id' => 'opens_an_email', 'name' => _l('opens_an_email')],
                     ['id' => 'submit_a_form', 'name' => _l('submit_a_form')],
                  ];
               ?>

               <?php $value = (isset($point_action) ? $point_action->action : ''); ?>
               <?php echo render_select('action',$actions, array('id', 'name'),'when_a_contact',$value); ?>
            </div>
         </div>
         </div>
         <?php echo form_close(); ?>
      </div>
   </div>
</div>
<?php init_tail(); ?>

</body>
</html>
