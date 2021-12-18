<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

$headers = apache_request_headers();
if ($headers)
{
	$doctor_id 	= $headers['doctor-id'];
	$timestamp 	= $headers['x-timestamp'];
	$hashKey 	= $headers['x-hash'];
	$device_id 	= $headers['device-id'];
}

$postdata 	= $_POST;
$finalHash 	= checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);


if(!empty($doctor_id) && !empty($finalHash))
{	
	if($finalHash == $hashKey)
	{
		
		$member_id 	= 	$_POST['member_id'];  //0- show, 1- hide 
		
		
		$get_visibility_status = mysqlSelect("visibility","health_app_healthfile_reports","member_id ='".$member_id."' and visibility='0'","","","","");
		
		$success = array('status' => "true",'visibility_status'=>$get_visibility_status);
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
