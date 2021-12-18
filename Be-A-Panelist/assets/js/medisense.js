jQuery(document).ready(function ($) {
	$('#home-slider').flexslider({
	pauseOnHover: false,    
	slideshow: true,                //Boolean: Animate slider automatically
	slideshowSpeed: 6000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
	animationSpeed: 600,
	animation: "fade",              //String: Select your animation type, "fade" or "slide"
	easing: "swing",               //{NEW} String: Determines the easing method used in jQuery transitions. jQuery easing plugin is supported!
	direction: "horizontal",
	controlNav: false,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
	useCSS: true,                   //{NEW} Boolean: Slider will use CSS3 transitions if available
	touch: true, 
	directionNav: false
	});
  });
  $(document).ready(function() {

	
		
	if($(".owl-carousel-2").length > 0){
		// Owl Carousel
		$(".owl-carousel-2").owlCarousel({
			items : 2,
			lazyLoad : true,
			pagination : false,
			autoPlay: 10000,
			stopOnHover: true
		}); 
	}
	if($(".owl-carousel-3").length > 0){
		// Owl Carousel
		$(".owl-carousel-3").owlCarousel({
			items : 3,
			lazyLoad : true,
			pagination : false,
			autoPlay: 10000,
			stopOnHover: true
		}); 
	}
	if($(".owl-carousel-4").length > 0){
		// Owl Carousel
		$(".owl-carousel-4").owlCarousel({
			items : 4, //10 items above 1000px browser width
      itemsDesktop : [1000,5], //5 items between 1000px and 901px
      itemsDesktopSmall : [900,3], // betweem 900px and 601px
      itemsTablet: [600,2], //2 items between 600 and 0
			lazyLoad : true,
			pagination: false,	
autoplay:false	,		
			autoPlay:1500,
			stopOnHover: true
		}); 
	}
	if($(".owl-carousel-5").length > 0){
		// Owl Carousel
		$(".owl-carousel-5").owlCarousel({
			items : 5,
			lazyLoad : true,
			pagination : false,
			autoPlay: 10000,
			stopOnHover: true
		}); 
	}
	// Sortable list
	
	
   	var _scroll = true, _timer = false, _floatbox = $("#contact_form"), _floatbox_opener = $(".contact-opener") ;
	_floatbox.css("right", "-500px"); //initial contact form position
	
	//Contact form Opener button
	_floatbox_opener.click(function(){
		if (_floatbox.hasClass('visiable')){
            _floatbox.animate({"right":"-500px"}, {duration: 300}).removeClass('visiable');
        }else{
           _floatbox.animate({"right":"0px"},  {duration: 300}).addClass('visiable');
        }
	});
	
	//Effect on Scroll
	$(window).load(function(){
		if(_scroll){
			_floatbox.animate({"bottom": "0px"},{duration: 300});
			_scroll = false;
		}
		if(_timer !== false){ clearTimeout(_timer); }           
			_timer = setTimeout(function(){_scroll = false; 
			_floatbox.animate({"bottom": "0px"},{easing: "linear"}, {duration: 500});}, 400); 
	});
	
	
	
    //testimonials
	
	 $('#testimonials-carousel').carousel({
      interval: 8000 
    });
	
   
});