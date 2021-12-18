<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//My Patiet Templates
 if((API_KEY == $_POST['API_KEY'] ) ){
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 

	$login_type = $_POST['login_type'];
	$user_id = $_POST['userid'];
	
	if($login_type == 1)
	{
		$getTemplates = $objQuery->mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$user_id."'","","","","");
		if($getTemplates == true)
		{
			$success = array('status' => "true","templates_details" => $getTemplates);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","templates_details" => $getTemplates);
			echo json_encode($success);
		}
		
	}
	else if($login_type == 2)
	{
		$getTemplates = $objQuery->mysqlSelect("*","patient_episode_prescription_templates","admin_id='".$user_id."'","","","","");
			if($getTemplates == true)
				{
					$success = array('status' => "true","templates_details" => $getTemplates);
					echo json_encode($success);
				}
			else {
					$success = array('status' => "false","templates_details" => $getTemplates);
					echo json_encode($success);
				}
	}
	
	
}


?>