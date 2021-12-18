<?php
ob_start();
error_reporting(0); 
session_start();
$admin_id = $_SESSION['user_id'];
if(empty($admin_id))
{
	header("Location:LOGIN");
}
require_once("../classes/querymaker.class.php");
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
if($_POST['act'] == 'add-status') 
{
	$postCom 	= htmlentities($postCom);
    $postType	= htmlentities($postType);
    $postId 	= htmlentities($postId);
	$userId 	= $userId;
    $userType 	= $userType;
	$acc_id 	= $_POST['acc_id'];
	$doc_id 	= $_POST['acc_doc_id'];
	$patient_id = $_POST['acc_pat_id'];
	$status_id 	= $_POST['status_id'];					// 1 - Request Sent from patient, 2 - ACCEPT, 3-DECLINE
	$unique_transID = $_POST['acc_unique_trans_id'];
	$appoint_transaction_id = $_POST['acc_appoint_trans_id'];
   	
	$check_avilability = mysqlSelect("*","appointment_accept_reject","unique_trans_id='".$unique_transID."' AND consult_status='2'","","","","");	
	if(empty($check_avilability) && ($status_id != '3')) 
	{
		$arrFields[] = 'consult_status';
		$arrValues[] = $status_id;
		$arrFields[] = 'accepted_by';
		$arrValues[] = '1';
		$getUser=mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");

		// Add to Appointment Tracking
		$arrFieldsTrack = array();
		$arrValuesTrack = array();
			
		$arrFieldsTrack[] = 	'doc_id';
		$arrValuesTrack[] = 	$doc_id;
		$arrFieldsTrack[] = 	'patient_id';
		$arrValuesTrack[] = 	$patient_id;
		$arrFieldsTrack[] = 	'appoint_trans_id';
		$arrValuesTrack[] = 	$appoint_transaction_id;
		$arrFieldsTrack[] = 	'message';
		$arrValuesTrack[] = 	'Accepted the request';
		$arrFieldsTrack[] = 	'status';
		$arrValuesTrack[] = 	'3';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - 									Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
		$arrFieldsTrack[] = 	'created_date';
		$arrValuesTrack[] = 	$curDate;
		$insertTrack 	  = 	mysqlInsert('appointment_tracking',$arrFieldsTrack,$arrValuesTrack);
		$success 		  = 	array('status' => "true", 'type' => "accept", "update_tokenID" => "success");     
		echo json_encode($success);
	}
	else if(empty($check_avilability) && ($status_id == '3')) 
	{
		$arrFields[] = 		'consult_status';
		$arrValues[] = 		$status_id;
		$arrFields[] = 		'accepted_by';
		$arrValues[] = 		'1';
		$getUser	 =	mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");
		// Add to Appointment Tracking
		$arrFieldsTrack = array();
		$arrValuesTrack = array();
			
		$arrFieldsTrack[] = 'doc_id';
		$arrValuesTrack[] = $doc_id;
		$arrFieldsTrack[] = 'patient_id';
		$arrValuesTrack[] = $patient_id;
		$arrFieldsTrack[] = 'appoint_trans_id';
		$arrValuesTrack[] = $appoint_transaction_id;
		$arrFieldsTrack[] = 'message';
		$arrValuesTrack[] = 'Rejected the request';
		$arrFieldsTrack[] = 'status';
		$arrValuesTrack[] = '4';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
		$arrFieldsTrack[] = 'created_date';
		$arrValuesTrack[] = $curDate;
		$insertTrack = mysqlInsert('appointment_tracking',$arrFieldsTrack,$arrValuesTrack);
		$success = array('status' => "true", 'type' => "decline", "update_tokenID" => "success");     
		echo json_encode($success);
	}
	else 
	{
		$arrFields[] = 	'consult_status';
		$arrValues[] = 	$status_id;
		$getUser	 =	mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");
		$success     = 	array('status' => "false", 'type' => "updated", "update_tokenID" => "Already accepted by other doctor !!!");     
		echo json_encode($success);
	}
	
}
if($_POST['act'] == 'status-check')
{
	
	$GetVCRequests = mysqlSelect("a.id as acc_id, a.doc_id as doc_id, a.patient_id as patient_id, a.unique_trans_id as unique_trans_id, a.appoint_trans_id as appoint_trans_id, c.patient_name as patient_name, b.address as patient_addrs, b.state as pat_state, b.country as pat_country, b.doc_agora_link as doc_video_link, b.pay_status as pay_status", "appointment_accept_reject as a inner join patients_transactions as b on b.transaction_id = a.appoint_trans_id inner join patients_appointment as c  on c.patient_id=b.patient_id", "a.doc_id='".$admin_id."' AND (a.consult_status=1 OR a.consult_status=2)", "a.id DESC", "", "", "0,15");

	$success = array('status' => "false", "GetVCRequests" => $GetVCRequests);     
	echo json_encode($success);
}
?>
	          
				