<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");


$searchTerm = $_GET['term'];
$patient_id=$_SESSION['patient_id'];
$getpatient = mysqlSelect("*","doc_my_patient","patient_id='".$patient_id."'","","","","");
$checkComplaint= mysqlSelect("family_history_id","family_history_auto","doc_id='".$getpatient[0]['doc_id']."' and doc_type='1'","","","","");
if(COUNT($checkComplaint)>0){
$select= mysqlSelect("*","family_history_auto","(family_history LIKE '%".$searchTerm."%') and ((doc_id='0' and doc_type='0') or (doc_id='".$getpatient[0]['doc_id']."' and doc_type='1'))","family_history asc","","","0,20");
}
else
{
$select= mysqlSelect("*","family_history_auto","family_history LIKE '%".$searchTerm."%' and doc_id='0' and doc_type='0'","family_history asc","","","0,20");
}

while (list($key, $value) = each($select)) 
{
 $data[] = $value['family_history_id']."-".$value['family_history'];
}
//return json data
echo json_encode($data);
?>