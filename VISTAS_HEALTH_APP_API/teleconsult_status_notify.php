<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//Teleconsult Status Check
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_id = $user_id;
		$uniqueTransID = $_POST['uniqueTransID'];
		
		$result_accept = mysqlSelect("*","appointment_accept_reject","login_id ='".$user_id."' AND unique_trans_id ='".$uniqueTransID."' AND accepted_by!=0","id ASC","","","0,1");
		$accepted_doctor = $result_accept[0]['doc_id'];	
		$accepted_patient = $result_accept[0]['patient_id'];				
					
		$success_status = array('result' => "success", 'accepted_doctor' => $accepted_doctor, 'accepted_patient' => $accepted_patient, 'message' => "Doctor has accepted the requests !!!", 'err_msg' => '');
		echo json_encode($success_status);
		
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
