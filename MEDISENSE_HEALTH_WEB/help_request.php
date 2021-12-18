<?php
 ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 3;
$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

require_once("../get_config.php");
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

include("send_mail_function.php");

if(API_KEY == $_POST['apikey']){  //TO CHECK AUTHENTICATION OF POST VALUES

	//$ownermail="salmabanu.h@gmail.com";
	$ownermail="medical@medisense.me,shashistavarmath@medisense.me,salmabanu.h@gmail.com";
	
	$user_name = $_POST['user_name'];
	$user_email = $_POST['user_email'];
	$user_phone = $_POST['user_phone'];
	$user_msg = $_POST['user_msg'];
	

		$account = "Medisense Health Web Application";
		$mailsubject = "Medisense Health Web App Help Request !!!";
		$mailformat="Dear Team<br><br>We got one new Medisense Health Web App Help Request. Please go through below details <br> </br><br>
					<b>Name :</b> ".$user_name."<br><b>Email :</b>".$user_email."<br><b>Mobile : </b>".$user_phone."<br><b>Request From :</b> ".$account."<br><b>Request Content :</b> ".$user_msg."<br><br><b>Many Thanks</b>";
					
					//Registration Email notification to Primary members
																			
						$url_page1 = 'feedback_request.php';
						$url = rawurlencode($url_page1);
						$url .= "?mailSubject=" . urlencode($mailsubject);
						$url .= "&mailformat=".urlencode($mailformat);
						$url .= "&recipientMail=".urlencode($ownermail);
						$url .= "&fromName=".urlencode($user_name);
						$url .= "&fromMail=".urlencode($user_email);		
						send_mail($url);
}

?>


