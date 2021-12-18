<?php
ob_start();
error_reporting(0);
session_start();


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

require_once("../classes/querymaker.class.php");

ob_start();


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Change Password
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$login_id = $user_id;
		$txtCurrent = $_POST['currentPassword'];
		$txtNew = $_POST['newPassword'];
		
		$result_login = mysqlSelect('*','login_user',"login_id='".$login_id."' AND passwd='".md5($txtCurrent)."'","","","","");
		if(!empty($result_login)){
			
			$arrFields_login = array();
			$arrValues_login = array();
			
			$arrFields_login[] = 'passwd';
			$arrValues_login[] = md5($txtNew);	
			$updateLoginUser=mysqlUpdate('login_user',$arrFields_login,$arrValues_login,"login_id='".$result_login[0]['login_id']."'");
			
			
			$result = array('result' => "success", 'status' => '1', 'message' => "Password Updated Successfully.", 'err_msg' => '');
			echo json_encode($result);
		}
		else {
			$result = array('result' => "success", 'status' => '0', 'message' => "Inavlid User/Password !!! \nFailed to update new password !!!", 'err_msg' => '');
			echo json_encode($result);
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