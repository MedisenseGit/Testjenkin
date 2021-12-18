<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
$curdate=date('Y-m-d');
$ToDay=date('Y-m-d');

//EMR Patients List
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
 

	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$load_all = $_POST['view_more'];   // 0 - initial load, 1- load all data
	$filter_type = $_POST['filter_type'];		// 0 - initial load, 1 - upcoming filer, 2 - All filter, 3 - Range of dates filter
	$fromDate=date('Y-m-d',strtotime($_POST['appt_date_from']));
	$toDate=date('Y-m-d',strtotime($_POST['appt_date_to']));
	
	if($logintype == 1)			// Premium Login
	{
		if($load_all == 0) {
			
			if($filter_type == 0) {
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date ='".$curdate."'","patient_id desc","","","0,30");		
			}
			else if($filter_type == 1) {	// 1 - upcoming filer
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date >'".$ToDay."'","patient_id desc","","","0,30");		
			}
			else if($filter_type == 2) {	// 2 - All filter
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."'","patient_id desc","","","0,30");		
			}
			else if($filter_type == 3) {	// 3 - Range of dates filter
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date BETWEEN '".$fromDate."' and '".$toDate."'","patient_id desc","","","0,30");		
			}
		}
		else {
			if($filter_type == 0) {
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date ='".$curdate."'","patient_id desc","","","");		
			}
			else if($filter_type == 1) {	// 1 - upcoming filer
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date >'".$ToDay."'","patient_id desc","","","");		
			}
			else if($filter_type == 2) {	// 2 - All filter
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."'","patient_id desc","","","");		
			}
			else if($filter_type == 3) {	// 3 - Range of dates filter
				$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date BETWEEN '".$fromDate."' and '".$toDate."'","patient_id desc","","","");		
			}
		}
		
		$success = array('status' => "true","mypatient_details" => $patientResult);
		echo json_encode($success);	
	
	}
	else if($logintype == 2)	// Standard Login
	{
	
	$patientResult = $objQuery->mysqlSelect("*","my_patient","partner_id='".$admin_id."'","patient_id desc","","","");	
	
	$success = array('status' => "true","mypatient_details" => $patientResult);
	echo json_encode($success);		
	}
	else {
		$success = array('status' => "false","mypatient_details" => $patientResult);
		echo json_encode($success);
	}
}


?>