<?php
$host="localhost";
$user="root";
$password="";
$db_name = "medisense_crm";
$con=mysql_connect($host,$user,$password,$db_name);
if($con) {
   // echo 'Connected to database';
} else {
    echo 'Failed to Connect database !!!';
}
?>