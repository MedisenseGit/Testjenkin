<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');


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
		
		$patient_id = $_POST['patient_id'];
		$appoint_transID = $_POST['appoint_trans_id'];
		
		$appointmentResult = mysqlSelect("*","appointment_tracking","doc_id='".$doctor_id."' and patient_id='".$patient_id."' and appoint_trans_id='".$appoint_transID."'","id DESC","","","");				
	
		$success = array('status' => "true", "appoint_tracking_details" => $appointmentResult, 'err_msg' => '');
		echo json_encode($success);
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