<?php
ob_start();
error_reporting(0); 
session_start();

	include_once('../send_text_message.php');
	include_once('../send_mail_function.php');
	include('../short_url.php');
	$headers = apache_request_headers();
	$img_url = "https://" . $headers['Host'] . "/standard/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	//$img_url = "/premium/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	//Get Short Url
	//$getUrl= get_shorturl($img_url);
	
if(isset($_GET['pmobile'])){

	$mobile = $_GET['pmobile'];
	$msg = "This is your digitalized prescription sent by Dr.".$_SESSION['doc_name']." Please press following Link ".$img_url." - Thank you";
	send_msg($mobile,$msg);
	
}

if(isset($_GET['pemail'])){

//Patient Info EMAIL notification Sent to Doctor
		if(!empty($_GET['pemail'])){
		
					$url_page = 'share_email_prescription.php';
					$url = rawurlencode($url_page);
					$url .= "?patemail=".urlencode($_GET['pemail']);
					$url .= "&docname=".urlencode($_SESSION['doc_name']);
					$url .= "&shortUrl=".urlencode($img_url);
					send_mail($url);
		}
}


?>


