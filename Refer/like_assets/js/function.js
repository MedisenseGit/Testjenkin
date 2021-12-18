$(function() {
//For going event Azax function	
$(".going").click(function() {
var event_id = $(this).attr("id");
var dataString = 'event_id='+event_id+'&type=going';  
$('a#'+event_id).removeClass('going');
$('a#'+event_id).html(); 
$.ajax({
	type: "POST",
	url: "ajax_like.php",
	data: dataString,
	cache: false,
	success: function(data){
    if (data == 0) {
	//alert('you have liked this quote before');
	} else {
	$('a#'+event_id).addClass('gone');
    $('a#'+event_id).html(data);
	}
	}  
});
return false;
});

//For Maybe event Azax function	
$(".maybe").click(function() {
var event_id = $(this).attr("id");
var dataString = 'event_id='+event_id+'&type=maybe';  
$('a#'+event_id).removeClass('maybe');
$('a#'+event_id).html(); 
$.ajax({
	type: "POST",
	url: "ajax_like.php",
	data: dataString,
	cache: false,
	success: function(data){
    if (data == 0) {
	//alert('you have liked this quote before');
	} else {
	$('a#'+event_id).addClass('maybe_done');
    $('a#'+event_id).html(data);
	}
	}  
});
return false;
});

//For Maybe event Azax function	
$(".cannot").click(function() {
var event_id = $(this).attr("id");
var dataString = 'event_id='+event_id+'&type=cannot';  
$('a#'+event_id).removeClass('cannot');
$('a#'+event_id).html(); 
$.ajax({
	type: "POST",
	url: "ajax_like.php",
	data: dataString,
	cache: false,
	success: function(data){
    if (data == 0) {
	//alert('you have liked this quote before');
	} else {
	$('a#'+event_id).addClass('cannot_done');
    $('a#'+event_id).html(data);
	}
	}  
});
return false;
});

//For post like event Azax function	
$(".like").click(function() {
var post_id = $(this).attr("id");
var dataString = 'post_id='+post_id+'&type=like';  
$('a#'+post_id).removeClass('like');
$('a#'+post_id).html(); 
$.ajax({
	type: "POST",
	url: "ajax_like.php",
	data: dataString,
	cache: false,
	success: function(data){
    if (data == 0) {
	//alert('you have liked this quote before');
	} else {
	$('a#'+post_id).addClass('liked');
    $('a#'+post_id).html(data);
	}
	}  
});
return false;
});
});

