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

$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", "", $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		
		$patient_id = $_POST['patient_id'];
		
		$getFrequentDrugAllery = mysqlSelect("*","doc_patient_drug_allergy_active","doc_id='".$doctor_id."' and doc_type='1' and patient_id='".$patient_id."'","allergy_id DESC","","","");
		
		$success = array('status' => "true","frequent_allergy_details" => $getFrequentDrugAllery);
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