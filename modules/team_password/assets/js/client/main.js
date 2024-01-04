function copyToClipboard(element) {
  "use strict";
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).data('hash')).select();
  document.execCommand("copy");
  $temp.remove();
  alert_float('success','Copied');
}

