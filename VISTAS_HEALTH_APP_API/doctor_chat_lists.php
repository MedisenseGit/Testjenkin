<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");

include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers)
{
    $user_id 	= 	$headers['user-id'];
	$timestamp  = 	$headers['x-timestamp'];
	$hashKey    = 	$headers['x-hash'];
	$device_id  = 	$headers['device-id'];
}

$postdata  = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);


// Second Opinion
if(!empty($user_id) && !empty($finalHash))
{	
	if($finalHash == $hashKey) 
	{
		
		$ref_id 	= $_POST['doctor_id'];
		
		$get_chathistory = mysqlSelect('*','chat_notification',"ref_id ='".$ref_id."'","TImestamp desc","","","");
		
		
		$success = array('status' => "true","chat_history"=>$get_chathistory);
		echo json_encode($success);
		
		
	}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else 
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}
?>