<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Countries Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
		
	$getStates = $objQuery->mysqlSelect('country_id, country_name','countries',"","","","","");
	
		if($getStates == true)
		{
			$success = array('status' => "true","state_details" => $getStates);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","state_details" => $getStates);
			echo json_encode($success);
		}
	
		
}


?>