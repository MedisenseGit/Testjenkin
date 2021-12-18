<?php

// Fetching Values From URL
$post_comment = $_POST['comment'];
$post_type=$_POST['posttype'];
$comment_id=$_POST['commentid'];
$user_id=$_POST['userid'];
$user_type=$_POST['usertype'];


$connection = mysql_connect("localhost", "root", ""); // Establishing Connection with Server..
$db = mysql_select_db("fdc_crm", $connection); // Selecting Database
if (isset($_POST['comment'])) {
$query = mysql_query("insert into home_post_comments(login_id, login_User_Type, topic_id, topic_type, comments) values ('1', '1', '1', '2', '3')"); //Insert Query
echo "Form Submitted succesfully";
}
mysql_close($connection); // Connection Closed
?>