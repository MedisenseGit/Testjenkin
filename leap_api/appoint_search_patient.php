<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
$curdate=date('Y-m-d');

//Appointment Patient Search
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	   
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];
	$day_val=date('D', strtotime($curdate));
	
	if($logintype == 1)			// Premium Login
	{
		$get_PatientDetails = $objQuery->mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
		
		//Todays appointment list
		$chkDocTimeSlot= $objQuery->mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$admin_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","","","","");
		
		$response["appoint_dates"] = array();
		$appoint_dates_details= array();
		
		for($i=0; $i<=20; $i++) {
		$stuff= array();
		$date = strtotime('+' . $i . 'day');
		$chkdate=date('D', $date);
		//$getDocDays= $objQuery->mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."'","","","","");
		$getDocDays= $objQuery->mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."' and a.hosp_id='".$hospital_id."'","","","","");
																
		$current_date=date('d-m-Y', $date);
		
		 $checkHoliday= $objQuery->mysqlSelect("holiday_id","doc_holidays","doc_id='".$admin_id."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");  
									
		
		$date_1 = new DateTime($current_date);
		$current_time_stamp=$date_1->format("U"); 
		$check_holiday=0; 
		
		 foreach($getDocDays as $daylist) { 
			$getDayName= $objQuery->mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
			
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
		
		
		$success = array('status' => "true","patient_details" => $get_PatientDetails,"todays_appoint_slots" => $chkDocTimeSlot,"appoint_details" => $appoint_dates_details);
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false");
		echo json_encode($success);
	}
}


?>