<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Set Appointment Slots / Number of appointments per hour
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$slot_number = (int) $_POST['num_slots'];
	$hospital_id = $_POST['hosp_id'];
	
	
	if($login_type == 1) {  // Premium LOgin
	
			$arrFields_time[] = 'doc_id';
			$arrValues_time[] = $admin_id;
			$arrFields_time[] = 'doc_type';
			$arrValues_time[] = "1";	
			$arrFields_time[] = 'hosp_id';
			$arrValues_time[] = $hospital_id;
			$arrFields_time[] = 'num_patient_hour';
			$arrValues_time[] = $slot_number;	
	
		$checkHospTiming = $objQuery->mysqlSelect("slot_id", "doc_appointment_slots", "doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."'", "", "", "", "");
		if(COUNT($checkHospTiming)==0) {	
			$docslotcreate=$objQuery->mysqlInsert('doc_appointment_slots',$arrFields_time,$arrValues_time);	
		}
		else {
			$docslotupdate=$objQuery->mysqlUpdate('doc_appointment_slots',$arrFields_time,$arrValues_time,"doc_id = '".$admin_id."' and doc_type = '1' and hosp_id='".$hospital_id."'");
		}
		
		$result = array("result" => "success");
		echo json_encode($result);
		
	}
	else {
		$$result = array("result" => "failed");
		echo json_encode($result);
	} 
	
}


?>