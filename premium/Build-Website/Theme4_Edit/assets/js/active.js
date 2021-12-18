
/* 
=========================================================================
Template Name: "Elegant - Personal CV/Portfolio/Resume Html5 Tamplate"
Template URI: http://www.sobuj4u.com/themezone/elegant
Description: "Elegant - Personal CV/Portfolio/Resume Html5 Tamplate"
Author: Al Amin Sobuj
Author URI: http://www.sobuj4u.com
Version: 1.0
========================================================================= 
*/

(function ($) {
  "use strict";
  jQuery(document).ready(function($){

  	$(".menu-bars").on("click" ,function(){
  		$('.navigation').toggleClass('active');
  		$(this).toggleClass('active');

  		return false
  	});

  	$(".navigation li a").on('click', function(){
      $(".navigation").removeClass("active");
      $(".menu-bars").removeClass("active");
    });

    // Smoot Scroll Effect
    $('li.smooth-scroll a').bind('click', function(event){
      var $anchor = $(this);
      var headerHeight = '0';
      $('html, body').stop().animate({
        scrollTop : $($anchor.attr('href')) .offset().top - headerHeight + "px"
      }, 1200, 'easeInOutExpo');
      event.preventDefault();
    });

    // scrollspy
    $('body').scrollspy({
        target : '.navigation-inner',
        offset : 0
    });

    // Progress Bar
    $('.skill-area').waypoint(function() {
      $('.progress-bar').addClass('slideInLeft');
    }, {
      triggerOnce: true,
      offset: '70%'
    });

    //experiences Slider
    $(".experiences-sllides").owlCarousel({
      items : 3,
      margin: 30,
      loop: true,
      autoplay: true,
      smartSpeed: 1000,
      dots: false,
      nav: true,
      navText: ["<i class='fa fa-long-arrow-left'></i>", "<i class='fa fa-long-arrow-right'></i>"],
      responsiveClass:true,
      responsive:{
        0:{
          items:1,
          center: false
        },
        600:{
          items:2,
          center: false
        },
        769:{
          items:3,
          center: false
        }
      }
    });

     // Counter
     $('.counter-item').counterUp({
      delay: 10,
      time: 2000
    });

    // Active Data Filter on isotope
    $(".portfolio-navigation li").on('click', function() {
      // Active class add & remove
      $(".portfolio-navigation li").removeClass("active");
      $(this).addClass("active");

      var selector = $(this).attr('data-filter');
      $(".portfolio-list").isotope({
        filter: selector
      });
    });
    // Magnific Portfolio Popup
    $('.portfolio-view').magnificPopup({
      type: 'image',
      removalDelay: 300,
      mainClass: 'mfp-fade',
      gallery:{
        enabled:true
      }
    });

    // Testtimonail Slides activation
    $(".testimonial-slides").owlCarousel({
      items :3,
      margin: 30,
      loop: true,
      autoplay: true,
      smartSpeed: 1000,
      dots: true,
      nav: false,
      center:true,
      responsive:{
      	0:{
            items:1
        },
      	600:{
      		items:3
      	}
      }
    });

    // Brand Activation
    $(".brand-slides").owlCarousel({
      items : 5,
      margin: 10,
      loop: true,
      autoplay: true,
      smartSpeed: 1000,
      responsive:{
        0:{
          items:1,
          center: true,
          margin: 0
        },
        600:{
          items:2
        },
        800:{
          items:3
        },
        1024:{
          items:4
        },
        1100:{
          items:4
        },
        1200:{
          items:4
        }
      }
    });

    //Scroll To top Function apply
    $(window).scroll(function(){
      var ScrollToTop = $(window).scrollTop();
    //ScrollToTop Function
    if( ScrollToTop > 500){
      $(".ScrollToTop").fadeIn();
    }
    else{
      $(".ScrollToTop").fadeOut();
    }
    });

    //Scroll To top With animate
    $(".ScrollToTop").on('click', function(){
      $("body, html").animate({'scrollTop' : 0}, 1000, 'easeInOutExpo');
      return false
    });

    // WOW JS
    new WOW().init();
  });

  jQuery(window).load(function(){
  	jQuery(".portfolio-list").isotope();
    jQuery(".elegant-preeloader").fadeOut(500);
  });
  
}(jQuery));