<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-4 border-right">
                      <h4 class="no-margin font-bold"><i class="fa fa-filter" aria-hidden="true"></i> <?php echo htmlspecialchars(_l($title)); ?></h4>
                      <hr />
                    </div>
                  </div>
                   <div class="_buttons">
                        <a href="#" onclick="new_task_bookmarks(); return false;" class="btn btn-info pull-left display-block">
                            <?php echo htmlspecialchars(_l('new_task_bookmarks')); ?>
                        </a>
                    </div>
                   <br><br><br>
                  <?php render_datatable(array(
                        '#'._l('id'),
                        _l('task_filter_name'),
                        _l('creator'),
                        _l('utilities_menu_icon'),
                        _l('options')
                        ),'task_bookmarks'); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="task_bookmarks" tabindex="-1" role="dialog"  >
    <div class="modal-dialog">
        <?php echo form_open(admin_url('task_bookmarks/task_bookmark'),array('id'=>'task_bookmarks-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo htmlspecialchars(_l('edit_task_bookmarks')); ?></span>
                    <span class="add-title"><?php echo htmlspecialchars(_l('new_task_bookmarks')); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                        <div id="additional">
                        </div>
                    <div class="col-md-12">
                        <?php echo render_input('task_bookmarks_name','task_bookmarks_name'); ?>
                    </div>
                    <div class="col-md-6">

                <label class="control-label"><?php echo htmlspecialchars(_l('utilities_menu_icon')); ?></label>
                  <div class="input-group">
                   <input type="text" name="icon" class="form-control main-item-icon icon-picker">
                   <span class="input-group-addon">
                     <i id="icon"></i>
                   </span>
                 </div>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_color_picker('color', _l('leads_status_color')); ?>
                    </div>
                </div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
                    <button id="sm_btn" type="submit" class="btn btn-info"><?php echo htmlspecialchars(_l('submit')); ?></button>
                </div>
            </div><!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<?php init_tail(); ?>
<?php $this->load->view('task_bookmarks/task_bookmarks_js'); ?>
</body>
</html>
<script>
  $('.icon-picker').iconpicker();
</script>