<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");

//Random Password Generator
function randomOtp() {
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 10; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// Forgot Password
		$user_email = $_POST['user_email'];
	
		$check_referring = mysqlSelect('*','login_user',"sub_email='".$user_email."'","","","","");

		if($check_referring==true)
		{
		  $otp = randomOtp();
		  $password = randomPassword();
		  $arrFields = array();
		  $arrValues = array();

		  $arrFields[] = 'otp';
		  $arrValues[] = $otp;
		  $arrFields[] = 'passwd';
		  $arrValues[] = md5($password);
		  
		   $editrecord=mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$check_referring[0]['login_id']."'");

			// Send OTP via Email
			if(!empty($check_referring[0]['sub_contact'])) 
			{
			  $txtMob=$check_referring[0]['sub_contact'];
			  $userotp="Your Temporary Password is ".$password." for Medisense Health App. \nThanks Medisense";
			  send_msg($txtMob,$userotp);
			}
		  
			// Send OTP via Email
			if(!empty($check_referring[0]['sub_email']) || !empty($user_email))
			{
					$txtEmail= $check_referring[0]['sub_email'];
					$txtUsername= $check_referring[0]['sub_name'];
				
					$url_page = 'health_forgot_password_request.php';
					$url = rawurlencode($url_page);
					$url .= "?username=".urlencode($txtUsername);
					$url .= "&password=".urlencode($password);
					$url .= "&reqmail=".urlencode($txtEmail);
							
					send_mail($url);
			}
		  

			$success_forgot_password = array('result' => "success", 'status' => '1','message' => "Password sent to registered email address !!!", 'err_msg' => '');
			echo json_encode($success_forgot_password);
	  }
	  else
	  {
		$success_forgot_password = array('result' => "success", 'status' => '0', 'message' => "Email ID not exists. Please register your account !!!", 'err_msg' => '');
		echo json_encode($success_forgot_password);
	  }

/*
$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// FORGOT PASSWORD
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_email = $_POST['user_email'];
	
		$check_referring = mysqlSelect('*','login_user',"sub_email='".$user_email."'","","","","");

		if($check_referring==true){
		  $otp = randomOtp();
		  $password = randomPassword();
		  $arrFields = array();
		  $arrValues = array();

		  $arrFields[] = 'otp';
		  $arrValues[] = $otp;
		  $arrFields[] = 'passwd';
		  $arrValues[] = md5($password);
		  
		   $editrecord=mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$check_referring[0]['login_id']."'");

			// Send OTP via Email
			if(!empty($check_referring[0]['sub_contact'])) {
			  $txtMob=$check_referring[0]['sub_contact'];
			  $userotp="Your Temporary Password is ".$password." for Medisense Health App. \nThanks Medisense";
			  send_msg($txtMob,$userotp);
			}
		  
			// Send OTP via Email
			if(!empty($check_referring[0]['sub_email']) || !empty($user_email)) {
					$txtEmail= $check_referring[0]['sub_email'];
					$txtUsername= $check_referring[0]['sub_name'];
				
					$url_page = 'health_forgot_password_request.php';
					$url = rawurlencode($url_page);
					$url .= "?username=".urlencode($txtUsername);
					$url .= "&password=".urlencode($password);
					$url .= "&reqmail=".urlencode($txtEmail);
							
					send_mail($url);
			}
		  

			$success_forgot_password = array('result' => "success", 'status' => '1','message' => "Password sent to registered email address !!!", 'err_msg' => '');
			echo json_encode($success_forgot_password);
	  }
	  else{
		$success_forgot_password = array('result' => "success", 'status' => '0', 'message' => "Email ID not exists. Please register your account !!!", 'err_msg' => '');
		echo json_encode($success_forgot_password);
	  }
		
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

?>
