<?php ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");



//Get doc my patient details

$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) {
		$patient_id = $_POST['patient_id'];
	
		$result_data = mysqlSelect("*","doc_my_patient","patient_id='".$patient_id."'","","","","");	

		
		$success = array('status' => "true","patient_details" => $result_data);     
		echo json_encode($success);

	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}

?>