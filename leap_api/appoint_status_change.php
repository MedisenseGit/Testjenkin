<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
$curdate=date('Y-m-d');

//Appointment Status Change
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 

	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];
	$visitStatus = $_POST['status'];
	$transaction_id = $_POST['transaction_id'];
	
	if($logintype == 1)			// Premium Login
	{
		/*if($status == 'Confirmed'){
			$visitStatus = '1';
		}
		else if($status == 'Consulted') {
			$visitStatus = '2';
		}
		else if($status == 'Cancelled') {
			$visitStatus = '3';
		}
		else if($status == 'Pending'){
			$visitStatus = '4';
		}
		else if($status == 'Missed') {
			$visitStatus = '5';
		}
		else if($status == 'At reception') {
			$visitStatus = '6';
		} */
		 
		$arrFields = array();
		$arrValues = array();
		
		$arrFields[]= 'pay_status';
		$arrValues[]= $visitStatus;
		//Update Patient Status
		$patientRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$transaction_id."'");
	
		$arrFieldsToken[]= 'status';
		$arrValuesToken[]= $visitStatus;
		//Update Patient Status
		$patientRef=$objQuery->mysqlUpdate('appointment_token_system',$arrFieldsToken,$arrValuesToken,"appoint_trans_id='".$transaction_id."'");

		$appointmentToday = $objQuery->mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."' and app_date='".$curdate."' and status!='Cancelled'","token_no ASC","","","");	

		$success = array('result' => "success","appointment_today_details" => $appointmentToday);
		echo json_encode($success);
	}
	else {
		$success = array('result' => "failure","appointment_today_details" => $appointmentToday);
		echo json_encode($success);
	}
}


?>