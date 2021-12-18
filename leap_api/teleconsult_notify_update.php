<?php ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


//Update Teleconsult Requests
if(API_KEY == $_POST['API_KEY']) {

	$doc_id = $_POST['doctor_id'];
	$patient_id = $_POST['patient_id'];
	$appoint_transaction_id = $_POST['transaction_id'];
	$status_id = $_POST['status_id'];					// 1 - Request Sent from patient, 2 - ACCEPT, 3-DECLINE
	$unique_transID = $_POST['unique_transID'];			// Unique transID 	
	
	$check_avilability = $objQuery->mysqlSelect("*","appointment_accept_reject","unique_trans_id='".$unique_transID."' AND consult_status='2'","","","","");	
	if(empty($check_avilability) && ($status_id != '3')) {
		$arrFields[] = 'consult_status';
		$arrValues[] = $status_id;
		$arrFields[] = 'accepted_by';
		$arrValues[] = '1';
		$getUser=$objQuery->mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");

		$success = array('status' => "true","update_tokenID" => "success");     
		echo json_encode($success);
	}
	else {
		
		$arrFields[] = 'consult_status';
		$arrValues[] = $status_id;
		$getUser=$objQuery->mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");

		$success = array('status' => "false","update_tokenID" => "Already accepted by other doctor !!!");     
		echo json_encode($success);
	}

}	
?>