  $(function() {
  "use strict";
  $(document).off('keypress.shortcuts keydown.shortcuts keyup.shortcuts');
  $('#expand-button').click(function(){
  $('#top-panel').slideToggle( "slow" );
   $('iframe').css('top','542px');
  $('#expand-button').hide();
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
 });

 $('#close').click(function(){
  $('#top-panel').slideToggle( "slow" );
  $('iframe').css('top','144px');
  $('#expand-button').show();
 });  
   
});


