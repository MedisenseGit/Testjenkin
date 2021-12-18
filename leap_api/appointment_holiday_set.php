<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Set Holidays
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];
	$holidayDate = date('Y-m-d',strtotime($_POST['holiday_date']));
	$holidayReason = $_POST['holiday_reason'];
	
	if($login_type == 1) {  // Premium LOgin
	
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
	
		$checkHospHoliday = $objQuery->mysqlSelect("*", "doc_holidays", "doc_id='".$admin_id."' and doc_type='1' and holiday_date='".$holidayDate."'", "", "", "", "");
		
		if(COUNT($checkHospHoliday)==0) {	
			$insertHoliday=$objQuery->mysqlInsert('doc_holidays',$arrFields_holiday,$arrValues_holiday);
		}
		else {
			$updateHoliday=$objQuery->mysqlUpdate('doc_holidays',$arrFields_holiday,$arrValues_holiday,"doc_id = '".$admin_id."' and doc_type = '1' and holiday_date='".$holidayDate."'");
		}
		
		$updatedHoliday = $objQuery->mysqlSelect("*", "doc_holidays", "doc_id='".$admin_id."' and doc_type='1'", "holiday_id DESC", "", "", "");
		
		$result = array("result" => "success","holiday_details" => $updatedHoliday);
		echo json_encode($result);
		
	}
	else {
		$$result = array("result" => "failed");
		echo json_encode($result);
	} 
	
}


?>