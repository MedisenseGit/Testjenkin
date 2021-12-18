<?php ob_start();
 error_reporting(0);
 session_start();


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

require_once("../classes/querymaker.class.php");

ob_start();
include('send_mail_function.php');
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

//User Address Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
	
		$login_id = $user_id;
		
		$user_address = mysqlSelect("*","user_address","user_id ='".$login_id."'","","","","");
			
		$success_register = array('result' => "success","user_address" => $user_address);
		echo json_encode($success_register);
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