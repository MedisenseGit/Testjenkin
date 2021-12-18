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
		
		$getFrequentComplaints= mysqlSelect("a.dfs_id as dfs_id, a.symptoms_id as symptoms_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.symptoms as symptoms","doctor_frequent_symptoms as a inner join chief_medical_complaints as b on a.symptoms_id = b.complaint_id","a.doc_id='".$doctor_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");
		$select= mysqlSelect("*","chief_medical_complaints"," (doc_id='0' and doc_type='0') or (doc_id='".$doctor_id."' and doc_type='1')","symptoms asc","","","");

		$success = array('status' => "true","frequent_medcomp_details" => $getFrequentComplaints,"chief_medcomp_details" => $select);
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