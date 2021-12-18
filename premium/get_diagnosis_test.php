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
$checkExam	= mysqlSelect("id","patient_diagnosis_tests","doc_id='".$admin_id."' and doc_type='1'","","","","");
if(COUNT($checkExam)>0)
{
	$select	= mysqlSelect("id,test_name_site_name","patient_diagnosis_tests","(test_name_site_name LIKE '%".$searchTerm."%') and ((doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1'))","test_name_site_name asc","","","0,20");
}
else
{
	$select	= mysqlSelect("id,test_name_site_name","patient_diagnosis_tests","test_name_site_name LIKE '%".$searchTerm."%'","test_name_site_name asc","","","0,20");
}

while (list($key, $value) = each($select)) 
{
	$data[] = $value['id']."-".$value['test_name_site_name'];
}
//return json data
echo json_encode($data);
?>