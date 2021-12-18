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
		
		$user_id 	= $user_id;
		$member_id  = $_POST['member_id'];
		
		
		$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","(member_id) ='".$member_id."'","id DESC","","","");

		$reports_details= array();
		foreach($reportlist_details as $result_reportList) 
		{

				$getReportList['report_id']		=	$result_reportList['id'];
				$getReportList['title']			=	$result_reportList['title'];
				$getReportList['description']	=	$result_reportList['description'];
				$getReportList['report_date']	=	$result_reportList['report_date'];
				$getReportList['report_date']	=	$result_reportList['report_date'];
				$getReportList['date_time']		=	$result_reportList['created_date'];
				$getReportList['doc_id']="";
				
				$getReportList['visibility']=$result_reportList['visibility'];//1 - hide , 0 - show 
				
				
				$attachment_details = mysqlSelect("id as attachment_id, attachment_name as attachment_name","health_app_healthfile_report_attachments","report_id ='".$result_reportList['id']."'","id ASC","","","");

				$getReportList['attachments']= $attachment_details;

				$getReportList['type']= '1';
				
			array_push($reports_details, $getReportList);
		}
		
		// echo $member_id;
		// Member General Health
		$member_general_health = mysqlSelect('*','user_family_general_health',"(member_id)='".$member_id."'","","","","");

		if(empty($member_general_health))
		{
			$arrFileds_medical[]='member_id';
			$arrValues_medical[]= $member_basic[0]['member_id'];
        	$family_general_health = mysqlInsert('user_family_general_health',$arrFileds_medical,$arrValues_medical );

			$member_general_health = mysqlSelect('*','user_family_general_health',"(member_id)='".$member_id."'","","","","");
		}
		// Member Details
		
		$result_family = mysqlSelect("*","user_family_member","member_id='".$member_id."'","","","","");
			
		
		
		$share_tests = array('result' => "success", 'reports_details' => $reports_details, 'general_health_array' => $member_general_health,  'member_details_array' => $result_family, 'err_msg' => '');
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
