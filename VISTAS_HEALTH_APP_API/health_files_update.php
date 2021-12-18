<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//Health Files - Medical Background Updates
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$login_id = $user_id;
		$member_id = $_POST['member_id'];
		$member_name = $_POST['member_name'];
		$member_gender = $_POST['member_gender'];
		$member_age = $_POST['member_age'];
		$member_height = $_POST['member_height'];
		$member_weight = $_POST['member_weight'];
		$member_blood_group = $_POST['member_blood_group'];
		$member_bp = $_POST['member_bp'];
		$member_thyroid = $_POST['member_thyroid'];
		$member_hypertension = $_POST['member_hypertension'];
		$member_asthama = $_POST['member_asthama'];
		$member_cholestrol = $_POST['member_cholestrol'];
		$member_epilepsy = $_POST['member_epilepsy'];
		$member_diabetic = $_POST['member_diabetic'];
		$member_allergies = $_POST['member_allergies'];
		$member_smoking = $_POST['member_smoking'];
		$member_alcohol = $_POST['member_alcohol'];
		
		$arrFields_Member = array();
		$arrValues_Member = array();
		
		$arrFields_Member[] = 'member_name';
		$arrValues_Member[] = $member_name;
		$arrFields_Member[] = 'gender';
		$arrValues_Member[] = $member_gender;
		$arrFields_Member[] = 'age';
		$arrValues_Member[] = $member_age;
		$arrFields_Member[] = 'height';
		$arrValues_Member[] = $member_height;	
		$arrFields_Member[] = 'weight';
		$arrValues_Member[] = $member_weight;
		$arrFields_Member[] = 'blood_group';
		$arrValues_Member[] = $member_blood_group;
	
		
		$get_Members = mysqlSelect('*','user_family_member',"member_id ='".$member_id."'","","","","");
		if($get_Members==true){
			$updateMember=mysqlUpdate('user_family_member',$arrFields_Member,$arrValues_Member,"member_id='".$member_id."'");		
		}
		
		$get_MedicalBackground = mysqlSelect('*','user_family_general_health',"member_id ='".$member_id."'","","","","");
			
		if($get_MedicalBackground==true){
			
			$arrFields_MedBackUpdate = array();
			$arrValues_MedBackUpdate = array();
		
			$arrFields_MedBackUpdate[] = 'bp';
			$arrValues_MedBackUpdate[] = $member_bp;
			$arrFields_MedBackUpdate[] = 'hypertension';
			$arrValues_MedBackUpdate[] = $member_hypertension;
			$arrFields_MedBackUpdate[] = 'cholesterol';
			$arrValues_MedBackUpdate[] = $member_cholestrol;
			$arrFields_MedBackUpdate[] = 'diabetic';
			$arrValues_MedBackUpdate[] = $member_diabetic;
			$arrFields_MedBackUpdate[] = 'thyroid';
			$arrValues_MedBackUpdate[] = $member_thyroid;
			$arrFields_MedBackUpdate[] = 'asthama';
			$arrValues_MedBackUpdate[] = $member_asthama;
			$arrFields_MedBackUpdate[] = 'epilepsy';
			$arrValues_MedBackUpdate[] = $member_epilepsy;
			$arrFields_MedBackUpdate[] = 'allergies_any';
			$arrValues_MedBackUpdate[] = $member_allergies;
			$arrFields_MedBackUpdate[] = 'smoking';
			$arrValues_MedBackUpdate[] = $member_smoking;
			$arrFields_MedBackUpdate[] = 'alcohol';
			$arrValues_MedBackUpdate[] = $member_alcohol;
			$arrFields_MedBackUpdate[] = 'created_date';
			$arrValues_MedBackUpdate[] = $curDate;
		
			$updateMedBackground=mysqlUpdate('user_family_general_health',$arrFields_MedBackUpdate,$arrValues_MedBackUpdate,"member_id='".$member_id."'");		
		}
		else {
			$arrFields_MedBack = array();
			$arrValues_MedBack = array();
		
			$arrFields_MedBack[] = 'member_id';
			$arrValues_MedBack[] = $member_id;
			$arrFields_MedBack[] = 'user_id';
			$arrValues_MedBack[] = $login_id;
			$arrFields_MedBack[] = 'bp';
			$arrValues_MedBack[] = $member_bp;
			$arrFields_MedBack[] = 'hypertension';
			$arrValues_MedBack[] = $member_hypertension;
			$arrFields_MedBack[] = 'cholesterol';
			$arrValues_MedBack[] = $member_cholestrol;
			$arrFields_MedBack[] = 'diabetic';
			$arrValues_MedBack[] = $member_diabetic;
			$arrFields_MedBack[] = 'thyroid';
			$arrValues_MedBack[] = $member_thyroid;
			$arrFields_MedBack[] = 'asthama';
			$arrValues_MedBack[] = $member_asthama;
			$arrFields_MedBack[] = 'epilepsy';
			$arrValues_MedBack[] = $member_epilepsy;
			$arrFields_MedBack[] = 'allergies_any';
			$arrValues_MedBack[] = $member_allergies;
			$arrFields_MedBack[] = 'smoking';
			$arrValues_MedBack[] = $member_smoking;
			$arrFields_MedBack[] = 'alcohol';
			$arrValues_MedBack[] = $member_alcohol;
			$arrFields_MedBack[] = 'created_date';
			$arrValues_MedBack[] = $curDate;
			$insertMedBackground = mysqlInsert('user_family_general_health',$arrFields_MedBack,$arrValues_MedBack);
		}
		
	/*	// Update Profie Percentage Number Starts
		$arrFields_profile_percent = array();
		$arrValues_profile_percent = array();
		
		$arrFields_profile_percent[] = 'profile_percentage';
		$arrValues_profile_percent[] = '60';
		$updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$login_id."'");
		
		$login_details = mysqlSelect('*','login_user',"login_id='".$login_id."'","","","","");
		$profile_percentage_status = $login_details[0]['profile_percentage'];
		// Update Profie Percentage Number Ends */
		
		// Update Profie Percentage Number Starts
		$getCurrentStatus = mysqlSelect('*','login_user',"login_id='".$login_id."'","","","",""); 
		$currentStatus = $getCurrentStatus[0]['profile_percentage'];
		if($currentStatus < 60) {
			// Update Profiel Percentage Number
			$arrFields_profile_percent = array();
			$arrValues_profile_percent = array();
			
			$arrFields_profile_percent[] = 'profile_percentage';
			$arrValues_profile_percent[] = '60';
			$updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$login_id."'");
		}
		
		$login_details = mysqlSelect('*','login_user',"login_id='".$login_id."'","","","","");
		$profile_percentage_status = $login_details[0]['profile_percentage'];
		// Update Profie Percentage Number Ends
		
		$result_family = mysqlSelect("*","user_family_member","user_id ='".$login_id."'","member_id ASC","","","");
		$result_medBackground = mysqlSelect("*","user_family_general_health","user_id ='".$login_id."'","id ASC","","","");
					
		$success_healthfile = array('result' => "success", "family_details"=>$result_family, "member_medical_background"=>$result_medBackground, "profile_percentage_status" => $profile_percentage_status, 'message' => "Updated your health files successfully !!!", 'err_msg' => '');
		echo json_encode($success_healthfile);
		
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
