<?php

ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$searchTerm = $_GET['term'];

$getDiagnosis= $objQuery->mysqlSelect("*","icd_code","icd_code LIKE '%".$searchTerm."%'","icd_code asc","","","0,20");
while (list($key, $value) = each($getDiagnosis)) 
{
 $data[] = $value['icd_id']."-".$value['icd_code'];
}
//return json data
echo json_encode($data);
?>