<?php
ob_start();
error_reporting(0); 
session_start();

$doc_id=$_GET['doc_id'];
include('send_text_message.php');
include('send_mail_function.php');
include('short_url.php');

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
	
//SEND APPOINTMENT/OPINION LINK
extract($_POST);
if($_POST['act'] == 'add-com'):

	$arrFieldsHistory[]='name';
	$arrValuesHistory[]=$userName;
	$arrFieldsHistory[]='email_id';
	$arrValuesHistory[]=$userMail;
	$arrFieldsHistory[]='mobile';
	$arrValuesHistory[]=$userMobile;
	$arrFieldsHistory[]='doc_id';
	$arrValuesHistory[]=$admin_id;
	$arrFieldsHistory[]='cur_date';
	$arrValuesHistory[]=$curDate;
	$insert_app_history= mysqlInsert('appointment_link_history',$arrFieldsHistory,$arrValuesHistory);
	
	$getDoc = mysqlSelect("ref_id,ref_name,ref_mail","referal","ref_id='".$admin_id."'" ,"","","","");
	//$weblink="https://medisensecrm.com/SendRequestLink/?d=".md5($getDoc[0]['ref_id']);	
	//Send SMS to requested person
	$longurl = "/SendRequestLink/?d=".md5($getDoc[0]['ref_id'])."&hid=".md5($hospID);
	//Get Shorten Url
	$getUrl= get_shorturl($longurl);
	
		if(!empty($userMobile))
		{
			$mobile = $userMobile;
			$msg = $getDoc[0]['ref_name']." - For Appointments/Opinion Please visit " . $getUrl." - Thank " . $hospName;
						
			send_msg($mobile,$msg);
		}	
		
		if(!empty($userMail))
		{
		$page_url = 'Custom_send_request_link.php';
						$paturl = rawurlencode($page_url);
						$paturl .= "?doclink=".urlencode($getUrl);										
						$paturl .= "&custmail=".urlencode($userMail);
						$paturl .= "&hospName=".urlencode($hospName);
						$paturl .= "&docEmail=".urlencode($getDoc[0]['ref_mail']);
						$paturl .= "&ccmail=".urlencode($ccmail);		
						send_mail($paturl);
		}
	
	$successmsg="Link sent successfully";

endif; 
?>
	