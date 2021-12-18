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

/*echo $doctor_id;
echo "\n";
echo $timestamp;
echo "\n";
echo $hashKey; */

$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	$result = mysqlSelect('ref_id, ref_name, ref_mail, contact_num, profile_percentage, doc_gen, doc_age, doc_qual, doc_city, doc_state, doc_country, ref_address, ref_exp, doc_interest, doc_research, doc_pub, cons_charge, cons_charge_currency_type, FCM_takenID, physical_consultation_charge, doc_photo, accessToken','referal',"ref_id='".$doctor_id."'");
	/*$result_access = mysqlSelect('id, accessToken','referal_sessions',"doc_id='".$doctor_id."' AND device_id='".$device_id."'","id DESC","","","1");
	
	if(!empty($result_access[0]['accessToken'])) {
		  $hash1 = hmacHashFunction($timestamp, $result_access[0]['accessToken']);
		  $finalHash = hmacHashFunction($hash1, ""); 	// Body is empty bcoz Its a GET Request
	} */

	if($finalHash == $hashKey) {
		
		$result_patTreat = mysqlSelect('COUNT(episode_id) AS patient_treated','doc_patient_episodes',"admin_id='".$doctor_id."'");
	
		$profile_stat = $result[0]['profile_percentage']; 
		$patient_Treated = $result_patTreat[0]['patient_treated']; 
		
		$patientAppreciated = '65'; 
		
		$totalPatients = mysqlSelect('COUNT(patient_id) AS patient_id','doc_my_patient',"doc_id='".$doctor_id."'");
		$totalPatients = $totalPatients[0]['patient_id'];
		$responseRate = ( (int) $patient_Treated / (int) $totalPatients) * 100;
		
		$responseMinutes = mysqlSelect('MIN(NULLIF(response_duration, 0)) as response_duration','appointment_accept_reject',"doc_id='".$doctor_id."'");
		$minResponse = $responseMinutes[0]['response_duration'];  
		
		$success = array('status' => "true", 'profile_stat' => $profile_stat, 'patient_Treated' => $patient_Treated, 'minResponse' => $minResponse, 'patientAppreciated' => $patientAppreciated, 'responseRate' => $responseRate, 'doc_details' => $result,  'err_msg' => '');
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