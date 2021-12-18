<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


include('send_mail_function.php');
include('send_text_message.php');
include('../premium/short_url.php');


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
		$admin_id = $doctor_id;
		$send_type = $_POST['send_type'];		// 1- EMR Only , 2- Prescription Only
		$patient_id = $_POST['patient_id'];
		$doctor_name = $_POST['doctor_name'];
		$episode_id = $_POST['episode_id'];

		$check_patient = mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."'","","","","");
		// $link = "/premium/print-emr/mobile_emr.php?pid=" .md5($patient_id). "&episode=".md5($episode_id)."&s=2";
		$link = "https://medisensemd.com/premium/print-emr/mobile_emr.php?pid=" .md5($patient_id). "&episode=".md5($episode_id)."&s=2";
		//$getUrl= get_shorturl($link);
		$getUrl = $link;		
		
		if($send_type == 1) {		// EMR Only
			if(!empty($check_patient[0]['patient_mob'])) {
				$mobile = $check_patient[0]['patient_mob'];
				$msg = "This is your digitalized EMR sent by Dr.".$doctor_name."\nPlease click following Link ".$getUrl." \n- Thank you, \n".$doctor_name."";
				send_msg($mobile,$msg);
			}
			
			if(!empty( $check_patient[0]['patient_email'])){
						$url_page = 'share_email_prescription.php';
						$url = rawurlencode($url_page);
						$url .= "?patemail=".urlencode($check_patient[0]['patient_email']);
						$url .= "&docname=".urlencode($doctor_name);
						$url .= "&shortUrl=".urlencode($getUrl);
						send_mail($url);
			}
		}
		else if($send_type == 2) {
			$link = "/premium/print-emr/mobile_emr.php?pid=" .md5($patient_id). "&episode=".md5($episode_id)."&s=3";
			$getUrl= get_shorturl($link);
		
			if(!empty($check_patient[0]['patient_mob'])) {
				$mobile = $check_patient[0]['patient_mob'];
				$msg = "This is your digitalized Prescriptions sent by Dr.".$doctor_name."\nPlease click following Link ".$getUrl." \n- Thank you, \n".$doctor_name."";
				send_msg($mobile,$msg);
			}
			
			if(!empty( $check_patient[0]['patient_email'])){
						$url_page = 'share_email_prescription.php';
						$url = rawurlencode($url_page);
						$url .= "?patemail=".urlencode($check_patient[0]['patient_email']);
						$url .= "&docname=".urlencode($doctor_name);
						$url .= "&shortUrl=".urlencode($getUrl);
						send_mail($url);
			}
		}
		
		$success = array('result' => "true");
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