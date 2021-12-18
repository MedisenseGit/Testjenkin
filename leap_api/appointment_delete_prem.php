<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$appoint_trans_id = $_POST['appoint_transaction_id'];
	
		$objQueryAppoint = $objQuery->mysqlDelete('appointment_transaction_detail',"appoint_trans_id='".$appoint_trans_id."'");
		$objQueryHospVistor = $objQuery->mysqlDelete('new_hospvisitor_details',"Transaction_id='".$appoint_trans_id."'");
		
		if(($objQueryAppoint == true) && ($objQueryHospVistor == true)) {
			$result = array("result" => "success");
			echo json_encode($result);
		}
		else {
			$result = array("result" => "failed");
			echo json_encode($result);
		}
 }
?>
