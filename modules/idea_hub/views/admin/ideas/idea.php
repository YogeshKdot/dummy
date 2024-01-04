<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="idea_hub">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body ad_bottom_cl">
                        <div class="in_cl_maksd">
                            <div class="back_btn_swa">
                                <div class="challenge_btn_cl">
                                    <a href="<?php echo admin_url('idea_hub/ideas/'.$challenge_id); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-angle-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       <?php
            if(isset($idea)){
                echo form_hidden('is_edit','true');
            }
            echo form_open_multipart($this->uri->uri_string(),array('id'=>'idea-form'));
        ?>
        <section class="new_ideas_cl wrap_data_new_ip">
            <input type="hidden" name="challenge_id" value="<?php echo $challenge_id; ?>">
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel_s">
                        <div class="panel-body" style="padding: 0;">
                            <div class="card">
                                <div class="card-header">
                                  Add new idea
                                </div>
                                <div class="card-body">
                                    <?php $value = (isset($idea) ? $idea->title : ''); ?>
                                    <?php  echo render_input('title','Title <small class="req text-danger">* </small>',$value, $type = 'text', $input_attrs = ['placeholder'=>'Enter Title']); ?>

                                    <?php $value = (isset($idea) ? $idea->description : ''); ?>
                                    <?php  echo render_textarea('description','description',$value, ['placeholder'=>'Enter Description'], [], '', 'contentTextArea'); ?>

                                    <div class="form-group">
                                        <?php $value = (isset($idea) ? $idea->cover_type : ''); ?>
                                        <label for="active" class="control-label clearfix">
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?= _l('select_file_type'); ?>" data-original-title="" title=""></i>
                                            <?= _l('cover_type'); ?>
                                        </label>
                                        <div class="radio radio-primary radio-inline">
                                            <input type="radio" id="cover_type_image" name="cover_type" value="image"  <?php echo $value == 'image' ? 'checked' : 'checked'; ?>>
                                            <label for="cover_type_image">
                                                <?=_l('image')?>
                                            </label>
                                        </div>
                                        <div class="radio radio-primary radio-inline">
                                            <input type="radio" id="cover_type_video" name="cover_type" value="video" <?php echo $value == 'video' ? 'checked' : ''; ?>>
                                            <label for="cover_type_video">
                                                <?=_l('video')?>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                      <div class="form-group">
                                          <div class="file">
                                              <?php echo render_input('image',_l('cover'),'','file'); ?>
                                          </div>
                                           <?php $type = isset($idea) ? $idea->cover_type : '';
                                            $value = '';
                                             if($type == 'image' && $idea->image){
                                                $value = $idea->image;
                                                echo '<div><img width="60px" height="60px" src="'.base_url('modules/idea_hub/uploads/ideas/'.$value).'"></div>';
                                            }elseif ($type == 'video') {
                                             $value = $idea->video_thumbnail;
                                             echo '<div><img width="60px" height="60px" src="'.base_url('modules/idea_hub/uploads/ideas/v_thumbnails/'.$value).'"></div>';
                                            } 
                                            ?>
                                      </div>
                                    </div>

                                    <div class="form-group <?php echo $type == 'video' ? 'hide_thumb_cl show_hide_thum':'hide_thumb_cl'; ?>">
                                      <div class="form-group">
                                          <div class="file">
                                              <?php echo render_input('video_thumbnail',_l('video_thumbnail').'<small class="req text-danger">* </small>','','file',[],[],''); ?>
                                          </div>
                                      </div>
                                    </div>

                                    <?php
                                        $value = (isset($idea) ? $idea->status_id : '');
                                        echo render_select('status_id',$this->idea_hub_model->get_statuses(),array('id','name'), _l('status'), $value); 
                                        $value = (isset($idea) ? $idea->stage_id : '');
                                        echo render_select('stage_id',$this->idea_hub_model->get_stages(),array('id','name'), _l('stage'), $value);
                                    ?>

                                    <div class="form-group">
                                        <?php $value = (isset($idea) ? $idea->visibility : ''); ?>
                                        <label for="active" class="control-label clearfix">
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?= _l('idea_visibility_help'); ?>" data-original-title="" title=""></i>
                                                <?= _l('visibility'); ?>
                                        </label>
                                        <div class="radio radio-primary radio-inline">
                                            <input type="radio" id="y_opt_1_visibility" name="visibility" value="private"  <?php echo $value == 'private' ? 'checked' : ''; ?>>
                                            <label for="y_opt_1_visibility">
                                                <?=_l('private')?>
                                            </label>
                                        </div>
                                        <div class="radio radio-primary radio-inline">
                                            <input type="radio" id="y_opt_2_visibility" name="visibility" value="public" <?php echo $value == 'public' ? 'checked' : ''; ?>>
                                            <label for="y_opt_2_visibility">
                                                <?=_l('public')?>
                                            </label>
                                        </div>
                                        <div class="radio radio-primary radio-inline">
                                            <input type="radio" id="y_opt_3_visibility" name="visibility" value="custom" <?php echo $value == 'custom' ? 'checked' : ''; ?>>
                                            <label for="y_opt_3_visibility">
                                                <?=_l('custom')?>
                                            </label>
                                        </div>
                                    </div>
                                  <?php $clients_ed = (isset($custom_visible) ? explode(',',$custom_visible) : []); ?>
                                   <div id="clientid-dropdown" class="form-group <?= $value == 'custom' ? '' : 'custom_f_cl'?>">
                                        <label class="form-label">Custom <small class="req text-danger">* </small></label>
                                        <select name="clients[]" id="clients" class="selectpicker" data-live-search="true" multiple data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                            <?php foreach($clients as $s) { ?>
                                                <option value="<?php echo html_entity_decode($s['userid']); ?>" <?php if(isset($clients_ed) && in_array($s['userid'], $clients_ed)){ echo 'selected'; } ?>>
                                                    <?php echo html_entity_decode($s['company']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                  </div>

                                  <div class="form-group">
                                      <label class="form-label"><i class="fa fa-tag" aria-hidden="true"></i> Tags <small class="req text-danger">* </small></label>
                                      <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($idea) && tags_name_array_by_id($idea->id) ? prep_tags_input(tags_name_array_by_id($idea->id)) : ''); ?>" data-role="tagsinput">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>

                <div class="col-lg-6 ">
                  <div class="panel_s">
                      <div class="panel-body" style="padding: 0;">
                          <div class="card">
                              <div class="card-header">
                                  Additional Details
                              </div>
                              <div class="card-body">
                                  <div class="form-group">
                                      <div class="form-group">
                                          <?php
                                          echo render_input('attachment[]','attachments','','file',array('multiple'=>true));
                                          ?>
                                      </div>
                                      <?php
                                      if(isset($idea)){
                                        $attachs = get_attachments_by_idea_id($idea->id); 
                                        if (isset($attachs) && !empty($attachs)) {
                                            foreach ($attachs as $key => $value) {
                                                echo '<span class="attach_icon"><a href="'.base_url('modules/idea_hub/uploads/ideas/attachment/'.$value['file_name']).'" /download>
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                                </a></span>';
                                            }   
                                        }
                                    }
                                    ?>
                                  </div>
                                  <?php
                                    $value = (isset($idea) ? $idea->bussiness_impact : ''); 
                                    echo render_textarea('bussiness_impact',_l('bussiness_impact'),$value, ['placeholder'=>'Bussiness impact'], [], '', 'contentTextArea'); 
                                    $value = (isset($idea) ? $idea->goal : ''); 
                                    echo render_textarea('goal','goal', $value, ['placeholder'=>'Goal'], [], '', 'contentTextArea'); 
                                    $value = (isset($idea) ? $idea->additional_info : ''); 
                                    echo render_textarea('additional_info','additional_info', $value, ['placeholder'=>'Additional info'], [], '', 'contentTextArea');
                                    ?>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
            </div>
        </section>
        <div class="btn-bottom-toolbar text-right">
            <button type="submit" class="btn btn-info save-ih">Save</button>
        </div>
         <?php echo form_close(); ?>
    </div>
</div>
<?php init_tail(); ?>
 <script>
        function validate_idea_form(){
            <?php if(isset($idea) && !empty($idea->image)) { ?>
                appValidateForm($('#idea-form'), {
                    title: 'required',
                    cover_type: 'required',
                    status_id : 'required',
                    stage_id:'required',
                    visibility : 'required',
                    'clients[]' : {
                        required: {
                            depends: function() {
                                return ($("input[name='visibility']:checked").val() == 'custom')?true:false;
                            }
                        }
                    },
                });
            <?php } else { ?>
                appValidateForm($('#idea-form'), {
                    title: 'required',
                    image : 'required',
                    video_thumbnail: {
                        required: {
                            depends: function(){
                                return ($("input[name='cover_type']:checked").val() == 'video')?true:false;
                            }
                        }
                    },
                    status_id : 'required',
                    stage_id:'required',
                    visibility : 'required',
                    'clients[]' : {
                        required: {
                            depends: function() {
                                return ($("input[name='visibility']:checked").val() == 'custom')?true:false;
                            }
                        }
                    },
                });
            <?php } ?>
        }
        $(function(){
            $('body').on('click','button.save-ih', function() {
                $('form#idea-form').submit();
            });
            validate_idea_form();
        })
        tinymce.init({
            selector: '.contentTextArea',
            plugins: 'lists',
            toolbar: 'numlist bullist',
            branding: false
        });
        
        $("#cover_type_video").click(function(){
          $(".hide_thumb_cl").addClass("show_hide_thum");
        });
            
        $("#cover_type_image").click(function(){
          $(".hide_thumb_cl").removeClass("show_hide_thum");
        });    

        $("#y_opt_3_visibility").click(function(){
          $(".custom_f_cl").addClass("show_hide_thum");
        });
             
        $("#y_opt_1_visibility").click(function(){
          $(".custom_f_cl").removeClass("show_hide_thum");
        });   
            
        $("#y_opt_2_visibility").click(function(){
          $(".custom_f_cl").removeClass("show_hide_thum");
        });
</script>
</body>
</html>