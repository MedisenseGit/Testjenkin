<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// VIDEO CALL - LIST
if(API_KEY == $_POST['API_KEY']) {
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	

	$admin_id = $_POST['userid'];
	
	$getCallResult = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_loc as pat_loc, a.pat_country as pat_country, a.pat_query as pat_query, a.patient_mob as patient_mob, a.patient_email as patient_email, a.transaction_status as transaction_status, a.videocall_pref_datetime as videocall_pref_datetime, c.ref_id as ref_id, c.ref_name as ref_name','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id',"b.ref_id='".$admin_id."' and a.looking_for='3'","a.patient_id desc","","","0,15");
		
	$success = array('status' => "true","call_details" => $getCallResult);
	echo json_encode($success);
	
}
else {
		$failed = array('status' => "false");
		echo json_encode($failed);
}

?>