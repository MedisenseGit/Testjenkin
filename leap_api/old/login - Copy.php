<?php

$hostname = "localhost";
$username = "root";
$password = "";
$db = "medisense_crm";

$mobile_num = "9535291621";
$password = md5("sach123");
$conn = mysql_connect($hostname,$username,$password,$db);

$invetory_report = array();

$result = mysql_query($conn,"SELECT * FROM referal where contact_num='$mobile_num' and doc_password='$password'");
while($row = mysqli_fetch_array($result))
{

array_push($invetory_report ,array("contact_num"=>$row['contact_num'],"doc_password"=>$row['doc_password']));

}


echo json_encode(array("medical_report"=>$invetory_report));
mysqli_close($conn);



?>