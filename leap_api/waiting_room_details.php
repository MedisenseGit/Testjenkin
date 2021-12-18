<?php ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


//Get doc my patient details
if(API_KEY == $_POST['API_KEY']) {

	$patient_id = $_POST['patient_id'];
	
	$result_data = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$patient_id."'","","","","");	

	
	$success = array('status' => "true","patient_details" => $result_data);     
	echo json_encode($success);

}
else {
	$success = array('status' => "false","msg" => 'Invalid API Key');     
	echo json_encode($success);
}	