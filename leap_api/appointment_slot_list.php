<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	$hospital_id = $_POST['hosp_id'];
	
	if($login_type==1){
	
		$get_schedule = $objQuery->mysqlSelect("*","doc_time_set","doc_id='".$admin_id."' and time_set=1 and hosp_id='".$hospital_id."'","","","","");
		$checkHospTiming = $objQuery->mysqlSelect("*", "doc_appointment_slots", "doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."'", "", "", "", "");
		$checkHospHoliday = $objQuery->mysqlSelect("*", "doc_holidays", "doc_id='".$admin_id."' and doc_type='1'", "holiday_id DESC", "", "", "");
			
		$result = array("result" => "success", "schedules" => $get_schedule,"slots_details" => $checkHospTiming,"holiday_details" => $checkHospHoliday);
		echo json_encode($result);
	}
	else {
		$$result = array("result" => "failed");
		echo json_encode($result);
	}
 }
?>
