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

// Registration
		$user_name = $_POST['user_name'];
		$user_email = $_POST['user_email'];
		$mobile_num = $_POST['contact_num'];
		$user_qid = $_POST['user_qid'];
		$user_country = $_POST['user_country'];
		$user_state = $_POST['user_state'];
		$user_city = $_POST['user_city'];
		$user_pincode = $_POST['user_pincode'];
		$user_address = $_POST['user_address'];
		$user_password = $_POST['user_password'];
		$user_country_id = $_POST['user_country_id'];

		$check_referring = mysqlSelect('*','login_user',"sub_contact='".$mobile_num."' OR sub_email='".$user_email."'","","","","");

		if(empty($check_referring)){
			$otp = randomOtp();
			$password = randomPassword();
			$arrFields_user[] = 'sub_name';
			$arrValues_user[] = $user_name;
			$arrFields_user[] = 'sub_contact';
			$arrValues_user[] = $mobile_num;
			$arrFields_user[] = 'sub_email';
			$arrValues_user[] = $user_email;
			$arrFields_user[] = 'otp';
			$arrValues_user[] = $otp;
			$arrFields_user[] = 'civil_id';
			$arrValues_user[] = $user_qid;
			$arrFields_user[] = 'sub_country';
			$arrValues_user[] = $user_country;
			$arrFields_user[] = 'passwd';
			$arrValues_user[] = md5($user_password);
			$arrFields_user[] = 'login_status';
			$arrValues_user[] = '0';
			$arrFields_user[] = 'verification_status';
			$arrValues_user[] = '0';
			$arrFields_user[] = 'login_permission';
			$arrValues_user[] = '0';
			
			$arrFields_user[] = 'sub_address';
			$arrValues_user[] = $user_address;
			$arrFields_user[] = 'sub_city';
			$arrValues_user[] = $user_city;
			$arrFields_user[] = 'sub_state';
			$arrValues_user[] = $user_state;
			$arrFields_user[] = 'sub_pincode';
			$arrValues_user[] = $user_pincode;
			$arrFields_user[] = 'country_id';
			$arrValues_user[] = $user_country_id;

			$usercreate=mysqlInsert('login_user',$arrFields_user,$arrValues_user);
			$id = $usercreate;
			
			$arrFields_src[] = 'source_name';
			$arrValues_src[] = $user_name;
			$arrFields_src[] = 'partner_id';
			$arrValues_src[] = $id;
			$arrFields_src[] = 'src_type';
			$arrValues_src[] = '1';
			
			$userSrccreate=mysqlInsert('source_list',$arrFields_src,$arrValues_src);
			
			
			$arrFields_member[] = 'member_name';
			$arrValues_member[] = $user_name;
			$arrFields_member[] = 'member_type';
			$arrValues_member[] = 'primary';
			$arrFields_member[] = 'user_id';
			$arrValues_member[] = $id;
			
			$userMember=mysqlInsert('user_family_member',$arrFields_member,$arrValues_member);

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
		
			$success_register = array('result' => "success", 'status' => '1','message' => "Registered Successfully.", 'err_msg' => '');
			echo json_encode($success_register);
		}
		else
		{
			$success_register = array('result' => "success", 'status' => '0', 'message' => "User Email or Phone number already exists !!!", 'err_msg' => '');
			echo json_encode($success_register);
		}

?>
