<script>
  _validate_form($('#task_bookmarks-form'),{task_bookmarks_name:'required'});

  initDataTable('.table-task_bookmarks', window.location.href);

  function new_task_bookmarks(){
    $('#task_bookmarks input[name="task_bookmarks_name"]').val('');
    $('.colorpicker-input').colorpicker('setValue', '#f0f509');
    $('#task_bookmarks i[id="icon"]').attr("class",'');
    $('#task_bookmarks input[name="icon"]').val('');
    $('#additional').find('input[name="id"]').remove();
     
    $('#task_bookmarks').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
  }
  function edit_task_bookmarks(invoker,id){
    $('#additional').find('input[name="id"]').remove();
    $('#additional').append(hidden_input('id',id));

    $('#task_bookmarks input[name="task_bookmarks_name"]').val($(invoker).data('name'));
    $('#task_bookmarks input[name="icon"]').val($(invoker).data('icon'));
    $('#task_bookmarks i[id="icon"]').attr("class",'fa '+$(invoker).data('icon'));
    $('.colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
    $('#task_bookmarks').modal('show');
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
    
}
var fnServerParams = {
   "rel_id": 'select[name="rel_id"]',
   "rel_type": 'select[name="rel_type"]',
 }
 $('#rel_id_wrapper').on('change', function() {
       task_bookmarks_list_task_filter();
     });
  function task_bookmarks_list_task_filter() {
     if ($.fn.DataTable.isDataTable('.table-task_bookmarks_list_task_filter')) {
       $('.table-task_bookmarks_list_task_filter').DataTable().destroy();
     }
     initDataTable('.table-task_bookmarks_list_task_filter', admin_url + 'task_bookmarks/task_bookmarks_list_task_filter', false, false, fnServerParams);
   }

function remove_list_task_bookmarks(task_bookmarks_id,taskid) {

    $.post(admin_url+'task_bookmarks/remove_list_task_bookmarks/'+task_bookmarks_id+'/'+id).done(function(response){
      $('#additional').find('input[value="'+taskid+'"]').remove();
      var list_tasks = $('input[name="list_tasks[]"]').map(function(){
      return $(this).val()
    }).get()
      $('input[name="list_tasks_<?php echo htmlspecialchars($id); ?>"]').remove();
      $('#additional').append(hidden_input('list_tasks_<?php echo htmlspecialchars($id); ?>',list_tasks));
      var list_tasks = {
     "list_tasks": 'input[name="list_tasks_<?php echo htmlspecialchars($id); ?>"]',
    }
    
     if ($.fn.DataTable.isDataTable('.table-task_bookmarks_list_task_add_<?php echo htmlspecialchars($id); ?>')) {
       $('.table-task_bookmarks_list_task_add_<?php echo htmlspecialchars($id); ?>').DataTable().destroy();
     }
     initDataTable('.table-task_bookmarks_list_task_add_<?php echo htmlspecialchars($id); ?>', admin_url + 'task_bookmarks/task_bookmarks_list_task_add', false, false, list_tasks);
    }); 
}
function add_dashboard(id){
    $.post(admin_url + 'task_bookmarks/add_task_bookmarks_widget/'+id).done(function(response) {
        response = JSON.parse(response);
            alert_float('success', response.message);
            window.location.reload();
     });
 }
 function remove_task_bookmarks_dashboard(id){
    $.post(admin_url + 'task_bookmarks/remove_task_bookmarks_widget/'+id).done(function(response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true') {
            alert_float('success', response.message);
            window.location.reload();
        }
     });
 }
</script>