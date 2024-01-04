          <div class="col-md-12">
          <?php echo form_hidden('list_tasks_'.htmlspecialchars($id), htmlspecialchars($list_tasks)); ?>
          <table class="table table-task_bookmarks_list_task_add_<?php echo htmlspecialchars($id); ?> dt-table scroll-responsive">
                    <thead>
                        <th><?php echo htmlspecialchars(_l('name')); ?></th>
                        <th><?php echo htmlspecialchars(_l('milestones_name')); ?></th>
                        <th><?php echo htmlspecialchars(_l('task_single_assignees')); ?></th>
                        <th><?php echo htmlspecialchars(_l('task_single_start_date')); ?></th>
                        <th><?php echo htmlspecialchars(_l('task_single_due_date')); ?></th>
                        <th><?php echo htmlspecialchars(_l('project_overview_total_logged_hours')); ?></th> 
                    </thead>
                    <tbody>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                  </tbody>
              </table>
              </div>



