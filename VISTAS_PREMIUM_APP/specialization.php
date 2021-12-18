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
		
		$getSpecialization = mysqlSelect('spec_id, spec_name','specialization',"","","","","");
	
		if($getSpecialization == true)
		{
			$success = array('status' => "true","specialization_details" => $getSpecialization);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","specialization_details" => $getSpecialization);
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