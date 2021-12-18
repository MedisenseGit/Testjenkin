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

// Doctors Appointment Dates Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$admin_id = $user_id;
		$hospital_id = $_POST['hospital_id'];
		$day_val = date('D', strtotime($curdate));
		
		//Todays appointment list
		$chkDocTimeSlot= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$admin_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","","","","");
			
		$response["appoint_dates"] = array();
		$appoint_dates_details= array();
			
		for($i=0; $i<=20; $i++) {
		$stuff= array();
		$date = strtotime('+' . $i . 'day');
		$chkdate=date('D', $date);
		//$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."'","","","","");
		$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."' and a.hosp_id='".$hospital_id."'","","","","");
																	
		$current_date=date('d-m-Y', $date);
			
		$checkHoliday= mysqlSelect("holiday_id","doc_holidays","doc_id='".$admin_id."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");  
										
			
		$date_1 = new DateTime($current_date);
		$current_time_stamp=$date_1->format("U"); 
		$check_holiday=0; 
			
		foreach($getDocDays as $daylist) { 
			$getDayName= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
				
			if((date('D', $date)==$getDayName[0]['da_name']) && COUNT($checkHoliday)==0){ 
				if(date('D', $date)==$getDayName[0]['da_name']){
					// echo date('D d-m-Y', $date);
				  $stuff["appt_date"] = date('D d-m-Y', $date);
				  $stuff["appt_id"] = date('Y-m-d', $date);		
				  array_push($appoint_dates_details, $stuff);
				}
			}
		 }					
		}
			
			$success = array('status' => "true","todays_appoint_slots" => $chkDocTimeSlot,"appoint_details" => $appoint_dates_details);
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