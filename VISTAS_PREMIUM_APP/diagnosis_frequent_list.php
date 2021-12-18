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
		
		$getFrequentDiagnosis = mysqlSelect("a.dfd_id as dfd_id, a.icd_id as icd_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on b.icd_id = a.icd_id","a.doc_id='".$doctor_id."' and a.doc_type='1'","a.freq_count DESC","","","0,8");
			
		$success = array('status' => "true","frequent_diagnosis_details" => $getFrequentDiagnosis);
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