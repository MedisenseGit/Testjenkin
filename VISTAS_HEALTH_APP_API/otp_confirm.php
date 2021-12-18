<?php ob_start();
 error_reporting(0);
 session_start();


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

require_once("../classes/querymaker.class.php");

ob_start();
include('send_mail_function.php');
include("send_text_message.php");

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

$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

// OTP Confirm
		$txtEmail = $_POST['user_email'];
		$txtMobile = $_POST['contact_num'];
		$txtOTP = $_POST['otp_number'];

			$result_referrring = mysqlSelect("*","login_user","(sub_contact LIKE '%".$txtMobile."%' or sub_email='".$txtEmail."') AND otp='".$txtOTP."'","","","","");
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
			
				$updateMapping=mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$result_referrring[0]['login_id']."'");
				$check_primary = mysqlSelect("member_id","user_family_member","user_id='".$result_referrring[0]['login_id']."' and member_type='primary'","","","","");
				
				if(COUNT($check_primary)==0){
					$arrFields_family[] = 'member_name';
					$arrValues_family[] = $result_referrring[0]['sub_name'];
					$arrFields_family[] = 'member_type';
					$arrValues_family[] = "primary";
					$arrFields_family[] = 'user_id';
					$arrValues_family[] = $result_referrring[0]['login_id'];
					$patientNote=mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);
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
		 
				$result_family = mysqlSelect("*","user_family_member","user_id ='".$result_referrring[0]['login_id']."'","member_id ASC","","","");
				$user_address = mysqlSelect("*","user_address","user_id ='".$result_referrring[0]['login_id']."'","","","","");
				$member_medical_background = mysqlSelect("*","user_family_general_health","user_id ='".$result_referrring[0]['login_id']."'","","","","");
				$languages = mysqlSelect("*","languages","","","","","");
				$result_wallet = mysqlSelect("*","health_app_wallet","login_id ='".$result_referrring[0]['login_id']."'","id DESC","","","1");
				// $reports_details = mysqlSelect("a.id as report_id, a.member_id as member_id, a.title as title, a.description as description, a.timeStampNum as timeStampNum, a.created_date as created_date, b.id as attachment_id, b.attachment_name as attachment_name","health_app_healthfile_reports as a inner join health_app_healthfile_report_attachments as b on b.report_id = a.id","a.login_id ='".$result_referrring[0]['login_id']."'","a.id DESC","","","");
				$getCountries = mysqlSelect('country_id, country_name','countries',"visibility = '1'","country_id ASC","","","");
			
				$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","login_id ='".$result_referrring[0]['login_id']."'","id DESC","","","");
				$reports_details= array();
				foreach($reportlist_details as $result_reportList) {
						$getReportList['report_id']=$result_reportList['id'];
						$getReportList['member_id']=$result_reportList['member_id'];
						$getReportList['title']=$result_reportList['title'];
						$getReportList['description']=$result_reportList['description'];
						$getReportList['timeStampNum']=$result_reportList['timeStampNum'];
						$getReportList['created_date']=$result_reportList['created_date'];
						$getReportList['report_date']=$result_reportList['report_date'];
						
						$attachment_details = mysqlSelect("id as attachment_id, attachment_name as attachment_name","health_app_healthfile_report_attachments","report_id ='".$result_reportList['id']."'","id ASC","","","");
						$getReportList['attachments']= $attachment_details;
						
					array_push($reports_details, $getReportList);
				}
				
				$profile_percentage_status = $result_referrring[0]['profile_percentage'];
				$corporate_employee = $result_referrring[0]['subscriber_id'];
				if($corporate_employee != 0) {
					$result_corporate = mysqlSelect("*","subscribers","id ='".$corporate_employee."'","","","","");
					$corporate_employee_id = $result_corporate[0]['employee_id'];
				}
				else {
					$corporate_employee_id = "";
				}
				
				// Check access Token in multiple sessions
				$check_session = mysqlSelect("*","login_sessions","login_id='".$result_referrring[0]['login_id']."' and device_id='".$device_id."'","","","","");
				$accessToken = $check_session[0]['accessToken'];
				
				$otp_confirm = array('result' => "success", 'status' => 'otp_confirm_success', 'accessToken' => $accessToken, "user_details" => $result_referrring, "family_details" => $result_family, "member_medical_background" => $member_medical_background, 'reports_details' => $reports_details, "user_address" => $user_address, "languages" => $languages, "result_wallet" => $result_wallet, "result_countries" => $getCountries, "profile_percentage_status" => $profile_percentage_status, "corporate_employee_id" => $corporate_employee_id, 'message' => "OTP verified successfully !!!", 'err_msg' => '');
				echo json_encode($otp_confirm);
			}
			else{

				$otp_confirm = array('result' => "success", 'status' => 'otp_confirm_failed', 'message' => "OTP doesn't match. Please try again !!!", 'err_msg' => '');
				echo json_encode($otp_confirm);
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

if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$txtEmail = $_POST['user_email'];
		$txtMobile = $_POST['contact_num'];
		$txtOTP = $_POST['otp_number'];

			$result_referrring = mysqlSelect("*","login_user","(sub_contact LIKE '%".$txtMobile."%' or sub_email='".$txtEmail."') AND otp='".$txtOTP."'","","","","");
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
			
				$updateMapping=mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$result_referrring[0]['login_id']."'");
				$check_primary = mysqlSelect("member_id","user_family_member","user_id='".$result_referrring[0]['login_id']."' and member_type='primary'","","","","");
				
				if(COUNT($check_primary)==0){
					$arrFields_family[] = 'member_name';
					$arrValues_family[] = $result_referrring[0]['sub_name'];
					$arrFields_family[] = 'member_type';
					$arrValues_family[] = "primary";
					$arrFields_family[] = 'user_id';
					$arrValues_family[] = $result_referrring[0]['login_id'];
					$patientNote=mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);
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
		 
				$result_family = mysqlSelect("*","user_family_member","user_id ='".$result_referrring[0]['login_id']."'","member_id ASC","","","");
				$user_address = mysqlSelect("*","user_address","user_id ='".$result_referrring[0]['login_id']."'","","","","");
				$member_medical_background = mysqlSelect("*","user_family_general_health","user_id ='".$result_referrring[0]['login_id']."'","","","","");
				$languages = mysqlSelect("*","languages","","","","","");
				$result_wallet = mysqlSelect("*","health_app_wallet","login_id ='".$result_referrring[0]['login_id']."'","id DESC","","","1");
				// $reports_details = mysqlSelect("a.id as report_id, a.member_id as member_id, a.title as title, a.description as description, a.timeStampNum as timeStampNum, a.created_date as created_date, b.id as attachment_id, b.attachment_name as attachment_name","health_app_healthfile_reports as a inner join health_app_healthfile_report_attachments as b on b.report_id = a.id","a.login_id ='".$result_referrring[0]['login_id']."'","a.id DESC","","","");
				$getCountries = mysqlSelect('country_id, country_name','countries',"visibility = '1'","country_id ASC","","","");
			
				$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","login_id ='".$result_referrring[0]['login_id']."'","id DESC","","","");
				$reports_details= array();
				foreach($reportlist_details as $result_reportList) {
						$getReportList['report_id']=$result_reportList['id'];
						$getReportList['member_id']=$result_reportList['member_id'];
						$getReportList['title']=$result_reportList['title'];
						$getReportList['description']=$result_reportList['description'];
						$getReportList['timeStampNum']=$result_reportList['timeStampNum'];
						$getReportList['created_date']=$result_reportList['created_date'];
						$getReportList['report_date']=$result_reportList['report_date'];
						
						$attachment_details = mysqlSelect("id as attachment_id, attachment_name as attachment_name","health_app_healthfile_report_attachments","report_id ='".$result_reportList['id']."'","id ASC","","","");
						$getReportList['attachments']= $attachment_details;
						
					array_push($reports_details, $getReportList);
				}
				
				$profile_percentage_status = $result_referrring[0]['profile_percentage'];
				$corporate_employee = $result_referrring[0]['subscriber_id'];
				if($corporate_employee != 0) {
					$result_corporate = mysqlSelect("*","subscribers","id ='".$corporate_employee."'","","","","");
					$corporate_employee_id = $result_corporate[0]['employee_id'];
				}
				else {
					$corporate_employee_id = "";
				}
				
				// Check access Token in multiple sessions
				$check_session = mysqlSelect("*","login_sessions","login_id='".$result_referrring[0]['login_id']."' and device_id='".$device_id."'","","","","");
				$accessToken = $check_session[0]['accessToken'];
				
				$otp_confirm = array('result' => "success", 'status' => 'otp_confirm_success', 'accessToken' => $accessToken, "user_details" => $result_referrring, "family_details" => $result_family, "member_medical_background" => $member_medical_background, 'reports_details' => $reports_details, "user_address" => $user_address, "languages" => $languages, "result_wallet" => $result_wallet, "result_countries" => $getCountries, "profile_percentage_status" => $profile_percentage_status, "corporate_employee_id" => $corporate_employee_id, 'message' => "OTP verified successfully !!!", 'err_msg' => '');
				echo json_encode($otp_confirm);
			}
			else{

				$otp_confirm = array('result' => "success", 'status' => 'otp_confirm_failed', 'message' => "OTP doesn't match. Please try again !!!", 'err_msg' => '');
				echo json_encode($otp_confirm);
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