<?php 
ob_start();
error_reporting(0);
session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('../MEDISENSE_HEALTH_APP/send_mail_function.php');
include("../MEDISENSE_HEALTH_APP/send_text_message.php");

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

if(HEALTH_API_KEY == $data ->api_key && isset($data ->mobile_num) && isset($data ->userotp))
{
	$txtMobile = $data ->mobile_num;
	$txtEmail = $data ->user_email;
	$txtOTP = $data ->userotp;
	 
	$result_referrring = $objQuery->mysqlSelect("*","login_user","(sub_contact LIKE '%".$txtMobile."%' or sub_email='".$txtEmail."') AND otp='".$txtOTP."'","","","","");
	if($result_referrring==true){
		$password = randomPassword();
		
		$arrFields[] = 'login_status';
		$arrValues[] = "1";
		$arrFields[] = 'verification_status';
		$arrValues[] = "1";
		$arrFields[] = 'login_permission';
		$arrValues[] = "1";
		$arrFields[] = 'passwd';
		$arrValues[] = md5($password);
	
		$updateMapping=$objQuery->mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$result_referrring[0]['login_id']."'");
		$check_primary = $objQuery->mysqlSelect("member_id","user_family_member","user_id='".$result_referrring[0]['login_id']."' and member_type='primary'","","","","");
		
		if(COUNT($check_primary)==0){
			$arrFields_family[] = 'member_name';
			$arrValues_family[] = $result_referrring[0]['sub_name'];
			$arrFields_family[] = 'member_type';
			$arrValues_family[] = "primary";
			$arrFields_family[] = 'user_id';
			$arrValues_family[] = $result_referrring[0]['login_id'];
			$patientNote=$objQuery->mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);
		}

		// Send Temporary Password via SMS
		if(!empty($result_referrring[0]['sub_contact'])) {
			$userotp="Your temporary password for Medisense Health App is ".$password." . \nThanks Medisense";
			send_msg($mobile_num,$userotp);	
		}

		// Send Temporary Password via Email
		if(!empty($result_referrring[0]['sub_email'])) {
			$txtName=$result_referrring[0]['sub_name'];
			$txtEmail=$result_referrring[0]['sub_email'];
			$txtMobile=$result_referrring[0]['sub_contact'];
			$txtPassword=$password;
		
			$url_page = 'health_app_password_send.php';
			$url = rawurlencode($url_page);
			$url .= "?username=".urlencode($txtName);
			$url .= "&loginID=".urlencode($txtEmail);
			$url .= "&password=".urlencode($txtPassword);
			$url .= "&reqmail=".urlencode($txtEmail);
					
			send_mail($url);
		}

		$user_consultation = $objQuery->mysqlSelect("patient_id","doc_my_patient","patient_mob ='".$result_referrring[0]['sub_contact']."'","","","","");
		
		if(COUNT($user_consultation) > 0) {
			$otp_confirm = array('result_otp' => "success", 'status' => 'otp_confirm_success', 'message' => "OTP verified successfully !!!" , "user_name" => $result_referrring[0]['sub_name'],"user_id" => $result_referrring[0]['login_id'],"user_mobile" => $result_referrring[0]['sub_contact'], "user_consult" => '1' );
			echo json_encode($otp_confirm);
		}else{
			$otp_confirm = array('result_otp' => "success", 'status' => 'otp_confirm_success', 'message' => "OTP verified successfully !!!" , "user_name" => $result_referrring[0]['sub_name'],"user_id" => $result_referrring[0]['login_id'],"user_mobile" => $result_referrring[0]['sub_contact'], "user_consult" => '1' );
			echo json_encode($otp_confirm);
		}
	}
	else{
		$otp_confirm = array('result_otp' => "success", 'status' => 'otp_confirm_failed', 'message' => "OTP doesn't match. Please try again !!!" );
		echo json_encode($otp_confirm);
	}
}
else{
	$otp_confirm = array('result_otp' => "failed", 'message' => "You have not permitted to access the account !!!");
	echo json_encode($otp_confirm);
}

?>