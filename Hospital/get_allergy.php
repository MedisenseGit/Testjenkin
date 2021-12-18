<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");



if(isset($_GET['term'])){
$searchTerm = $_GET['term'];
$select= mysqlSelect("DISTINCT(pharma_generic) as dist_pharma_generic,generic_id","pharma_products","pharma_generic LIKE '%".$searchTerm."%'","generic_id asc","","","0,20");

while (list($key, $value) = each($select)) 
{
 $data[] = $value['generic_id']."-".$value['dist_pharma_generic'];
}
//return json data
echo json_encode($data);
}

if(isset($_GET['generic']) || isset($_GET['allergyid']))
{
	$params = explode("-", $_GET['generic']);
	$generic_id = $params[0];
	$generic_name = $params[1];
	
	$getpatient = mysqlSelect("*","doc_my_patient","patient_id='".$_GET['patientid']."'","","","","");
	
	if(isset($_GET['generic']))
	{
		
		
		$arrFileds = array();
		$arrValues = array();
									
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		
		$arrFileds[]='generic_id';
		$arrValues[]=$generic_id;
		
		$arrFileds[]='generic_name';
		$arrValues[]=$generic_name;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$getpatient[0]['doc_id'];
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
			
		$arrFileds[]='status';
		$arrValues[]="0";
		if($generic_id!=0){
		$insert_allergy=mysqlInsert('doc_patient_drug_allergy_active',$arrFileds,$arrValues);
		}
	}	
	if(isset($_GET['allergyid'])){
		
		mysqlDelete('doc_patient_drug_allergy_active',"allergy_id='".$_GET['allergyid']."'");
	}
	
	if($generic_id!=0 || $_GET['allergyid']!=0) {
	$getAllergy= mysqlSelect("*","doc_patient_drug_allergy_active","patient_id='".$_GET['patientid']."' and doc_id ='".$getpatient[0]['doc_id']."' and doc_type='1' and status='0'","allergy_id desc","","","");

	while(list($key, $value) = each($getAllergy)){ 
		echo '<span class="tag label label-primary m-r">' . $value['generic_name'] . '<a data-role="remove" class="text-white del_allergy m-l" data-drug-allergy-id="'.$value['allergy_id'].'">x</a></span>';
	}
	}

	
}
?>