(function($) {
    "use strict";
    
// ----------------------------- Counter Function
        var timer = $('.timer');
        if(timer.length) {
            timer.appear(function () {
              timer.countTo();
          });
        }

// ------------------------ Navigation Scroll

// -------------------- From Bottom to Top Button
            //Check to see if the window is top if not then display button
        $(window).on('scroll', function (){
          if ($(this).scrollTop() > 200) {
            $('.scroll-top').fadeIn();
          } else {
            $('.scroll-top').fadeOut();
          }
        });
        
//---------------------- Click event to scroll to top
        $('.scroll-top').on('click', function() {
          $('html, body').animate({scrollTop : 0},1500);
          return false;
        });
// -------------------------- scroll animate
        var links = $('a.scroll-target');
        links.on('click', function() {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') || location.hostname == this.hostname) {
            var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 120,
                    }, 1000);
                    return false;
                }
            }
        });


// --------------------- Add .active class to current navigation based on URL
        var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
          $(".navbar-nav > li  a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
          $(this).addClass("active");
          // $(this).parent("li").addClass("active");
        })
          
// ----------------------------- MixItUp
        if ($(".mixitUp-container").length) {
          var containerEl = document.querySelector('.mixitUp-container');
          var mixer = mixitup(containerEl);
        };


// ------------------------ Password Toggler
        if($(".user-data-form").length) {
          $(".passVicon").on('click', function() {
            $(".passVicon").toggleClass("eye-slash");
            var input = $(".pass_log_id");
            if (input.attr("type") === "password") {
              input.attr("type", "text");
            } else {
              input.attr("type", "password");
            }

          });
        }


// ------------------------ Company Logo Slider
        if($(".companies-logo-slider").length) {
          $('.companies-logo-slider').slick({
              centerMode: true,
              centerPadding: '0px',
              slidesToShow: 7,
              prevArrow: $('.prev'),
              nextArrow: $('.next'),
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
              {
                  breakpoint: 991,
                  settings: {
                    arrows: true,
                    centerMode: true,
                    slidesToShow: 5
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    arrows: true,
                    centerMode: true,
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    arrows: true,
                    centerMode: true,
                    slidesToShow: 2
                  }
                }
              ]
            });
        }

// ------------------------ Company Logo Slider
        if($(".partnerSliderTwo").length) {
          $('.partnerSliderTwo').slick({
              centerMode: true,
              centerPadding: '0px',
              arrows: false,
              slidesToShow: 5,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
              {
                  breakpoint: 992,
                  settings: {
                    centerMode: true,
                    slidesToShow: 4
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    centerMode: true,
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    centerMode: true,
                    slidesToShow: 2
                  }
                }
              ]
            });
        }

// ------------------------ Client Feedback Slider One
        if($(".clientSliderOne").length) {
          $('.clientSliderOne').slick({
              centerMode: true,
              centerPadding: '0px',
              slidesToShow: 1,
              prevArrow: $('.prev_c'),
              nextArrow: $('.next_c'),
              autoplay: true,
              autoplaySpeed: 6000,
            });
        }


// ------------------------ Image Slick Slider 
        if($(".img-slick-slider").length) {
          $('.img-slick-slider').slick({
              dots: true,
              arrows: false,
              centerPadding: '0px',
              slidesToShow: 1,
              autoplay: true,
              autoplaySpeed: 6000,
            });
        }



// ------------------------ Client Feedback Slider Two
        if($(".clientSliderTwo").length) {
          $('.clientSliderTwo').slick({
              dots: true,
              arrows: false,
              centerMode: true,
              centerPadding: '0px',
              slidesToShow: 3,
              slidesToScroll: 3,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
        }

// ------------------------ Team Slider One
        if($(".teamSliderOne").length) {
          $('.teamSliderOne').slick({
              dots: false,
              arrows: true,
              prevArrow: $('.prev_c'),
              nextArrow: $('.next_c'),
              centerPadding: '0px',
              slidesToShow: 4,
              slidesToScroll: 1,
              autoplay: false,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
        }


// ------------------------ Client Feedback Slider Three
        if($(".clientSliderThree").length) {
          $('.clientSliderThree').slick({
              dots: false,
              arrows: true,
              prevArrow: $('.prevT'),
              nextArrow: $('.nextT'),
              centerPadding: '0px',
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: false,
              autoplaySpeed: 3000,
            });
        }


// ------------------------ Client Feedback Slider Four
        if($(".clientSliderFour").length) {
          $('.clientSliderFour').slick({
              dots: true,
              arrows: false,
              centerPadding: '0px',
              slidesToShow: 3,
              slidesToScroll: 3,
              autoplay: false,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                  }
                }
              ]
            });
        }

// ------------------------ Client Feedback Slider Five
        if($(".clientSliderFive").length) {
          $('.clientSliderFive').slick({
              centerMode: true,
              centerPadding: '0px',
              slidesToShow: 1,
              prevArrow: $('.prev_f'),
              nextArrow: $('.next_f'),
              autoplay: true,
              autoplaySpeed: 6000,
            });
        }


// ------------------------ Client Feedback Slider Six
        if($(".clientSliderSix").length) {
          $('.clientSliderSix').slick({
              dots: true,
              arrows: false,
              centerMode: true,
              centerPadding: '0px',
              slidesToShow: 3,
              slidesToScroll: 3,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 1200,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                  }
                }
              ]
            });
        }


