<?php
ob_start();
error_reporting(0); 
session_start();

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

	include_once('../send_text_message.php');
	include_once('../send_mail_function.php');
	include('../short_url.php');
	$headers = apache_request_headers();
	//$img_url = "https://" . $headers['Host'] . "/premium/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	$img_url = "/premium/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	//Get Short Url
	$getUrl= get_shorturl($img_url);
	
	
	//$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['pid']."'","","","","");
	$patient_tab = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob"," patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$_GET['pid']."'","","","","");
	
	
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


?>


