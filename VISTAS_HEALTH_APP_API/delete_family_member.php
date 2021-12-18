<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
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
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
$data = json_decode(file_get_contents('php://input'), true);
// Health Reports Lists
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{
		
		$user_id 		= 	$user_id;
		$member_id  	= 	$_POST['member_id'];
		
		// Member General Health
		
		$member_general_health = mysqlSelect('*','user_family_member',"(member_id)='".$member_id."'","","","","");

		if(!empty($member_general_health))
		{
			$family_general_health =  mysqlDelete('user_family_member',"member_id='".$member_id."'");
		}
		
		$share_tests = array('result' => "success", 'err_msg' => '');
		echo json_encode($share_tests);
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
