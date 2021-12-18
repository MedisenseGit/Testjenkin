<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$admin_id = $_POST['user_id'];
	$response["appoint_dates"] = array();
	
	for($i=1; $i<=20; $i++) {
		$stuff= array();
		$date = strtotime('+' . $i . 'day');
		$chkdate=date('D', $date);
		$getDocDays= $objQuery->mysqlSelect("DISTINCT(b.day_id) as DayId","ref_doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."'","","","","");
									
		$current_date=date('d-m-Y', $date);
		$date_1 = new DateTime($current_date);
		$current_time_stamp=$date_1->format("U"); 
		$check_holiday=0; 
		
		 foreach($getDocDays as $daylist) { 
			$getDayName= $objQuery->mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
		
			if(date('D', $date)==$getDayName[0]['da_name']){
				// echo date('D d-m-Y', $date);
				  $stuff["appt_date"] = date('D d-m-Y', $date);
 				  $stuff["appt_id"] = date('Y-m-d', $date);		
				  array_push($response["appoint_dates"], $stuff);
			}
 		
		 }
							
	}
	
	echo json_encode($response);	
	
	
	
 }
?>
