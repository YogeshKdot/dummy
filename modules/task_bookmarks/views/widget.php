<?php $this->load->model('task_bookmarks/task_bookmarks_model');
$task_bookmarks_list = $this->task_bookmarks_model->get_filter_widget(get_staff_user_id(),'task_bookmarks'); ?>

<?php foreach ($task_bookmarks_list as $task_bookmarks) { ?>
<div class="widget" id="widget-<?php echo basename(__FILE__,".php"); ?>" data-name="<?php echo htmlspecialchars(_l('project_roadmap')); ?>">
<div class="panel_s user-data">
  <div class="panel-body">
    <div class="widget-dragger"></div>
     <?php
      $data = $this->task_bookmarks_model->view_task_bookmarks_helper($task_bookmarks['rel_id']); 
     ?>
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-6">
         <p class="text-dark text-uppercase bold"><?php echo htmlspecialchars(_l('task_bookmarks')).': '.htmlspecialchars($data['task_bookmarks']['name']); ?></p>
      </div>
         <div class="col-md-3 pull-right">
          <a href="#" class="pull-right btn btn-danger btn-icon" data-toggle="tooltip" title="" onclick="remove_dashboard(<?php echo htmlspecialchars($task_bookmarks['id']); ?>)" data-original-title="remove_dashboard"><i class="fa fa-compress"></i></a> 
         </div>
         <br>
         <hr class="mtop15" />
      </div>
     <?php $this->load->view('task_bookmarks/view_task_bookmarks_dashboard', $data); ?>
     </div>
    </div>
  </div>
</div>
<?php } ?> 