<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.new_pro_cl{}
    
.new_pro_cl > div {
    width: 24%;
    margin: 0 auto;
}
.card_ih_text_data a h4 {
    color: #51647c !important;
} 
</style>

<div id="wrapper" class="idea_hub">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              <div class="panel_s">
                  <div class="panel-body ad_bottom_cl">
                     <input type="hidden" id="challenge_id" value="<?php echo $challenge_id; ?>">
                     <div class="row">
                           <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_left">
                                <div class="challenge_btn_cl">
                                    <a href="<?php echo admin_url('idea_hub/idea/'.$challenge_id); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-lightbulb-o"></i> <?= _l('new_idea'); ?>
                                    </a>
                                </div>
                              </div>
                          </div>
                           <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_right">
                                 <div class="list_and_grid">
                                          <span>
                                              <a href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=tree" data-toggle="tooltip"  title="Tree View">
                                                  <img src="<?php echo base_url('modules/idea_hub/assets/img/hierarchical-structure.png'); ?>">
                                              </a>
                                          </span>
                                          <span>
                                              <a href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=kanban" data-toggle="tooltip" title="Kanban Board">
                                                  <img src="<?php echo base_url('modules/idea_hub/assets/img/kanban.png'); ?>">
                                              </a>
                                          </span>
                                          <span>
                                            <a data-toggle="tooltip" title="List View" href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=list">
                                                  <img src="<?php echo base_url('modules/idea_hub/assets/img/list.png'); ?>">
                                              </a>
                                          </span>
                                          <span class="grid_list_bg">
                                              <a data-toggle="tooltip" title="Grid View" href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=grid">
                                             <img src="<?php echo base_url('modules/idea_hub/assets/img/menu.png'); ?>">
                                              </a>
                                          </span>
                                  </div>
                              </div>
                          </div>
                     </div>
                     
                  </div>
                  
              </div>   
            </div>
        </div>
        <div class="kan-ban-tab" id="kan-ban-tab" style="overflow:auto;">
            <div class="row">
                <div id="kanban-params"></div>
                <div class="container-fluid">
                    <div id="kan-ban"></div>
                </div>
            </div>
        </div>
    </div>
 <!---MODEL PANEL-->
<div class="container">
  <!-- The Modal -->
  <div class="modal fade" id="idea_view">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">idea view</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>Title</th>
                        <td>Font Awesome 6 Beta</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>font family</td>
                    </tr>
                     <tr>
                        <th>Description</th>
                        <td>Generate the latest CRM report for company projects</td>
                    </tr>
                    <tr>
                        <th>Instruction</th>
                        <td>The next generation</td>
                    </tr>
                    <tr>
                        <th>Deadline</th>
                        <td>2021-09-30 21:00:00</td>
                    </tr>
                    <tr>
                        <th>Cover</th>
                        <td class="cover_img_ma">
                            <img src="<?php echo base_url('modules/idea_hub/assets/img/second_baner.jpg');?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td class="lable_cl_he">
                            <label style="background-color: #1a5303;">Active</label>
                        </td>
                    </tr>
                   
                </tbody>
            </table>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>   
</div>

<?php init_tail(); ?>
</body>
</html>
<script type="text/javascript">
	$(function(){
    	init_kanban('idea_hub/idea_kanban_view/<?php echo $challenge_id; ?>', idea_kanban_update, '.tasks-status', 265, 360);
	});
</script>