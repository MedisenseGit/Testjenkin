<?php


$hostname = "localhost";
$username = "root";
$password = "";
$db = "medisense_crm";
$contact_num = 1234;

$conn = mysqli_connect($hostname,$username,$password,$db);

$invetory_report = array();

$result = mysqli_query($conn,"SELECT * FROM referal where contact_num='$contact_num' ");
while($row = mysqli_fetch_array($result))
{

array_push($invetory_report ,array("contact_num"=>$row['contact_num'],"doc_password"=>$row['doc_password']));

}


echo json_encode(array("medical_report"=>$invetory_report));
mysqli_close($conn);



?>