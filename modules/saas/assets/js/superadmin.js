$(document).ready(function() {
	"use strict";

	$('#plan_image').change(function() {
		const file = this.files[0];
		if (file) {
		 	let reader = new FileReader();
		 	reader.onload = function(event) {
		    	$('#imgPreview').attr('src', event.target.result);
		    	$('#imgPreview').removeClass('hide');
		  	}
		 	reader.readAsDataURL(file);
		 	$('.existing_image').addClass('hide');
		}
	});

	$('body').on('click', '#checkDbUser',function (event) {
		mysql_host = $('input[name="settings[mysql_host]"]').val().trim();
		mysql_root_username = $('input[name="settings[mysql_root_username]"]').val().trim();
		mysql_port = $('input[name="settings[mysql_port]"]').val().trim();
		mysql_password = $('input[name="settings[mysql_password]"]').val().trim();

		var btnCheckDbUser = $(this);
		btnCheckDbUser.attr('disabled', true);
		$('.loader').show();

		$.ajax({
			url: `${admin_url}saas/plans/checkDbUser`,
			type: 'POST',
			data: {mysql_host, mysql_port, mysql_root_username, mysql_password},
			dataType: 'json',
		}).done(function(res) {
			btnCheckDbUser.attr('disabled', false);
			$('.loader').hide();
			alert_float(res.color, res.message);
		});
	});

	$('body').on('show.bs.modal', '#change_plan_modal', function(event) {
		$('#change-plan-form')[0].reset();
		$('.selectpicker').selectpicker('refresh');
	});

	function changeSaasPlan(form) {
		$.ajax({
			url: `${admin_url}saas/plans/changeSaasPlan`,
			type: 'POST',
			dataType: 'json',
			data: $(form).serialize(),
		})
		.done(function(res) {
			var type = (res.status) ? 'success' : 'danger';
			alert_float(type,res.message);
			$('#change_plan_modal').modal('hide');
			setTimeout(function () {
				location.reload();
	        }, 2000);
		});
	}

	appValidateForm($('#change-plan-form'), {
		saas_plan: "required"
	}, changeSaasPlan);
});
