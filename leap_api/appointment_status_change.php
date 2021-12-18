<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();

// Appointment Status Change
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) &&  isset($_POST['transaction_id']) && isset($_POST['status_change']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$login_type = $_POST['login_type'];  // 1 - Hospital Doctor, 2 - Partner, 3 - MArketing Person
	$status_change_val = $_POST['status_change'];
	$transaction_id = $_POST['transaction_id'];
	
	$arrFields = array();
	$arrValues = array();
	
	if($login_type == 1)
	{
		$arrFields[]= 'pay_status';
		$arrValues[]= $status_change_val;
		
		$appointmentRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$transaction_id."'");
		
		//GET Patient Details
		$getPatient = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$transaction_id."'","","","","");
		$getDoc=$objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPatient[0]['pref_doc']."'","","","","");
		//Get Timing
		$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$getPatient[0]['Visiting_time']."'","","","","");
	
		if($appointmentRef == true) {
					if($status_change_val=="Confirmed"){
					//Message Notification to Patient only when appointment is confirmed
					$mobile = $getPatient[0]['Mobile_no'];
					$msg = "Appointment Confirmed, Patient Name ".$getPatient[0]['patient_name'] . " | ".$getDoc[0]['spec_name']." | ".$getPatient[0]['Visiting_date']." | ".$getTiming[0]['Timing']." | ".$getDoc[0]['ref_name']." Thanks";
					send_msg($mobile,$msg);
					}
			$success = array('status' => "true","status_change" => "Status updated successfully");   
			echo json_encode($success);	
		}
		else {
			$success = array('status' => "false","status_change" => "Failed to update status");   
			echo json_encode($success);	
		}
			
		
	}
	else if($login_type == 2) 
	{
		$arrFields[]= 'pay_status';
		$arrValues[]= $status_change_val;
		
		$appointmentRef=$objQuery->mysqlUpdate('partner_appointment_transaction',$arrFields,$arrValues,"appoint_trans_id='".$transaction_id."'");
		//GET Patient Details
		$getPatient = $objQuery->mysqlSelect("*","partner_appointment_transaction","appoint_trans_id='".$transaction_id."'","","","","");
		$getDoc=$objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPatient[0]['pref_doc']."'","","","","");
		//Get Timing
		$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$getPatient[0]['Visiting_time']."'","","","","");
	
		if($appointmentRef == true) {
			
					if($status_change_val=="Confirmed"){
					//Message Notification to Patient only when appointment is confirmed
					$mobile = $getPatient[0]['Mobile_no'];
					$msg = "Appointment Confirmed, Patient Name ".$getPatient[0]['patient_name'] . " | ".$getDoc[0]['spec_name']." | ".$getPatient[0]['Visiting_date']." | ".$getTiming[0]['Timing']." | ".$getDoc[0]['ref_name']." Thanks";
					send_msg($mobile,$msg);
					}
			$success = array('status' => "true","status_change" => "Status updated successfully");   
			echo json_encode($success);	
		}
		else {
			$success = array('status' => "false","status_change" => "Failed to update status");   
			echo json_encode($success);	
		}
	}
	else if($login_type == 3) 
	{
		$arrFields[]= 'pay_status';
		$arrValues[]= $status_change_val;
		
		$appointmentRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$transaction_id."'");
		//GET Patient Details
		$getPatient = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$transaction_id."'","","","","");
		$getDoc=$objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPatient[0]['pref_doc']."'","","","","");
		//Get Timing
		$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$getPatient[0]['Visiting_time']."'","","","","");
	
		if($appointmentRef == true) {
					if($status_change_val=="Confirmed"){
					//Message Notification to Patient only when appointment is confirmed
					$mobile = $getPatient[0]['Mobile_no'];
					$msg = "Appointment Confirmed, Patient Name ".$getPatient[0]['patient_name'] . " | ".$getDoc[0]['spec_name']." | ".$getPatient[0]['Visiting_date']." | ".$getTiming[0]['Timing']." | ".$getDoc[0]['ref_name']." Thanks";
					send_msg($mobile,$msg);
					}
			$success = array('status' => "true","status_change" => "Status updated successfully");   
			echo json_encode($success);	
		}
		else {
			$success = array('status' => "false","status_change" => "Failed to update status");   
			echo json_encode($success);	
		}
	}
	
}


?>