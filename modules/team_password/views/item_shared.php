<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<?php $share = get_shared_item_by_client($client->userid) ?>
		<table class="table dt-table">
			 <thead>
              <th>#</th>
              <th><?php echo _l('name');  ?></th>
              <th><?php echo _l('type');  ?></th>
              <th><?php echo _l('effective_time');  ?></th>
              <th><?php echo _l('related_contract');  ?></th>
              <th><?php echo _l('options'); ?></th>                   
            </thead>
            <tbody>
                <?php if(count($share) > 0){
                foreach ($share as $key => $aRow) { ?>
                  <tr>
                <td><?php  echo html_entity_decode($key+1); ?></td>

                <td><?php  echo html_entity_decode($CI->team_password_model->get_name_obj($aRow['type'],$aRow['share_id'])); ?></td> 
                <td><?php  echo _l($aRow['type']); ?></td>
                <td><span class="label label-info"><?php  echo _dt($aRow['effective_time']); ?></span></td>

                <td><?php  $contracts = get_contract_relate($aRow['type'],$aRow['share_id']);
                if($contracts != ''){
                  foreach($contracts as $ctr){
                    $contract = get_contract_id($ctr);
                    if($contract){
                      echo '<a href="'.site_url('contract/'.$contract->id.'/'.$contract->hash).'" >'.$contract->subject.'</a>';
                    }else{
                      echo '';
                    }
                  }
                }else{
                  echo '';
                }

                 ?></td>
                <td><a href="<?php echo admin_url('team_password/view_'.$aRow['type'].'/'.$aRow['share_id']); ?>" class="btn btn-icon btn-success"><i class="fa fa-eye"></i></a></td>                       
              </tr>
              <?php } } ?>
            </tbody>
        </table>
	</div>
</div>