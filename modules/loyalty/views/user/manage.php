<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="panel_s mbot10">
				<div class="panel-body">
	              	<div class="row">    
	                     
	                    	
                        <div class="col-md-3">
                          <select name="client_filter[]" id="client_filter" class="selectpicker"  data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('client'); ?>" >
                              <?php foreach($clients as $cli){ ?>
                                <option value="<?php echo html_entity_decode($cli['userid']); ?>"><?php echo html_entity_decode($cli['company']); ?></option>
                              <?php } ?>
                          </select>
                        </div>  
                        <div class="col-md-3">
                          <select name="client_group_filter[]" id="client_group_filter" class="selectpicker" multiple data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('client_group'); ?>" >
                              <?php foreach($client_groups as $gr){ ?>
                          <option value="<?php echo html_entity_decode($gr['id']); ?>"><?php echo html_entity_decode($gr['name']); ?></option>
                        <?php } ?>
                          </select> 
                          <br>  
                        </div>
                        
					         
          						<div class="col-md-12">
          							<hr>	
          						</div>
	            	</div>
	              	<div class="row">
        						<div class="col-md-12" id="small-table">
    			                    <?php render_datatable(array(
    			                        _l('client'),
    			                        _l('email'),
    			                        _l('membership'),
                                  _l('loyalty_point'),
                                  _l('actions'),
    			                        ),'table_user'); ?>
        						</div>
            		</div>
	            </div>
            </div>
		</div>
	</div>
</div>

<?php init_tail(); ?>
</body>
</html>

