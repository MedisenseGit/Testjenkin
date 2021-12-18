<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");




$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) {
			$user_id = $doctor_id;
			$event_id = $_POST['eventid'];
			$listing_type = $_POST['listing_type'];
			
			$arrFields1 = array();
			$arrValues1 = array();
			 
			$current_date = date('Y-m-d h:i:s');
			
			
    		$userType = "2"; //For Hospital Doctors
   	
			$arrFields = array();
			$arrValues = array();

			$arrFields[]= 'category_id';
			$arrValues[]= $event_id;
			$arrFields[]= 'category_type';
			$arrValues[]= $listing_type;
			$arrFields[]= 'likes';
			$arrValues[]= $user_id;
			$arrFields[]= 'user_type';
			$arrValues[]= $userType;
			$arrFields[]= 'like_date';
			$arrValues[]= time();
		
			$addLike=mysqlInsert('home_post_like',$arrFields,$arrValues);
			
			$success = array('status' => "true","Event_Details" => $addLike);     
			echo json_encode($success);

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