
<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

require_once("../classes/querymaker.class.php");


$headers = apache_request_headers();
if($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata  = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Book Consultation
if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey)
	{
		$appoint_trans_id	= $_POST['appoint_trans_id'];
		$status 			= $_POST['status'];

		$arrFields	=	array();
		$arrValues	=	array();

		$arrFields[] = 'pay_status';
		$arrValues[] = $status;
		
		$update_patstatus	= mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$appoint_trans_id."'");

		$success_consults   = array('result' => "success");
		echo json_encode($success_consults);

		
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