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
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) &&  isset($_POST['transaction_id']) && isset($_POST['reschedule_date']) && isset($_POST['selectTime']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$login_type = $_POST['login_type'];  // 1 - Hospital Doctor, 2 - Partner, 3 - MArketing Person
	$status_cahnge_val = $_POST['status_change'];
	$transaction_id = $_POST['transaction_id'];
	
	
	
	if($login_type == 1)
	{
		$visitDate = date('Y-m-d',strtotime($_POST['reschedule_date']));
		$slctTime = $_POST['selectTime'];
	
		$arrFields = array();
		$arrValues = array();
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$transaction_id."'");
		
		$getInfo1 = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$transaction_id."'" ,"","","","");	
		$getDoc = $objQuery->mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = $objQuery->mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
		
		//Message to Patient	
		$mobile=$getInfo1[0]['Mobile_no'];	
		$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thx";
		send_msg($mobile,$responsemsg);
		$response="reschedule";
		
		$success = array('status' => "true","appt_status_change" => "Appointment Rescheduled Successfully");   
		echo json_encode($success);	
		
	}
	else if($login_type == 2) 
	{
		$visitDate = date('Y-m-d',strtotime($_POST['reschedule_date']));
		$slctTime = $_POST['selectTime'];
	
		$arrFields = array();
		$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=$objQuery->mysqlUpdate('partner_appointment_transaction',$arrFields,$arrValues,"appoint_trans_id='".$_POST['Pat_Trans_Id']."'");
		
		$getInfo1 = $objQuery->mysqlSelect("*","partner_appointment_transaction","appoint_trans_id='".$_POST['Pat_Trans_Id']."'" ,"","","","");	
		$getDoc = $objQuery->mysqlSelect("*","our_partners","partner_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = $objQuery->mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
		//Message to Patient	
		$mobile=$getInfo1[0]['Mobile_no'];	
		$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['contact_person']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks";
		send_msg($mobile,$responsemsg);
		$response="reschedule";
		// header("Location:appointment_patient_history.php?pattransid=".$_POST['Pat_Trans_Id']."&response=".$response);			
		$success = array('status' => "true","appt_status_change" => "Appointment Rescheduled Successfully");   
		echo json_encode($success);
	}
	else if($login_type == 3) 
	{
		$visitDate = date('Y-m-d',strtotime($_POST['reschedule_date']));
		$slctTime = $_POST['selectTime'];
	
		$arrFields = array();
		$arrValues = array();
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$transaction_id."'");
		
		$getInfo1 = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$transaction_id."'" ,"","","","");	
		$getDoc = $objQuery->mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = $objQuery->mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
		
		//Message to Patient	
		$mobile=$getInfo1[0]['Mobile_no'];	
		$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thx";
		send_msg($mobile,$responsemsg);
		$response="reschedule";
		
		$success = array('status' => "true","appt_status_change" => "Appointment Rescheduled Successfully");   
		echo json_encode($success);	
	}
	
}


?>