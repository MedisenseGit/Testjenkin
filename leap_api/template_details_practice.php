<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//State Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$templateID = $_POST['template_id'];
		
	$get_States = $objQuery->mysqlSelect('*','patient_episode_prescription_template_details',"template_id='".$templateID."'","","","","");
	$success = array('status' => "true","template_details" => $get_States);
	echo json_encode($success);
	
}


?>