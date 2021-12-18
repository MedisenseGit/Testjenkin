<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
include('send_text_message.php');
include('send_mail_function.php');
include('../premium/short_url.php');

// Diagnostic Referal
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$episode_id = $_POST['episode_id'];
	$patient_id = $_POST['patient_id'];
	
	if($login_type == 1) {	
	
		$patient_info = $objQuery->mysqlSelect('*','doc_my_patient',"patient_id='".$patient_id."'","","","","");
		$txtPatientName = $patient_info[0]['patient_name'];
		$txtAge = $patient_info[0]['patient_age'];
		$txtGender = $patient_info[0]['patient_gen'];
		$txtPhone = $patient_info[0]['patient_mob'];
		$txtEmail = $patient_info[0]['patient_email'];
		$txtAddress = $patient_info[0]['patient_addrs'];
		$txtCity = $patient_info[0]['patient_loc'];
		$txtState = $patient_info[0]['pat_state'];
		$txtCountry = $patient_info[0]['pat_country'];
	
		$getDoc= $objQuery->mysqlSelect("*","referal","ref_id='".$admin_id."'");
	
		$check_customer = $objQuery->mysqlSelect('*','diagnostic_customer',"patient_id='".$patient_id."' and patient_type='1'","","","","");
		 if(empty($check_customer)){
			 
				
			 
			 while (list($key, $val) = each($_POST['se_diagnostic_ID']))
				{
					$diagnostic_ID = $_POST['se_diagnostic_ID'][$key];
					$diagno_date_time = $Cur_Date;
					
					$getDiagno= $objQuery->mysqlSelect("*","Diagnostic_center","diagnostic_id='".$diagnostic_ID."'");
					
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
				
						$insert_diagnostic_customer = $objQuery->mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
						$customer_id = mysql_insert_id(); //Get customer_id
						
						$arrFields_refer = array();
						$arrValues_refer = array();
						$arrFields_refer[] = 'patient_id';
						$arrValues_refer[] = $patient_id;
						$arrFields_refer[] = 'diagnostic_customer_id';
						$arrValues_refer[] = $customer_id;
						$arrFields_refer[] = 'doc_id';
						$arrValues_refer[] = $admin_id;
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
						
						$insert_diagnostic_customer = $objQuery->mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
				}
			 
		 }
		 else {
					$customer_id = (int)$check_customer[0]['diagnostic_customer_id'];
					
					while (list($key, $val) = each($_POST['se_diagnostic_ID']))
					{
						$diagnostic_ID = $_POST['se_diagnostic_ID'][$key];
						$diagno_date_time = $Cur_Date;
						
						$getDiagno= $objQuery->mysqlSelect("*","Diagnostic_center","diagnostic_id='".$diagnostic_ID."'");
					
						$arrFields_refer = array();
						$arrValues_refer = array();
						$arrFields_refer[] = 'patient_id';
						$arrValues_refer[] = $patient_id;
						$arrFields_refer[] = 'diagnostic_customer_id';
						$arrValues_refer[] = $customer_id;
						$arrFields_refer[] = 'doc_id';
						$arrValues_refer[] = $admin_id;
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
						
						$insert_diagnostic_customer = $objQuery->mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
					}
		 }
		 
		$link = "/premium/Diagnostic-Refer?d=" . md5($patient_id) ."&e=".md5($episode_id );
	
		//$link = "https://medisensecrm.com/premium/Diagnostic-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
		
		//Get Shorten Url
		$getUrl= get_shorturl($link);
		
		//SMS notification to Diagnostic center
		if(!empty($getDiagno[0]['diagnosis_contact_num'])){
		$mobile = $getDiagno[0]['diagnosis_contact_num'];
		$msg = "Hello ".$getDiagno[0]['diagnosis_name'].", ".$getDoc[0]['ref_name']." has referred the following tests. Click here to view & update reports ".$getUrl." - Thank you";
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
		
		$success = array('result' => "success");
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "false");
		echo json_encode($success);
	}
		

	
}


?>