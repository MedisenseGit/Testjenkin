<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//$ccmail="medical@medisense.me";
$ccmail="salmabanu.h@gmail.com";

//Appointment Reschedule
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	   
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];
	$chkInDate = $_POST['check_date'];
	$chkInTime = $_POST['check_time'];
	$transaction_id = $_POST['transaction_id'];
	
	if($logintype == 1)			// Premium Login
	{
		$visitDate = date('Y-m-d',strtotime($chkInDate));
		
		$arrFields = array();
		$arrValues = array();
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $chkInTime;
		
		$patientRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$transaction_id."'");
		
		$getTime=$objQuery->mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
		
		$arrFields_token = array();
		$arrValues_token = array();
		$arrFields_token[] = 'app_date';
		$arrValues_token[] = $visitDate;
		$arrFields_token[] = 'app_time';
		$arrValues_token[] = $getTime[0]['Timing'];
		
		$tokenRef=$objQuery->mysqlUpdate('appointment_token_system',$arrFields_token,$arrValues_token,"appoint_trans_id='".$transaction_id."'");
		
		$getInfo1 = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$transaction_id."'" ,"","","","");	
		$getDoc = $objQuery->mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = $objQuery->mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
		
		//Message to Patient	
		$mobile=$getInfo1[0]['Mobile_no'];	
		$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."\n- Thanks, \n".$getDoc[0]['ref_name']."";
		send_msg($mobile,$responsemsg);
		$response="reschedule";
				
		$success = array('result' => "success","appt_reschedule_status" => "Appointment Rescheduled Successfully");
		echo json_encode($success);
	}
	else {
		$success = array('result' => "failure","appt_reschedule_status" => "Failed to reschedule the appointment !!!");
		echo json_encode($success);
	}
}


?>