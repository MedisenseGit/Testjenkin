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

// Resend OTP
		$user_email = $_POST['user_email'];
		$mobile_num = $_POST['contact_num'];


		$result_referrring = mysqlSelect('*','login_user',"sub_contact='".$mobile_num."' OR sub_email='".$user_email."'","","","","");

		if($result_referrring==true){
			$otp = randomOtp();
			$password = randomPassword();
			$arrFields[] = 'otp';
			$arrValues[] = $otp;
			$arrFields[] = 'passwd';
			$arrValues[] = $password;
			
			$updateMapping=mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$result_referrring[0]['login_id']."'");
			
			$userotp="Your OTP for Medisense Healthcare App is ".$otp." . \nThanks Medisense";
			send_msg($mobile_num,$userotp);
			
			// Send OTP via Email
			if(!empty($user_email)) {
					$txtDocName=$user_name;
					$txtEmail=$user_email;
				
					$url_page = 'health_otp_request.php';
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($txtDocName);
					$url .= "&otp=".urlencode($otp);
					$url .= "&reqmail=".urlencode($txtEmail);
							
					send_mail($url);
			}
			
			$success_resend_otp = array('result' => "success", 'status' => 'resend_success','message' => "OTP resent to your email id or mobile number successfully.", 'err_msg' => '');
			echo json_encode($success_resend_otp);
		}
		else {
			$success_resend_otp = array('result' => "failed",'status' => 'resend_failed', 'message' => "Invalid email id or mobile number !!!", 'err_msg' => '');
			echo json_encode($success_resend_otp);
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

//Resend OTP
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_email = $_POST['user_email'];
		$mobile_num = $_POST['contact_num'];


		$result_referrring = mysqlSelect('*','login_user',"sub_contact='".$mobile_num."' OR sub_email='".$user_email."'","","","","");

		if($result_referrring==true){
			$otp = randomOtp();
			$password = randomPassword();
			$arrFields[] = 'otp';
			$arrValues[] = $otp;
			$arrFields[] = 'passwd';
			$arrValues[] = $password;
			
			$updateMapping=mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$result_referrring[0]['login_id']."'");
			
			$userotp="Your OTP for Medisense Healthcare App is ".$otp." . \nThanks Medisense";
			send_msg($mobile_num,$userotp);
			
			// Send OTP via Email
			if(!empty($user_email)) {
					$txtDocName=$user_name;
					$txtEmail=$user_email;
				
					$url_page = 'health_otp_request.php';
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($txtDocName);
					$url .= "&otp=".urlencode($otp);
					$url .= "&reqmail=".urlencode($txtEmail);
							
					send_mail($url);
			}
			
			$success_resend_otp = array('result' => "success", 'status' => 'resend_success','message' => "OTP resent to your email id or mobile number successfully.", 'err_msg' => '');
			echo json_encode($success_resend_otp);
		}
		else {
			$success_resend_otp = array('result' => "failed",'status' => 'resend_failed', 'message' => "Invalid email id or mobile number !!!", 'err_msg' => '');
			echo json_encode($success_resend_otp);
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
