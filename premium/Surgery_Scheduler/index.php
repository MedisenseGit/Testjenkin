<?php 
ob_start();
error_reporting(0); 
session_start();
$admin_id = $_SESSION['user_id'];
include_once('functions.php'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Surgery Schedulers</title>
<link type="text/css" rel="stylesheet" href="style.css"/>
<script src="jquery.min.js"></script>
</head>
<body>

<div id="calendar_div">
	<?php echo get_calender_full(); ?>
</div>
</body>
</html>
