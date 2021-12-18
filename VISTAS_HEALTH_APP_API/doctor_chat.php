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
		$patient_id 	= $_POST['patient_id'];
		$msg 			= $_POST['msg'];
		$login_id 		= $user_id;
		
		//$msg="Patient Registered on ".$Cur_Date;
			
			$arrFields2 = array();
			$arrValues2 = array();
			
			$arrFields2[] = 'patient_id';
			$arrValues2[] = $patient_id;
			
			$arrFields2[] = 'ref_id';
			$arrValues2[] = "0";
			
			$arrFields2[] = 'chat_note';
			$arrValues2[] = $msg;
			
			$arrFields2[] = 'user_id';
			$arrValues2[] = "9";
			
			$arrFields2[] = 'TImestamp';
			$arrValues2[] = $Cur_Date;

			$userchat=mysqlInsert('chat_notification',$arrFields2,$arrValues2);
			
			
			$success_opinion = array('result' => "success");
			echo json_encode($success_opinion);
		
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