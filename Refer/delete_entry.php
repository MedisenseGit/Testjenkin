<?php
$host = "localhost";
$uname = "root";
$pass = "";
$database = "medisense_crm";

$connection=mysql_connect($host,$uname,$pass) or die("connection in not ready <br>");
$result=mysql_select_db($database) or die("database cannot be selected <br>");


$id=$_POST['id'];
$delete = "DELETE FROM prescription_template WHERE template_id=$id";
$result = mysql_query($delete) or die(mysql_error());
?>
