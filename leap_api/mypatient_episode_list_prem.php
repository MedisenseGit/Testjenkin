<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//State Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$templateID = $_POST['template_id'];
	$adminId = $_POST['userid'];
	$patientId = $_POST['patient_id'];
	$loginType = $_POST['login_type'];
		
	$get_Episodes = $objQuery->mysqlSelect('*','doc_patient_episodes',"admin_id='".$adminId."' and 	patient_id ='".$patientId."'","","","","");
	$success = array('status' => "true","episode_details" => $get_Episodes);
	echo json_encode($success);
	
}


?>