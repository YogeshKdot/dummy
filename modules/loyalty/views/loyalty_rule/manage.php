<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="panel_s mbot10">
				<div class="panel-body">
	              	<div class="row">    
		                  <div class="col-md-12"> 
		                    	<?php if (has_permission('loyalty', '', 'create') || is_admin()) { ?>
		                        <a href="<?php echo admin_url('loyalty/create_loyalty_rule'); ?>"class="btn btn-info pull-left mright10 display-block">
		                            <?php echo _l('new'); ?>
		                        </a>
		                        <?php } ?>
		                   
		                    
		                    <div class="col-md-3">
			                    <select name="redeemp_type[]" id="redeemp_type" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('redeemp_type'); ?>" >
				                       
				                        <option value="full"><?php echo _l('full'); ?></option>
				                        <option value="partial"><?php echo _l('partial'); ?></option>
				                    </select> 
			                    <br>  
							</div>
							<div class="col-md-3">
			                    <select name="rule_base[]" id="rule_base" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('rule_base'); ?>" >
			                      
			                        <option value="card_total"><?php echo _l('card_total'); ?></option>
			                        <option value="product_category"><?php echo _l('product_category'); ?></option>
			                        <option value="product"><?php echo _l('product'); ?></option>
			                    </select> 
			                    <br>  
							</div>

						</div>
						<div class="col-md-12">
							<hr>	
						</div>
						
	            	</div>
	              	<div class="row">
					<div class="col-md-12" id="small-table">
		                    <?php render_datatable(array(
		                        _l('name'),
		                        _l('redeemp_type'),
		                        _l('start_date'),
		                        _l('end_date'),
		                        _l('min_poin_to_redeem'),
		                        _l('rule_base'),
		                        _l('minium_purchase'),
		                        ),'table_loyalty_rule'); ?>
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
