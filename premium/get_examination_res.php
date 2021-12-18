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

$checkExam= mysqlSelect("examination_id","examination","doc_id='".$admin_id."' and doc_type='1'","","","","");
if(COUNT($checkExam)>0)
{
	$select= mysqlSelect("*","examination","(examination LIKE '%".$searchTerm."%') and ((doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1'))","examination asc","","","0,20");
}
else
{
	$select= mysqlSelect("*","examination","examination LIKE '%".$searchTerm."%' and doc_id='0' and doc_type='0'","examination asc","","","0,20");
}

while (list($key, $value) = each($select)) 
{
	$data[] = $value['examination_id']."-".$value['examination'];
}
//return json data
echo json_encode($data);
?>