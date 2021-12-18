<?php
ob_start();
error_reporting(0); 
session_start();
$admin_id = $_SESSION['user_id'];
if(empty($admin_id))
{
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
$searchTerm 	= $_GET['term'];
$checkTreat		= mysqlSelect("dft_id","doctor_frequent_treatment","doc_id='".$admin_id."' and doc_type='1'","","","","");
if(COUNT($checkTreat)>0)
{
	$select= mysqlSelect("*","doctor_frequent_treatment","(treatment LIKE '%".$searchTerm."%') and ((doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1'))","treatment asc","","","0,20");
}
else
{
	$select= mysqlSelect("*","doctor_frequent_treatment","treatment LIKE '%".$searchTerm."%' and doc_id='0' and doc_type='0'","treatment asc","","","0,20");
}

while (list($key, $value) = each($select)) 
{
	$data[] = $value['treatment'];
}
//return json data
echo json_encode($data);
?>