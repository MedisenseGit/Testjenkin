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
		
		$getFrequentDrugAbuse= mysqlSelect("a.fda_id as fda_id, a.drug_abuse_id as drug_abuse_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.drug_abuse as drug_abuse","doctor_frequent_drug_abuse as a inner join drug_abuse_auto as b on a.drug_abuse_id = b.drug_abuse_id","a.doc_id='".$doctor_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");

		$selectDrugAbuse= mysqlSelect("*","drug_abuse_auto"," (doc_id='0' and doc_type='0') or (doc_id='".$doctor_id."' and doc_type='1')","drug_abuse asc","","","");

		$success = array('status' => "true","frequent_drug_abuse_details" => $getFrequentDrugAbuse,"drug_abuse_details" => $selectDrugAbuse);
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