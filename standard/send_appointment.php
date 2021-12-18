<?php
ob_start();
error_reporting(0); 
session_start();

$doc_id=$_GET['doc_id'];
include('send_text_message.php');
include('send_mail_function.php');

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
	
//SEND APPOINTMENT/OPINION LINK
extract($_POST);
if($_POST['act'] == 'add-com'):

	
	$getDoc = $objQuery->mysqlSelect("partner_id,contact_person,partner_name,cont_num1,Email_id","our_partners","partner_id='".$admin_id."'" ,"","","","");	
	//$weblink="http://pixeleyecare.com/SendRequestLink/RefLink?d=".md5($getDoc[0]['partner_id']);	 
	$weblink="https://medisensecrm.com/SendRequestLink/RefLink?d=".md5($getDoc[0]['partner_id']);	
	//Send SMS to requested person

	
		if(!empty($userMobile))
		{
			$mobile = $userMobile;
			$msg = $getDoc[0]['partner_name']." - For Appointments Please visit " . $weblink." - Thank you";
						
			send_msg($mobile,$msg);
		}	
		
		if(!empty($userMail))
		{
		$page_url = 'Custom_send_request_link.php';
							$paturl = rawurlencode($page_url);
							$paturl .= "?doclink=".urlencode($weblink);										
							$paturl .= "&custmail=".urlencode($userMail);
							$paturl .= "&hospName=".urlencode($getDoc[0]['partner_name']);
							$paturl .= "&docEmail=".urlencode($getDoc[0]['Email_id']);
							//$paturl .= "&ccmail=".urlencode($ccmail);		
							send_mail($paturl);
		}
	
	$successmsg="Link sent successfully";

endif; 
?>
	