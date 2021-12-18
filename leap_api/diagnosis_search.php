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
	$searchTerm = $_POST['searchTerm'];
	
	if($login_type == 1) {						// Premium LoginType
		
		$getDiagnosis= $objQuery->mysqlSelect("*","icd_code","icd_code LIKE '%".$searchTerm."%'","icd_code asc","","","0,50");

		$success = array('status' => "true","diagnosis_details" => $getDiagnosis);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>