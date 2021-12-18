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

$select= $objQuery->mysqlSelect("*","patient_diagnosis_tests","test_name_site_name LIKE '%".$searchTerm."%'","test_name_site_name asc","","","0,20");


while (list($key, $value) = each($select)) 
{
 $data[] = $value['id']."-".$value['test_name_site_name'];
}
//return json data
echo json_encode($data);
?>