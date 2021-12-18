<?php 
ob_start();
 error_reporting(0);
 session_start();


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

//require_once("../classes/querymaker.class.php");
require_once("../classes/querymaker.class.php");

ob_start();
include('send_mail_function.php');
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

//Add Family Member
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$login_id = $user_id;
		$member_name = $_POST['member_name'];
		$gender = $_POST['member_gender'];
		$relationship = $_POST['user_relationship'];
		$dob = date('Y-m-d',strtotime($_POST['member_dob']));
		$age = $_POST['age'];
		
			$arrFields_family[] = 'member_name';
			$arrValues_family[] = $member_name;
			$arrFields_family[] = 'member_type';
			$arrValues_family[] = "secondary";
			$arrFields_family[] = 'gender';
			$arrValues_family[] = $gender;
			$arrFields_family[] = 'relationship';
			$arrValues_family[] = $relationship;
			$arrFields_family[] = 'dob';
			$arrValues_family[] = $dob;
			$arrFields_family[] = 'age';
			$arrValues_family[] = $age;
			$arrFields_family[] = 'user_id';
			$arrValues_family[] = $login_id;
			$patientNote=mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);
					
			
     
		  $result_family = mysqlSelect("*","user_family_member","user_id ='".$login_id."'","","","","");
			
		  $success_register = array('result' => "success","family_details" => $result_family);
		  echo json_encode($success_register);
		
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
