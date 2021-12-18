<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
// App Security Section Starts
function hmac($string, $secret) {
	return hash_hmac('sha256', $string, $secret);
}
// App Security Section Ends
/*
$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}  */

		// Login 		
		$user_email = $_POST['user_email'];
		$user_password = $_POST['user_password'];
		$device_id = $_POST['device-id'];

		//$result_referrring = $objQuery->mysqlSelect('*','login_user',"sub_email='".$user_email."' AND passwd='".md5($user_password)."'","","","","");
		$result_referrring = mysqlSelect('*','login_user AS a INNER JOIN user_family_member AS b ON a.login_id = b.user_id',"sub_email='".$user_email."' AND passwd='".md5($user_password)."' ","","","","");
		
		
		if($result_referrring==true){
			
			//Logic for creating accessToken
			$accessToken = hmac($result_referrring[0]['login_id'], $Cur_Date);
			
			// Update access Token in multiple sessions
			$check_session = mysqlSelect("*","login_sessions","login_id='".$result_referrring[0]['login_id']."' and device_id='".$device_id."' and created_date='".$Cur_Date."'","","","","");
			$arrFields2 = array();
			$arrValues2 = array();
			$arrFields2[] = 'login_id';
			$arrValues2[] = $result_referrring[0]['login_id'];
			$arrFields2[] = 'device_id';
			$arrValues2[] = $device_id;
			$arrFields2[] = 'accessToken';
			$arrValues2[] = $accessToken;
			$arrFields2[] = 'created_date';
			$arrValues2[] = $Cur_Date;
			if(COUNT($check_session)>0){	
				 $updateAccess=mysqlUpdate('login_sessions',$arrFields2,$arrValues2,"login_id='".$result_referrring[0]['login_id']."' and device_id='".$device_id."'");
			}
			else{
				$updateAccess=mysqlInsert('login_sessions',$arrFields2,$arrValues2);
			}
		
			$result_family = mysqlSelect("*","user_family_member","user_id ='".$result_referrring[0]['login_id']."'","member_id ASC","","","");
			$user_address = mysqlSelect("*","user_address","user_id ='".$result_referrring[0]['login_id']."'","","","","");
			$member_medical_background = mysqlSelect("*","user_family_general_health","user_id ='".$result_referrring[0]['login_id']."'","","","","");
			// $reports_details = mysqlSelect("a.id as report_id, a.member_id as member_id, a.title as title, a.description as description, a.timeStampNum as timeStampNum, a.created_date as created_date, b.id as attachment_id, b.attachment_name as attachment_name","health_app_healthfile_reports as a inner join health_app_healthfile_report_attachments as b on b.report_id = a.id","a.login_id ='".$result_referrring[0]['login_id']."'","a.id DESC","","","");
		
			$languages = mysqlSelect("*","languages","","","","","");
			$result_wallet = mysqlSelect("*","health_app_wallet","login_id ='".$result_referrring[0]['login_id']."'","id DESC","","","1");
			
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
			
			$success_register = array('result' => "success", 'status' => '1', 'accessToken' => $accessToken, "user_details" => $result_referrring, "family_details" => $result_family, "member_medical_background" => $member_medical_background, 'reports_details' => $reports_details, "user_address" => $user_address, "languages" => $languages, "result_wallet" => $result_wallet, "result_countries" => $getCountries, "profile_percentage_status" => $profile_percentage_status, "corporate_employee_id" => $corporate_employee_id, 'message' => "Logged In Successfully.", 'err_msg' => '');
			echo json_encode($success_register);
		}
		else{
			$success_register = array('result' => "success", 'status' => '0', 'message' => "Email ID and password doesn't match !!!", 'err_msg' => '');
			echo json_encode($success_register);
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

//LOGIN
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_email = $_POST['user_email'];
		$user_password = $_POST['user_password'];

		$result_referrring = mysqlSelect('*','login_user',"sub_email='".$user_email."' AND passwd='".md5($user_password)."'","","","","");
		if($result_referrring==true){
			
			//Logic for creating accessToken
			$accessToken = hmac($result[0]['login_id'], $Cur_Date);
			
			// Update access Token in multiple sessions
			$check_session = mysqlSelect("*","login_sessions","login_id='".$result[0]['login_id']."' and device_id='".$device_id."' and created_date='".$Cur_Date."'","","","","");
			$arrFields2 = array();
			$arrValues2 = array();
			$arrFields2[] = 'login_id';
			$arrValues2[] = $result[0]['login_id'];
			$arrFields2[] = 'device_id';
			$arrValues2[] = $device_id;
			$arrFields2[] = 'accessToken';
			$arrValues2[] = $accessToken;
			$arrFields2[] = 'created_date';
			$arrValues2[] = $Cur_Date;
			if(COUNT($check_session)>0){	
				 $updateAccess=mysqlUpdate('login_sessions',$arrFields2,$arrValues2,"login_id='".$result[0]['login_id']."' and device_id='".$device_id."'");
			}
			else{
				$updateAccess=mysqlInsert('login_sessions',$arrFields2,$arrValues2);
			}
		
			$result_family = mysqlSelect("*","user_family_member","user_id ='".$result_referrring[0]['login_id']."'","member_id ASC","","","");
			$user_address = mysqlSelect("*","user_address","user_id ='".$result_referrring[0]['login_id']."'","","","","");
			$member_medical_background = mysqlSelect("*","user_family_general_health","user_id ='".$result_referrring[0]['login_id']."'","","","","");
			// $reports_details = mysqlSelect("a.id as report_id, a.member_id as member_id, a.title as title, a.description as description, a.timeStampNum as timeStampNum, a.created_date as created_date, b.id as attachment_id, b.attachment_name as attachment_name","health_app_healthfile_reports as a inner join health_app_healthfile_report_attachments as b on b.report_id = a.id","a.login_id ='".$result_referrring[0]['login_id']."'","a.id DESC","","","");
		
			$languages = mysqlSelect("*","languages","","","","","");
			$result_wallet = mysqlSelect("*","health_app_wallet","login_id ='".$result_referrring[0]['login_id']."'","id DESC","","","1");
			
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
			
			$success_register = array('result' => "success", 'status' => '1', 'accessToken' => $accessToken, "user_details" => $result_referrring, "family_details" => $result_family, "member_medical_background" => $member_medical_background, 'reports_details' => $reports_details, "user_address" => $user_address, "languages" => $languages, "result_wallet" => $result_wallet, "result_countries" => $getCountries, "profile_percentage_status" => $profile_percentage_status, "corporate_employee_id" => $corporate_employee_id, 'message' => "Logged In Successfully.", 'err_msg' => '');
			echo json_encode($success_register);
	  }
	  else{
			$success_register = array('result' => "success", 'status' => '0', 'message' => "Email ID and password doesn't match !!!", 'err_msg' => '');
			echo json_encode($success_register);
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
