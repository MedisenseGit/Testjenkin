<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Appointment Patient Details
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	   
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = $_POST['Pat_Id'];
	
	if($logintype == 1)			// Premium Login
	{
		$get_PatientDetails = $objQuery->mysqlSelect("patient_id,patient_name,patient_age,patient_email,patient_mob,patient_gen,patient_loc,pat_state,pat_country,patient_addrs","doc_my_patient","patient_id='".$patient_id."'","","","","");	
		$success = array('status' => "true","patient_details" => $get_PatientDetails);
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false");
		echo json_encode($success);
	}
}


?>