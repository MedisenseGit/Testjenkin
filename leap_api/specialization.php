<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
		
	$getSpecialization = $objQuery->mysqlSelect('spec_id, spec_name','specialization',"","","","","");
	
		if($getSpecialization == true)
		{
			$success = array('status' => "true","specialization_details" => $getSpecialization);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","specialization_details" => $getSpecialization);
			echo json_encode($success);
		}
	
		
}


?>