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
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Health Reports Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$user_id = $user_id;
		$member_id = $_POST['member_id'];
		$txtTitle = addslashes($_POST['title']);
		$txtDescription = addslashes($_POST['description']);
		$Cur_Date=date('Y-m-d H:i:s');
		$txtDate =  $_POST['date_given'];
		$file_name = $_FILES['file-3'];
		$visibility = $_POST['visibility'];		//0-show, 1- hide 
		
		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'login_id';
		$arrValues[] = $user_id;
		
		$arrFields[] = 'member_id';
		$arrValues[] = $member_id;

		$arrFields[] = 'title';
		$arrValues[] = $txtTitle;

		$arrFields[] = 'description';
		$arrValues[] = $txtDescription;

		// $arrFields[] = 'timeStampNum';
		// $arrValues[] = $txtTimeStamp;
		
		$arrFields[] = 'created_date';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'report_date';
		$arrValues[] = date('Y-m-d',strtotime($txtDate));
		
		$arrFields[] = 'visibility';
		$arrValues[] = $visibility;


		$usercraete=mysqlInsert('health_app_healthfile_reports',$arrFields,$arrValues);
		$id = $usercraete;
		
	
		
		//Add Health REPORTS functionality
		$errors= array();
	//	foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){
			
				$file_name = $_FILES['file-3']['name'];
				$file_size =$_FILES['file-3']['size'];
				$file_tmp =$_FILES['file-3']['tmp_name'];
				$file_type=$_FILES['file-3']['type'];

				if(!empty($file_name)){
					$Photo1  = $file_name;
					$arrFields_attach = array();
					$arrValues_attach = array();

					$arrFields_attach[] = 'report_id';
					$arrValues_attach[] = $id;
					
					$arrFields_attach[] = 'login_id';
					$arrValues_attach[] = $user_id;
					
					$arrFields_attach[] = 'member_id';
					$arrValues_attach[] = $member_id;

					$arrFields_attach[] = 'attachment_name';
					$arrValues_attach[] = $file_name;

					$pat_attach=mysqlInsert('health_app_healthfile_report_attachments',$arrFields_attach,$arrValues_attach);
					$attachid= $pat_attach;


					//Uploading image file
					$uploaddirectory = realpath("../HealthFilesReports");

					$uploaddir = $uploaddirectory . "/" .$attachid;
					$dotpos = strpos($uploaddir, '.');

					$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
					$uploadfile = $uploaddir . "/" . $Photo1;


					//Checking whether folder with category id already exist or not.
					if (file_exists($uploaddir)) {
					//echo "The file $uploaddir exists";
					} else {
						$newdir = mkdir($uploaddirectory . "/" . $attachid, 0777, true);
						
						/* Moving uploaded file from temporary folder to desired folder. */
						if(move_uploaded_file ($file_tmp, $uploadfile)) {
							//echo "File uploaded.";
						} else {
							//echo "File cannot be uploaded";
						}
					}
				}  
		//	}
		
		
	/*	//Add Health REPORTS functionality
		$errors= array();
		foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){
			
			echo "files loop:".$_FILES['file-3']['name'][$key];

				$file_name = $_FILES['file-3']['name'][$key];
				$file_size =$_FILES['file-3']['size'][$key];
				$file_tmp =$_FILES['file-3']['tmp_name'][$key];
				$file_type=$_FILES['file-3']['type'][$key];

				if(!empty($file_name)){
					$Photo1  = $file_name;
					$arrFields_attach = array();
					$arrValues_attach = array();

					$arrFields_attach[] = 'report_id';
					$arrValues_attach[] = $id;
					
					$arrFields_attach[] = 'login_id';
					$arrValues_attach[] = $user_id;
					
					$arrFields_attach[] = 'member_id';
					$arrValues_attach[] = $member_id;

					$arrFields_attach[] = 'attachment_name';
					$arrValues_attach[] = $file_name;

					$pat_attach=mysqlInsert('health_app_healthfile_report_attachments',$arrFields_attach,$arrValues_attach);
					$attachid= mysql_insert_id();


					//Uploading image file
					$uploaddirectory = realpath("../HealthFilesReports");

					$uploaddir = $uploaddirectory . "/" .$attachid;
					$dotpos = strpos($uploaddir, '.');

					$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
					$uploadfile = $uploaddir . "/" . $Photo1;


					//Checking whether folder with category id already exist or not.
					if (file_exists($uploaddir)) {
					//echo "The file $uploaddir exists";
					} else {
						$newdir = mkdir($uploaddirectory . "/" . $attachid, 0777, true);
					}
				}  
			}
		*/	
			
			// Get All Updated Health Report Lists
			$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","md5(member_id) ='".$member_id."'","id DESC","","","");

			$reports_details= array();
			foreach($reportlist_details as $result_reportList) {

					$getReportList['report_id']=$result_reportList['id'];
					$getReportList['title']=$result_reportList['title'];
					$getReportList['description']=$result_reportList['description'];
					$getReportList['report_date']=$result_reportList['report_date'];
					$getReportList['report_date']=$result_reportList['report_date'];
					$getReportList['date_time']=$result_reportList['created_date'];
					$getReportList['doc_id']="";
					
					$attachment_details = mysqlSelect("id as attachment_id, attachment_name as attachment_name","health_app_healthfile_report_attachments","report_id ='".$result_reportList['id']."'","id ASC","","","");

					$getReportList['attachments']= $attachment_details;

					$getReportList['type']= '1';
					
				array_push($reports_details, $getReportList);
			}
		
		
		$share_tests = array('result' => "success", 'reports_details' => $reports_details, 'err_msg' => '');
		echo json_encode($share_tests);
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
