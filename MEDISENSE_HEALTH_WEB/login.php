<?php
 ob_start();
 error_reporting(0);
 session_start(); 

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

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

//Random Password Generator
function randomOtp() {
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


if(API_KEY == $_POST['apikey']){  

	 $txtUserName = $_POST['txtUser'];
	 $txtMobile = $_POST['txtMobile'];
	 $txtEmail = $_POST['txtEmail'];
	
	$result = $objQuery->mysqlSelect('*','login_user',"sub_contact='".$txtMobile."'");
	
	if(COUNT($result)>0 && $result[0]['login_permission']=="1")
	{
		$otp = randomOtp();
		$arrFields = array();
		$arrValues = array();		
		
		$arrFields[] = 'otp';
		$arrValues[] = $otp;
		
		$editrecord=$objQuery->mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$result[0]['login_id']."'");
				
		$txtMob=$result[0]['sub_contact'];
		$docotp="Use ".$otp." as your login OTP for Medisense Healthcare Solution Pvt.Ltd. Thanks Medisense";
		send_msg($txtMob,$docotp);
		
		$txtDocName=$result[0]['sub_name'];
		$txtEmail=$result[0]['sub_email'];
		
					$url_page = 'otp_request.php';
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($txtDocName);
					$url .= "&otp=".urlencode($otp);
					$url .= "&reqmail=".urlencode($txtEmail);
					
					send_mail($url);
					
		$success_login = array('result' => "success", 'user_id' => $result[0]['login_id'], 'member_name' => $result[0]['sub_name']);
        echo json_encode($success_login);
		
	}
	else if(COUNT($result)>0 && $result[0]['login_permission']=="0")
	{
		$respond=2; //User permission has been denied
		//header('location:Login?respond='.$respond);
		$failure_login = array('result' => "failed", 'err_msg' => "Account permission denied.");
        echo json_encode($failure_login);
	}
	else
	{
		$otp = randomOtp();
		$arrFields = array();
		$arrValues = array();	
		
		$arrFields[] = 'sub_name';
		$arrValues[] = $txtUserName;
		
		$arrFields[] = 'otp';
		$arrValues[] = $otp;
		
		$arrFields[] = 'sub_contact';
		$arrValues[] = $txtMobile;
		
		$arrFields[] = 'sub_email';
		$arrValues[] = $txtEmail;
		
		$insertRecord=$objQuery->mysqlInsert('login_user',$arrFields,$arrValues);
		$memberid = mysql_insert_id();  //Get User Id
		
		$arrFields_src[] = 'source_name';
		$arrValues_src[] = $txtUserName;
		$arrFields_src[] = 'partner_id';
		$arrValues_src[] = $memberid;
		$arrFields_src[] = 'src_type';
		$arrValues_src[] = '1';
		
		$userSrccreate=$objQuery->mysqlInsert('source_list',$arrFields_src,$arrValues_src);
	
		$userotp="Use ".$otp." as your login OTP for Medisense. Thanks Medisense";
		send_msg($txtMobile,$userotp);
		
				
					$url_page = 'otp_request.php';
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($txtUserName);
					$url .= "&otp=".urlencode($otp);
					$url .= "&reqmail=".urlencode($txtEmail);
					
					send_mail($url);
					
		//$_SESSION['user_id']=$memberid;
		//$_SESSION['member_name']=$txtUserName;
		//header('location:OTP');
		
		$success_login = array('result' => "success", 'user_id' => $memberid, 'member_name' => $txtUserName);
        echo json_encode($success_login);
	}
	
}

?>


