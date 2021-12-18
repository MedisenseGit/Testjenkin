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
$event_id = abs(intval($_POST['post_id']));
$user_id = abs(intval($userid));
//$ip = get_real_ip();
$query = mysql_query("SELECT * FROM home_post_like WHERE category_id='$event_id' LIMIT 1");
$check = mysql_num_rows($query);
if ($check == 0) {
$datetime = time();
$add = mysql_query("INSERT INTO home_post_like (category_id,likes,user_type) VALUES ('$event_id','$user_id','1')");
if ($add) {
$check = mysql_query("SELECT category_id FROM home_post_like WHERE category_id='$event_id'");
$number = mysql_num_rows($check);
sleep(1);
echo "<a href='javascript:void();' class='liked' data-toggle='tooltip' data-placement='bottom' title='Like' style='font-size:20px; color:green; pointer-events: none;'> <i class='fa fa-thumbs-up'></i> <small>".$number."</small></a>";

}
} else {
echo 0;
}
}
//Ends here
 else {
echo 0;
}