// ------------------------ Client Feedback Slider Seven
        if($(".clientSliderSeven").length) {
          $('.clientSliderSeven').slick({
              centerMode: true,
              centerPadding: '0px',
              slidesToShow: 1,
              prevArrow: $('.prev_cs1'),
              nextArrow: $('.next_cs1'),
              autoplay: true,
              fade: true,
              autoplaySpeed: 6000,
            });
        }

// ------------------------ Client Feedback Slider Eight
        if($(".clientSliderEight").length) {
          $('.clientSliderEight').slick({
              dots: true,
              arrows: false,
              centerPadding: '0px',
              slidesToShow: 3,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 1200,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                  }
                }
              ]
            });
        }


// ------------------------ App Screen Preview 
        if($(".app-preview-slider-one").length) {
          $('.app-preview-slider-one').slick({
              dots: false,
              arrows: false,
              centerPadding: '0px',
              slidesToShow: 3,
              centerMode: true,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 2,
                    centerMode: false,
                  }
                }
              ]
            });
        }


// ------------------------ App Screen Preview Two
        if($(".app-preview-slider-two").length) {
          $('.app-preview-slider-two').slick({
              dots: false,
              arrows: false,
              centerPadding: '0px',
              slidesToShow: 5,
              centerMode: true,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
        }


// ------------------------ Portfolio Slider One
        if($(".portfolio_slider_one").length) {
          $('.portfolio_slider_one').slick({
              dots: false,
              arrows: true,
              prevArrow: $('.prev_case1'),
              nextArrow: $('.next_case1'),
              centerPadding: '0px',
              slidesToShow: 3,
              slidesToScroll: 1,
              autoplay: false,
              centerMode:true,
              autoplaySpeed: 3000,

            });
        }

// ------------------------ Portfolio Slider Two
        if($(".portfolio_slider_two").length) {
          $('.portfolio_slider_two').slick({
              dots: false,
              arrows: true,
              prevArrow: $('.prev_case2'),
              nextArrow: $('.next_case2'),
              centerPadding: '0px',
              slidesToShow: 3,
              slidesToScroll: 1,
              autoplay: false,
              centerMode:true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
        }


// ------------------------ Portfolio Three 
        if($(".portfolio_slider_three").length) {
          $('.portfolio_slider_three').slick({
              dots: false,
              arrows: true,
              prevArrow: $('.prev_c'),
              nextArrow: $('.next_c'),
              centerPadding: '0px',
              slidesToShow: 4,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
        }


// ------------------------ Product Slider One
        if($(".product_slider_one").length) {
          $('.product_slider_one').slick({
              dots: false,
              arrows: true,
              prevArrow: $('.prev_p1'),
              nextArrow: $('.next_p1'),
              centerPadding: '0px',
              slidesToShow: 3,
              slidesToScroll: 1,
              autoplay: false,
              centerMode:true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 1200,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
        }


// ------------------------ Product Slider Two
        if($(".product_slider_two").length) {
          $('.product_slider_two').slick({
              dots: false,
              arrows: false,
              centerPadding: '0px',
              slidesToShow: 4,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 3000,
              responsive: [
                {
                  breakpoint: 1200,
                  settings: {
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 576,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
            });
        }


// -------------------- Remove Placeholder When Focus Or Click
        $("input,textarea").each( function(){
            $(this).data('holder',$(this).attr('placeholder'));
            $(this).on('focusin', function() {
                $(this).attr('placeholder','');
            });
            $(this).on('focusout', function() {
                $(this).attr('placeholder',$(this).data('holder'));
            });     
        });


// -------------------------- Doc Sidebar
        var subMenu = $ (".doc-sidebar ul li.dropdown-holder>h4"),
            secSubMenu = $ (".doc-sidebar .sec-menu"),
            expender = $ (".doc-sidebar ul li.dropdown-holder .expander");
            subMenu.on("click", function (e) {
              e.preventDefault();
            });

            subMenu.append(function () {
              return '<span class="expander"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>';
            });

            subMenu.on('click', function () {
              if ( $(this).parent('li').children('ul').hasClass('show') ) {
                $(this).parent('li').children('ul').removeClass('show');
                } else {
                $('.sub-menu.show').removeClass('show');
                $(this).parent('li').children('ul').addClass('show');    
                };
            });

            secSubMenu.on('click', function () {
              if ( $(this).parent('li').children('ul').hasClass('open') ) {
                $(this).parent('li').children('ul').removeClass('open');
                } else {
                $('.sub-menu.open').removeClass('open');
                $(this).parent('li').children('ul').addClass('open');    
                };
            });

// -------------------------- Accordion
        var subMenu = $ (".card .card-header");
            subMenu.on("click", function (e) {
              e.preventDefault();
            });


            subMenu.on('click', function () {
              if ( $(this).parent('.card').children('.collapse').hasClass('show') ) {
                $(this).parent('.card').children('.collapse').removeClass('show');
                } else {
                $('.collapse.show').removeClass('show');
                $(this).parent('.card').children('.collapse').addClass('show');    
                };
            });

// -------------------------- scroll animate
        if($(".main-side-nav").length) {
          $('.main-side-nav a').on('click', function(){
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                $('html, body').animate({
                  scrollTop: (target.offset().top - 100)
                }, 800);
                return false;
              }
            }
          });
        }


// -------------------------- Mobile Nav
        if($(".theme-main-menu").length) {
          $('.theme-main-menu .navbar-toggler').on('click', function(){
            $(".navbar-collapse").toggleClass("show");
            $(this).toggleClass("open");
          });
          $('.dropdown-menu .dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
              $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            $(this).next(".dropdown-menu").toggleClass('show');
            return false;
          });
        }
// ----------------------- Closes responsive menu when a scroll trigger link is clicked
        $('#one-page-nav .nav-link').on('click', function(){
          $('.navbar-collapse').removeClass('show');
          $('.navbar-toggler').removeClass("open");
        })

// -------------------------- Mobile Doc Side Nav
        if($(".doc-sidebar").length) {
          $('.doc-sidebar .btn').on('click', function(){
            $(".doc-links").toggleClass("show");
          });
        }

// -------------------------- Sidebar Menu
        if($(".main-sidebar-nav").length) {
          $('.sidebar-nav-button').on('click', function(){
            $(".main-sidebar-nav").addClass("show");
          });
          $('.main-sidebar-nav .close-btn').on('click', function(){
            $(".main-sidebar-nav").removeClass("show");
          });
        }

// ------------------------ Product Quantity Selector
        if ($(".product-value").length) {
            $('.value-increase').on('click',function(){
              var $qty=$(this).closest('ul').find('.product-value');
              var currentVal = parseInt($qty.val());
              if (!isNaN(currentVal)) {
                  $qty.val(currentVal + 1);
              }
          });
          $('.value-decrease').on('click',function(){
              var $qty=$(this).closest('ul').find('.product-value');
              var currentVal = parseInt($qty.val());
              if (!isNaN(currentVal) && currentVal > 1) {
                  $qty.val(currentVal - 1);
              }
          });
        }

// ------------------------ Credit Card Option 
        if($("#credit-card").length) {
          $(".payment-radio-button").on('click',function(){
             if ($("#credit-card").is(":checked")) {
               $(".credit-card-form").show(300);
             } else {
               $(".credit-card-form").hide(300);
             }
           });
        }

// -------------------------- JS tilt Effect
        if($(".js-tilt").length) {
          $('.js-tilt').tilt({
              glare: true,
              maxGlare: .3
          })
        }
        
// --------------------------------- Contact Form
          // init the validator
          // validator files are included in the download package
          // otherwise download from http://1000hz.github.io/bootstrap-validator

        if($("#contact-form").length) {
            $('#contact-form').validator();
            // when the form is submitted
            $('#contact-form').on('submit', function (e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {
                    var url = "inc/contact.php";

                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function (data)
                        {
                            // data = JSON object that contact.php returns

                            // we recieve the type of the message: success x danger and apply it to the
                            var messageAlert = 'alert-' + data.type;
                            var messageText = data.message;

                            // let's compose Bootstrap alert box HTML
                            var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';

                            // If we have messageAlert and messageText
                            if (messageAlert && messageText) {
                                // inject the alert to .messages div in our form
                                $('#contact-form').find('.messages').html(alertBox);
                                // empty the form
                                $('#contact-form')[0].reset();
                            }
                        }
                    });
                    return false;
                }
            });
          }

    
    $(window).on ('load', function (){ // makes sure the whole site is loaded

// -------------------- Site Preloader
        $('#ctn-preloader').fadeOut(); // will first fade out the loading animation
        $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
        $('body').delay(350).css({'overflow':'visible'});



// ------------------------------- AOS Animation
        if ($("[data-aos]").length) { 
            AOS.init({
            duration: 1000,
            mirror: true
          });
        }
        
// ------------------------------------- Fancybox
        var fancy = $ (".fancybox");
        if(fancy.length) {
          fancy.fancybox({
            arrows: true,
            buttons: [
              "zoom",
              //"share",
              "slideShow",
              //"fullScreen",
              //"download",
              "thumbs",
              "close"
            ],
            animationEffect: "zoom-in-out",
            transitionEffect: "zoom-in-out",
          });
        }


// ------------------------------- AOS Animation
        if ($(".map-canvas").length) { 
            var map = new google.maps.Map($(".map-canvas")[0], {
                zoom: 14,
                center: new google.maps.LatLng(40.72, -74),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                clickableIcons: false
            });

            var marker = new google.maps.Marker({
                map: map,
                draggable: true,
                position: new google.maps.LatLng(40.72, -74),
                visible: true
            });
        }

    });  //End On Load Function
    
})(jQuery);