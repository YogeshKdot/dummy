$(function(){
	"use strict"; // Start of use strict

	$('#template_id_builder').hide();

	$(".btn_builder_template").on("click", function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		$('#template_id_builder').val(id);

	});

});