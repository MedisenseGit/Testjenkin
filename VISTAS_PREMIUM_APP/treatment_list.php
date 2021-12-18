<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		
		$getFrequentTreatment = mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$doctor_id."' and doc_type='1')","freq_count DESC","","","8");
		$selectTreatment = mysqlSelect("*","doctor_frequent_treatment","(doc_id='".$doctor_id."' and doc_type='1') or (doc_id='0' and doc_type='0') ","treatment asc","","","");
		
		$success = array('status' => "true","frequent_treatment_details" => $getFrequentTreatment,"treatment_details" => $selectTreatment);
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