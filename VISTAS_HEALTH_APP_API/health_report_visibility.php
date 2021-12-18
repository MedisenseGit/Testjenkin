<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers)
{
    $user_id 	= $headers['user-id'];
	$timestamp 	= $headers['x-timestamp'];
	$hashKey 	= $headers['x-hash'];
	$device_id 	= $headers['device-id'];
}

$postdata 	= $_POST;
$finalHash 	= checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);


if(!empty($user_id) && !empty($finalHash))
{	
	if($finalHash == $hashKey)
	{
		
		$report_id 	= 	$_POST['report_id'];   
		$visibility = 	$_POST['visibility'];  // 0- show , 1- hide 
		
		$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","id ='".$report_id."'","","","","");
		if(!empty($reportlist_details))
		{
			$arrFields = array();
			$arrValues = array();
			
			$arrFields[] = 'visibility';
			$arrValues[] = $visibility;
			
			
			$update_details		=	mysqlUpdate('health_app_healthfile_reports',$arrFields,$arrValues,"id='".$report_id."'");
		}
		
		$success = array('status' => "true");
		echo json_encode($success);
		
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
