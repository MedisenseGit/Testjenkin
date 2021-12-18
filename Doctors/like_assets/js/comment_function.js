function myFunction() {
var comment = document.getElementById("medical_cmnt_txt").value;

// Returns successful data submission message when the entered information is stored in database.
var dataString = 'comment1=' + comment;

// AJAX code to submit form.
$.ajax({
type: "POST",
url: "https://medisensehealth.com/ajax_comment.php",
data: dataString,
cache: false,
success: function(html) {
alert(html);

});
}
return false;
}