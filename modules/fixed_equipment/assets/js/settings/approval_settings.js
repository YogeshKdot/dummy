	var addMoreVendorsInputKey = $('.list_approve select[name*="staff"]').length;
	(function(){
		"use strict";
		var fnServerParams = {
		}
		initDataTable('.table-approve', admin_url + 'fixed_equipment/approve_setting_table', false, false, fnServerParams, [0, 'desc']);
		appValidateForm($('#form_depreciations'), {
			'name': 'required',
			'term': 'required',
		})

		appValidateForm($('#approval-setting-form'),{'name':'required', 'staff[]':'required', 'related':'required'});

		$("body").on('click', '.new_vendor_requests', function() {
			"use strict";
			if ($(this).hasClass('disabled')) { return false; }    
			var newattachment = $('.list_approve').find('#item_approve').eq(0).clone().appendTo('.list_approve');
			newattachment.find('button[role="combobox"]').remove();
			newattachment.find('select').selectpicker('refresh');

			newattachment.find('select[name*="staff"]').attr('id', 'staff[' + addMoreVendorsInputKey + ']');
			newattachment.find('select[name*="staff"]').attr('name', 'staff[' + addMoreVendorsInputKey + ']');

			newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
			newattachment.find('button[name="add"]').removeClass('new_vendor_requests').addClass('remove_vendor_requests').removeClass('btn-success').addClass('btn-danger');

			addMoreVendorsInputKey++;
		});

		$("body").on('click', '.remove_vendor_requests', function() {
			"use strict";
			$(this).parents('#item_approve').remove();
		});

		$('input[name="choose_when_approving"]').click(function(){
			var val = $(this).is(':checked');
			if(val == true){
				$('.list_approve select[name*="staff"]').removeAttr('required');
				$('.list_approve').addClass('hide');
			}
			else{
				$('.list_approve select[name*="staff"]').attr('required', 'required');
				$('.list_approve').removeClass('hide');
			}
		});

	})(jQuery);
	/**
	 *  add approval 
	 */
	function add(){
		"use strict";
		addMoreVendorsInputKey = 1;
		$('.add-title').removeClass('hide');
		$('.edit-title').addClass('hide');
		$('input[name="approval_setting_id"]').val('');
		$('input[type="text"]').val('');
		$('input[type="number"]').val('');
		$('.remove_vendor_requests').click();
		$('select').val('').change();
		$('#approve_modal').modal();
	}
	/**
	 *  edit approval 
	 */
	function edit(id){
		"use strict";
		$('input[name="approval_setting_id"]').val(id);
		$('.add-title').addClass('hide');
		$('.edit-title').removeClass('hide');
		addMoreVendorsInputKey = 1;
		$.post(admin_url+'fixed_equipment/get_approve_setting/'+id).done(function(response){
			response = JSON.parse(response);
			if(response.success == true) {

				var item_approve = jQuery.parseJSON(response.data_setting.setting);
				$('.remove_vendor_requests').click();

				for (var i = 0;i < item_approve.length; i++) {
					if(i>0){
						$('.new_vendor_requests').click();
					}
					$('select[name="staff['+i+']"]').val(item_approve[i].staff).change();
				}
				if(response.data_setting.choose_when_approving == 1){
					$('#choose_when_approving').prop("checked", true);
					$('.list_approvest').addClass('hide');
					$('.list_approve select[name*="staff"]').removeAttr('required');
					$('.list_approve').addClass('hide');
				}
				else{
					$('#choose_when_approving').prop("checked", false);
					$('.list_approvest').removeClass('hide');  
				}


				$('input[name="name"]').val(response.data_setting.name);
				$('select[name="related"]').val(response.data_setting.related).change();


				$('select[name="notification_recipient[]"]').val(response.data_setting.notification_recipient).change();
				$('input[name="number_day_approval"]').val(response.data_setting.number_day_approval);

				$('#approve_modal').modal();
			}
			$('#savePredefinedReplyFromMessageModal').modal('hide');
		});
	}