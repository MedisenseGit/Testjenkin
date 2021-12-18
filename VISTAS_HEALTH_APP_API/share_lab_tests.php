<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");
include('../premium/short_url.php');

$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Share Lab Tests to Diagnostic Center
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		
		$login_id = $user_id;
		$patient_id = $_POST['patientID'];
		$episode_id = $_POST['episodeID'];
		$diagnostic_ID = '77';	// Default Nova Diagnostic Center
		$diagno_date_time = $Cur_Date;
		$referralType = '0';				// 0-Default EMR referrals, 1- Attachments referrals from patient app
		
		$patient_info = mysqlSelect('*','doc_my_patient',"patient_id='".$patient_id."'","","","","");
		/* $txtPatientName = $patient_info[0]['patient_name'];
		$txtAge = $patient_info[0]['patient_age'];
		$txtGender = $patient_info[0]['patient_gen'];
		$txtPhone = $patient_info[0]['patient_mob'];
		$txtEmail = $patient_info[0]['patient_email'];
		$txtAddress = $patient_info[0]['patient_addrs'];
		$txtCity = $patient_info[0]['patient_loc'];
		$txtState = $patient_info[0]['pat_state'];
		$txtCountry = $patient_info[0]['pat_country']; */
		
		$txtPatientName = $_POST['orderCustomerName'];
		$txtAge = $patient_info[0]['patient_age'];
		$txtGender = $patient_info[0]['patient_gen'];
		$txtPhone =  $_POST['orderCustomerMobile'];
		$txtEmail = $patient_info[0]['patient_email'];
		$txtAddress = $_POST['orderCustomerAddress'];
		$txtCity = $_POST['orderCustomerCity'];
		$txtState = $_POST['orderCustomerState'];
		$txtCountry = $_POST['orderCustomerCountry'];
		
		$txtPincode = $_POST['orderCustomerPincode'];
		$txtShippingAddress = $_POST['orderShippingAddress'];
		$txtShippingCity = $_POST['orderShippingCity'];
		$txtShippingState = $_POST['orderShippingState'];
		$txtShippingCountry = $_POST['orderShippingCountry'];
		$txtShippingPincode = $_POST['orderShippingPincode'];
		$txtCustomerMsg = $_POST['orderCustomerMsg'];
		
		$getDoc= mysqlSelect("*","referal","ref_id='".$patient_info[0]['doc_id']."'");
		$check_customer = mysqlSelect('*','diagnostic_customer',"patient_id='".$patient_id."' and patient_type='1'","","","","");
		$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$diagnostic_ID."'");
		
		if(empty($check_customer)){
			
				$arrFields_customer = array();
				$arrValues_customer = array();
				$arrFields_customer[] = 'diagnostic_id';
				$arrValues_customer[] = $diagnostic_ID;
				$arrFields_customer[] = 'patient_id';
				$arrValues_customer[] = $patient_id;
				$arrFields_customer[] = 'patient_type';
				$arrValues_customer[] = "1";
				$arrFields_customer[] = 'diagnostic_customer_name';
				$arrValues_customer[] = $txtPatientName;
				$arrFields_customer[] = 'diagnostic_cust_age';
				$arrValues_customer[] = $txtAge;
				$arrFields_customer[] = 'diagnostic_cust_gender';
				$arrValues_customer[] = $txtGender;
				$arrFields_customer[] = 'diagnostic_customer_phone';
				$arrValues_customer[] = $txtPhone;
				$arrFields_customer[] = 'diagnostic_customer_email';
				$arrValues_customer[] = $txtEmail;
				$arrFields_customer[] = 'diagnostic_cust_address';
				$arrValues_customer[] = $txtAddress;
				$arrFields_customer[] = 'diagnostic_cust_city';
				$arrValues_customer[] = $txtCity;
				$arrFields_customer[] = 'diagnostic_cust_state';
				$arrValues_customer[] = $txtState;
				$arrFields_customer[] = 'diagnostic_cust_country';
				$arrValues_customer[] = $txtCountry;
				
				$arrFields_customer[] = 'diagnostic_customer_pincode';
				$arrValues_customer[] = $txtPincode;
				$arrFields_customer[] = 'diagnostic_shipping_address';
				$arrValues_customer[] = $txtShippingAddress;
				$arrFields_customer[] = 'diagnostic_shipping_city';
				$arrValues_customer[] = $txtShippingCity;
				$arrFields_customer[] = 'diagnostic_shipping_state';
				$arrValues_customer[] = $txtShippingState;
				$arrFields_customer[] = 'diagnostic_shipping_country';
				$arrValues_customer[] = $txtShippingCountry;
				$arrFields_customer[] = 'diagnostic_shipping_pincode';
				$arrValues_customer[] = $txtShippingPincode;
				$arrFields_customer[] = 'login_id';
				$arrValues_customer[] = $login_id;
					
				$insert_diagnostic_customer = mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
				$customer_id = $insert_diagnostic_customer; //Get customer_id
				
				$arrFields_refer = array();
				$arrValues_refer = array();
				$arrFields_refer[] = 'patient_id';
				$arrValues_refer[] = $patient_id;
				$arrFields_refer[] = 'diagnostic_customer_id';
				$arrValues_refer[] = $customer_id;
				$arrFields_refer[] = 'doc_id';
				$arrValues_refer[] = $getDoc[0]['ref_id'];
				$arrFields_refer[] = 'doc_type';
				$arrValues_refer[] = "1";
				$arrFields_refer[] = 'episode_id';
				$arrValues_refer[] = $episode_id;
				$arrFields_refer[] = 'diagnostic_id';
				$arrValues_refer[] = $diagnostic_ID;
				$arrFields_refer[] = 'status1';
				$arrValues_refer[] = "0";
				$arrFields_refer[] = 'status2';
				$arrValues_refer[] = "0";
				$arrFields_refer[] = 'referred_date';
				$arrValues_refer[] = $diagno_date_time;
				$arrFields_refer[] = 'referred_by';
				$arrValues_refer[] = '1';					// 1- referred from patient app
				$arrFields_refer[] = 'login_id';
				$arrValues_refer[] = $login_id;
				$arrFields_refer[] = 'patient_type';
				$arrValues_refer[] = '1';					// For Prime Referrals
				$arrFields_refer[] = 'order_status';
				$arrValues_refer[] = '1';					// 1- Referred
				$arrFields_refer[] = 'customer_note';
				$arrValues_refer[] = $txtCustomerMsg;
				$arrFields_refer[] = 'referral_type';
				$arrValues_refer[] = $referralType;
							
				$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
				
			 }
			 else {
				 
				$customer_id = (int)$check_customer[0]['diagnostic_customer_id'];
							
				$arrFields_refer = array();
				$arrValues_refer = array();
				$arrFields_refer[] = 'patient_id';
				$arrValues_refer[] = $patient_id;
				$arrFields_refer[] = 'diagnostic_customer_id';
				$arrValues_refer[] = $customer_id;
				$arrFields_refer[] = 'doc_id';
				$arrValues_refer[] = $getDoc[0]['ref_id'];
				$arrFields_refer[] = 'doc_type';
				$arrValues_refer[] = "1";
				$arrFields_refer[] = 'episode_id';
				$arrValues_refer[] = $episode_id;
				$arrFields_refer[] = 'diagnostic_id';
				$arrValues_refer[] = $diagnostic_ID;
				$arrFields_refer[] = 'status1';
				$arrValues_refer[] = "0";
				$arrFields_refer[] = 'status2';
				$arrValues_refer[] = "0";
				$arrFields_refer[] = 'referred_date';
				$arrValues_refer[] = $diagno_date_time;
				$arrFields_refer[] = 'referred_by';
				$arrValues_refer[] = '1';					// 1- referred from patient app
				$arrFields_refer[] = 'login_id';
				$arrValues_refer[] = $login_id;
				$arrFields_refer[] = 'patient_type';
				$arrValues_refer[] = '1';					// For Prime Referrals
				$arrFields_refer[] = 'order_status';
				$arrValues_refer[] = '1';					// 1- Referred
				$arrFields_refer[] = 'customer_note';
				$arrValues_refer[] = $txtCustomerMsg;
				$arrFields_refer[] = 'referral_type';
				$arrValues_refer[] = $referralType;
							
				$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
			 }
			 
			
			$link = "/premium/Diagnostic-Refer?d=" . md5($patient_id) ."&e=".md5($episode_id );
			//$link = "https://medisensemd.com/premium/Diagnostic-Refer?d=" . md5($patient_id) ."&e=".md5($episode_id);
			
			//Get Shorten Url
			$getUrl= get_shorturl($link);
			
			//SMS notification to Diagnostic center
			if(!empty($getDiagno[0]['diagnosis_contact_num'])){
				$mobile = $getDiagno[0]['diagnosis_contact_num'];
				$msg = "Hello ".$getDiagno[0]['diagnosis_name'].", ".$txtPatientName." has requested the following tests. Click here to view & update reports ".$getUrl." - Thank you";
				send_msg($mobile,$msg);
			}
			
			//EMAIL notification Diagnostic center
			if(!empty($getDiagno[0]['diagnosis_email'])){
			$PatAddress=$patient_info[0]['patient_addrs'].",<br>".$patient_info[0]['patient_loc'].", ".$patient_info[0]['pat_state'].", ".$patient_info[0]['pat_country'];
			
						$url_page = 'refer_diagno.php';
						$url = rawurlencode($url_page);
						$url .= "?patname=".urlencode($patient_info[0]['patient_name']);
						$url .= "&patID=".urlencode($patient_info[0]['patient_id']);
						$url .= "&link=".urlencode($getUrl);
						$url .= "&patAddress=".urlencode($PatAddress);
						$url .= "&patContact=".urlencode($patient_info[0]['patient_mob']);
						$url .= "&patEmail=".urlencode($patient_info[0]['patient_email']);
						$url .= "&diagnoName=" . urlencode($getDiagno[0]['diagnosis_name']);
						$url .= "&tomail=" . urlencode($getDiagno[0]['diagnosis_email']);
						$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
						$url .= "&replymail=" . urlencode($getDoc[0]['ref_mail']);						
						send_mail($url);	
			}
		
		
		$share_tests = array('result' => "success", 'status' => '1', 'message' => "Your order request has been sent successfully.", 'err_msg' => '');
		echo json_encode($share_tests);
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
