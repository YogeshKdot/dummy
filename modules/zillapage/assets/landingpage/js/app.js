(function($) {
  "use strict"; // Start of use strict


    $("#mobile_device").on('click',function(){
        $('.website-append').addClass('mobile');
        $('.website-append').removeClass('labtop');
        $('.website-append').removeClass('tablet');
        $('#mobile_device').addClass('active');
        $('#labtop_device').removeClass('active');
        $('#tablet_device').removeClass('active');
      });

      $("#labtop_device").on('click',function(){
        $('.website-append').addClass('labtop');
        $('.website-append').removeClass('mobile');
        $('.website-append').removeClass('tablet');
        $('#mobile_device').removeClass('active');
        $('#labtop_device').addClass('active');
        $('#tablet_device').removeClass('active');
      });

      $("#tablet_device").on('click',function(){
        $('.website-append').addClass('tablet');
        $('.website-append').removeClass('labtop');
        $('.website-append').removeClass('mobile');
        $('#mobile_device').removeClass('active');
        $('#labtop_device').removeClass('active');
        $('#tablet_device').addClass('active');
      });

     

      $("#btn-main-page").on('click',function(){
        $('#btn-main-page').addClass('active');
        $('#btn-thank-you-page').removeClass('active');

        $( "#frameMainPage" ).removeClass('d-none');
        $( "#frameThankYouPage" ).addClass('d-none');
      });
      

      $("#btn-thank-you-page").on('click',function(){
        $('#btn-main-page').removeClass('active');
        $('#btn-thank-you-page').addClass('active');

        $( "#frameThankYouPage" ).removeClass('d-none');
        $( "#frameMainPage" ).addClass('d-none');
        
      });

       $(".btn_builder_template").on("click", function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#template_id_builder').val(id);

      });
   
     
})(jQuery); // End of use strict
