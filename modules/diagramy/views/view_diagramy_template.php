<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>modules/diagramy/assets/css/template.css">
<div class="modal-header task-single-header" data-task-single-id="<?php echo $diagramy->id; ?>" >
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $diagramy->title; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-8 task-single-col-left">
            <div class="tc-content">
                <div id="diagramy_draw">
                    <div id="map">
                        <img id="image" style="max-width:100%;"  src="<?php echo $value = (isset($diagramy) ? $diagramy->diagramy_content : ''); ?>" />
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

        </div>
        <div class="col-md-4 task-single-col-right">
            <h4 class="task-info-heading"><?php echo _l('diagramy_info'); ?></h4>
            <div class="clearfix"></div>
            <h5 class="no-mtop task-info-created">
                <small class="text-dark"><?php echo _l('task_created_at', '<span class="text-dark">'._dt($diagramy->dateadded).'</span>'); ?></small>
            </h5>

            <hr class="task-info-separator">

            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-user-o"></i>
                    <?php echo _l('diagramy_filter_staff'); ?>: <?php echo ($staff) ? $staff->firstname.' '.$staff->lastname : ''; ?>
                </h5>
            </div>

            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-cog"></i>
                    <?php echo _l('diagramy_filter_group'); ?>: <?php echo ($group) ? $group->name : ''; ?>
                </h5>
            </div>

        </div>
    </div>
</div>
