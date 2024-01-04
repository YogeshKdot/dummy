<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                     <div class="_buttons">
                        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#mindmap-group-modal"><?php echo _l('diagramy_new_group'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="clearfix"></div>
                    <?php render_datatable([
                        _l('diagramy_group_name'),
                        _l('options'),
                        ], 'mindmap-group'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('diagramy_group.php'); ?>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-mindmap-group', window.location.href, [1], [1]);
   });
</script>
</body>
</html>
