<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="<?php if(is_client_logged_in()){echo 'col-md-12';} ?> tc-content project-overview">
   <hr class="hr-panel-heading project-area-separation" />
   <p class="bold font-size-14 text-uppercase">
      <?php echo _l('items_relate'); ?>
   </p>
   <?php $CI->load->model('team_password/team_password_model');
	$items['bank_account'] = $CI->team_password_model->get_item_relate_project($project->id,'bank_account');
    $items['credit_card'] = $CI->team_password_model->get_item_relate_project($project->id,'credit_card');
    $items['email'] = $CI->team_password_model->get_item_relate_project($project->id,'email');
    $items['normal'] = $CI->team_password_model->get_item_relate_project($project->id,'normal');
    $items['server'] = $CI->team_password_model->get_item_relate_project($project->id,'server');
    $items['software_license'] = $CI->team_password_model->get_item_relate_project($project->id,'software_license');
 ?>
<table class="table dt-table">
	<thead>
		<th><?php echo _l('name'); ?></th>
		<th><?php echo _l('password'); ?></th>
	</thead>
	<tbody>
		<?php foreach($items as $type => $value){ ?>
              <?php foreach($value as $val){ 
                if(is_staff_logged_in()){
                  $url = admin_url('team_password/view_'.$type.'/'.$val['id']);
                }else{
              		$hash = get_hash_by_item_client($val['id'],$type,$project->clientid);
              		if($hash != ''){
              			$url = site_url('team_password/team_password_client/view_share_client/'.$hash.'/'.$type);	
              		}else{
              			$url = 'javascript:void(0)';
              		}            	
                }

                if($type == 'normal' || $type == 'server' || $type == 'email'){
                	
                ?>
                <tr>
                  <td><a href="<?php echo html_entity_decode($url); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo _l($type); ?>"><?php echo html_entity_decode($val['name']); ?></a></td>
                  <td><span type="password"><?php echo AES_256_Decrypt($val['password']); ?></span></td>

                </tr>
              <?php }elseif($type == 'software_license'){ ?>
                <tr>
                  <td><a href="<?php echo html_entity_decode($url); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo _l($type); ?>"><?php echo html_entity_decode($val['name']); ?></a></td>  
                  <td><?php echo html_entity_decode($val['license_key']); ?></td>
                </tr>
              <?php }elseif ($type == 'bank_account' || $type == 'credit_card' ) { ?>
                <tr>
                  <td><a href="<?php echo html_entity_decode($url); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo _l($type); ?>"><?php echo html_entity_decode($val['name']); ?></a></td>  
                  <td><?php echo html_entity_decode($val['pin']); ?></td>
                </tr>
             <?php } ?>
              <?php } ?>
            <?php } ?>
	</tbody>
</table>
 </div>