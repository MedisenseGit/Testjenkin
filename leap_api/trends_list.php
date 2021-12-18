<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY']) {
	 
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	$patient_id = $_POST['patient_id'];
	
	if($login_type == 1)		// Type-1 Premium Login
	{
		$getTrends = $objQuery->mysqlSelect("*","trend_analysis","patient_id='".$patient_id."'","","","","");
		$getPrescriptions = $objQuery->mysqlSelect("a.episode_prescription_id as episode_prescription_id, a.episode_id as episode_id, a.pp_id as pp_id, a.prescription_trade_name as prescription_trade_name, a.prescription_generic_name as prescription_generic_name, a.prescription_frequency as prescription_frequency, a.timing as timing, a.duration as duration, a.doc_id as doc_id, a.prescription_instruction as prescription_instruction, a.prescription_date_time as prescription_date_time","doc_patient_episode_prescriptions as a inner join doc_patient_episodes as b on b.episode_id=a.episode_id","b.patient_id='".$patient_id."'","","","","");
		
		$success = array('status' => "true","trends_details" => $getTrends,"prescription_details" => $getPrescriptions);     
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","trends_details" => $getTrends,"prescription_details" => $getPrescriptions);     
		echo json_encode($success);
	}
	
}


?>