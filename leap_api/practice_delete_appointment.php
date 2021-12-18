<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$appoint_id = $_POST['appointment_id'];
	
		$objQuery->mysqlDelete('partner_appointment_transaction',"appoint_id='".$appoint_id."'");
		$result = array("result" => "success");
		echo json_encode($result);
	
 }
?>
