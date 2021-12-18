<?php ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");



//Update Teleconsult Requests

$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) {
		$doc_id =  $doctor_id;
		$patient_id = $_POST['patient_id'];
		$appoint_transaction_id = $_POST['transaction_id'];
		$status_id = $_POST['status_id'];					// 1 - Request Sent from patient, 2 - ACCEPT, 3-DECLINE
		$unique_transID = $_POST['unique_transID'];			// Unique transID 	
		
		$check_avilability = mysqlSelect("*","appointment_accept_reject","unique_trans_id='".$unique_transID."' AND consult_status='2'","","","","");	
		if(empty($check_avilability) && ($status_id != '3')) {
			$arrFields[] = 'consult_status';
			$arrValues[] = $status_id;
			$arrFields[] = 'accepted_by';
			$arrValues[] = '1';
			$getUser=mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");

			$success = array('status' => "true", 'type' => "accept", "update_tokenID" => "success");     
			echo json_encode($success);
		}
		else if(empty($check_avilability) && ($status_id == '3')) {
			$arrFields[] = 'consult_status';
			$arrValues[] = $status_id;
			$arrFields[] = 'accepted_by';
			$arrValues[] = '1';
			$getUser=mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");

			$success = array('status' => "true", 'type' => "decline", "update_tokenID" => "success");     
			echo json_encode($success);
		}
		else {
			
			$arrFields[] = 'consult_status';
			$arrValues[] = $status_id;
			$getUser=mysqlUpdate('appointment_accept_reject',$arrFields,$arrValues,"doc_id='".$doc_id."' AND patient_id='".$patient_id."' AND appoint_trans_id='".$appoint_transaction_id."'");

			$success = array('status' => "false", 'type' => "updated", "update_tokenID" => "Already accepted by other doctor !!!");     
			echo json_encode($success);
		}
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}

?>