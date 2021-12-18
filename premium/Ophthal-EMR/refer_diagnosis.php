<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

if(isset($_GET['diagnoid']))
{
	
	$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_GET['patientid']."'");
	
	$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$_GET['diagnoid']."'");
	$getDoc= mysqlSelect("*","referal","ref_id='".$admin_id."'");
	$checkDiagnoCust= mysqlSelect("*","diagnostic_customer","diagnostic_id='".$_GET['diagnoid']."' and patient_id='".$_GET['patientid']."'");
	//Insert 'diagnostic_customer and diagnostic_referrals table'
		$arrFileds[]='diagnostic_id';
		$arrValues[]=$_GET['diagnoid'];
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		$arrFileds[]='patient_type';
		$arrValues[]="1";
		$arrFileds[]='diagnostic_customer_name';
		$arrValues[]=$getPatient[0]['patient_name'];
		$arrFileds[]='diagnostic_cust_age';
		$arrValues[]=$getPatient[0]['patient_age'];
		$arrFileds[]='diagnostic_cust_gender';
		$arrValues[]=$getPatient[0]['patient_gen'];
		$arrFileds[]='diagnostic_customer_phone';
		$arrValues[]=$getPatient[0]['patient_mob'];
		$arrFileds[]='diagnostic_customer_email';
		$arrValues[]=$getPatient[0]['patient_email'];
		$arrFileds[]='diagnostic_cust_address';
		$arrValues[]=$getPatient[0]['patient_addrs'];
		$arrFileds[]='diagnostic_cust_city';
		$arrValues[]=$getPatient[0]['patient_loc'];
		$arrFileds[]='diagnostic_cust_state';
		$arrValues[]=$getPatient[0]['pat_state'];
		$arrFileds[]='diagnostic_cust_country';
		$arrValues[]=$getPatient[0]['pat_country'];
		if(count($checkDiagnoCust)>0){
			$update_icd=mysqlUpdate('diagnostic_customer',$arrFileds,$arrValues,"diagnostic_id = '".$_GET['diagnoid']."' and patient_id='".$_GET['patientid']."'");
					
		}
		else{
			$insert_temp_value=mysqlInsert('diagnostic_customer',$arrFileds,$arrValues);
		}
		
	//Insert 'diagnostic_referrals' table
		$arrFileds_referral[]='patient_id';
		$arrValues_referral[]=$_GET['patientid'];
		$arrFileds_referral[]='patient_type';
		$arrValues_referral[]="1";
		$arrFileds_referral[]='doc_id';
		$arrValues_referral[]=$admin_id;
		$arrFileds_referral[]='doc_type';
		$arrValues_referral[]="1";
		$arrFileds_referral[]='episode_id';
		$arrValues_referral[]=$_GET['episodeid'];
		$arrFileds_referral[]='diagnostic_id';
		$arrValues_referral[]=$_GET['diagnoid'];
		//$arrFileds_referral[]='status1';
		//$arrValues_referral[]=$_GET['patientid'];
		//$arrFileds_referral[]='status2';
		//$arrValues_referral[]=$_GET['patientid'];
		$arrFileds_referral[]='referred_date';
		$arrValues_referral[]=$Cur_Date;
		$insert_temp_value=mysqlInsert('diagnostic_referrals',$arrFileds_referral,$arrValues_referral);
		
	$link = HOST_MAIN_URL."premium/Diagnostic-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
	
	//SMS notification to Diagnostic center
	if(!empty($getDiagno[0]['diagnosis_contact_num'])){
	$mobile = $getDiagno[0]['diagnosis_contact_num'];
	$msg = "Request from ".$getDoc[0]['ref_name']." For more details click here ".$link." - Thank you";
	send_msg($mobile,$msg);
	}

	//EMAIL notification Diagnostic center
		if(!empty($getDiagno[0]['diagnosis_email'])){
		$PatAddress=$getPatient[0]['patient_addrs'].",<br>".$getPatient[0]['patient_loc'].", ".$getPatient[0]['pat_state'].", ".$getPatient[0]['pat_country'];
		
					$url_page = 'refer_diagno.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatient[0]['patient_id']);
					$url .= "&link=".urlencode($link);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatient[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatient[0]['patient_email']);
					$url .= "&diagnoName=" . urlencode($getDiagno[0]['diagnosis_name']);
					$url .= "&tomail=" . urlencode($getDiagno[0]['diagnosis_email']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&replymail=" . urlencode($getDoc[0]['ref_mail']);						
					send_mail($url);	
		}
		
}

if(isset($_GET['pharmaid']))
{
	//$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_GET['patientid']."'");
	$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_GET['patientid']."'");
	
	$getPharma= mysqlSelect("*","pharma","pharma_id='".$_GET['pharmaid']."'");
	$getDoc= mysqlSelect("*","referal","ref_id='".$admin_id."'");
	
	
	$link = HOST_MAIN_URL."premium/Diagnostic-Refer?d=" . md5($_GET['patientid']);
	
	//SMS notification to Diagnostic center
	if(!empty($getDiagno[0]['diagnosis_contact_num'])){
	$mobile = $getDiagno[0]['diagnosis_contact_num'];
	$msg = "Request from ".$getDoc[0]['ref_name']." For more details click here ".$link." - Thank you";
	send_msg($mobile,$msg);
	}

	//EMAIL notification Diagnostic center
		if(!empty($getDiagno[0]['diagnosis_email'])){
		$PatAddress=$getPatient[0]['patient_addrs'].",<br>".$getPatient[0]['patient_loc'].", ".$getPatient[0]['pat_state'].", ".$getPatient[0]['pat_country'];
		
					$url_page = 'refer_diagno.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatient[0]['patient_id']);
					$url .= "&link=".urlencode($link);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatient[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatient[0]['patient_email']);
					$url .= "&diagnoName=" . urlencode($getPharma[0]['pharma_name']);
					$url .= "&tomail=" . urlencode($getPharma[0]['pharma_email']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&replymail=" . urlencode($getDoc[0]['ref_mail']);						
					send_mail($url);	
		}		
		
}

if(isset($_GET['opticleid']))
{
	$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_GET['patientid']."'");
	
	$getOpticle= mysqlSelect("*","Opticle_center","opticale_id='".$_GET['opticleid']."'");
	$getDoc= mysqlSelect("*","referal","ref_id='".$admin_id."'");
	
	
	$link = "http://manipalarogyacard.com/premiumDemo/premium/Opticles-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
	
	//SMS notification to Opticle center
	if(!empty($getOpticle[0]['opticle_contact_num'])){
	$mobile = $getOpticle[0]['opticle_contact_num'];
	$msg = "Request from ".$getDoc[0]['ref_name']." For more details click here ".$link." - Thank you";
	send_msg($mobile,$msg);
	}

	//EMAIL notification Opticle center
		if(!empty($getOpticle[0]['opticle_email'])){
		$PatAddress=$getPatient[0]['patient_addrs'].",<br>".$getPatient[0]['patient_loc'].", ".$getPatient[0]['pat_state'].", ".$getPatient[0]['pat_country'];
		
					$url_page = 'refer_diagno.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatient[0]['patient_id']);
					$url .= "&link=".urlencode($link);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatient[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatient[0]['patient_email']);
					$url .= "&diagnoName=" . urlencode($getOpticle[0]['opticle_name']);
					$url .= "&tomail=" . urlencode($getOpticle[0]['opticle_email']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&replymail=" . urlencode($getDoc[0]['ref_mail']);						
					send_mail($url);	
		}		
		
}
              
?>
