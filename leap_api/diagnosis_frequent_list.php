<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Examination Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {						// Premium LoginType
		
		$getFrequentDiagnosis = $objQuery->mysqlSelect("a.dfd_id as dfd_id, a.icd_id as icd_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on b.icd_id = a.icd_id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_count DESC","","","0,8");
			
		$success = array('status' => "true","frequent_diagnosis_details" => $getFrequentDiagnosis);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>