<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


// Get Countries Lists
	$getCountries = mysqlSelect('country_id, country_name','countries',"visibility = '1'","country_id ASC","","","");
	
	$success_country = array('result' => "success", 'countries' => $getCountries, 'country_status' => '1','message' => "Countries Lists !!!", 'err_msg' => '');
	echo json_encode($success_country);
	

/*
$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

//Get Countries Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$getCountries = mysqlSelect('country_id, country_name','countries',"visibility = '1'","country_id ASC","","","");
	
		$success_country = array('result' => "success", 'countries' => $getCountries, 'country_status' => '1','message' => "Countries Lists !!!", 'err_msg' => '');
		echo json_encode($success_country);
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
*/

?>
