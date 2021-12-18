

<?php

ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
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
		$patient_id		 = $_POST['patient_id'];
		$get_chathistory = mysqlSelect('*','chat_notification',"patient_id ='".$patient_id."'","patient_id desc","","","");
		
		
		$success = array('status' => "true","chat_history"=>$get_chathistory);
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