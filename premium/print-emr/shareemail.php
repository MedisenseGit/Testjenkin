<?php
ob_start();
error_reporting(0); 
session_start();

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
$admin_id = $_SESSION['user_id'];

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

	include_once('../send_text_message.php');
	include_once('../send_mail_function.php');
	include('../short_url.php');
	$headers = apache_request_headers();
	//$img_url = "https://" . $headers['Host'] . "/premium/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	$img_url = "/premium/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	//Get Short Url
	$getUrl= get_shorturl($img_url);
	
	
	$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['pid']."'","","","","");
	
	
if(isset($_GET['pmobile'])){

	
	$mobile = $_GET['pmobile'];
	$msg = "Hello ".$patient_tab[0]['patient_name'].", \nThis is your digitized consultation summary sent by  ".$_SESSION['doc_name']." \nDated: ".$patient_tab[0]['system_date']." \nLink ".$getUrl." \nThanks";
	send_msg($mobile,$msg);
	
}

if(isset($_GET['pemail'])){

//Patient Info EMAIL notification Sent to Doctor
		if(!empty($_GET['pemail'])){
		
					$url_page = 'share_email_prescription.php';
					$url = rawurlencode($url_page);
					$url .= "?patemail=".urlencode($_GET['pemail']);
					$url .= "&docname=".urlencode($_SESSION['doc_name']);
					$url .= "&shortUrl=".urlencode($getUrl);
					send_mail($url);
		}
}

if(isset($_GET['docid'])){
		
	$ref_doc_tab = mysqlSelect("*","doctor_out_referral","doc_out_ref_id='".$_GET['docid']."'","","","","");
	//$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['pid']."'","","","","");
	
	$patient_tab = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$_GET['pid']."'","","","","");
	
	
			$arrFileds[]='patient_id';
			$arrValues[]=$patient_tab[0]['patient_id'];
			$arrFileds[]='episode_id';
			$arrValues[]=$_GET['episodeid'];
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
			$arrFileds[]='doc_type';
			$arrValues[]="1";
			$arrFileds[]='referral_id';
			$arrValues[]=$_GET['docid'];
			$arrFileds[]='type';
			$arrValues[]="4";
			$arrFileds[]='timestamp';
			$arrValues[]=$Cur_Date;
			$insert_outgoing_referrals=mysqlInsert('doctor_outgoing_referrals',$arrFileds,$arrValues);
			
	if(!empty($ref_doc_tab[0]['doctor_mobile'])){

	
	$mobile = $ref_doc_tab[0]['doctor_mobile'];
	$msg = "Referring this case of ".$patient_tab[0]['patient_name']."(".$patient_tab[0]['patient_mob']."), for your review and treatment. \nLink ".$getUrl."\n Regards, \n".$_SESSION['doc_name'];
	send_msg($mobile,$msg);
	
	}

	if(!empty($ref_doc_tab[0]['doctor_email'])){
		
			$url_page = 'refer_patient_emr.php';
			$url = rawurlencode($url_page);
			$url .= "?refdocemail=".urlencode($ref_doc_tab[0]['doctor_email']);
			$url .= "&docname=".urlencode($_SESSION['doc_name']);
			$url .= "&refdocname=".urlencode($ref_doc_tab[0]['doctor_name']);
			$url .= "&patname=".urlencode($patient_tab[0]['patient_name']);
			$url .= "&patmobile=".urlencode($patient_tab[0]['patient_mob']);
			$url .= "&shortUrl=".urlencode($getUrl);
			send_mail($url);
	}
}


?>


