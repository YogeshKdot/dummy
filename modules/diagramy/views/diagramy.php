<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>modules/diagramy/assets/css/style.css">
<div id="wrapper">
 <div class="content">
  <div class="row">
   <?php
   if (isset($diagramy)) {
    echo form_hidden('is_edit', 'true');
  }
  ?>
  <?php echo form_open_multipart($this->uri->uri_string(), ['id'=>'diagramy-form']); ?>
  <div class="col-lg-12">
    <div class="panel_s" id="top-panel">
     <div class="panel-body">
      <h4 class="no-margin"><?php if (isset($diagramy) && $diagramy->title) {
       echo 'Edit '.$diagramy->title;
     } else {
       echo _l('diagramy_create_new');
     } ?>
     <span class="close2" id="close">×</span>         
   </h4>
   <hr class="hr-panel-heading" />
   <?php $value = (isset($diagramy) ? $diagramy->title : ''); ?>
   <?php echo render_input('title', 'Title', $value); ?>
   <?php
   $selected = (isset($diagramy) ? $diagramy->diagramy_group_id : '');
   if (is_admin() || '1' == get_option('staff_members_create_inline_diagramy_group')) {
     echo render_select_with_input_group('diagramy_group_id', $diagramy_groups, ['id', 'name'], 'diagramy_group', $selected, '<a href="#" onclick="new_group();return false;"><i class="fa fa-plus"></i></a>');
   } else {
     echo render_select('diagramy_group_id', $diagramy_groups, ['id', 'name'], 'diagramy_group', $selected);
   }
   ?>
   <?php $value = (isset($diagramy) ? $diagramy->description : ''); ?>
   <?php echo render_textarea('description', 'Description', $value, ['rows'=>4], []); ?>
   <div class="row">
     <div class="col-lg-6">
      <div class="form-group">
       <label for="related_to" class="control-label"><?php echo _l('related_to'); ?></label>
       <select name="related_to" class="selectpicker form-control" id="related_to" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
        <option value=""></option>
        <option value="project"
        <?php if (isset($diagramy) || $this->input->get('related_to')) {
          if ('project' == $diagramy->related_to) {
            echo 'selected';
          }
        } ?>><?php echo _l('project'); ?>
      </option>
      <option value="task"
      <?php if (isset($diagramy) || $this->input->get('related_to')) {
        if ('task' == $diagramy->related_to) {
          echo 'selected';
        }
      } ?>><?php echo _l('task'); ?>
    </option>
  </select>
</div>
</div>
<div class="col-lg-6">
  <div class="form-group<?php if ('' == isset($diagramy->rel_id)) {
   echo ' hide';
 } ?>" id="rel_id_wrapper">
 <label for="rel_id" class="control-label"><span class="rel_id_label"></span></label>
 <div id="rel_id_select">
  <select name="rel_id" id="rel_id" class="selectpicker ajax-sesarch" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
    <?php if ('' != $diagramy->related_to && '' != $diagramy->rel_id) {
     $rel_data = get_relation_data($diagramy->related_to, $diagramy->rel_id);
     $rel_val  = get_relation_values($rel_data, $diagramy->related_to);
     echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
   } ?>
 </select>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php echo render_input('staffid', '', get_staff_user_id(), 'hidden'); ?>
<?php $value = (isset($diagramy) ? $diagramy->diagramy_content : ''); ?>
<input type="hidden"  id="diagramy_content" name="diagramy_content" value="<?php echo $value; ?>">
<div class="col-lg-12">
  <div class="panel_s">
   <div class="panel-body">
    <h4 class="no-margin"><?php echo _l('diagramy'); ?>
    <span><button id="expand-button" type="button" class="collapsible btn btn-success">Properties</button></span>
  </h4>
  <hr class="hr-panel-heading" />
  <div class="row">
   <div class="col-md-12">
    <div id="map">
     <div id="image" style="max-width:100%;cursor:pointer;" onclick="edit(this,1);" src="<?php echo $value = (isset($diagramy) ? $diagramy->diagramy_content : ''); ?>" /></div>
     <div id="load_ifm"></div>
   </div>
 </div>
</div>
</div>
</div>
<div class="btn-bottom-toolbar text-right">
 <button type="button" class="btn btn-info diagramy-btn"><?php echo _l('submit'); ?></button>
