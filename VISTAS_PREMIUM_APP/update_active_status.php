<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

$headers = apache_request_headers();
if($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);

// Doctors Lists
if(!empty($doctor_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{
		
		
		$admin_id	    		= $doctor_id;
		$active_status  		= $_POST['active_status'];
		$last_active_timestamp  = $_POST['last_active_timestamp'];
		
        $doctor_registration = 	mysqlSelect("*","referal","ref_id='".$admin_id."'","","","");
		if(!empty($doctor_registration))
		{
			
            $arry_Field     =   array();
            $arry_Value     =   array();

            $arry_Field[]   =   "active_status";
            $arry_Value[]   =   $active_status;
			
			$arry_Field[]   =   "last_active_timestamp";
            $arry_Value[]   =   $last_active_timestamp;
			
			

		
			$result_doctor = mysqlUpdate('referal',$arry_Field,$arry_Value,"ref_id=".$admin_id);
           
           
			$success = array('status' => "true");
			echo json_encode($success);
		
        }
		
		
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
