<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

$curdate=date('Y-m-d');


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Doctors Appointment Timings Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$admin_id = $_POST['doc_id'];
		$hospital_id = $_POST['hospital_id'];
		
		$response["appoint_timing"] = array();
		$appoint_timings_details= array();
		$day_val=date('D', strtotime($_POST["day_val"]));
		
		$GetTiming= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$admin_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","","","","");

		foreach($GetTiming as $TimeList) {
			$stuff= array();
			$chkDocTimeSlot = mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$admin_id."' and doc_type='1' and hosp_id = '".$hospital_id."'","","","","");
			$countPrevAppBook = mysqlSelect("COUNT(id) as Appoint_Count","appointment_transaction_detail","pref_doc='".$admin_id."' and hosp_id = '".$hospital_id."' and Visiting_date = '".$_POST["day_val"]."' and Visiting_time = '".$TimeList["time_id"]."'","","","","");
			$Timing= mysqlSelect("*","timings","Timing_id='".$TimeList["time_id"]."'","","","","");
			
			if($countPrevAppBook[0]['Appoint_Count']<$chkDocTimeSlot[0]['num_patient_hour']) {
				$stuff["aapt_time"] = $Timing[0]["Timing"];	
				$stuff["aapt_time_id"] = $Timing[0]["Timing_id"];	
			}
			else {
				$stuff["aapt_time"] = "Slot unavailable";	
				$stuff["aapt_time_id"] = "0";
			}
				// array_push($response["appoint_timing"], $stuff);	
				array_push($appoint_timings_details, $stuff);			
			}
		
			$success = array('status' => "true","appoint_timing_details" => $appoint_timings_details);
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