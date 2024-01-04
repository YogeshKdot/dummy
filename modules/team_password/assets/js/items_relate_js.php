<script>
function copyToClipboard(password){
	"use strict";
    var $copyText = $("<input>");
    $("body").append($copyText);
    $copyText.val(password).select();
    document.execCommand("copy");
    alert_float('success','<?php echo _l('coppied'); ?>!');
}
</script>