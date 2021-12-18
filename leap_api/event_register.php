<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include("send_mail_function.php");
include("send_text_message.php");
//$ccmail="medical@medisense.me";

ob_start();
$curDate=date('Y-m-d H:i:s');
 	
	$event_id = $_POST['eventid'];
	$user_id = $_POST['userid'];
	$user_type = $_POST['login_type'];	
	
		
	if($user_type=="1"){   //For Hospital Doctor
	$applicantType= "2";
	$userType="Premium User";
	$getApplicantDetails = $objQuery->mysqlSelect("a.ref_name as Doc_Name,a.ref_mail as Email_id,a.contact_num as Contact_num,b.spec_name as Specialization","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$user_id."'","","","","");
	
	}
	else if($user_type=="2"){  //For Standard User
	$applicantType= "1";
	$userType="Standard User";
	$getApplicantDetails = $objQuery->mysqlSelect("a.contact_person as Doc_Name,a.Email_id as Email_id,a.cont_num1 as Contact_num,b.spec_name as Specialization","our_partners as a left join specialization as b on a.specialisation=b.spec_id","a.partner_id='".$user_id."'","","","","");
	
	}

	//Check if this user is already registered or not
	$checkApplicant = $objQuery->mysqlSelect("*","job_event_application","job_id='".$event_id."' and applicant_id='".$user_id."' and applicant_type='".$applicantType."'","","","","");
	if($checkApplicant==true){
		$result = array("result" => "You have already registered this event");
		echo json_encode($result);
	}
	else{
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'applicant_id';
	$arrValues[] = $user_id;
	$arrFields[] = 'applicant_type';
	$arrValues[] = $applicantType;
	$arrFields[] = 'job_id';
	$arrValues[] = $event_id;
	$arrFields[] = 'type';
	$arrValues[] = "Event";	
	$arrFields[] = 'TImestamp';
	$arrValues[] = $curDate;
	$createJob=$objQuery->mysqlInsert('job_event_application',$arrFields,$arrValues);
	$getDocMail = $objQuery->mysqlSelect("b.contact_email as To_mail,a.company_name as OrgName,b.title as JobTitle","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
	
	$getEventName= $objQuery->mysqlSelect("title","offers_events","event_id='".$event_id."'" ,"","","","");
	//$id= mysql_insert_id();
	$tomail=$getDocMail[0]['To_mail'];
					
						$url_page = 'event_registration.php';
						$url = rawurlencode($url_page);
						$url .= "?tomail=" . urlencode($tomail);
						$url .= "&eventtitle=" . urlencode($getEventName[0]['title']);
						$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
						$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
						$url .= "&usertype=" . urlencode($userType);
						send_mail($url);
		
		$result = array("result" => "Successfully registered");
		echo json_encode($result);
	}

?>
