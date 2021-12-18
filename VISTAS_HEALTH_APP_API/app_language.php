<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");

/*
$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

if(!empty($user_id) && !empty($finalHash)) {
	

	if($finalHash == $hashKey) {

		$app_languages = mysqlSelect('*','health_app_languages',"","id ASC","","","");

		$result_app_lang = array('result' => "success",'appLanguageArray' => $app_languages, 'err_msg' => '');
		echo json_encode($result_app_lang);
		
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
*/

$app_languages = mysqlSelect('*','health_app_languages',"","id ASC","","","");

$result_app_lang = array('result' => "success",'appLanguageArray' => $app_languages, 'err_msg' => '');
echo json_encode($result_app_lang);

?>
