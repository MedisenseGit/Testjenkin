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
		
		$getFrequentFamilyHistory= mysqlSelect("a.ffh_id as ffh_id, a.family_history_id as family_history_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.family_history as family_history","doctor_frequent_family_history as a inner join family_history_auto as b on a.family_history_id = b.family_history_id","a.doc_id='".$doctor_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");

		$selectFamilyHistory= mysqlSelect("*","family_history_auto"," (doc_id='0' and doc_type='0') or (doc_id='".$doctor_id."' and doc_type='1')","family_history asc","","","");

		$success = array('status' => "true","frequent_family_history_details" => $getFrequentFamilyHistory,"family_history_details" => $selectFamilyHistory);
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