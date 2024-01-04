<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $CI->load->model('team_password/team_password_model');
	$items['bank_account'] = $CI->team_password_model->get_item_relate_contract($contract->id,'bank_account');
    $items['credit_card'] = $CI->team_password_model->get_item_relate_contract($contract->id,'credit_card');
    $items['email'] = $CI->team_password_model->get_item_relate_contract($contract->id,'email');
    $items['normal'] = $CI->team_password_model->get_item_relate_contract($contract->id,'normal');
    $items['server'] = $CI->team_password_model->get_item_relate_contract($contract->id,'server');
    $items['software_license'] = $CI->team_password_model->get_item_relate_contract($contract->id,'software_license');
 ?>
<table class="table table-striped table-bordered">
	<thead>
		<th class="text-uppercase"><?php echo _l('information_tp'); ?></th>
		<th class="text-uppercase"><?php echo _l('password'); ?></th>
	</thead>
	<tbody>
		<?php foreach($items as $type => $value){ ?>
              <?php foreach($value as $val){           	

                if($type == 'normal' || $type == 'server' || $type == 'email'){
                ?>

                <tr>
                  <?php if($type == 'normal'){ ?>
                    <td>
                      
                      <h6><span><?php echo _l('url').': '; ?><a href="<?php echo html_entity_decode($val['url']); ?>" target="_blank" ><?php echo html_entity_decode($val['url']); ?></a></span><br>
                      <span><?php echo _l('user_name').': '.$val['user_name']; ?></span></h6>
                    </td>
                  <?php }elseif ($type == 'server') { ?>
                     <td>
                      
                      <h6><span><?php echo _l('host').': '.$val['host']; ?></span><br>
                      <span><?php echo _l('user_name').': '.$val['user_name']; ?></span><br>
                      <span><?php echo _l('port').': '.$val['port']; ?></span></h6>
                    </td>
                  <?php }elseif ($type == 'email'){ ?>
                    <td>
                      
                      <h6><span><?php echo _l('email_type').': '.$val['email_type']; ?></span><br>
                      <span><?php echo _l('auth_method').': '.$val['auth_method']; ?></span><br>
                      <span><?php echo _l('host').': '.$val['host']; ?></span><br>
                      <span><?php echo _l('port').': '.$val['port']; ?></span><br>
                      <span><?php echo _l('smtp_host').': '.$val['smtp_host']; ?></span><br>
                      <span><?php echo _l('smtp_port').': '.$val['smtp_port']; ?></span><br>
                      <span><?php echo _l('smtp_user_name').': '.$val['smtp_user_name']; ?></span></h6>
                    </td>
                  <?php } ?>
                  <td>
                    <span id="span_<?php  echo AES_256_Decrypt($val['password']); ?>"><?php echo AES_256_Decrypt($val['password']); ?></span>
                    <button onclick="copyToClipboard('<?php echo AES_256_Decrypt($val['password']); ?>');" class="btn btn-success btn-icon pull-right" data-toggle="tooltip" data-placement="top" title="<?php echo _l('copy_password'); ?>" ><i class="fa fa-copy"></i></button>
                    
                    <?php if($type == 'email'){ ?>
                      <hr> 
                    <span id="span_<?php  echo AES_256_Decrypt($val['smtp_password']); ?>"><?php echo AES_256_Decrypt($val['smtp_password']); ?></span> 
                    <button onclick="copyToClipboard('<?php echo AES_256_Decrypt($val['smtp_password']); ?>');" class="btn btn-success btn-icon pull-right" data-toggle="tooltip" data-placement="top" title="<?php echo _l('copy_smtp_password'); ?>" ><i class="fa fa-copy"></i></button>
                    <?php } ?>
                  </td>
                </tr>

              <?php }elseif($type == 'software_license'){ ?>
                <tr>
                  <td>
                  
                    <h6><span><?php echo _l('url').': '; ?><a href="<?php echo html_entity_decode($val['url']); ?>" target="_blank" ><?php echo html_entity_decode($val['url']); ?></a></span><br>
                    <span><?php echo _l('version').': '.$val['version']; ?></span></h6>
                  </td>
                  <td> 
                    <span id="span_<?php  echo html_entity_decode($val['license_key']); ?>" ><?php echo html_entity_decode($val['license_key']); ?></span> 
                    <button onclick="copyToClipboard('<?php echo html_entity_decode($val['license_key']); ?>');" class="btn btn-success btn-icon pull-right" data-toggle="tooltip" data-placement="top" title="<?php echo _l('copy_license_key'); ?>" ><i class="fa fa-copy"></i></button>
                  </td>
                </tr>
              <?php }elseif ($type == 'bank_account' || $type == 'credit_card' ) { ?>
                <tr>
                  <?php if($type == 'bank_account'){ ?>
                    <td>
                      <h6><span><?php echo _l('url').': '; ?><a href="<?php echo html_entity_decode($val['url']); ?>" target="_blank" ><?php echo html_entity_decode($val['url']); ?></a></span><br>
                      <span><?php echo _l('user_name').': '.$val['user_name']; ?></span><br>
                      <span><?php echo _l('bank_code').': '.$val['bank_code']; ?></span><br>
                      <span><?php echo _l('bank_name').': '.$val['bank_name']; ?></span></h6>
                    </td>
                  <?php }elseif ($type == 'credit_card') { ?>
                    <td>
                     
                      <h6><span><?php echo _l('credit_card_type').': '.$val['credit_card_type']; ?></span><br>
                      <span><?php echo _l('card_number').': '.$val['card_number']; ?></span></h6>
                    </td>
                  <?php } ?>

                  <td> 
                    <span id="span_<?php  echo AES_256_Decrypt($val['pin']); ?>" ><?php echo AES_256_Decrypt($val['pin']); ?></span>
                    <button onclick="copyToClipboard('<?php echo AES_256_Decrypt($val['pin']); ?>');" class="btn btn-success btn-icon pull-right" data-toggle="tooltip" data-placement="top" title="<?php echo _l('copy_pin'); ?>" ><i class="fa fa-copy"></i></button>
                  </td>
                </tr>
             <?php } ?>
              <?php } ?>
            <?php } ?>
	</tbody>
</table>
<?php require('modules/team_password/assets/js/items_relate_js.php'); ?>