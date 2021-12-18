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
$data 		= json_decode(file_get_contents('php://input'), true);

$ip 		= $_SERVER['REMOTE_ADDR']; // find time zone
$ipInfo 	= file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo 	= json_decode($ipInfo);
$timezone	= $ipInfo->timezone;
//date_default_timezone_set($timezone);

// Global Doctors Lists

if(!empty($user_id) && !empty($finalHash))
{	
	if($finalHash == $hashKey)
	{
		
		$doc_id 	= 	$_POST['doc_id'];
		$hosp_id 	= 	$_POST['hosp_id'];
		$date_val 	= 	date('Y-m-d',strtotime($_POST['appoint_date']));//'27-10-2021';//$data['appoint_date'];  //'2021-10-27';
		
		$timestamp  = strtotime($date_val);
		$day 	    = date('D', $timestamp);
		
		//echo $day ;
		if($day == "Sun")
		{
			$day_id  =  1;
		}
		else if($day == "Mon")
		{
			$day_id = 2;
		}
		else if($day == "Tue")
		{
			$day_id = 3;
		}
		else if($day == "Wed")
		{
			$day_id	= 4;
		}
		else if($day == "Thu")
		{
			$day_id	= 5;
		}
		else if($day == "Fri")
		{
			$day_id	= 6;
		}
		else
		{
			$day_id	= 7;
		}	
		
		$GetTimeSlot = mysqlSelect("b.time_id as time_id,a.utc_slots as utc_slots,a.categoty as categoty ","appointment_utc_slots AS a INNER JOIN doctor_appointment_slots_set AS b ON a.id = b.time_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hosp_id."' AND b.day_id = '".$day_id."'","","","","");	
		
		
		
		$slot_details = array();
		if(!empty($GetTimeSlot))
		{
			
			foreach($GetTimeSlot as $TimeSlot)
			{ 
				//var_dump($GetTimeSlot);
				$utc_slot = $TimeSlot['utc_slots'];
				$UTCObj   = new DateTime($utc_slot, new DateTimeZone("UTC"));
				$LocalObj = $UTCObj;
				$LocalObj->setTimezone(new DateTimeZone($timezone));
				$categoty = $TimeSlot['categoty'];
				$timeId   = $TimeSlot['time_id'];
				$dtA = new DateTime($Cur_Date);
				$dtB = new DateTime($LocalObj->format("g:i A"));
				$time_slot 	= 	$LocalObj->format("g:i A");
				//$timeId		=	$timeId;
				$getTimeSlotList['time_slot']	=	$time_slot;
				$getTimeSlotList['timeId']		=	$timeId;
				$getTimeSlotList['categoty']	=	$categoty;	 //1- morning , 2 -afternoon ,3 - evening ,4 - night
				array_push($slot_details, $getTimeSlotList);
			}
		
		}
		$success = array('status' => "true", "time_slot"=>$GetTimeSlot);
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
