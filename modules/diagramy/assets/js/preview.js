$(function() {
  "use strict";
  $('#expand-button').click(function(){
  $('#top-panel').slideToggle( "slow" );
  $('#expand-button').hide();
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
 });

 $('#close').click(function(){
  $('#top-panel').slideToggle( "slow" );
  $('#expand-button').show();
 });
   
});

$(document).ready(function(){
    $('#print').click(function(){
    window.print();
 });
});