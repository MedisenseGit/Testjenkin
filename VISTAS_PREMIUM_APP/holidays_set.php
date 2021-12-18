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
		$admin_id = $doctor_id;
		$hospital_id = $_POST['hosp_id'];
		$holidayDate = date('Y-m-d',strtotime($_POST['holiday_date']));
		$holidayReason = $_POST['holiday_reason'];
		
			$arrFields_holiday = array();
			$arrValues_holiday = array();
			
			$arrFields_holiday[] = 'doc_id';
			$arrValues_holiday[] = $admin_id;

			$arrFields_holiday[] = 'doc_type';
			$arrValues_holiday[] = "1"; //1 for prime doctor
			
			$arrFields_holiday[] = 'holiday_date';
			$arrValues_holiday[] = $holidayDate;
			
			/* $arrFields_holiday[] = 'hosp_id';
			$arrValues_holiday[] = $hospital_id; */ 
			
			$arrFields_holiday[] = 'reason';
			$arrValues_holiday[] = $holidayReason; 
	
			$checkHospHoliday = mysqlSelect("*", "doc_holidays", "doc_id='".$admin_id."' and doc_type='1' and holiday_date='".$holidayDate."'", "", "", "", "");
			
			if(COUNT($checkHospHoliday)==0) {	
				$insertHoliday=mysqlInsert('doc_holidays',$arrFields_holiday,$arrValues_holiday);
			}
			else {
				$updateHoliday=mysqlUpdate('doc_holidays',$arrFields_holiday,$arrValues_holiday,"doc_id = '".$admin_id."' and doc_type = '1' and holiday_date='".$holidayDate."'");
			}
			
			$updatedHoliday = mysqlSelect("*", "doc_holidays", "doc_id='".$admin_id."' and doc_type='1'", "holiday_id DESC", "", "", "");
			
			$result = array("result" => "success","holiday_details" => $updatedHoliday);
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