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
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

// App Notifications
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$login_id = $user_id;

		$getNotifications=mysqlSelect('*','health_app_notifications',"patient_login_id='".$login_id."' and visibility ='1'","id DESC","","","");
		
		$success_notify = array('result' => "success","notifyCount" => COUNT($getNotifications), "notifications" => $getNotifications, 'err_msg' => '');
		echo json_encode($success_notify);
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
