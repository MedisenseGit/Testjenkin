<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);

// Adult Vaccine Delete
if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey)
	{
		
		$user_id   = $user_id;
		$member_id = $_POST['member_id'];
		
		$vaccine_id = $_POST['vaccine_id'];

		$delReports = mysqlDelete('vaccine_adults',"id='".$vaccine_id."'");
		$delReportAttachments = mysqlDelete('vaccine_adults_reports',"vaccine_id='".$vaccine_id."'");

		
		$delete_vaccine = array('result' => "success", 'err_msg' => '');
		echo json_encode($delete_vaccine);
	}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else 
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}
?>
