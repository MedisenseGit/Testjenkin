<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//State Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$episodeID = $_POST['episode_id']; 
	$adminId = $_POST['userid'];
	$loginType = $_POST['login_type'];
		
	$get_prescriptions = $objQuery->mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id ='".$episodeID."'","","","","");
	$success = array('status' => "true","prescription_details" => $get_prescriptions);
	echo json_encode($success);
	
}


?>