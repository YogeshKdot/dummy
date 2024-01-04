<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 

  $name = '';
  $url = '';
  $user_name = '';
  $notice = '';
  $password = '';
  $custom_field = [];
  $enable_log = '';
  if(isset($normal)){
    $name = $normal->name;
    $url = $normal->url;
    $user_name = $normal->user_name;
    $notice = $normal->notice;
    $password = AES_256_Decrypt($normal->password);
    $custom_field = json_decode($normal->custom_field);
    $enable_log = $normal->enable_log;
  }
 ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
	 <div class="clearfix"></div><br>
	 <div class="col-md-12">
	 	<h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo html_entity_decode($title); ?></h4>
	 </div>

   <table class="table border table-striped ">
             <tbody>                                  
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('name'); ?>

                    </td>
                    <td>                                        
                      <?php
                        
                       echo html_entity_decode($name); 

                       ?>      
                    </td>
                 </tr>  
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('url'); ?>

                    </td>
                    <td>                                        
                      <?php
                        
                       echo html_entity_decode($url); 

                       ?>      
                    </td>
                 </tr>  
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('user_name'); ?>

                    </td>
                    <td>                                        
                      <?php
                        
                       echo html_entity_decode($user_name); 

                       ?>      
                    </td>
                 </tr>  
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('notice'); ?>

                    </td>
                    <td>                                        
                      <?php
                        
                       echo html_entity_decode($notice); 

                       ?>      
                    </td>
                 </tr>  
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('password'); ?>

                    </td>
                    <td>                                        
                      <?php
                        
                       echo html_entity_decode($password); 

                       ?>      
                    </td>
                 </tr> 
                 <?php if(count($custom_field)>0){ ?>
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('custom_field'); ?>

                    </td>
                    <td>                                        
                       <?php foreach ($custom_field as $key => $tag) { ?>
                         &nbsp;<span class="btn btn-default ptop-10 tag">
                         <label  name="field_name[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->name); ?></label>&nbsp; - &nbsp;<label  name="field_value[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->value); ?></label>&nbsp;
                         </span>&nbsp;
                        <?php } ?>     
                    </td>
                 </tr>   
                 <?php } ?>     
                </tbody>
              </table>
  </div>
 </div>
</div>

