<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//Add Family Member
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_name = $_POST['user_name'];
		$user_age = $_POST['user_age'];
		$user_gender = $_POST['user_gender'];
		$user_height = $_POST['user_height'];
		$user_weight = $_POST['user_weight'];
		$user_blood_group = addslashes($_POST['user_blood_group']);
		$user_relationship = $_POST['user_relationship'];
		$login_id = $user_id;
		
			$arrFields_family[] = 'member_name';
			$arrValues_family[] = $user_name;
			$arrFields_family[] = 'member_type';
			$arrValues_family[] = "secondary";
			$arrFields_family[] = 'gender';
			$arrValues_family[] = $user_gender;
			$arrFields_family[] = 'relationship';
			$arrValues_family[] = $user_relationship;
			$arrFields_family[] = 'age';
			$arrValues_family[] = $user_age;
			$arrFields_family[] = 'user_id';
			$arrValues_family[] = $login_id;
			$arrFields_family[] = 'height';
			$arrValues_family[] = $user_height;
			$arrFields_family[] = 'weight';
			$arrValues_family[] = $user_weight;
			$arrFields_family[] = 'blood_group';
			$arrValues_family[] = $user_blood_group;
			
			$patientNote=mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);
			
			$result_family = mysqlSelect("*","user_family_member","user_id ='".$login_id."'","member_id ASC","","","");
			
						
			$success_member = array('result' => "success", 'family_details' => $result_family, 'message' => "Family member added successfully !!!", 'err_msg' => '');
			echo json_encode($success_member);
		
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
