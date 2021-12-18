<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Cancel / Delete Holidays
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$holiday_id = $_POST['holiday_id'];
	
	if($login_type == 1) {  // Premium LOgin
	
		$objQuery->mysqlDelete('doc_holidays',"doc_id='".$admin_id."' and doc_type ='1' and holiday_id='".$holiday_id."'");
	
		$result = array("result" => "success");
		echo json_encode($result);
		
	}
	else {
		$$result = array("result" => "failed");
		echo json_encode($result);
	} 
	
}


?>