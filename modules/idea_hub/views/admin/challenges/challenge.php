<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'challenge-form')) ;?>
                        <?php $attrs = (isset($challenge) ? array() : array('autofocus'=>true)); ?>
                        <?php $value = (isset($challenge) ? $challenge->title : ''); ?>
                        <?php echo render_input('title',_l('title'),$value,'text',$attrs); ?>

                        <?php $value = (isset($challenge) ? $challenge->category_id : ''); ?>
                        <?php echo render_select('category_id',$this->idea_hub_model->get_categories(),array('id','name'), _l('category'), $value); ?>

                        <?php $value = (isset($challenge) ? $challenge->description : ''); ?>
                        <?php  echo render_textarea('description',_l('description'),$value); ?>

                        <?php $value = (isset($challenge) ? $challenge->instruction : ''); ?>
                        <?php  echo render_textarea('instruction',_l('instruction'),$value); ?>
                        <?php $value = (isset($challenge) ? $challenge->cover_image : ''); 
                        if(!empty($value)) {
                            echo '<div><img width="60px" height="60px" src="'.base_url('modules/idea_hub/uploads/challenges/'.$value).'"></div>';
                        }
                        ?>
                        <?php  echo render_input('cover_image',_l('cover_image'),'','file'); ?>
                        <?php $value = (isset($challenge) ? $challenge->deadline : ''); ?>
                        <?php  echo render_datetime_input('deadline',_l('deadline'),$value); ?>
                        <div class="form-group">
                            <?php $value = (isset($challenge) ? $challenge->status : ''); ?>
                            
                            <label for="active" class="control-label clearfix">
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-title="active and deactive this challenge" data-original-title="" title=""></i>
                                    <?= _l('status'); ?>
                            </label>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="y_opt_1_Status" name="status" value="active"  <?php echo $value == 'active' ? 'checked' : 'checked'; ?>>
                                <label for="y_opt_1_Status">
                                    <?=_l('active')?></label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                            <input type="radio" id="y_opt_2_Status" name="status" value="inactive" <?php echo $value == 'inactive' ? 'checked' : ''; ?>>
                            <label for="y_opt_2_Status">
                                <?=_l('deactive')?></label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                            <input type="radio" id="y_opt_3_Status" name="status" value="archived" <?php echo $value == 'archived' ? 'checked' : ''; ?>>
                            <label for="y_opt_3_Status">
                                <?=_l('archived')?></label>
                            </div>
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
		</div>
		 <div class="col-md-6"></div>
	</div>
</div>
<?php init_tail(); ?>
<script>
    function validate_challenge_form(){
        <?php if(isset($challenge)) { ?>
            appValidateForm($('#challenge-form'), {
                title: 'required',
                category_id: 'required',
                description : 'required',
                deadline : 'required',
                status : 'required',
            });
        <?php } else { ?>
            appValidateForm($('#challenge-form'), {
                title: 'required',
                category_id: 'required',
                description : 'required',
                cover_image : 'required',
                deadline : 'required',
                status : 'required',
            });
        <?php } ?>
    }
    $(function(){
        $('body').on('click','button.save-ih', function() {
            $('form#challenge-form').submit();
        });
        validate_challenge_form();
		$("#y_opt_2_Status").change(function(){
			if(confirm("Are you sure ?")){
				$(this).prop("checked", true);
			}else{
				$("#y_opt_1_Status").prop("checked", true);
			}
		});
    })
</script>
</body>
</html>