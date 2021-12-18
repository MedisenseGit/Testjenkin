<?php
ob_start();
session_start();
error_reporting(0);

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



//RESET PASSWORD

if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{
		$user_email = $_POST['user_email'];
		$check_referring = mysqlSelect('*','login_user',"sub_email='".$user_email."'","","","","");
		if($check_referring==true)
		{
			$old_password 	= $_POST['old_password'];
			$reset_password = $_POST['reset_password'];
			
			$arrFields = array();
			$arrValues = array();
			
			$arrFields[] = 'passwd';
			$arrValues[] = md5($reset_password); 
			$update_doc_reg	= mysqlUpdate('login_user',$arrFields,$arrValues,"sub_email='".$user_email."'");
			
			$success_reset_password = array('result' => "success", 'status' => '1','message' => "Password Reset sucessfully!!!", 'err_msg' => '');
			echo json_encode($success_reset_password);
			
		}	
		
		
	}
	else
	{
		$success_reset_password = array('result' => "success", 'status' => '0' ,'err_msg' => '');
		echo json_encode($success_reset_password);
		
	}
	
	
}	
else
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
	
}
	


?>
