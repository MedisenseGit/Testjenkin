<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['contact_num']) && isset($_POST['doc_password'])) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$mobile_num = $_POST['contact_num'];
	$password = md5($_POST['doc_password']);
	$invetory_report = array();	 
	
	$result = $objQuery->mysqlSelect('*','referal',"contact_num='".$mobile_num."' and doc_password='".$password."'");
	
	if($result == true)
	{
		$success = array('status' => "true","doc_details" => $result);
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","doc_details" => $result);      // Invalid Mobile number and Password
		echo json_encode($success);
	}
	
}
else {
	$success = array('status' => "false","msg" => 'Authentication Failed', "doc_details" => $result);      // Invalid Mobile number and Password
	echo json_encode($success);
}


?>