<?php
ob_start();
 error_reporting(0);
 session_start();
 
include('connect.php');
include('functions.php');

$userid=$_SESSION['user_id'];
if(isset($_POST) && $_POST['type']=="going") {
$event_id = abs(intval($_POST['event_id']));
$user_id = abs(intval($userid));
//$ip = get_real_ip();
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE event_id='$event_id' and going='$user_id' LIMIT 1");
$check = mysql_num_rows($query);
if ($check == 0) {
$datetime = time();
mysql_query("DELETE FROM event_visitors_tab WHERE (event_id='$event_id') and (maybe='$user_id' or cannotgo='$user_id')");
$add = mysql_query("INSERT INTO event_visitors_tab (event_id,going) VALUES ('$event_id','$user_id')");
if ($add) {
$check = mysql_query("SELECT event_id FROM event_visitors_tab WHERE event_id='$event_id' and going!=0");
$number = mysql_num_rows($check);
sleep(1);
echo '<a href="javascript:void();" class="gone" data-toggle="tooltip" data-placement="bottom" title="Going" style="font-size:20px; color:green; pointer-events: none;"> <i class="fa fa-check"></i> <small>'.$number.'</small></a>';
}
} else {
echo 0;
}
}
else if(isset($_POST) && $_POST['type']=="maybe") {
$event_id = abs(intval($_POST['event_id']));
$user_id = abs(intval($userid));
//$ip = get_real_ip();
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE event_id='$event_id' and maybe='$user_id' LIMIT 1");
$check = mysql_num_rows($query);
if ($check == 0) {
$datetime = time();
mysql_query("DELETE FROM event_visitors_tab WHERE (event_id='$event_id') and (going='$user_id' or cannotgo='$user_id')");
$add = mysql_query("INSERT INTO event_visitors_tab (event_id,maybe) VALUES ('$event_id','$user_id')");
if ($add) {
$check = mysql_query("SELECT event_id FROM event_visitors_tab WHERE event_id='$event_id' and maybe!=0");
$number = mysql_num_rows($check);
sleep(1);
echo '<a href="javascript:void();" class="maybe_done" data-toggle="tooltip" data-placement="bottom" title="May be" style="font-size:20px; color:green; pointer-events: none;"> <i class="fa fa-question"></i> <small>'.$number.'</small></a>';

}
} else {
echo 0;
}
}
else if(isset($_POST) && $_POST['type']=="cannot") {
$event_id = abs(intval($_POST['event_id']));
$user_id = abs(intval($userid));
//$ip = get_real_ip();
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE event_id='$event_id' and cannotgo='$user_id' LIMIT 1");
$check = mysql_num_rows($query);
if ($check == 0) {
$datetime = time();
mysql_query("DELETE FROM event_visitors_tab WHERE (event_id='$event_id') and (going='$user_id' or maybe='$user_id')");
$add = mysql_query("INSERT INTO event_visitors_tab (event_id,cannotgo) VALUES ('$event_id','$user_id')");
if ($add) {
$check = mysql_query("SELECT event_id FROM event_visitors_tab WHERE event_id='$event_id' and cannotgo!=0");
$number = mysql_num_rows($check);
sleep(1);
echo "<a href='javascript:void();' class='cannot_done' data-toggle='tooltip' data-placement='bottom' title='Can't go' style='font-size:20px; color:green; pointer-events: none;'> <i class='fa fa-times'></i> <small>".$number."</small></a>";

}
} else {
echo 0;
}
}
//Blog Like ajax functionality start here
else if(isset($_POST) && $_POST['type']=="like") {
$event_list_id = abs(intval($_POST['event_list_id']));
$user_id = abs(intval($userid));
//$ip = get_real_ip();
echo $event_list_id;
//Check Post category and category id
$checkcat = mysql_query("SELECT * FROM blogs_offers_events_listing WHERE listing_id='$event_list_id' LIMIT 1");
$getcheckcat = mysql_fetch_array($checkcat);

//$query = mysql_query("SELECT * FROM home_post_like WHERE category_id='$getcheckcat[0]['listing_type_id']' and category_type='$getcheckcat[0]['listing_type']' LIMIT 1");
//$check = mysql_num_rows($query);
echo $getcheckcat[0]['listing_type'];
$datetime = time();
$add = mysql_query("INSERT INTO home_post_like (category_id,category_type,likes,user_type) VALUES ('$getcheckcat[0]['listing_type_id']','$getcheckcat[0]['listing_type']','$user_id','1')");
if ($add) {
$check = mysql_query("SELECT category_id FROM home_post_like WHERE category_id='$getcheckcat[0]['listing_type_id']' and category_type='$getcheckcat[0]['listing_type']'");
$number = mysql_num_rows($check);
sleep(1);
echo "<a href='javascript:void();' class='liked' data-toggle='tooltip' data-placement='bottom' title='Like'><div class='btn-group'><button class='btn btn-danger btn-circle btn-outline' type='button'><i class='fa fa-heart'></i></button></a><code>".$number."</code></div>";
}

}
//Ends here
 else {
echo 0;
}