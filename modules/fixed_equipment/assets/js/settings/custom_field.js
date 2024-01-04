(function(){
	"use strict";
	var fnServerParams = {
	}

	initDataTable('.table-customfield', admin_url + 'fixed_equipment/customfield_table', false, false, fnServerParams, [0, 'desc']);
	appValidateForm($('#add_fieldset-form'), {
		'name': 'required'
	})
})(jQuery);
/**
 * add custom field
 */
function add(){
	"use strict";
	$('#add_fieldset').modal('show');
	$('#add_fieldset .add-title').removeClass('hide');
	$('#add_fieldset .edit-title').addClass('hide');
	$('#add_fieldset input[name="id"]').val('');
	$('#add_fieldset input[type="text"]').val('');
	$('#add_fieldset textarea').val('');
}
/**
 * edit custom field
 */
function edit(id, el){
	"use strict";
	$('#add_fieldset').modal('show');
	var name = $(el).data('name');
	var notes = $(el).data('notes');
	$('#add_fieldset .add-title').addClass('hide');
	$('#add_fieldset .edit-title').removeClass('hide');
	$('#add_fieldset input[name="id"]').val(id);
	$('#add_fieldset input[name="name"]').val(name);
	$('#add_fieldset textarea[name="notes"]').val(notes);
}
