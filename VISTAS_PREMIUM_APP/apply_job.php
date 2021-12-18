<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";

ob_start();
$curDate=date('Y-m-d H:i:s');


$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		$coverNote = addslashes($_POST['coverNote']);
		$event_id = $_POST['event_id'];
		$attachment = basename($_FILES['txtAttach']['name']);
		
		//Check if this user is already applied this job or not
		$checkApplicant = mysqlSelect("*","job_event_application","job_id='".$event_id."' and applicant_id='".$doctor_id."' and applicant_type='2' and type='Job'","","","","");
		if($checkApplicant==true){
			$result = array("result" => "failure");
			echo json_encode($result);
		}
		else{		

			$arrFields = array();
			$arrValues = array();
			
			$arrFields[] = 'applicant_id';
			$arrValues[] = $doctor_id;
			$arrFields[] = 'applicant_type';
			$arrValues[] = "2";
			$arrFields[] = 'job_id';
			$arrValues[] = $event_id;
			$arrFields[] = 'type';
			$arrValues[] = "Job";
			$arrFields[] = 'cover_note';
			$arrValues[] = $coverNote;
			$arrFields[] = 'resume';
			$arrValues[] = $attachment;
			$arrFields[] = 'TImestamp';
			$arrValues[] = $curDate;
			$createJob=mysqlInsert('job_event_application',$arrFields,$arrValues);
			$getDocMail = mysqlSelect("b.contact_email as To_mail,a.company_name as OrgName,b.title as JobTitle","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
			
			$getApplicantDetails = mysqlSelect("a.ref_name as Doc_Name,a.ref_mail as Email_id,a.contact_num as Contact_num,b.spec_name as Specialization","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$doctor_id."'" ,"","","","");
			
			$getEventName= mysqlSelect("title","offers_events","event_id='".$event_id."'" ,"","","","");
			$id= $getEventName;
			
			/* Uploading image file */ 
					if(basename($_FILES['txtAttach']['name']!==""))
					{ 
						$folder_name	=	"Resume";
						$sub_folder		=	$id;
						$filename		=	$_FILES['txtAttach']['name'];
						$file_url		=	$_FILES['txtAttach']['tmp_name'];
						fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

						// $uploaddirectory = realpath("../Resume");
						//mkdir("../Resume/". "/" . $id, 0777);
						// mkdir("../Resume/". "/" . $id, 0777);
						// $uploaddir = $uploaddirectory."/".$id;
						// $dotpos = strpos($_FILES['txtAttach']['name'], '.');
						// $photo = $attachment;
						// $uploadfile = $uploaddir . "/" . $photo;			
					
								
						// /* Moving uploaded file from temporary folder to desired folder. */
						// if(move_uploaded_file ($_FILES['txtAttach']['tmp_name'], $uploadfile)) {
						//	echo "File uploaded.";
						// } else {
						//	echo "File cannot be uploaded";
						// }
					}
					$downloadlink="https://medisensemd.com/Refer/download-Attachments.php?appid=".$id."&resume=".$attachment;
						$tomail=$getDocMail[0]['To_mail'];
						$userType="Premium User";

							$url_page = 'job_application_mail.php';
							$url = rawurlencode($url_page);
							$url .= "?tomail=" . urlencode($tomail);
							$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
							$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
							$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
							$url .= "&specialisation=" . urlencode($getApplicantDetails[0]['Specialization']);
							$url .= "&usertype=" . urlencode($userType);
							$url .= "&jobtitle=" . urlencode($getDocMail[0]['JobTitle']);
							$url .= "&resumelink=" . urlencode($downloadlink);	
							$url .= "&covernote=" . urlencode($coverNote);				
									
							send_mail($url);
							
							//MAIL TO JOB APPLICANT
							if(!empty($getApplicantDetails[0]['Email_id'])){
							$url_page = 'job_application_success_mail.php';
							$userurl = rawurlencode($url_page);
							$userurl .= "?tomail=" . urlencode($getApplicantDetails[0]['Email_id']);
							$userurl .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
							$userurl .= "&jobheading=" . urlencode($getEventName[0]['title']);
							$userurl .= "&orgname=" . urlencode($getDocMail[0]['OrgName']);
							send_mail($userurl);
							}
					
		
			
			$result = array("result" => "success");
			echo json_encode($result);
		}
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
