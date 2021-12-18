<?php
$host="localhost";
$user="root";
$password="";
$con=mysql_connect($host,$user,$password);
if($con) {
    echo '<h4>Connected to MySQL</h4>';
} else {
    echo '<h4>MySQL Server is not connected</h4>';
}
?>