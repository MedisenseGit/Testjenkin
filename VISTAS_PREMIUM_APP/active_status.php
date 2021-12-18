<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

$headers = apache_request_headers();
if ($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", $postdata, $doctor_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);

// Doctors Lists
if(!empty($doctor_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{
		$admin_id	= $doctor_id;
		
		$result_doctor = mysqlSelect("ref_id,active_status","referal"," ref_id='".$admin_id."'","ref_id DESC","","","");
		
		 
		}

		
		$success = array('status' => "true", "active_status" => $result_doctor);
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
