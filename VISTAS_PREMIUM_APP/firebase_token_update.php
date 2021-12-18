<?php ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");



//Update Firebase Token ID4

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
		$token_id = $_POST['txt_firebase_tokenID'];
		$user_id = $doctor_id;
		
		$arrFields[] = 'FCM_takenID';
		$arrValues[] = $token_id;
		$getUser=mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$user_id."'");

		
		$success = array('status' => "true","update_tokenID" => "success");     
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