<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


// VIDEO CALL - LIST

$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) {
			
			
		$admin_id = $doctor_id;
		
		$getCallResult = mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_loc as pat_loc, a.pat_country as pat_country, a.pat_query as pat_query, a.patient_mob as patient_mob, a.patient_email as patient_email, a.transaction_status as transaction_status, a.videocall_pref_datetime as videocall_pref_datetime, c.ref_id as ref_id, c.ref_name as ref_name','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id',"b.ref_id='".$admin_id."' and a.looking_for='3'","a.patient_id desc","","","0,15");
			
		$success = array('status' => "true","call_details" => $getCallResult);
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