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
		$admin_id = $doctor_id;

		$episode_details= array();
		$get_attachments= array();
		
		$get_diagnostics = mysqlSelect('a.diagnostic_id as diagnostic_id, a.diagnosis_name as diagnosis_name, a.diagnosis_city as diagnosis_city, a.diagnosis_state as diagnosis_state, a.diagnosis_country as diagnosis_country, a.diagnosis_contact_person as diagnosis_contact_person, a.diagnosis_contact_num as diagnosis_contact_num, a.diagnosis_email as diagnosis_email, a.diagnosis_password as diagnosis_password ','Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id',"b.doc_id='".$admin_id."'","a.diagnosis_name ASC","","","");
	
		
		$success = array('status' => "true","diagnostics_details"=>$get_diagnostics);
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