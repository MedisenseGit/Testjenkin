<?php 
ob_start();
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

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

if(HEALTH_API_KEY == $data ->api_key &&  isset($data ->user_email))
{
	$user_name = $data ->user_name;
	$user_email = $data ->user_email;
	/*$mobile_num = $data ->contact_num;
	$user_country = $data ->user_country;
	$user_qid = $data ->user_qid;
	$user_state=$data ->user_state;
	$user_city=$data ->user_city;
	$user_address=$data ->user_address;
	$user_pincode=$data ->user_pincode;
	$country_code=$data ->country_code;
	$pswd=$data ->password;*/

	$check_referring = $objQuery->mysqlSelect('*','login_user'," sub_email='".$user_email."'","","","","");

	if(empty($check_referring)){
		$otp = randomOtp();
		$password = randomPassword();
		$arrFields_user[] = 'sub_name';
		$arrValues_user[] = $user_name;
		$arrFields_user[] = 'sub_email';
		$arrValues_user[] = $user_email;
		
		$arrFields_user[] = 'login_status';
		$arrValues_user[] = '0';
		$arrFields_user[] = 'verification_status';
		$arrValues_user[] = '0';
		$arrFields_user[] = 'login_permission';
		$arrValues_user[] = '0';

		$usercreate=$objQuery->mysqlInsert('login_user',$arrFields_user,$arrValues_user);
		$id = mysql_insert_id();
		$member_id = $id;
		
		$arrFields_src[] = 'source_name';
		$arrValues_src[] = $user_name;
		$arrFields_src[] = 'partner_id';
		$arrValues_src[] = $id;
		$arrFields_src[] = 'src_type';
		$arrValues_src[] = '1';
		
		$userSrccreate=$objQuery->mysqlInsert('source_list',$arrFields_src,$arrValues_src);
		
		
		$arrFields_member[] = 'member_name';
		$arrValues_member[] = $user_name;
		$arrFields_member[] = 'member_type';
		$arrValues_member[] = 'primary';
		$arrFields_member[] = 'user_id';
		$arrValues_member[] = $id;
		
		$userMember=$objQuery->mysqlInsert('user_family_member',$arrFields_member,$arrValues_member);

		$userotp="Your OTP for Medisense Healthcare App is ".$otp." . \nThanks Medisense";
		send_msg($mobile_num,$userotp);
		
		// Send OTP via Email
		if(!empty($user_email)) {
		
			$url_page = 'health_otp_request.php';
			$url = rawurlencode($url_page);
			$url .= "?docname=".urlencode($user_name);
			$url .= "&otp=".urlencode($otp);
			$url .= "&reqmail=".urlencode($user_email);
					
			send_mail($url);
		}

		$success_register = array('status' => '0','message' => "Registered Successfully.", 'user_name' =>$check_referring[0]['sub_name'], 'user_email' =>$check_referring[0]['sub_email'] ,'login_id' =>$check_referring[0]['login_id']);
		echo json_encode($success_register);
	}
	else{
	$success_register = array('status' => '0','message' => "login Successfully.", 'user_name' =>$check_referring[0]['sub_name'], 'user_email' =>$check_referring[0]['sub_email'] ,'login_id' =>$check_referring[0]['login_id']);
		echo json_encode($success_register);
	}
	
}
else{
    $success_register = array('status' => '2' ,'message' => "You have not permitted to access the account !!!");
    echo json_encode($success_register);
}

?>


