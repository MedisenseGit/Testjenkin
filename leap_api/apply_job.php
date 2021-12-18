<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();
ob_start();
$curDate=date('Y-m-d H:i:s');
 	
	$coverNote = addslashes($_POST['coverNote']);
	$event_id = $_POST['event_id'];
	$partner_id = $_POST['partner_id'];
	$attachment = basename($_FILES['txtAttach']['name']);
	
	//echo $coverNote;
	//echo $event_id; echo $partner_id;

	//Check if this user is already applied this job or not
	$checkApplicant = $objQuery->mysqlSelect("*","job_event_application","job_id='".$event_id."' and applicant_id='".$partner_id."' and applicant_type='1' and type='Job'","","","","");
	if($checkApplicant==true){
		$result = array("result" => "failure");
		echo json_encode($result);
	}
	else{
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'applicant_id';
	$arrValues[] = $partner_id;
	$arrFields[] = 'applicant_type';
	$arrValues[] = "1";
	$arrFields[] = 'job_id';
	$arrValues[] = $event_id;
	$arrFields[] = 'cover_note';
	$arrValues[] = $coverNote;	
	$arrFields[] = 'type';
	$arrValues[] = "Job";
	$arrFields[] = 'resume';
	$arrValues[] = $attachment;
	$arrFields[] = 'TImestamp';
	$arrValues[] = $curDate;
	$createJob=$objQuery->mysqlInsert('job_event_application',$arrFields,$arrValues);
	$getDocMail = $objQuery->mysqlSelect("b.contact_email as To_mail,a.company_name as OrgName,b.title as JobTitle","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
	
	$getApplicantDetails = $objQuery->mysqlSelect("a.contact_person as Doc_Name,a.Email_id as Email_id,a.cont_num1 as Contact_num,b.spec_name as Specialization","our_partners as a left join specialization as b on a.specialisation=b.spec_id","a.partner_id='".$partner_id."'" ,"","","","");
	$getEventName= $objQuery->mysqlSelect("title","offers_events","event_id='".$event_id."'" ,"","","","");
	$id= mysql_insert_id();
	/* Uploading image file */ 
				if(basename($_FILES['txtAttach']['name']!=="")){ 
					$uploaddirectory = realpath("../Resume");
					// mkdir("../Resume/". "/" . $id, 0777);
					mkdir("../Resume/". "/" . $id, 0777);
					$uploaddir = $uploaddirectory."/".$id;
					$dotpos = strpos($_FILES['txtAttach']['name'], '.');
					$photo = $attachment;
					$uploadfile = $uploaddir . "/" . $photo;			
				
							
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtAttach']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}
				$downloadlink="https://medisensecrm.com/Refer/download-Attachments.php?appid=".$id."&resume=".$attachment;
					$tomail=$getDocMail[0]['To_mail'];
					$userType="Standard User";
					//$tomail="salmabanu.h@gmail.com";

						$url_page = 'job_application_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?tomail=" . urlencode($tomail);
						$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
						$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
						$url .= "&specialisation=" . urlencode($getApplicantDetails[0]['Specialization']);
						$url .= "&jobtitle=" . urlencode($getDocMail[0]['JobTitle']);
						$url .= "&usertype=" . urlencode($userType);
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

?>
