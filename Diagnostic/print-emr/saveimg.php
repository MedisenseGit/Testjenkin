<?php 
ob_start();
error_reporting(0); 
session_start();
if(empty($_SESSION['user_id'])){ header("Location:index.php"); }
$img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['img']));
$fn = "prescs/" . $_POST['pid'] . ".jpg";
file_put_contents($fn, $img);
print($fn);
?>
