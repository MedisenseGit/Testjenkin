//side menu
var height=$("#header").height();
$("#timeline").css({paddingTop: height+20});
$("#time_line").css({paddingTop: height+10});
$("#timeline-inter").css({paddingTop: height+20});
$("#menu-close").click(function(e) {
   e.stopPropagation();
   $("#sidebar-wrapper").toggleClass("active");
});
$("#menu-toggle").click(function(e) {
   e.stopPropagation();
   $("#sidebar-wrapper").toggleClass("active");
});
$(document).click(function(){
   if($("#sidebar-wrapper").hasClass('active')){
      $("#sidebar-wrapper").removeClass("active");
   }
});
$('.dropdown-toggle').dropdown();

//scrol to top
			$(document).ready(function(){ 
			
			$(window).scroll(function(){
				if ($(this).scrollTop() > 100) {
					$('.scrollup').fadeIn();
				} else {
					$('.scrollup').fadeOut();
				}
			}); 
			
			$('.scrollup').click(function(){
				$("html, body").animate({ scrollTop: 0 }, 600);
				return false;
			});
 
		});
	$(document).ready(function() {
	
	$(".").shorten();
	
	
 });
 //comment btn
$(document).ready(function(){


   $('.comment-btn').showHide({			 
		speed: 1000,  // speed you want the toggle to happen	
		easing: '',  // the animation effect you want. Remove this line if you dont want an effect and if you haven't included jQuery UI
		changeText: 0 // if you dont want the button text to change, set this to 0
		//showText: 'View',// the button text to show when a div is closed
		//hideText: 'Close' // the button text to show when a div is open
					 
	}); 


});


$(document).ready(function(){
 $("#tag").autocomplete("autocomplete.php", {
		selectFirst: true
	});
});



 
      $(function() {
          $('.chosen-select').chosen();
      });
   
		autosize(document.querySelectorAll('textarea'));
		 jQuery("[data-toggle='tooltip']").tooltip();

    $('#filterbtn').click(function(){
	
	$('#filters_panel').removeClass("hidden-stb hidden-xs hidden-sm");
	$('#filters_panel').addClass("show-filter");
var $clickedPos = $this.offset();

var $filterPanel = $("#filters_panel");
	$('#filters_panel').css({"left":$clickedPos.left + $this.outerWidth(),"top":$clickedPos.top - 32 // filterPanel.top + filterPanel.height/2
																															   }).fadeIn(500);
	
	  
	

});
	