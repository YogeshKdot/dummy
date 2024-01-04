<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
?>
<div id="wrapper"block>
    <div class="content">
        <div class="row">
                <div class="panel_s">

                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-8">
                            <div class="_buttons">
                                <a href="<?php echo admin_url('zillapage/templates/template'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_template'); ?></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                           
                        </div>
                      </div>
                  <div class="clearfix mbot20"></div>
                       <?php render_datatable(array(
                        _l('thumb'),
                        _l('name'),
                        _l('active'),
                        _l('created_at'),
                        _l('updated_at'),
                        ),'templates'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/templates/js/index.js'); ?>"></script>
</body>
</html>
