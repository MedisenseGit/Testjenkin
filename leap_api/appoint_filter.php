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
		$hospital_id = $_POST['hosp_id'];

		$filter_type = $_POST['appt_filter_type']; //appt_filter_type is 1 for Upcoming, 2 - ALL, 3 - Range of dates	
		$fromDate=date('Y-m-d',strtotime($_POST['appt_date_from']));
		$toDate=date('Y-m-d',strtotime($_POST['appt_date_to']));
		
		if($login_type == 1) {    //Premium Login
			
			if($filter_type == 1){
			//	$appointmentResult = $objQuery->mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."' and app_date>='".$ToDay."' and status!='Cancelled'","app_date ASC","","","");				
				$appointmentResult = $objQuery->mysqlSelect("a.token_id as token_id, a.token_no as token_no, a.patient_id as patient_id, a.appoint_trans_id as appoint_trans_id, a.patient_name as patient_name, a.doc_id as doc_id, a.doc_type as doc_type, a.hosp_id as hosp_id, a.status as status, a.app_date as app_date, a.app_time as app_time, a.created_date as created_date, b.patient_email as patient_email, b.patient_mob as patient_mob, b.doc_video_link as doc_video_link","appointment_token_system as a inner join doc_my_patient as b on b.patient_id = a.patient_id","a.doc_id='".$admin_id."' and a.doc_type='1' and a.hosp_id='".$hospital_id."' and a.app_date>='".$ToDay."' and a.status!='Cancelled'","a.app_date DESC","","","");				
			
			} else if($filter_type == 2){
				$appointmentResult = $objQuery->mysqlSelect("a.token_id as token_id, a.token_no as token_no, a.patient_id as patient_id, a.appoint_trans_id as appoint_trans_id, a.patient_name as patient_name, a.doc_id as doc_id, a.doc_type as doc_type, a.hosp_id as hosp_id, a.status as status, a.app_date as app_date, a.app_time as app_time, a.created_date as created_date, b.patient_email as patient_email, b.patient_mob as patient_mob, b.doc_video_link as doc_video_link","appointment_token_system as a inner join doc_my_patient as b on b.patient_id = a.patient_id","a.doc_id='".$admin_id."' and a.doc_type='1' and a.hosp_id='".$hospital_id."'","a.app_date DESC","","","");
			} else if($filter_type == 3){
				$appointmentResult = $objQuery->mysqlSelect("b.token_id as token_id, b.token_no as token_no, b.patient_id as patient_id, b.appoint_trans_id as appoint_trans_id, b.patient_name as patient_name, b.doc_id as doc_id, b.doc_type as doc_type, b.hosp_id as hosp_id, b.status as status, b.app_date as app_date, b.app_time as app_time, b.created_date as created_date, c.patient_email as patient_email, c.patient_mob as patient_mob, c.doc_video_link as doc_video_link","appointment_transaction_detail as a inner join appointment_token_system as b on b.appoint_trans_id=a.appoint_trans_id inner join doc_my_patient as c on c.patient_id = b.patient_id","a.pref_doc='".$admin_id."' and a.Visiting_date BETWEEN '".$fromDate."' and '".$toDate."'","a.Visiting_date DESC","","","");
			} 
			$success = array('status' => "true","appointment_details" => $appointmentResult);
			echo json_encode($success);
		
		}
		else {
			$success = array('status' => "false");
			echo json_encode($success);
		}
		
	
 }
?>
