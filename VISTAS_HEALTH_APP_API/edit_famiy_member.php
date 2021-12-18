<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");


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
// Health Reports Lists
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{
		
		$user_id 		= 	$user_id;
		$member_id  	= 	$_POST['member_id'];
		$member_name	=	$_POST['member_name'];
		$member_type	=	$_POST['member_type'];
		$gender  		= 	$_POST['gender'];
		$age			=	$_POST['age'];
		$relationship	=	$_POST['relationship'];
		$dob			=	$_POST['dob'];
		//$user_id		=	$_POST['user_id'];
		$member_photo	=	$_POST['member_photo'];
		$height			=	$_POST['height'];
		$weight			=	$_POST['weight'];
		$blood_group	=	$_POST['blood_group'];
		
		
		
	
		// Member General Health
		$member_general_health = mysqlSelect('*','user_family_member',"(member_id)='".$member_id."'","","","","");

		if(!empty($member_general_health))
		{
			$arrFileds_member	= array();
			$arrValues_member	= array();
			
			$arrFileds_member[]='member_name';
			$arrValues_member[]= $member_name;
			
			$arrFileds_member[]='member_type';
			$arrValues_member[]= $member_type;
			
			$arrFileds_member[]='gender';
			$arrValues_member[]= $gender;
			
			$arrFileds_member[]='age';
			$arrValues_member[]= $age;
			
			$arrFileds_member[]='relationship';
			$arrValues_member[]= $relationship;
			
			$arrFileds_member[]='dob';
			$arrValues_member[]= $dob;
			
			$arrFileds_member[]='member_photo';
			$arrValues_member[]= $member_photo;
			
			$arrFileds_member[]='height';
			$arrValues_member[]= $height;
			
			$arrFileds_member[]='weight';
			$arrValues_member[]= $weight;
			
			$arrFileds_member[]='blood_group';
			$arrValues_member[]= $blood_group;
			
			$arrFileds_member[]='user_id';
			$arrValues_member[]= $user_id;
			
			
			$family_general_health	=	mysqlUpdate('user_family_member',$arrFileds_member,$arrValues_member,"member_id='".$member_id."'");
			
			
        	
		}
		
		$share_tests = array('result' => "success", 'err_msg' => '');
		echo json_encode($share_tests);
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
