<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

$ToDay=date('Y-m-d');

 if(API_KEY == $_POST['API_KEY']) {	
	
		$admin_id = $_POST['userid'];
		$login_type = $_POST['login_type'];
		$today_date = $_POST['appt_today'];
		$date_from = $_POST['appt_date_from'];
		$date_to = $_POST['appt_date_to'];
		$filter_type = $_POST['appt_filter_type']; //appt_filter_type is 1 for Today & 2 Between date & 3 for selected date(one date)
		if($login_type == 1) {    //$login_type is 1 for Hospital Doctor, 2 for Care Partners, 3 for marketing professionals
			
			if($filter_type == 1){
			$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date ='".$ToDay."'","patient_id desc","","","");
			} else if($filter_type == 2){
			$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date BETWEEN '".$date_from."' and '".$date_to."'","patient_id desc","","","");
			} else if($filter_type == 3){
			$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date ='".$today_date."'","patient_id desc","","","");
			} 
			$success = array('status' => "true","mypatient_details" => $patientResult);
			echo json_encode($success);	
		
		}
		else if($login_type == 3) { //For marketing professionals
			// $patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and system_date ='".$ToDay."'","patient_id desc","","","");
			$success = array('status' => "true","mypatient_details" => $patientResult);
			echo json_encode($success);	
		}
		else if($login_type == 2) {
			$account = "As a Partner";
			if($filter_type == 1){
			$patientResult = $objQuery->mysqlSelect("*","my_patient","partner_id='".$admin_id."' and system_date ='".$ToDay."'","patient_id desc","","","");
			} else if($filter_type == 2){
			$patientResult = $objQuery->mysqlSelect("*","my_patient","partner_id='".$admin_id."' and system_date BETWEEN '".$date_from."' and '".$date_to."'","patient_id desc","","","");
			} else if($filter_type == 3){
			$patientResult = $objQuery->mysqlSelect("*","my_patient","partner_id='".$admin_id."' and system_date ='".$today_date."'","patient_id desc","","","");
			} 
			$success = array('status' => "true","mypatient_details" => $patientResult);
			echo json_encode($success);		
		}
		
	
 }
?>
