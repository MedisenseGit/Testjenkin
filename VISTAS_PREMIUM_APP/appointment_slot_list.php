<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");

ob_start();


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
		$admin_id = $doctor_id;
		$hospital_id = $_POST['hosp_id'];
		
		$get_schedule = mysqlSelect("*","doc_time_set","doc_id='".$admin_id."' and time_set=1 and hosp_id='".$hospital_id."'","","","","");
		$checkHospTiming = mysqlSelect("*", "doc_appointment_slots", "doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."'", "", "", "", "");
		$checkHospHoliday = mysqlSelect("*", "doc_holidays", "doc_id='".$admin_id."' and doc_type='1'", "holiday_id DESC", "", "", "");
			
		$result = array("result" => "success", "schedules" => $get_schedule,"slots_details" => $checkHospTiming,"holiday_details" => $checkHospHoliday);
		echo json_encode($result);
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
