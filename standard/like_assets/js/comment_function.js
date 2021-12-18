function myFunction() {
var medicalcmnt = document.getElementById("medical_cmnt_txt").value;
var posttype = document.getElementById("posttype").value;
var commentid = document.getElementById("comment_id").value;
var userid = document.getElementById("user_id").value;
var usertype = document.getElementById("user_type").value;

// Returns successful data submission message when the entered information is stored in database.
var dataString = 'comment=' + medicalcmnt;

// AJAX code to submit form.
$.ajax({
type: "POST",
url: "ajax_comment.php",
data: dataString,
cache: false,
success: function(html) {
alert(html);

});
}
return false;
}