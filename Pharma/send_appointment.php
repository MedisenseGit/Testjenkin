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

	
//SEND APPOINTMENT/OPINION LINK
extract($_POST);
if($_POST['act'] == 'add-com'):

	
	$getDoc = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.ref_mail as ref_mail,c.hosp_name as hosp_name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'" ,"","","","");	
	$weblink=HOST_MAIN_URL."SendRequestLink/?d=".md5($getDoc[0]['ref_id']);	
	//Send SMS to requested person

	
		if(!empty($userMobile))
		{
			$mobile = $userMobile;
			$msg = $getDoc[0]['ref_name']." - For Appointments/Opinion Please visit " . $weblink." - Thank you";
						
			send_msg($mobile,$msg);
		}	
		
		if(!empty($userMail))
		{
		$page_url = 'Custom_send_request_link.php';
						$paturl = rawurlencode($page_url);
						$paturl .= "?doclink=".urlencode($weblink);										
						$paturl .= "&custmail=".urlencode($userMail);
						$paturl .= "&hospName=".urlencode($getDoc[0]['hosp_name']);
						$paturl .= "&docEmail=".urlencode($getDoc[0]['ref_mail']);
						$paturl .= "&ccmail=".urlencode($ccmail);		
						send_mail($paturl);
		}
	
	$successmsg="Link sent successfully";

endif; 
?>
	