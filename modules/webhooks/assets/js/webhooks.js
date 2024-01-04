let tribute;
$(function() {
	"use strict";
	$(document.body).on('change', '#webhook_for', function(event) {
		var selectedValue = $(this).val()
		var fields = _.filter(merge_fields, function(num){
			return typeof num[selectedValue] != "undefined" || typeof num["other"] != "undefined";
		});

		var other_index = _.findIndex(fields, function (data) {
			return _.allKeys(data)[0] == "other";
		});
		var selected_index = _.findIndex(fields, function (data) {
			return _.allKeys(data)[0] == selectedValue;
		});

		var options = [];

		if (fields[selected_index]) {
			fields[selected_index][selectedValue].forEach(field => {
				if (field.name != "") {
					options.push({ key: field.name, value: field.key });
				}
			})
		}
		if (fields[other_index]) {
			fields[other_index]["other"].forEach(field => {
				if (field.name != "") {
					options.push({ key: field.name, value: field.key });
				}
			})
		}
		tribute = new Tribute({
			values: options,
			selectClass: "highlights"
		});
		tribute.detach(document.querySelectorAll(".mentionable"));
		tribute.attach(document.querySelectorAll(".mentionable"));
	});
	$("#webhook_for").trigger('change');

	appValidateForm($("#webhook-form"), {
        webhook_name: 'required',
        webhook_for: 'required',
        request_url: 'required',
        "webhook_action[]":'required',
    });

});

function refreshTribute(){
	"use strict";
	if($("#webhook_for").val() != ""){
		tribute.attach(document.querySelectorAll(".mentionable"));
	}
}