<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();


	//SEND APPOINTMENT/OPINION LINK  PRACTICE
if(API_KEY == $_POST['API_KEY']) {
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	

	$admin_id = $_POST['user_id'];
	

	$getDoc = $objQuery->mysqlSelect("partner_id,contact_person,partner_name,cont_num1,Email_id","our_partners","partner_id='".$admin_id."'" ,"","","","");	
	$weblink="https://medisensecrm.com/SendRequestLink/RefLink?d=".md5($getDoc[0]['partner_id']);	 
	//Send SMS to requested person
	if($_POST['pat_mobile']!="")
	{
		$mobile = $_POST['pat_mobile'];
		$msg = $getDoc[0]['partner_name']." - For Appointments Please visit " . $weblink." - Thank you";
					
		send_msg($mobile,$msg);
	}	
	
	if($_POST['pat_email']!="")
	{
	$page_url = 'Custom_send_request_link.php';
						$paturl = rawurlencode($page_url);
						$paturl .= "?doclink=".urlencode($weblink);										
						$paturl .= "&custmail=".urlencode($_POST['pat_email']);
						$paturl .= "&hospName=".urlencode($getDoc[0]['partner_name']);
						$paturl .= "&docEmail=".urlencode($getDoc[0]['Email_id']);
						//$paturl .= "&ccmail=".urlencode($ccmail);		
						send_mail($paturl);
	}
	$response="send";
	// header("Location:Blogs-Offers-Events-List?response=".$response);			

	$success = array('status' => "true","send_appointment" => "Appointment sent successfully. ");    	//  partner created resume
	echo json_encode($success);
	
}


?>