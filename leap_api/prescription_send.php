<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('send_mail_function.php');
include('send_text_message.php');
include('../premium/short_url.php');

// Send  Prescription SMS/EMAIL
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$send_type = $_POST['send_type'];		// 1- EMR Only , 2- Prescription Only
	$patient_id = $_POST['patient_id'];
	$doctor_name = $_POST['doctor_name'];
	$episode_id = $_POST['episode_id'];
	
	if($login_type == 1) {  // Premium LOgin
	
		$check_patient = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."'","","","","");
		$link = "/premium/print-emr/mobile_emr.php?pid=" .md5($patient_id). "&episode=".md5($episode_id)."&s=2";
		$getUrl= get_shorturl($link);
		
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
		$success = array('result' => "false");
		echo json_encode($success);
	} 
		

	
}


?>