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
		$time_id = $_POST['time_id'];
		$day_id = $_POST['day_id'];
		$status = $_POST['status'];
		$admin_id = $doctor_id;
		$hospital_id = $_POST['hosp_id'];
		
		 if($status == 1)  {
		 
				$arrFields_time = array();
				$arrValues_time = array();

				$arrFields_time[] = 'doc_id';
				$arrValues_time[] = $admin_id;
				
				$arrFields_time[] = 'time_id';
				$arrValues_time[] = $time_id;
				
				$arrFields_time[] = 'day_id';
				$arrValues_time[] = $day_id;
				
				$arrFields_time[] = 'time_set';
				$arrValues_time[] = $status;
				
				$arrFields_time[] = 'hosp_id';
				$arrValues_time[] = $hospital_id;
			 
				mysqlDelete('doc_time_set',"doc_id='".$admin_id."' and time_id='".$time_id."' and day_id='".$day_id."' and hosp_id='".$hospital_id."'");
			 
				$doctimecreate=mysqlInsert('doc_time_set',$arrFields_time,$arrValues_time);
				$result = array("result" => "update success");
				echo json_encode($result);
		 }
		 else if($status == 0)  {
			  mysqlDelete('doc_time_set',"doc_id='".$admin_id."' and time_id='".$time_id."' and day_id='".$day_id."' and hosp_id='".$hospital_id."'");
			  $result = array("result" => "delete success");
			  echo json_encode($result);
		 }
		 else {
				$result = array("result" => "failed");
				echo json_encode($result);
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
