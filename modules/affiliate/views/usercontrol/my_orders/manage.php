<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
	<div class="content">
		<div class="row">
			
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
      
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('my_orders'); ?></p>
        <hr class="hr_style"/>
        <a href="<?php echo site_url('affiliate/usercontrol/order'); ?>" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
        <table class="table dt-table">
             <thead>
              <th><?php echo _l('#'); ?></th>
               <th><?php echo _l('order_code'); ?></th>
                <th><?php echo _l('date'); ?></th>
                <th><?php echo _l('company'); ?></th>
                <th><?php echo _l('total'); ?></th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
             </thead>
            <tbody>
               <?php 
              foreach($orders as $order){ ?>
                <tr>
                  <td><?php echo html_entity_decode($order['order_id']); ?></td>
                  <td><?php echo html_entity_decode($order['order_code']); ?></td>
                  <td><?php echo _dt($order['datecreated']); ?></td>
                  <td><?php echo html_entity_decode($order['company']); ?></td>
                  <td><?php echo app_format_money($order['total'], $currency->name); ?></td>
                  <?php 
                  if($order['approve_status'] == 1){
                      $status_name = _l('approve');
                      $label_class = 'success';
                  }elseif($order['approve_status'] == 2){
                      $status_name = _l('reject');
                      $label_class = 'danger';
                  }else{
                      $status_name = _l('waiting');
                      $label_class = 'default';
                  }
                   ?>
                  <td><span class="label label-<?php echo html_entity_decode($label_class); ?> s-status order-status-<?php echo html_entity_decode($order['approve_status']); ?>"><?php echo html_entity_decode($status_name); ?></span></td>
                  <td><?php echo icon_btn(site_url('affiliate/usercontrol/order_detail/'.$order['order_id']), 'eye', 'btn-default', [
                        'title' => _l('view')
                    ]).''.icon_btn(site_url('affiliate/usercontrol/order/'.$order['order_id']), 'edit', 'btn-default _delete', [
                        'title' => _l('edit') 
                    ]).''.icon_btn(site_url('affiliate/usercontrol/delete_order/'.$order['order_id']), 'remove', 'btn-danger _delete', [
                        'title' => _l('delete') 
                    ]); ?></td>
               </tr>
             <?php } ?>
            </tbody>
         </table> 
        
        </div>
      </div>
        </div>
      
        </div>

			</div>
		
			
		</div>
	</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
