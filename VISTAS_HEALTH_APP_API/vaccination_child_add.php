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
///$data = json_decode(file_get_contents('php://input'), true);

// Health Reports Lists
if(!empty($user_id) && !empty($finalHash))
{	
	if($finalHash == $hashKey)
	{
		
		$user_id 	= $user_id;
		$member_id 	= $_POST['member_id'];
		$child_id   = $_POST['child_id'];
		$remarks    = $_POST['remarks']; 
		$user_type  = $_POST['user_type']; 	// type - 1 for Asha Worker, 2- parent, 3 - Hospital Doctors
		$created_at = date('Y-m-d H:i:s');
		$vaccine_given_date = $_POST['vaccine_given_date'];
		$child_vaccine_id   = $_POST['child_vaccine_id'];
		$child_duration_id  = $_POST['child_duration_id'];
		
		
			$arrFields1 = array();
			$arrValues1 = array();

			$arrFields1[]= 'vaccine_given_date';
			$arrValues1[]=  date('Y-m-d',strtotime($vaccine_given_date));
			$arrFields1[]= 'vaccine_id';
			$arrValues1[]=  $child_vaccine_id;
			$arrFields1[]= 'vaccine_duration_id';
			$arrValues1[]=  $child_duration_id;
			$arrFields1[]= 'child_tab_id';
			$arrValues1[]=  $child_id;
			$arrFields1[]= 'remarks';
			$arrValues1[]=  $remarks;
			$arrFields1[]= 'user_id';
			$arrValues1[]=  $user_id;
			$arrFields1[]= 'user_type';
			$arrValues1[]=  $user_type;
			$arrFields1[]= 'created_at';
			$arrValues1[]=  $created_at;

			$getChild = mysqlSelect('child_name','child_tab',"child_id='".$child_id."'","","","","");
		
			$userDetails = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
			$userName=$userDetails[0]['ref_name'];
			
			$getcount = mysqlSelect('count(vaccine_given_date) AS NumberofGivenDate','vaccine_child_tab',"vaccine_id='".$child_vaccine_id."' and child_tab_id='".$child_id."' and vaccine_duration_id='".$child_duration_id."' and user_id='".$user_id."' and user_type='".$user_type."'","","","","");

			if($getcount[0]['NumberofGivenDate'] >= 1) 
			{
				$vaccine_update=mysqlUpdate('vaccine_child_tab',$arrFields1,$arrValues1,"vaccine_id='".$child_vaccine_id."' and child_tab_id='".$child_id."' and vaccine_duration_id='".$child_duration_id."' and user_id='".$user_id."' and user_type='".$user_type."'");
				
			}
			else 
			{
				$add_vaccine=mysqlInsert('vaccine_child_tab',$arrFields1,$arrValues1);
				
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
