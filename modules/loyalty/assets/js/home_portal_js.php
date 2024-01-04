<script>
(function($) {	
	"use strict";
	var greetDate = new Date();
	var hrsGreet = greetDate.getHours();

	var greet;
	if (hrsGreet < 12)
		greet = "<?php echo _l('good_morning'); ?>";
	else if (hrsGreet >= 12 && hrsGreet <= 17)
		greet = "<?php echo _l('good_afternoon'); ?>";
	else if (hrsGreet >= 17 && hrsGreet <= 24)
		greet = "<?php echo _l('good_evening'); ?>";

	if(greet) {
		document.getElementById('greeting').innerHTML =
		'<b>' + greet + ' <?php echo html_entity_decode($contact->firstname); ?>!</b>';
	}
})(jQuery);

/**
 * { program detail }
 *
 * @param        id      The identifier
 */
function program_detail(id){
	"use strict";
	$.post(site_url + 'loyalty/loyalty_portal/program_detail/' +id).done(function(response) {
        response = JSON.parse(response);
        $('#program_detail').html('');
        $('#program_detail').append(response.html);

    });
}
</script>