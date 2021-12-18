<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


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

// Add New Delivery Address Update
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_id = $user_id;
		$txtAddress = $_POST['address'];
		$txtCity = $_POST['city'];		
		$txtPincode = $_POST['pincode'];
		$txtState = $_POST['state'];
		$txtCountry = $_POST['country'];
		
			$arrFields_address = array();
			$arrValues_address = array();
			
			$arrFields_address[] = 'user_id';
			$arrValues_address[] = $user_id;
			$arrFields_address[] = 'city';
			$arrValues_address[] = $txtCity;
			$arrFields_address[] = 'state';
			$arrValues_address[] = $txtState;
			$arrFields_address[] = 'country';
			$arrValues_address[] = $txtCountry;
			$arrFields_address[] = 'address';
			$arrValues_address[] = $txtAddress;
			$arrFields_address[] = 'pincode';
			$arrValues_address[] = $txtPincode;
			$patientNote=mysqlInsert('user_address',$arrFields_address,$arrValues_address);
			
			$user_address = mysqlSelect("*","user_address","user_id ='".$user_id."'","address_id DESC","","","");
			
			$success_register = array('result' => "success","user_address" => $user_address, 'message' => "Address Added Successfully.", 'err_msg' => '');
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
