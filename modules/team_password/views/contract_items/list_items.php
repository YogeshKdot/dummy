<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
   <div class="panel_s">
    <div class="panel-body">
   <div class="clearfix"></div><br>
   <div class="col-md-12">
    <h4><i class="fa fa-list-ul">&nbsp;&nbsp;</i><?php echo html_entity_decode($title); ?></h4>
   </div>
    <div class="row">

      <div class="clearfix"></div>
      <hr />
      <div class="clearfix"></div>
      <div class="col-md-12">
        <table class="table dt-table">
          <thead>
            <th><?php echo _l('name'); ?></th>
            <th><?php echo _l('category'); ?></th>
            <th><?php echo _l('type'); ?></th>
            <th><?php echo _l('date_create'); ?></th>
            <th><?php echo _l('options'); ?></th>
          </thead>
          <tbody>
            <?php foreach($items as $type => $value){ ?>
              <?php foreach($value as $val){ 
                if($type != 'normal'){
                ?>
                <tr>
                  <td><?php echo html_entity_decode($val['name']); ?></td>
                  <td><?php echo get_category_name_tp($val['mgt_id']); ?></td>
                  <td><?php echo _l($type); ?></td>
                  <td><span class="label label-info"><?php echo _dt($val['datecreator']); ?></span></td>
                  <td><a href="<?php echo admin_url('team_password/view_'.$type.'/'.$val['id']); ?>" class="btn btn-icon btn-success"><i class="fa fa-eye"></i></a></td>
                </tr>
              <?php }else{ ?>
                <tr>
                  <td><?php echo html_entity_decode($val['name']); ?></td>
                  <td><?php echo get_category_name_tp($val['mgt_id']); ?></td>
                  <td><?php echo _l($type); ?></td>
                  <td></td>
                  <td><a href="<?php echo admin_url('team_password/view_'.$type.'/'.$val['id']); ?>" class="btn btn-icon btn-success"><i class="fa fa-eye"></i></a></td>
                </tr>
              <?php } } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
      </div>
  </div>
 </div>
</div>
<?php init_tail(); ?>
</body>
</html>

