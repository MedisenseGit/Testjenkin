<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Helath Files Reports Delete
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_id = $user_id;
		$member_id = $_POST['member_id'];
		$report_id = $_POST['reportID'];
		
		$delReports = mysqlDelete('health_app_healthfile_reports',"id='".$report_id."' AND member_id='".$member_id."' AND login_id='".$user_id."'");
		$delReportAttachments = mysqlDelete('health_app_healthfile_report_attachments',"report_id='".$report_id."' AND member_id='".$member_id."'");

		$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","login_id ='".$user_id."'","id DESC","","","");
		$reports_details= array();
		foreach($reportlist_details as $result_reportList) {
				$getReportList['report_id']=$result_reportList['id'];
				$getReportList['member_id']=$result_reportList['member_id'];
				$getReportList['title']=$result_reportList['title'];
				$getReportList['description']=$result_reportList['description'];
				$getReportList['timeStampNum']=$result_reportList['timeStampNum'];
				$getReportList['created_date']=$result_reportList['created_date'];
				$getReportList['report_date']=$result_reportList['report_date'];
				
				$attachment_details = mysqlSelect("id as attachment_id, attachment_name as attachment_name","health_app_healthfile_report_attachments","report_id ='".$result_reportList['id']."'","id ASC","","","");
				$getReportList['attachments']= $attachment_details;
				
			array_push($reports_details, $getReportList);
		}
		
		
		$success_opinion = array('result' => "success", 'status' => '1', 'reports_details' => $reports_details, 'message' => "Reports deleted successfully !!!", 'err_msg' => '');
		echo json_encode($success_opinion);
		
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}
?>