</div>
</div>
<?php echo form_close(); ?>
</div>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<?php $this->load->view('diagramy/diagramy_group'); ?>
<?php init_tail(); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>modules/diagramy/assets/js/style.js"></script>
<script type="text/javascript">
 var eventcheck='';
 var editor = 'https://embed.diagrams.net/?embed=1&spin=1&ui=atlas&proto=json&saveAndExit=0&noSaveBtn=1&noExitBtn=1&modified=1';
 var initial = null;
 var name = null;
       //Control Diagramy iframe
       function edit2()
       {
        var iframe = document.createElement('iframe');
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('id', 'map');
        var close = function()
        {
          var el = document.getElementsByTagName("iframe")[0];
          if(el)
          {
           el.parentNode.removeChild(el);
         }
         else
         {
           return false;
         }
       };
       close();
       $('#edit_text').text('(Note : If you want to edit project again double click on image.)');
     }
       //diagramy io code
       function edit(image,id)
       {
        var iframe = document.createElement('iframe');
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('id', 'map');
        var close = function()
        {
         window.removeEventListener('message', receive);
         document.body.removeChild(iframe);
       };
       var draft = localStorage.getItem('.draft-' + name);
       if (draft != null)
       {
         draft = JSON.parse(draft);
         draft= null;
       }
       var receive = function(evt)
       {
         if (evt.data.length > 0)
         {
           var msg = JSON.parse(evt.data);
           if (msg.event == 'init')
           {
             if (draft != null)
             {
               iframe.contentWindow.postMessage(JSON.stringify({action: 'load',
                                                               autosave: 1, xml: draft.xml}), '*');
               iframe.contentWindow.postMessage(JSON.stringify({action: 'status',
                                                               modified: true}), '*');
             }
             else
             {
               iframe.contentWindow.postMessage(JSON.stringify({action: 'load',
                                                               autosave: 1, xmlpng: image.getAttribute('src')}), '*');
             }
           }
           else if (msg.event == 'export')
           {
             $('#diagramy_content').val(msg.data);
             image.setAttribute('src', msg.data);
             localStorage.setItem(name, JSON.stringify({lastModified: new Date(), data: msg.data}));
           }
           else if (msg.event == 'autosave')
           {
            eventcheck=1;
            iframe.contentWindow.postMessage(JSON.stringify({action: 'export',
                                                            format: 'xmlpng', xml: msg.xml, spin: 'Updating page'}), '*');
            localStorage.setItem('.draft-' + name, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
            console.log(localStorage, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
            $('#diagramy_content').val(msg.data);
          }
          else if (msg.event == 'save')
          {
            eventcheck=3;
            iframe.contentWindow.postMessage(JSON.stringify({action: 'export',
                                                            format: 'xmlpng', xml: msg.xml, spin: 'Updating page'}), '*');
            localStorage.setItem('.draft-' + name, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
            console.log(localStorage, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
            $('#diagramy_content').val(msg.data);
          }
          else if (msg.event == 'exit')
          {
           localStorage.removeItem('.draft-' + name);
           draft = null;
           close();
         }
       }
     };
     window.addEventListener('message', receive);
     iframe.setAttribute('src', editor);
     document.getElementById("load_ifm").appendChild(iframe);
   };
   function load()
   {
     initial = document.getElementById('image').getAttribute('src');
     start();
   };
       // start loading diagramy
       function start()
       {
         name = (window.location.hash.length > 1) ? window.location.hash.substring(1) : 'default';
         var current = localStorage.getItem(name);
         if (current != null)
         {
           var entry = JSON.parse(current);
           document.getElementById('image').setAttribute('src', entry.data);
         }
         else
         {
           document.getElementById('image').setAttribute('src', initial);
         }
         $('#image').click();
       };
       window.addEventListener('hashchange', start);
     </script>
     <script type="text/javascript">
       $(document).ready(function() { 
         $('#image').click();
       });
     </script>
     <script type="text/javascript">
       $(function() {
         $("button.diagramy-btn").on('click', function (e) {
           var diagramy_content = $('#diagramy_content').val();
           if(diagramy_content=='')
           {
             alert('Please draw your project first then save!');
           }
           else
           {
             setTimeout( function(){ 
              $('#top-panel').show( "slow" );
              var count=0;
              var data = $('#diagramy-form').serializeArray().reduce(function(obj, item) {
               if(item.value=='')
               {
                 validate_diagramy_form();
                 count++;
               }   
             }, {});
              if(count>0)
              {
               $('#top-panel').show( "slow" ); 
               $('#expand-button').hide();
             }
             // edit2();
             $('#diagramy-form').submit();
           }  , 200);
           }
         });
         validate_diagramy_form();
       });
   //validation of form
   function validate_diagramy_form(){
     appValidateForm($('#diagramy-form'), {
       title: 'required',
       description : 'required',
       diagramy_group_id: 'required',
       related_to:'required',
       rel_id:'required',
     });
   }
 </script>
 <script>
   var _rel_id = $('#rel_id'),
   _rel_type = $('#related_to'),
   _rel_id_wrapper = $('#rel_id_wrapper'),
   data = {};
   var _milestone_selected_data;
   _milestone_selected_data = undefined;
   $(function(){
    $( "body" ).off( "change", "#rel_id" );
    $('.rel_id_label').html(_rel_type.find('option:selected').text());
    _rel_type.on('change', function() {
     var clonedSelect = _rel_id.html('').clone();
     _rel_id.selectpicker('destroy').remove();
     _rel_id = clonedSelect;
     $('#rel_id_select').append(clonedSelect);
     $('.rel_id_label').html(_rel_type.find('option:selected').text());
     task_rel_select();
     if($(this).val() != ''){
      _rel_id_wrapper.removeClass('hide');
    } else {
      _rel_id_wrapper.addClass('hide');
    }
    init_project_details(_rel_type.val());
  });
    init_datepicker();
    init_color_pickers();
    init_selectpicker();
    task_rel_select();
    $('body').on('change','#rel_id',function(){
     if($(this).val() != ''){
       if(_rel_type.val() == 'project'){
         $.get(admin_url + 'projects/get_rel_project_data/'+$(this).val()+'/'+taskid,function(project){
           $("select[name='milestone']").html(project.milestones);
           if(typeof(_milestone_selected_data) != 'undefined'){
            $("select[name='milestone']").val(_milestone_selected_data.id);
            $('input[name="duedate"]').val(_milestone_selected_data.due_date)
          }
          $("select[name='milestone']").selectpicker('refresh');
          if(project.billing_type == 3){
           $('.task-hours').addClass('project-task-hours');
         } else {
           $('.task-hours').removeClass('project-task-hours');
         }
         if(project.deadline) {
          var $duedate = $('#_task_modal #duedate');
          var currentSelectedTaskDate = $duedate.val();
          $duedate.attr('data-date-end-date', project.deadline);
          $duedate.datetimepicker('destroy');
          init_datepicker($duedate);
          if(currentSelectedTaskDate) {
           var dateTask = new Date(unformat_date(currentSelectedTaskDate));
           var projectDeadline = new Date(project.deadline);
           if(dateTask > projectDeadline) {
            $duedate.val(project.deadline_formatted);
          }
        }
      } else {
        reset_task_duedate_input();
      }
      init_project_details(_rel_type.val(),project.allow_to_view_tasks);
    },'json');
       } else {
         reset_task_duedate_input();
       }
     }
   });
    <?php if (isset($diagramy->related_to) && '' != $diagramy->rel_id) { ?>
      _rel_id.change();
    <?php } ?>
  });
   <?php if (isset($_milestone_selected_data)) { ?>
    _milestone_selected_data = '<?php echo json_encode($_milestone_selected_data); ?>';
    _milestone_selected_data = JSON.parse(_milestone_selected_data);
  <?php } ?>
  function task_rel_select(){
    var serverData = {};
    serverData.rel_id = _rel_id.val();
    data.type = _rel_type.val();
    var url;
    if (data.type == "task") {
      url = admin_url + "diagramy/search_task";
    }
    init_ajax_search(_rel_type.val(),_rel_id,serverData, url);
  }
  function init_project_details(type,tasks_visible_to_customer){
    var wrap = $('.non-project-details');
    var wrap_task_hours = $('.task-hours');
    if(type == 'project'){
      if(wrap_task_hours.hasClass('project-task-hours') == true){
        wrap_task_hours.removeClass('hide');
      } else {
        wrap_task_hours.addClass('hide');
      }
      wrap.addClass('hide');
      $('.project-details').removeClass('hide');
    } else {
      wrap_task_hours.removeClass('hide');
      wrap.removeClass('hide');
      $('.project-details').addClass('hide');
      $('.task-visible-to-customer').addClass('hide').prop('checked',false);
    }
    if(typeof(tasks_visible_to_customer) != 'undefined'){
      if(tasks_visible_to_customer == 1){
        $('.task-visible-to-customer').removeClass('hide');
        $('.task-visible-to-customer input').prop('checked',true);
      } else {
        $('.task-visible-to-customer').addClass('hide')
        $('.task-visible-to-customer input').prop('checked',false);
      }
    }
  }
  function reset_task_duedate_input() {
    var $duedate = $('#_task_modal #duedate');
    $duedate.removeAttr('data-date-end-date');
    $duedate.datetimepicker('destroy');
    init_datepicker($duedate);
  }
</script>
</body>
</html>