<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

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
$data = json_decode(file_get_contents('php://input'), true);
// Doctors Near Me
if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{
			
		$searchTerm = $_POST['searchTerm'];
		
		$get_symptoms 	= mysqlSelect("*","chief_medical_complaints","symptoms LIKE '%".$searchTerm."%'","symptoms asc","","","");
		
		$success = array('status' => "false","get_symptoms" => $get_symptoms);
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
