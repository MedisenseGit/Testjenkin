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

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) {
		 
		$admin_id =  $doctor_id;
		$patient_id = $_POST['patient_id'];

		$getTrends = mysqlSelect("*","trend_analysis","patient_id='".$patient_id."'","","","","");
		$getPrescriptions = mysqlSelect("a.episode_prescription_id as episode_prescription_id, a.episode_id as episode_id, a.pp_id as pp_id, a.prescription_trade_name as prescription_trade_name, a.prescription_generic_name as prescription_generic_name, a.prescription_frequency as prescription_frequency, a.timing as timing, a.duration as duration, a.doc_id as doc_id, a.prescription_instruction as prescription_instruction, a.prescription_date_time as prescription_date_time","doc_patient_episode_prescriptions as a inner join doc_patient_episodes as b on b.episode_id=a.episode_id","b.patient_id='".$patient_id."'","","","","");
		
		$success = array('status' => "true","trends_details" => $getTrends,"prescription_details" => $getPrescriptions);     
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