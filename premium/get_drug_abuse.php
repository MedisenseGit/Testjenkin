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

$searchTerm		= $_GET['term'];
$checkComplaint = mysqlSelect("drug_abuse_id","drug_abuse_auto","doc_id='".$admin_id."' and doc_type='1'","","","","");
if(COUNT($checkComplaint)>0)
{
	$select= mysqlSelect("*","drug_abuse_auto","(drug_abuse LIKE '%".$searchTerm."%') and ((doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1'))","drug_abuse asc","","","0,20");
}
else
{
	$select= mysqlSelect("*","drug_abuse_auto","drug_abuse LIKE '%".$searchTerm."%' and doc_id='0' and doc_type='0'","drug_abuse asc","","","0,20");
}

while (list($key, $value) = each($select)) 
{
	$data[] = $value['drug_abuse_id']."-".$value['drug_abuse'];
}
//return json data
echo json_encode($data);
?>