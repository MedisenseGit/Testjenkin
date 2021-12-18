

<?php

ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

$Cur_Date=date('Y-m-d H:i:s');
$curDate=date('Y-m-d',strtotime($Cur_Date));

$headers = apache_request_headers();
if ($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata 	= 	$_POST;
$finalHash 	= 	checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{
		$patientID 		= $_POST['patient_id'];
		$txtRefId 		= $doctor_id;
		$txtProNote 	= $_POST['chat_note'];
		$admin_id		= $_POST['admin_id'];
		
		$arrFieldsChat = array();
		$arrValuesChat = array();
		
		$arrFields1[]= 'patient_id';
		$arrValues1[]= $patientID;
		$arrFields1[]= 'ref_id';
		$arrValues1[]= $txtRefId;
		$arrFields1[]= 'chat_note';
		$arrValues1[]= $txtProNote;
		$arrFields1[]= 'user_id';
		$arrValues1[]= $admin_id;
		$arrFields1[]= 'status_id';
		$arrValues1[]= "7";
		$arrFields1[]= 'TImestamp';
		$arrValues1[]= $curDate;
		$patientNote = mysqlInsert('chat_notification',$arrFields1,$arrValues1);
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