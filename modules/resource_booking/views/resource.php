<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-6 col-md-offset-1">

            <div class="panel_s">
               <div class="panel-body">
                  <?php 
                  $this->load->model('resource_booking/resource_booking_model');
                  
                  $this->load->model('resource_booking/resource_booking_model');
                  $group = $this->resource_booking_model->get_resource_group_by_id($resource->resource_group);
                  if($resource->status == 'active'){ ?>
                  <div class="ribbon success"><span><?php echo 'active'; ?></span></div>
                  <?php }else{ ?>
                     <div class="ribbon danger"><span><?php echo 'deactive'; ?></span></div>
                  <?php } ?>
                  
                  <div class="col-md-4">
                      <h4 class="no-margin font-bold" ><?php echo _l('generals_infor'); ?></h4>
                      <hr>
                  </div>
                  <div class="col-md-12">
                     <table class="table no-margin project-overview-table">
                        <tbody>
                           <tr class="project-overview paddedbooking">
                              <td class="bold"><?php echo _l('resource_name'); ?></td>
                              <td><?php echo htmlspecialchars($resource->resource_name); ?></td>
                           </tr>
                           <tr class="project-overview paddedbooking">
                              <td class="bold"><?php echo _l('resource_group'); ?></td>
                              <td><?php echo '<i class="fa '.$group->icon.'"></i> '.$group->group_name; ?></td>
                           </tr>
                           <tr class="project-overview paddedbooking">
                              <td class="bold"><?php echo _l('manager'); ?></td>
                              <td><a href="<?php echo admin_url('staff/profile/'.$resource->manager); ?>"> <?php echo staff_profile_image($resource->manager, ['staff-profile-image-small',]); ?> </a><a href=" <?php echo admin_url('staff/profile/'.$resource->manager); ?>"><?php echo get_staff_full_name($resource->manager); ?></a>
                              </td>
                           </tr>
                           <tr class="project-overview paddedbooking">
                              <td class="bold"><?php echo _l('color'); ?></td>
                              <td><span class="label label-tag tag-id-1" style="background-color: <?php echo htmlspecialchars($resource->color); ?>;">&nbsp;&nbsp;&nbsp;</span></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>

               </div>
               
            </div>

           
         </div>
         <div class="col-md-4">
              <div class="panel_s">
               <div class="panel-body">
                  <div class="col-md-5">
                      <h4 class="no-margin font-bold" ><?php echo _l('activity_log'); ?></h4>
                      <hr>
                  </div>

                  <div class="col-md-12">
                  <div class="activity-feed">
                  <?php foreach($booking as $b){ ?>
                  <div class="feed-item">
                    <div class="date"><span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($b['end_time']); ?>">
                      <?php echo time_ago($b['end_time']); ?>
                    </span>
                  </div>
                    <div class="text">
                     <p class="bold no-mbot">
                      <a href="<?php echo admin_url('resource_booking/booking/'.$b['id']); ?>"><?php echo htmlspecialchars($b['purpose']); ?></a> -
                      <?php echo htmlspecialchars($b['description']); ?></p>
                      <?php echo _l('start_time'); ?>: <?php echo _dt($b['start_time']); ?><br>
                      <?php echo _l('end_time'); ?>: <?php echo _dt($b['end_time']); ?>
                    </div>
                  </div>
                  <?php } ?>
                </div>
             </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
</body>
</html>
