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

$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	
	if($finalHash == $hashKey) {
		$admin_id = $doctor_id;

		$get_pharma = mysqlSelect('a.pharma_id as pharma_id, a.pharma_name as pharma_name, a.pharma_city as pharma_city, a.pharma_state as pharma_state, a.pharma_country as pharma_country, a.phrama_contact_person as phrama_contact_person, a.pharma_contact_num as pharma_contact_num, a.pharma_email as pharma_email, a.pharma_password as pharma_password','pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id',"b.doc_id='".$admin_id."'","a.pharma_name ASC","","","");
		
		$success = array('status' => "true","pharma_details"=>$get_pharma);
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