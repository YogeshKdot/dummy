<?php init_head(); ?>
<div id="wrapper">
    
  <div class="content">
    <div class="clearfix"></div>
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="row">
             <div class="col-md-12 border-right">
              <h4 class="no-margin font-bold"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo htmlspecialchars(_l('task_bookmarks')).': '. _l($title); ?></h4>
              <hr />
            </div>
          </div>
            <?php $this->load->view('task_bookmarks/view_task_bookmarks_dashboard'); ?>
            </div>
          </div>
        </div>
      </div>
</div>
</div>

<?php init_tail(); ?>
<?php $this->load->view('task_bookmarks/task_bookmarks_js'); ?>
</body>
</html>
<script>
      
  var list_tasks = {
            "list_tasks": 'input[name="list_tasks_'+<?php echo htmlspecialchars($id); ?>+'"]',
        }
  $(function(){
      task_bookmarks();
    });
function task_bookmarks() {
    if ($.fn.DataTable.isDataTable('.table-task_bookmarks_list_task_add_'+<?php echo htmlspecialchars($id); ?>)) {
       $('.table-task_bookmarks_list_task_add_'+<?php echo htmlspecialchars($id); ?>).DataTable().destroy();
     }
      initDataTable('.table-task_bookmarks_list_task_add_'+<?php echo htmlspecialchars($id); ?>, admin_url + 'task_bookmarks/task_bookmarks_list_task_add', false, false, list_tasks);
   }
</script>