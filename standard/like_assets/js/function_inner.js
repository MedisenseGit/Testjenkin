$(function() {
$(".like").click(function() {
var cat_id = $(this).attr("id");
var dataString = 'cat_id='+cat_id;  
$('a#'+cat_id).removeClass('like');
$('a#'+cat_id).html(); 
$.ajax({
	type: "POST",
	url: "../../../ajax_like.php",
	data: dataString,
	cache: false,
	success: function(data){
    if (data == 0) {
	alert('you have liked this quote before');
	} else {
	$('a#'+cat_id).addClass('liked');
    $('a#'+cat_id).html(data);
	}
	}  
});
return false;
});

});

