<script>
(function(){
    "use strict";
   var fnServerParams = {  
      "cate": "[name='cate']"    
   }
   initDataTable('.table-permission', admin_url + 'team_password/permission_table_by_cate', false, false, fnServerParams, [0, 'desc']);

   var shareServerParams = {  
      "cate": "[name='cate']"    
   }
   initDataTable('.table-share', admin_url + 'team_password/share_table_by_cate', false, false, shareServerParams, [0, 'desc']);

   $('#tree').treeview({
    data:  <?php echo html_entity_decode($tree_cate); ?>,
    enableLinks: true,
  });
})(jQuery); 



function add_share(){
  "use strict";
  $('.add-title').removeClass('hide');
  $('.update-title').addClass('hide');
  $('input[name="id"]').val('');
  $('input[name="not_in_the_system"]').prop('checked',false); 
  $('.email_fr').addClass('hide');
  $('.client_fr').removeClass('hide');
  $('input[name="share_id"]').val('');
	$('#share').modal();
}

function update_share(el){
    "use strict";
    $('.add-title').addClass('hide');
    $('.update-title').removeClass('hide');
    var id = $(el).data('id');
    $('input[name="shareid"]').val(id);
    if($(el).data('not_in_the_system') == 'on'){
        $('input[name="not_in_the_system"]').prop('checked',true); 
        $('.email_fr').removeClass('hide');
        $('.client_fr').addClass('hide');
        $('input[name="email"]').val($(el).data('email'));
    }
    else{
        $('input[name="not_in_the_system"]').prop('checked',false); 
        $('.client_fr').removeClass('hide');
        $('.email_fr').addClass('hide');
        $('select[name="client"]').val($(el).data('client')).change();
    }
    $('select[name="share_id"]').val($(el).data('share_id')).change();
    $('input[name="effective_time"]').val($(el).data('effective_time'));
    if($(el).data('read')=='on'){
      $('input[name="read"]').prop('checked',true);
    }
    else{
      $('input[name="read"]').prop('checked',false);      
    }

    if($(el).data('write')=='on'){
      $('input[name="write"]').prop('checked',true);
    }
    else{
      $('input[name="write"]').prop('checked',false);      
    }

    $('#share').modal();

  }

function open_frame(el){
  "use strict";
    if($(el).is(':checked')){
      $('.email_fr').removeClass('hide');
      $('.client_fr').addClass('hide');
    }
    else{
      $('.email_fr').addClass('hide');
      $('.client_fr').removeClass('hide');
    }
}

 function add_permission(){
  "use strict";
  $('.add-title').removeClass('hide');
  $('.update-title').addClass('hide');
  $('input[name="id"]').val('');
  $('#permission').modal();
}

function update_permission(id,el){
    "use strict";
    $('.add-title').addClass('hide');
    $('.update-title').removeClass('hide');

    $('input[name="id"]').val(id);
    var list_id = [];
        list_id.push($(el).data('staff'));
    $('select[name="staff[]"]').val(list_id).change();
     if($(el).data('read')=='on'){
      $('input[name="read"]').prop('checked',true);
    }
    else{
      $('input[name="read"]').prop('checked',false);      
    }

    if($(el).data('write')=='on'){
      $('input[name="write"]').prop('checked',true);
    }
    else{
      $('input[name="write"]').prop('checked',false);      
    }
    $('#permission').modal();
}

</script>

