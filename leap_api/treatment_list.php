<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Treatment Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {						// Premium LoginType
		
		$getFrequentTreatment = $objQuery->mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","freq_count DESC","","","8");
		$selectTreatment = $objQuery->mysqlSelect("*","doctor_frequent_treatment","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0') ","treatment asc","","","");
		
		$success = array('status' => "true","frequent_treatment_details" => $getFrequentTreatment,"treatment_details" => $selectTreatment);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>