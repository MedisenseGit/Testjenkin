<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Filter
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$filter = $_POST['filter'];
	
		if($filter == "0") {   // 0 - default filter, 1-search symptoms, 2-search specialities
			
			$result_symptoms = mysqlSelect("*","health_app_symptoms","","","","","");
			$result_specialities = mysqlSelect("*","specialization","","","","","");
				
		}

		$success_filter = array('result' => "success", 'status' => '1', 'symptoms_lists' => $result_symptoms, 'specialty_lists' => $result_specialities, 'err_msg' => '');
		echo json_encode($success_filter);
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
