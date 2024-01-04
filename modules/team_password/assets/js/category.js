(function(){
   "use strict";
  var fnServerParams = {
      "vehicle_filter": "[name='vehicle_filter']"
  }
 initDataTable('.table-category_management', admin_url + 'team_password/category_management_table', false, false, fnServerParams, [0, 'desc']);
  appValidateForm($('#form_category_management'), {
           'category_name': 'required',
           'icon': 'required'
  });

  $('.icon-picker').iconpicker();
})(jQuery);

function add(){
   "use strict";
   $('input[name="id"]').val('');
   $('#category_management').modal('show');
   $('#category_management .add-title').removeClass('hide');
   $('#category_management .update-title').addClass('hide');
}
function update(el){
   "use strict";
   $('input[name="id"]').val($(el).data('id'));
   $('input[name="category_name"]').val($(el).data('category_name'));
   $('input[name="icon"]').val($(el).data('icon'));
   $('select[name="parent"]').val($(el).data('parent')).change();
     tinyMCE.activeEditor.setContent($(el).data('description'));
   $('#category_management').modal('show');
     $('#category_management .add-title').addClass('hide');
     $('#category_management .update-title').removeClass('hide');
}



