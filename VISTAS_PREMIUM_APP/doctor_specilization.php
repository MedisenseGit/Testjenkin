<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

$headers = apache_request_headers();
if ($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) 
{
	if($finalHash == $hashKey)
	{
			$admin_id 	= $doctor_id;
			
			$doc_specilization = mysqlSelect('a.doc_spec_id as doc_spec_id , a.spec_id as spec_id, a.doc_id as doc_id,b.spec_name as spec_name','doc_specialization as a inner join specialization as b on a.spec_id = b.spec_id',"a.doc_id ='".$admin_id."'","","","","");
			
			$success = array('doc_specilization' => $doc_specilization);
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