<?php 
ob_start();
error_reporting(0);
session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
include('../premium/send_mail_function.php');
include("../premium/send_text_message.php");


// get posted data
$data = json_decode(file_get_contents("php://input"));

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
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

if(HEALTH_API_KEY == $data ->api_key  && isset($data ->useremail))	
{	
	$useremail=$data ->useremail;
	$check_referring = $objQuery->mysqlSelect("*","login_user","sub_email='".$useremail."'","","","","");
	
	if($check_referring==true)
	{
		$login_id=$check_referring[0]['login_id'];
		$sub_contact=$check_referring[0]['sub_contact'];
		$sub_email=$check_referring[0]['sub_email'];
		$sub_name=$check_referring[0]['sub_name'];
      $otp = randomOtp();
	  $password = randomPassword();
	  $arrFields = array();
	  $arrValues = array();

	  $arrFields[] = 'otp';
	  $arrValues[] = $otp;
	  $arrFields[] = 'passwd';
	  $arrValues[] = md5($password);
	 
	  
	   $editrecord=$objQuery->mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$login_id."'");
		 
		
		//$check_referring[0]['sub_email'];
		
		// Send OTP via Email
		if(!empty($sub_contact)) 
		{
			
		  $txtMob=$sub_contact;
		  $userotp="Your Temporary Password is ".$password." for Medisense Health App. \nThanks Medisense";
		  send_msg($txtMob,$userotp);
		}
	  
		// Send OTP via Email
		if(!empty($sub_email) || !empty($user_email))
		{
				$txtEmail= $sub_email;
				$txtUsername= $sub_name;
			
				$url_page = 'health_forgot_password_request.php';
				$url = rawurlencode($url_page);
				$url .= "?username=".urlencode($txtUsername);
				$url .= "&password=".urlencode($password);
				$url .= "&reqmail=".urlencode($txtEmail);
						
				send_mail($url);
		}
	  

		$success_forgot_password = array('result' => "success", 'status' => '1','message' => "Password sent to registered email address !!!", 'err_msg' => '');
		echo json_encode($otp);
  }
  else
  {
    $success_forgot_password = array('result' => "success", 'status' => '0', 'message' => "Email ID not exists. Please register your account !!!", 'err_msg' => '');
	echo json_encode($success_forgot_password);
  }
	
	
	

	
}



else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}


?>


