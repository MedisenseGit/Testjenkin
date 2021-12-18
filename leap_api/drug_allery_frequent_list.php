<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Prescription Frequent Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = $_POST['patient_id'];
	
	if($login_type == 1) {						// Premium LoginType
		
		$getFrequentDrugAllery = $objQuery->mysqlSelect("*","doc_patient_drug_allergy_active","doc_id='".$admin_id."' and doc_type='1' and patient_id='".$patient_id."'","allergy_id DESC","","","");
		
		$success = array('status' => "true","frequent_allergy_details" => $getFrequentDrugAllery);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>