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
//$objQuery = new CLSQueryMaker();

$searchTerm = $_GET['term'];

$checkICD= mysqlSelect("icd_id","icd_code","doc_id='".$admin_id."' and doc_type='1'","","","","");
if(COUNT($checkICD)>0)
{
	$getDiagnosis= mysqlSelect("icd_id,icd_code","icd_code","(icd_code LIKE '%".$searchTerm."%') and ((doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1'))","","","","0,100");
}
else
{
	$getDiagnosis= mysqlSelect("icd_id,icd_code","icd_code","icd_code LIKE '%".$searchTerm."%'","","","","0,100");
}

while (list($key, $value) = each($getDiagnosis)) 
{
	$data[] = $value['icd_id']."-".$value['icd_code'];
}
//return json data
echo json_encode($data);
?>