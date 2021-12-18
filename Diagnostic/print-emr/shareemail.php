<?php
	include_once('../send_text_message.php');
	include_once('../send_mail_function.php');
	include('../short_url.php');
	//$headers = apache_request_headers();
	//$img_url = "https://" . $headers['Host'] . "/premium/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	$img_url = "/Diagnostic/print-emr/prescs/" . $_GET['pid'] . ".jpg";
	//Get Short Url
	$getUrl= get_shorturl($img_url);
	
if(isset($_GET['pmobile'])){

	$mobile = $_GET['pmobile'];
	$msg = "Your Report is at ".$getUrl." - Thank you";
	send_msg($mobile,$msg);
	
}

if(isset($_GET['pemail'])){

		if(!empty($_GET['pemail'])){
		
					$url_page = 'share_email_prescription.php';
					$url = rawurlencode($url_page);
					$url .= "?patemail=".urlencode($_GET['pemail']);
					$url .= "&shortUrl=".urlencode($getUrl);
					send_mail($url);
		}
}


?>


