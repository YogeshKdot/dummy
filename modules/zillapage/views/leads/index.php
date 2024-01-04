<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <div class="_filters _hidden_inputs hidden">
               <?php
                  foreach($landingpages as $item){
                     echo form_hidden('landing_page_id_'.$item['id']);
                  }
                  ?>
            </div>
                <div class="panel_s">

                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-8">
                            <h4 class="no-margin"><?php echo _l('form_leads'); ?></h4>
                        </div>
                        <div class="col-md-4">
                           <div class="_buttons">
                             <div class="visible-xs">
                                <div class="clearfix"></div>
                             </div>
                             <div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-left">
                                   <li class="active"><a href="#" data-cview="all" onclick="dt_custom_view('','.table-landingpage-leads',''); return false;"><?php echo _l('all'); ?></a>
                                   </li>
                                   <li class="divider"></li>
                                   <?php if(count($landingpages) > 0){ ?>
                                   <li class="dropdown-submenu pull-left groups">
                                      <a href="#" tabindex="-1"><?php echo _l('landing_pages'); ?></a>
                                      <ul class="dropdown-menu dropdown-menu-left">
                                         <?php foreach($landingpages as $item){ ?>
                                         <li><a href="#" data-cview="landing_page_id_<?php echo $item['id']; ?>" onclick="dt_custom_view('landing_page_id_<?php echo $item['id']; ?>','.table-landingpage-leads','landing_page_id_<?php echo $item['id']; ?>'); return false;"><?php echo $item['name']; ?></a></li>
                                         <?php } ?>
                                      </ul>
                                   </li>
                                   <div class="clearfix"></div>
                                   <?php } ?>
                                </ul>
                             </div>
                          </div>
                        </div>
                      </div>
                       
                  <div class="clearfix mbot20"></div>
                       <?php render_datatable(array(
                        _l('from_landing_page'),
                        _l('lead_info'),
                        _l('browser'),
                        _l('os'),
                        _l('device'),
                        _l('created_at'),
                        _l('actions'),
                        ),'landingpage-leads'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="landingpage_lead_to_customer"></div>
<div id="landingpage_form_data_to_lead"></div>

<?php init_tail(); ?>
<script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/leads/js/index.js'); ?>"></script>
</body>
</html>
