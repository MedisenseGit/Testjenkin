<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['patient_id']) || isset($_POST['attach_name']) )
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	
	// echo date("Y-m-d");
	 
	 $arrFields1 = array();
	 $arrValues1 = array();
	 
	$arrFields1[]= 'patient_id';
	$arrValues1[]=  $_POST['patient_id'];
	$arrFields1[]= 'attachments';
	$arrValues1[]=  $_POST['attach_name'];
	
	
	$patientInsert=$objQuery->mysqlInsert('patient_attachment',$arrFields1,$arrValues1);
			$pid= mysql_insert_id();
			if($patientCreate == true)
			{
				$success = array('status' => "true","patient_create" => $patientCreate);    	//  patient created resume
				echo json_encode($success);
			}
			else {
				$success = array('status' => "false","patient_create" => $patientCreate);      // patient insert failed
				echo json_encode($success);
			}
	
}


?>