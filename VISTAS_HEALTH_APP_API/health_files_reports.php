<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");

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

// Helath Files Reports
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$login_id = $user_id;
		$member_id = $_POST['member_id'];
		$txtTitle = addslashes($_POST['title']);
		$txtDescription = addslashes($_POST['description']);
		$txtTimeStamp = $_POST['timeStamp'];
		$txtDate = $_POST['reportDate'];
		
		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'login_id';
		$arrValues[] = $login_id;
		
		$arrFields[] = 'member_id';
		$arrValues[] = $member_id;

		$arrFields[] = 'title';
		$arrValues[] = $txtTitle;

		$arrFields[] = 'description';
		$arrValues[] = $txtDescription;

		$arrFields[] = 'timeStampNum';
		$arrValues[] = $txtTimeStamp;
		
		$arrFields[] = 'created_date';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'report_date';
		$arrValues[] = date('Y-m-d',strtotime($txtDate));


		$usercraete=mysqlInsert('health_app_healthfile_reports',$arrFields,$arrValues);
		$id = $usercraete;

		//Add Lab Test Attachments functionality
		$errors= array();
		foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){

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
						$arrValues_attach[] = $login_id;
						
						$arrFields_attach[] = 'member_id';
						$arrValues_attach[] = $member_id;

						$arrFields_attach[] = 'attachment_name';
						$arrValues_attach[] = $file_name;

						$pat_attach=mysqlInsert('health_app_healthfile_report_attachments',$arrFields_attach,$arrValues_attach);
						$attachid= $pat_attach;
						
						$folder_name	=	"HealthFilesReports";
						$sub_folder		=	$attachid;
						$filename		=	$_FILES['file-3']['name'][$key];
						$file_url		=	$_FILES['file-3']['tmp_name'][$key];
						fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload


						//Uploading image file
						// $uploaddirectory = realpath("../HealthFilesReports");
						// $uploaddir = $uploaddirectory . "/" .$attachid;
						// $dotpos = strpos($fileName, '.');
						// $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
						// $uploadfile = $uploaddir . "/" . $Photo1;


						//Checking whether folder with category id already exist or not.
						// if (file_exists($uploaddir)) {
						//echo "The file $uploaddir exists";
							// } else {
								// $newdir = mkdir($uploaddirectory . "/" . $attachid, 0777);
							// }

							//Moving uploaded file from temporary folder to desired folder.
							// if(move_uploaded_file ($file_tmp, $uploadfile)) {

								// $successAttach="";
									// } else {
											//echo "File cannot be uploaded";
								// }
					}  

		}
		//End of    
		
		
		//$reports_details = mysqlSelect("a.id as report_id, a.member_id as member_id, a.title as title, a.description as description, a.timeStampNum as timeStampNum, a.created_date as created_date, b.id as attachment_id, b.attachment_name as attachment_name","health_app_healthfile_reports as a inner join health_app_healthfile_report_attachments as b on b.report_id = a.id","a.login_id ='".$login_id."'","a.id DESC","","","");
		
		$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","login_id ='".$login_id."'","id DESC","","","");
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
		
	/*	// Update Profie Percentage Number Starts
		$arrFields_profile_percent = array();
		$arrValues_profile_percent = array();
			
		$arrFields_profile_percent[] = 'profile_percentage';
		$arrValues_profile_percent[] = '80';
		$updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$login_id."'");
			
		$login_details = mysqlSelect('*','login_user',"login_id='".$login_id."'","","","","");
		
		 if(empty($login_details[0]['emergency_contact_name']) || empty($login_details[0]['emergency_contact_number']) || empty($login_details[0]['emergency_contact_email'])) {
			$profile_percentage_status = $login_details[0]['profile_percentage'];
		 }
		 else {
			 $profile_percentage_status = '100';
			 $arrFields_profile_percent[] = 'profile_percentage';
			 $arrValues_profile_percent[] = $profile_percentage_status;
			 $updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$login_id."'");
		 }
		
		$login_details1 = mysqlSelect('*','login_user',"login_id='".$login_id."'","","","","");
		$profile_percentage_status1 = $login_details1[0]['profile_percentage'];
		// Update Profie Percentage Number Ends  */
		
		// Update Profie Percentage Number Starts
		$getCurrentStatus = mysqlSelect('*','login_user',"login_id='".$login_id."'","","","",""); 
		$currentStatus = $getCurrentStatus[0]['profile_percentage'];
		if($currentStatus < 80) {
			
			if(empty($getCurrentStatus[0]['emergency_contact_name']) || empty($getCurrentStatus[0]['emergency_contact_number']) || empty($getCurrentStatus[0]['emergency_contact_email'])) {
				 $profile_percentage_status1 = '80';
				 $arrFields_profile_percent[] = 'profile_percentage';
				 $arrValues_profile_percent[] = $profile_percentage_status1;
				 $updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$login_id."'");
			
			}
			else {
				 $profile_percentage_status = '100';
				 $arrFields_profile_percent[] = 'profile_percentage';
				 $arrValues_profile_percent[] = $profile_percentage_status;
				 $updateProfilePercent=mysqlUpdate('login_user',$arrFields_profile_percent,$arrValues_profile_percent,"login_id='".$login_id."'");
			 }
		}
			
		$login_details = mysqlSelect('*','login_user',"login_id='".$login_id."'","","","","");
		$profile_percentage_status = $login_details[0]['profile_percentage'];
		// Update Profie Percentage Number Ends
		
		
		$success_opinion = array('result' => "success", 'status' => '1', 'reports_details' => $reports_details, "profile_percentage_status" => $profile_percentage_status, 'message' => "Your reports uploaded successfully !!!", 'err_msg' => '');
		echo json_encode($success_opinion);
		
	}
	else
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}
?>
