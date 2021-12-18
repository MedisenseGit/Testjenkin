<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Appointment Doctor Appointment Timings
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	   
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];
	
	if($logintype == 1)			// Premium Login
	{
		$response["appoint_timing"] = array();
		$appoint_timings_details= array();
		$day_val=date('D', strtotime($_POST["day_val"]));
	
		$GetTiming= $objQuery->mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$admin_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","","","","");

		foreach($GetTiming as $TimeList) {
			 $stuff= array();
			$chkDocTimeSlot = $objQuery->mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$admin_id."' and doc_type='1' and hosp_id = '".$hospital_id."'","","","","");
			$countPrevAppBook = $objQuery->mysqlSelect("COUNT(id) as Appoint_Count","appointment_transaction_detail","pref_doc='".$admin_id."' and hosp_id = '".$hospital_id."' and Visiting_date = '".$_POST["day_val"]."' and Visiting_time = '".$TimeList["time_id"]."'","","","","");
			$Timing= $objQuery->mysqlSelect("*","timings","Timing_id='".$TimeList["time_id"]."'","","","","");
		
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
		$success = array('status' => "false");
		echo json_encode($success);
	}
}


?>