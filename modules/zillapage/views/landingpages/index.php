<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                
                <?php if($alert_route){ ?>
                <div class="alert alert-danger">
                    <p><strong>Module Zillapage publish need:</strong></br>
                    Please add the line below at the end of the file <strong><?php echo APPPATH.'config/my_routes.php'; ?></strong><br/></p>
                    <p>
                    <pre>$route['publish/(:any)'] = 'zillapage/publishlandingpage/index/$1'; 
$route['publish/thankyou/(:any)'] = 'zillapage/publishlandingpage/thankyou/$1';
                    </pre>
                    </p>
                </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body">
                     <?php if(has_permission('landingpages','','create')){ ?>
                     <div class="_buttons">
                        <a href="<?php echo admin_url('zillapage/landingpages/templates'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_landingpage'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <?php } ?>
                    
                       <?php render_datatable(array(
                        _l('name'),
                        _l('publish'),
                        _l('created_at'),
                        _l('updated_at'),
                        _l('actions'),
                        ),'landingpages'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/index.js'); ?>"></script>
</body>
</html>
