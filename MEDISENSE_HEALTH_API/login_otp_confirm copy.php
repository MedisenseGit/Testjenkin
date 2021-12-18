<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

include('../MEDISENSE_HEALTH_APP/send_mail_function.php');
include("../MEDISENSE_HEALTH_APP/send_text_message.php");

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

if(HEALTH_API_KEY == $data ->api_key && isset($data ->memberid) && isset($data ->userotp))
{
		
	$txtMember = $data ->memberid;
	$txtOTP = $data ->userotp;
	

	$result_referrring = $objQuery->mysqlSelect("*","login_user","login_id ='".$txtMember."' and otp='".$txtOTP."'","","","","");
	if($result_referrring==true){

			$arrFields[] = 'login_status';
			$arrValues[] = "1";
			$arrFields[] = 'verification_status';
			$arrValues[] = "1";
			$arrFields[] = 'login_permission';
			$arrValues[] = "1";
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
			
		
		$result_family = $objQuery->mysqlSelect("*","user_family_member","user_id ='".$result_referrring[0]['login_id']."'","","","","");
		$user_address = $objQuery->mysqlSelect("*","user_address","user_id ='".$result_referrring[0]['login_id']."'","","","","");
		
		$user_consultation = $objQuery->mysqlSelect("patient_id","doc_my_patient","patient_mob ='".$result_referrring[0]['sub_contact']."'","","","","");
		
		if(COUNT($user_consultation) > 0) {
			$success_register = array('result_otp' => "success","user_name" => $result_referrring[0]['sub_name'],"user_id" => $result_referrring[0]['login_id'],"user_mobile" => $result_referrring[0]['sub_contact'],"user_consult" => '1');
			echo json_encode($success_register);
		}
		else {
			$success_register = array('result_otp' => "success","user_name" => $result_referrring[0]['sub_name'],"user_id" => $result_referrring[0]['login_id'],"user_mobile" => $result_referrring[0]['sub_contact'],"user_consult" => '0');
			echo json_encode($success_register);
		}
		
		//$success_register = array('result_otp' => "success","user_name" => $result_referrring[0]['sub_name'],"user_id" => $result_referrring[0]['login_id'],"user_mobile" => $result_referrring[0]['sub_contact']);
		//echo json_encode($success_register);
	}
	else {
			
			$success_register["result_otp"] = "false";
			echo(json_encode($success_register));
		}
}
else{
	$response["status"] = "Not Allowed";
	echo json_encode($response);
}
?>


