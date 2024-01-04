<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>modules/diagramy/assets/css/preview.css">
<div id="wrapper">
    <div class="content">
        
        <div class="panel-body" style="background:white;">
                        <div class="_buttons">
        <a href="http://localhost/perfex_crm/admin/diagramy/diagramy_create" class="btn btn-info pull-left display-block mright5">Create New</a>
                            
                            <a href="<?php echo base_url(); ?>admin/diagramy" class="btn btn-default hidden-xs">
                                Switch to List                            </a>
                            <div class="visible-xs">
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">
                        <div class="clearfix mtop20"></div>
                        <div class="row" id="mindmap-table">
                                                        <div class="col-md-12">
                                                    <div class="grid-tab" id="grid-tab">
<div class="row">
    <div id="cl-grid-view" class="container-fluid">
   <?php foreach ($diagramy as $data):?>     
    <div class="col-md-3">
        <div class="cardbox text-center">
          
            <div class="map_grid" id="map_1">
                 <img id="image" style="max-width:100%;cursor:pointer;" src="<?php echo $data['diagramy_content']; ?>">
            </div>
            <h4><a href="http://localhost/perfex_crm/admin/diagramy/preview/1"><?php echo $data['title']; ?></a></h4>
            <?php if (!empty($data['firstname'])):?>
             <p><?php echo $data['name']; ?></p>
            <p>Created By : <?php echo $data['firstname'].$data['lastname']; ?></p>
            <?php else:?>
            <?php endif; ?>
                    </div>
    </div>
<?php endforeach; ?>
  
</div></div>
<div class="row">
    <div id="pagination">
            </div>
</div>
<link rel="stylesheet" type="text/css" href="http://localhost/perfex_crm/modules/diagramy/assets/css/template.css">
<link href="http://localhost/perfex_crm/modules/diagramy/assets/css/cl.css" rel="stylesheet">
<style type="text/css">
    .map_grid{
        background: #eee;
    }
    .cardbox img {
    border-radius:0;
    height: auto;
    transform: scale(2.0);
    margin-top: 50px;
}
</style></div>
                                                </div>
                        </div>

                        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>modules/diagramy/assets/js/preview.js"></script>

</body>
</html>