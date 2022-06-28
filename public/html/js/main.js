$(function() {
  'use strict'

  // var isMobile = {
  //   Android: function() {
  //     return navigator.userAgent.match(/Android/i);
  //   },
  //   BlackBerry: function() {
  //     return navigator.userAgent.match(/BlackBerry/i);
  //   },
  //   iOS: function() {
  //     return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  //   },
  //   Opera: function() {
  //     return navigator.userAgent.match(/Opera Mini/i);
  //   },
  //   Windows: function() {
  //     return navigator.userAgent.match(/IEMobile/i);
  //   },
  //   any: function() {
  //     return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
  //   }
  // };

  // if (isMobile.any()) {
  //   console.log("Mobile");
  // } else {

  //   $('.dropdown_toggle').on("mouseenter", function(e) {
  //     // make sure it is not shown:
  //     if (!$(this).parent().hasClass("show")) {
  //       $(this).click();
  //     }

  //   });

  //   $(".btn-group, .dropdown").on("mouseleave", function(e) {
  //     // make sure it is shown:
  //     if ($(this).hasClass("show")) {
  //       $(this).children('.dropdown_toggle').first().click();
  //     }

  //   });
  // }

  $('[data-toggle="offcanvas"]').on('click', function() {
    $('.offcanvas-collapse').toggleClass('open')
  })


  $('#home_carousel').owlCarousel({
    loop: true,
    margin: 30,
    autoplay: true,
    responsive: {
      0: {
        items: 1
      },
      768: {
        items: 2
      },
      992: {
        items: 3
      },
      1200: {
        items: 4
      }
    }
  });

  $('#spcl_detail').owlCarousel({
    loop: true,
    margin: 30,
    autoplay: true,
    responsive: {
      0: {
        items: 1
      },
      768: {
        items: 2
      },
      992: {
        items: 2
      },
      1200: {
        items: 3
      }
    }
  });

})