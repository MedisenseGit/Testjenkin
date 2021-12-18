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
		
		$getFrequentTests = mysqlSelect("a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department ","doctor_frequent_investigations as a inner join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_id='".$doctor_id."' and a.doc_type='1'","a.freq_test_count DESC","","","0,10");
				
		$response["status"] = "true";
		$response["frequent_investigation_details"] = $getFrequentTests;
		echo(json_encode($response));	
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