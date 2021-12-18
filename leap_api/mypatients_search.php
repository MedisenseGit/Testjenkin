<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//PATIENT SEARCH
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['search_string']) && isset($_POST['userid']) && isset($_POST['login_type']) ) {

	$search_string = $_POST['search_string'];
	$user_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	
	
	 if($login_type == 1)		// Type-1 Hospital Doctors
	{
			$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$user_id."' and (patient_id ='".$search_string."'or patient_mob ='".$search_string."'or patient_name LIKE '%".$search_string."%' or patient_email ='".$search_string."' or patient_loc LIKE '%".$search_string."%' or contact_person LIKE '%".$search_string."%' or patient_addrs LIKE '%".$search_string."%')","patient_id desc","","","");
			$success = array('status' => "true","mypatient_details" => $patientResult);
			echo json_encode($success);	
	}
	else if($login_type == 2)		// Type-2 Referring Partners
	{
			$patientResult = $objQuery->mysqlSelect("*","my_patient","partner_id='".$user_id."' and (patient_id ='".$search_string."'or patient_mob ='".$search_string."'or patient_name LIKE '%".$search_string."%' or patient_email ='".$search_string."' or patient_loc LIKE '%".$search_string."%' or contact_person LIKE '%".$search_string."%' or patient_addrs LIKE '%".$search_string."%')","patient_id desc","","","");
			$success = array('status' => "true","mypatient_details" => $patientResult);
			echo json_encode($success);	
	} 
	else if($login_type == 3)		// Type-3 Marketing Person
	{
		$success = array('status' => "true","mypatient_details" => $patientResult);
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","mypatient_details" => $patientResult);
		echo json_encode($success);
	}
		
}


?>