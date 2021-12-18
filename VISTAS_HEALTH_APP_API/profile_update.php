<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");


include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Profile Update
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$user_id = $user_id;
		$user_name = $_POST['user_name'];
		$user_mobile = $_POST['contact_num'];		// cannot update mobile as its unique id (not used)
		$user_email = $_POST['user_email'];
		$user_gender = $_POST['user_gender'];
		$user_age = $_POST['user_age'];
		$user_password = $_POST['user_password'];
		$user_image = basename($_FILES['profileImage']['name']);
		$user_address = $_POST['profileAddress'];
		$user_city = $_POST['profileCity'];
		$user_state = $_POST['profileState'];
		$user_country = $_POST['profileCountry'];
		$user_pincode = $_POST['profilePincode'];
		$user_emergency_name = $_POST['profileEmergencyName'];
		$user_emergency_contact_num = $_POST['profileEmergencyMobile'];
		$user_emergency_email = $_POST['profileEmergencyEmail'];

		$result_login = mysqlSelect('*','login_user',"login_id='".$user_id."'","","","","");
		if(!empty($result_login)){
			
			$arrFields_login = array();
			$arrValues_login = array();
			
			$arrFields_login[] = 'sub_name';
			$arrValues_login[] = $user_name;
			$arrFields_login[] = 'sub_email';
			$arrValues_login[] = $user_email;
			$arrFields_login[] = 'sub_age';
			$arrValues_login[] = $user_age;
			$arrFields_login[] = 'sub_gender';
			$arrValues_login[] = $user_gender;				// 1- Male, 2-Female, 3-Other, 0-Not mentioned
			$arrFields_login[] = 'sub_address';
			$arrValues_login[] = $user_address;
			$arrFields_login[] = 'sub_city';
			$arrValues_login[] = $user_city;
			$arrFields_login[] = 'sub_state';
			$arrValues_login[] = $user_state;
			$arrFields_login[] = 'sub_country';
			$arrValues_login[] = $user_country;
			$arrFields_login[] = 'sub_pincode';
			$arrValues_login[] = $user_pincode;
			$arrFields_login[] = 'emergency_contact_name';
			$arrValues_login[] = $user_emergency_name;
			$arrFields_login[] = 'emergency_contact_number';
			$arrValues_login[] = $user_emergency_contact_num;
			$arrFields_login[] = 'emergency_contact_email';
			$arrValues_login[] = $user_emergency_email;
			
			
			if(!empty($user_password)) {
				$arrFields_login[] = 'passwd';
				$arrValues_login[] = md5($user_password);	
			}
			$updateLoginUser=mysqlUpdate('login_user',$arrFields_login,$arrValues_login,"login_id='".$result_login[0]['login_id']."'");
			
			$arrFields_member = array();
			$arrValues_member = array();
			
			$arrFields_member[] = 'member_name';
			$arrValues_member[] = $user_name;
			$arrFields_member[] = 'gender';
			$arrValues_member[] = $user_gender;				// 1- Male, 2-Female, 3-Other, 0-Not mentioned
			if(!empty($user_image)){
				$arrFields_member[] = 'member_photo';
				$arrValues_member[] = $user_image;
			}
		
			$updateMember=mysqlUpdate('user_family_member',$arrFields_member,$arrValues_member,"user_id='".$result_login[0]['login_id']."' AND member_type = 'primary'");
			
			$result_member = mysqlSelect("*","user_family_member","user_id ='".$result_login[0]['login_id']."' AND member_type = 'primary'","","","","");
			$member_id = $result_member[0]['member_id'];
			
			/* Uploading image file */ 
			if(basename($_FILES['profileImage']['name']!==""))
			{ 
				$folder_name	=	"Medisense-Patient-Care/memberPics";
				$sub_folder		=	$member_id;
				$filename		=	$_FILES['profileImage']['name'];
				$file_url		=	$_FILES['profileImage']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

				// $uploaddirectory = realpath("../Medisense-Patient-Care/memberPics");
				// mkdir("../Medisense-Patient-Care/memberPics/". "/" . $member_id, 0777);
				// $uploaddir = $uploaddirectory."/".$member_id;
				// $dotpos = strpos($_FILES['profileImage']['name'], '.');
				// $photo = $user_image;
				// $uploadfile = $uploaddir . "/" . $photo;			
					
								
				// /* Moving uploaded file from temporary folder to desired folder. */
				// if(move_uploaded_file ($_FILES['profileImage']['tmp_name'], $uploadfile)) {
				//	echo "File uploaded.";
					// } else {
				//	echo "File cannot be uploaded";
				// }
			}		
		
			$result_family = mysqlSelect("*","user_family_member","user_id ='".$result_login[0]['login_id']."'","member_id ASC","","","");
			
		/*	// Update Profiel Percentage Number
			$arrFields_profile_percent = array();
			$arrValues_profile_percent = array();
			
			$arrFields_profile_percent[] = 'profile_percentage';
			$arrValues_profile_percent[] = '40';
			$updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$user_id."'");
			
			//Extra Added for Emergency starts
			$check_emergency = mysqlSelect('*','login_user',"login_id='".$user_id."'","","","","");
			if(empty($check_emergency[0]['emergency_contact_name']) || empty($check_emergency[0]['emergency_contact_number']) || empty($check_emergency[0]['emergency_contact_email'])) {
				$profile_percentage_status = $check_emergency[0]['profile_percentage'];
			}
			else {
				$profile_percentage_status = '100';
				$arrFields_profile_percent[] = 'profile_percentage';
				$arrValues_profile_percent[] = $profile_percentage_status;
				$updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$login_id."'");
		
			}
			//Extra Added for Emergency Ends
			
			$login_details = mysqlSelect('*','login_user',"login_id='".$user_id."'","","","","");
			$profile_percentage_status = $login_details[0]['profile_percentage'];  */
			
			// Update Profie Percentage Number Starts
			$getCurrentStatus = mysqlSelect('*','login_user',"login_id='".$user_id."'","","","",""); 
			$currentStatus = $getCurrentStatus[0]['profile_percentage'];
			if($currentStatus < 40) {
				// Update Profiel Percentage Number
				$arrFields_profile_percent = array();
				$arrValues_profile_percent = array();
				
				$arrFields_profile_percent[] = 'profile_percentage';
				$arrValues_profile_percent[] = '40';
				$updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$user_id."'");
			}
			
			if($currentStatus == 80 && (!empty($getCurrentStatus[0]['emergency_contact_name']) && !empty($getCurrentStatus[0]['emergency_contact_number']) && !empty($getCurrentStatus[0]['emergency_contact_email']))) {
				$arrFields_profile_percent = array();
				$arrValues_profile_percent = array();
				
				$arrFields_profile_percent[] = 'profile_percentage';
				$arrValues_profile_percent[] = '100';
				$updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$user_id."'");
			
			}
			
			$login_details = mysqlSelect('*','login_user',"login_id='".$user_id."'","","","","");
			$profile_percentage_status = $login_details[0]['profile_percentage'];
			// Update Profie Percentage Number Ends
			
			$success_register = array('result' => "success", 'status' => '1', "login_details" => $login_details, "family_details" => $result_family, "profile_percentage_status" => $profile_percentage_status, 'message' => "Profile Updated Successfully.", 'err_msg' => '');
			echo json_encode($success_register);
	  }
	  else{
			$success_register = array('result' => "success", 'status' => '0', 'message' => "Inavlid User !!!", 'err_msg' => '');
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

?>
