<?php
ob_start();
session_start();
error_reporting(1);  

include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");
//

$admin_id = $_SESSION['user_id'];
//$ccmail="medical@medisense.me";

//Random Password Generator
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function createKey(){
	//create a random key
	$strKey = md5(microtime());
	
	//check to make sure this key isnt already in use
	$resCheck = mysql_query("SELECT count(*) FROM patient_attachment WHERE downloadkey = '{$strKey}' LIMIT 1");
	$arrCheck = mysql_fetch_assoc($resCheck);
	if($arrCheck['count(*)']){
		//key already in use
		return createKey();
	}else{
		//key is OK
		return $strKey;
	}
}

function hyphenize($string) {
	
    return 
    ## strtolower(
          preg_replace(
            array('#[\\s+]+#', '#[^A-Za-z0-9\. -]+#', '/\@^|(\.+)/'),
            array('-',''),
        ##     cleanString(
              urldecode($string)
        ##     )
        )
    ## )
    ;
}
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');



//$hostname="http://beta.referralio.com"; //For Beta version
$hostname=HOST_MAIN_URL;//"https://medisensecrm.com/"; //For Prod version
//Image Compress functionality
$name = ''; $type = ''; $size = ''; $error = '';
	function compress_image($source_url, $destination_url, $quality) {

		$info = getimagesize($source_url);

    		if ($info['mime'] == 'image/jpeg')
        			$image = imagecreatefromjpeg($source_url);

    		elseif ($info['mime'] == 'image/gif')
        			$image = imagecreatefromgif($source_url);

   		elseif ($info['mime'] == 'image/png')
        			$image = imagecreatefrompng($source_url);

    		imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}
//REGISTER EVENT
if(isset($_POST['cmdReg'])){

	$event_id = $_POST['event_id'];
	$doc_id = $_POST['doc_id'];
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'applicant_id';
	$arrValues[] = $doc_id;
	$arrFields[] = 'applicant_type';
	$arrValues[] = "2";
	$arrFields[] = 'job_id';
	$arrValues[] = $event_id;
	$arrFields[] = 'type';
	$arrValues[] = "Event";

	$arrFields[] = 'TImestamp';
	$arrValues[] = $curDate;
	
	$createJob=mysqlInsert('job_event_application',$arrFields,$arrValues);
	$getDocMail = mysqlSelect("b.contact_email as Contact_mail,a.company_name as OrgName","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
	
	$getApplicantDetails = mysqlSelect("a.contact_person as Doc_Name,a.Email_id as Email_id,a.cont_num1 as Contact_num,b.spec_name as Specialization","our_partners as a left join specialization as b on a.specialisation=b.spec_id","a.partner_id='".$admin_id."'" ,"","","","");
	$getEventName= mysqlSelect("title","offers_events","event_id='".$event_id."'" ,"","","","");
	$id= $getEventName;
	
					$tomail=$getDocMail[0]['Contact_mail'];
					$userType="Premium User";
						$url_page = 'event_registration.php';
						$url = rawurlencode($url_page);
						$url .= "?tomail=" . urlencode($tomail);
						$url .= "&eventtitle=" . urlencode($getEventName[0]['title']);
						$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
						$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
						$url .= "&usertype=" . urlencode($userType);
						send_mail($url);
						
					
				
		header("location:Offers?s=Events&id=".md5($event_id)."&response=event-success");
}
//APPLY JOB
if(isset($_POST['addJobRequest'])){

	$coverNote = addslashes($_POST['coverNote']);
	$event_id = $_POST['event_id'];
	$partner_id = $_POST['doc_id'];
	$attachment = basename($_FILES['txtAttach']['name']);
	
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'applicant_id';
	$arrValues[] = $partner_id;
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
	$getDocMail = mysqlSelect("b.contact_email as Contact_mail,a.company_name as OrgName","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
	
	$getApplicantDetails = mysqlSelect("a.ref_name as Doc_Name,a.ref_mail as Email_id,a.contact_num as Contact_num,b.spec_name as Specialization","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$admin_id."'" ,"","","","");
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
				$downloadlink=HOST_MAIN_URL."Refer/download-Attachments.php?appid=".$id."&resume=".$attachment;
					$tomail=$getDocMail[0]['Contact_mail'];
					$userType="Premium User";
						$url_page = 'job_application_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?tomail=" . urlencode($tomail);
						$url .= "&jobtitle=" . urlencode($getEventName[0]['title']);
						$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
						$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
						$url .= "&specialisation=" . urlencode($getApplicantDetails[0]['Specialization']);
						$url .= "&usertype=" . urlencode($userType);
						$url .= "&resumelink=" . urlencode($downloadlink);	
						$url .= "&covernote=" . urlencode($coverNote);				
								
						send_mail($url);
						
						//MAIL TO JOB APPLICANT
						if(!empty($getApplicantDetails[0]['Email_id'])){
						$url_page = 'job_event_application_success_mail.php';
						$userurl = rawurlencode($url_page);
						$userurl .= "?tomail=" . urlencode($getApplicantDetails[0]['Email_id']);
						$userurl .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$userurl .= "&jobheading=" . urlencode($getEventName[0]['title']);
						$userurl .= "&orgname=" . urlencode($getDocMail[0]['OrgName']);
						send_mail($userurl);
						}
				
		header("location:offers.php?s=Jobs&id=".$_POST['id']."&response=job-success");
}	
 
//TURN TO DIRECT APPOINTMENT
if(isset($_POST['cmdAppt'])){
	
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //GET TRANSACTION ID
	
$chkRefInfo = mysqlSelect("*","patient_referal","ref_id='".$txtRefId."' and patient_id='".$patientID."'","","","","");
$arrFields2 = array();
$arrValues2 = array();
$arrFields2[]= 'patient_id'; 
$arrValues2[]= $patientID;
$arrFields2[]= 'ref_id'; 
$arrValues2[]= $txtRefId;
$arrFields2[]= 'status1';
$arrValues2[]= "1";
$arrFields2[]= 'status2';
$arrValues2[]= "7";
$arrFields2[]= 'conversion_status';
$arrValues2[]= "2";

if($chkRefInfo==true){
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$patientID."' and ref_id='".$txtRefId."'");
$arrFields1 = array();
$arrValues1 = array();
$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "OP-DESIRED"
$arrValues1[]= "8";
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patientID."'");

}

$chkPatInfo = mysqlSelect("patient_id,patient_name,patient_email,patient_mob,TImestamp","patient_tab","patient_id='".$patientID."'","","","","");	
$get_pro = mysqlSelect('a.ref_id as ref_id,a.ref_name as ref_name,a.ref_address as ref_address,a.doc_state as doc_state,a.doc_spec as doc_spec,a.doc_photo as doc_photo,c.hosp_name as hosp_name,d.company_name as company_name,d.email_id as CompEmail','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id',"a.ref_id='".$txtRefId."'");
$getDepartment = mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
									
						if(!empty($chkPatInfo[0]['patient_email'])){
							
							
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
						$url_page = 'Custom_Turn_to_Appointment.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
						$url .= "&compemail=" . urlencode($get_pro[0]['CompEmail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
						}	
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an appointment. Thx, ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Appointment Link for ".$get_pro[0]['ref_name']."has been sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $patientID;
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $txtRefId;
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= "7";
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $curDate;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
										
					$Successmessage="Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
	$response="Appointment-Success";
	header("Location:patient-history?p=".md5($patientID)."&response=".$response);			
}

//SEND PAYMENT LINK
if(isset($_POST['cmdPay'])){
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //UPDATE TRANSACTION ID	
$arrFields = array();
$arrValues = array();
$arrFields[]= 'transaction_id';
$arrValues[]= $trans_id;
$arrFields[]= 'transaction_status';
$arrValues[]= "Pending";

$editPatient=mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$_POST['patient_id']."'");


$chkDocStatus = mysqlSelect("status2","patient_referal","patient_id='".$_POST['patient_id']."'and ref_id='".$txtRefId."'","","","","");
$chkPatInfo = mysqlSelect("patient_id,patient_name,patient_mob,patient_email,pat_country,TImestamp","patient_tab","patient_id='".$_POST['patient_id']."'","","","","");	
$get_pro = mysqlSelect('a.ref_id as ref_id,a.ref_name as ref_name,a.doc_spec as doc_spec,a.on_op_cost as on_op_cost,a.doc_photo as doc_photo,a.ref_address as ref_address,a.doc_state as doc_state,c.hosp_name as hosp_name,d.company_name as companyName,d.email_id as compEmail,d.company_logo as compLogo','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id',"a.ref_id='".$txtRefId."'");
$getDepartment = mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
				
				if(!empty($get_pro[0]['on_op_cost'])){
						
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
						$service="Second Opinion";
						if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']=="India")
						{ //DOMESTIC PATIENT MAIL
						
						
						$opcost=$get_pro[0]['on_op_cost'].".00";
						$paylink=HOST_HEALTH_URL."turn-to-pay.php?patid=".$_POST['patient_id']."&patname=".$chkPatInfo[0]['patient_name']."&mobile=".$chkPatInfo[0]['patient_mob']."&email=".$chkPatInfo[0]['patient_email']."&amount=".$opcost."&service=".$service."&docname=".$get_pro[0]['ref_name']."&docid=".$txtRefId;
							if($chkDocStatus[0]['status2']==5){ //IF DOCTOR ALREADY RESPONDED TO PATIENT QUERY THEN FOLLOWING PAYMENT MAIL WILL SEND TO PATIENT
								$url_page = 'Custom_Turn_to_Paylink.php';
							}else{
								$url_page = 'Custom_Turn_to_Paylink.php';
							}
								
								$url = rawurlencode($url_page);
								$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
								$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
								$url .= "&docimg=".urlencode($docimg);
								$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
								$url .= "&doclink=".urlencode($Link);
								$url .= "&regdate=" . urlencode($reg_date);
								$url .= "&paylink=".urlencode($paylink);
								$url .= "&docamount=".urlencode($get_pro[0]['on_op_cost']);
								$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
								$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
								$url .= "&patmobile=" . urlencode($chkPatInfo[0]['patient_mob']);					
								$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
								$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
								$url .= "&compemail=" . urlencode($get_pro[0]['compEmail']);
								$url .= "&ccmail=" . urlencode($ccmail);		
								
								send_mail($url);
						}
						else if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']!="India")
						{ //INTERNATIONAL PATIENT MAIL (PAYPAL LINK NEED TO BE SEND)
						
						
						$url_page = 'Custom_Non_Indian_paylink.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmobile=" . urlencode($chkPatInfo[0]['patient_mob']);					
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
						$url .= "&compemail=" . urlencode($get_pro[0]['compEmail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
						}
								
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = $get_pro[0]['ref_name']."-Action Required. We have sent you a mail. Please complete the action to get an opinion. Thanks, Medisensehealth.com";
					
					//send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Payment Link Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $_POST['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= '0';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//SET PAYEMNT REMINDER TABLE
					$arrFields3 = array();
					$arrValues3 = array();
									
					$arrFields3[]= 'patient_id';
					$arrValues3[]= $_POST['patient_id'];
					$arrFields3[]= 'doc_id';
					$arrValues3[]= $get_pro[0]['ref_id'];
					$arrFields3[]= 'reminder_count';
					$arrValues3[]= '0';
					$arrFields3[]= 'payment_status';
					$arrValues3[]= '1';
					$arrFields3[]= 'TImestamp';
					$arrValues3[]= $Cur_Date;
					$inserReminder=mysqlInsert('payment_reminder',$arrFields3,$arrValues3);
					
					$Successmessage="Payment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
				
				}
				else{
					$errormessage="Error !!!! Please check this Expert Opinion Cost";
					
				}
}
	

//RESCHEDULE APPOINTMENT	
if($_POST['act']=="add-reschedule"){
	
	$visitDate = date('Y-m-d',strtotime($_POST['appDate']));
	$slctTime = $_POST['appTime'];
	
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['patTransId']."'");
		
		$getInfo1 = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['patTransId']."'" ,"","","","");	
		$getDoc = mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks";
	send_msg($mobile,$responsemsg);
	
}

	
if(isset($_POST['ref_appointment'])){

	$chkInDate = $_POST['dateadded'];
	$chkInTime = $_POST['check_time'];
	$txtName = $_POST['se_pat_name'];
	$txtAge = $_POST['se_pat_age'];
	$txtMail = $_POST['se_email'];
	$txtGen = $_POST['se_gender'];
	//$chkDate = date('Y-m-d',strtotime($Cur_Date));
	
	$txtContact = addslashes($_POST['se_con_per']);
	$txtMob = addslashes($_POST['se_phone_no']);
	$txtAddress = addslashes($_POST['se_address']);
	$txtLoc = addslashes($_POST['se_city']);
	$txtCountry = addslashes($_POST['se_country']);
	$txtState = addslashes($_POST['se_state']);
	
	$docspec = addslashes($_SESSION['docspec']);
	$transid=time();
	$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$admin_id."'");
	
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;

			$arrFields_patient[] = 'patient_age';
			$arrValues_patient[] = $txtAge;

			$arrFields_patient[] = 'patient_email';
			$arrValues_patient[] = $txtMail;

			$arrFields_patient[] = 'patient_gen';
			$arrValues_patient[] = $txtGen;

		
			/*pat_blood*/

			$arrFields_patient[] = 'contact_person';
			$arrValues_patient[] = $txtContact;

			/*profession*/

			$arrFields_patient[] = 'patient_mob';
			$arrValues_patient[] = $txtMob;

			$arrFields_patient[] = 'patient_loc';
			$arrValues_patient[] = $txtLoc;

			$arrFields_patient[] = 'pat_state';
			$arrValues_patient[] = $txtState;

			$arrFields_patient[] = 'pat_country';
			$arrValues_patient[] = $txtCountry;

			$arrFields_patient[] = 'patient_addrs';
			$arrValues_patient[] = $txtAddress;

			$arrFields_patient[] = 'doc_id';
			$arrValues_patient[] = $admin_id;

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = date('Y-m-d');
			
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = $curDate;
		
			$arrFields_patient[] = 'transaction_id';
			$arrValues_patient[] = $transid;
		
		$patientcreate=mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
		$patientid = $patientcreate;  //Get Patient Id
		
		//Insert to new_hospvisitor_details table
				$arrFields = array();
				$arrValues = array();
				
				$arrFields[] = 'Transaction_id';
				$arrValues[] = $transid;
				$arrFields[] = 'pat_name';
				$arrValues[] = $txtName;
				$arrFields[] = 'Email_id';
				$arrValues[] = $txtMail;
				$arrFields[] = 'Mobile_number';
				$arrValues[] = $txtMob;
				$arrFields[] = 'pat_age';
				$arrValues[] = $txtAge;
				$arrFields[] = 'pat_gen';
				$arrValues[] = $txtGen;
				$arrFields[] = 'City';
				$arrValues[] = $txtLoc;
				$arrFields[] = 'State';
				$arrValues[] = $txtState;
				$arrFields[] = 'Country';
				$arrValues[] = $txtCountry;
				$arrFields[] = 'Address';
				$arrValues[] = $txtAddress;
		
				
				$craetevisitor=mysqlInsert('new_hospvisitor_details',$arrFields,$arrValues);
				$newvisitorid= $craetevisitor;
				$getPatInfo = mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
		
				
				$arrFields1 = array();
				$arrValues1 = array();
				
				$arrFields1[] = 'appoint_trans_id';
				$arrValues1[] = $transid;
				$arrFields1[] = 'pref_doc';
				$arrValues1[] = $admin_id;
				$arrFields1[] = 'department';
				$arrValues1[] = $get_pro[0]['doc_spec'];
				$arrFields1[] = 'Visiting_date';
				$arrValues1[] = date('Y-m-d',strtotime($chkInDate));
				$arrFields1[] = 'Visiting_time';
				$arrValues1[] = $chkInTime;
				$arrFields1[] = 'patient_name';
				$arrValues1[] = $txtName;
				$arrFields1[] = 'Mobile_no';
				$arrValues1[] = $txtMob;
				$arrFields1[] = 'Email_address';
				$arrValues1[] = $txtMail;
				
				$arrFields1[] = 'pay_status';
				$arrValues1[] = "Pending";
				$arrFields1[] = 'visit_status';
				$arrValues1[] = "new_visit";
				$arrFields1[] = 'Time_stamp';
				$arrValues1[] = $curDate;
				
				$createappointment=mysqlInsert('appointment_transaction_detail',$arrFields1,$arrValues1);
	
	/*$docmsg="Dear Doctor, ".$getPatInfo[0]['patient_name']."( Ph: ".$getPatInfo[0]['patient_mob']." )has expressed interest to meet you in person. For more info please login into your medisense leap dash board or email . Thanks";
	$mobile = $get_pro[0]['contact_num'];
	send_msg($mobile,$docmsg);*/
	
					
	$getTime=mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
	
	//Patient Info EMAIL notification Sent to Doctor
		if(!empty($get_pro[0]['ref_mail'])){
		$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
		
					$url_page = 'pat_appointment_info.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatInfo[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatInfo[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatInfo[0]['patient_email']);
					$url .= "&patContactName=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
					$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
					$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
					$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
					$url .= "&ccmail=" . urlencode($ccmail);	
					$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
					send_mail($url);	
		}	

	
	$response="appointment-success";
	header("Location:Appointments?response=".$response);

	
}
	
	
//Add Blog Post
if(isset($_POST['cmdBlg'])){
	$blogTitle= addslashes($_POST['blog_title']);
	$txtRefId= $admin_id;
	$blogDesc= addslashes($_POST['descr']);
	$blog_pic = basename($_FILES['txtPhoto']['name']);
	$postkey=time();
	$logintype="doc";
	
	$getDocDetails = mysqlSelect("a.ref_id as ref_id,b.hosp_id as hosp_id,c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
	
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $blogTitle;
	$arrFields[]= 'Login_User_Id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'post_description';
	$arrValues[]= $blogDesc;
	$arrFields[]= 'post_type';
	$arrValues[]= "blog";
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $logintype;
	$arrFields[]= 'company_id';
	$arrValues[]= $getDocDetails[0]['company_id'];
	$arrFields[]= 'hosp_id';
	$arrValues[]= $getDocDetails[0]['hosp_id'];
	$arrFields[]= 'post_date';
	$arrValues[]= $curDate;
	$arrFields[]= 'post_image';
	$arrValues[]= $blog_pic;
	$addblogs=mysqlInsert('home_posts',$arrFields,$arrValues);
	$blog_id= $addblogs;
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $blog_id;
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Blog";
	$arrFields1[]= 'hosp_id';
	$arrValues1[]= $getDocDetails[0]['hosp_id'];
	$arrFields1[]= 'company_id';
	$arrValues1[]= $getDocDetails[0]['company_id'];
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	
	$searchTags=$_POST['searchTags'].",".$blogTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	$arrFields_search[]= 'type_id';
	$arrValues_search[]= $blog_id;
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Blog";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"Postimages";
					$sub_folder		=	$blog_id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

					// $uploaddirectory = realpath("Postimages");
					// mkdir("Postimages/". "/" . $blog_id, 0777);
					// $uploaddir = $uploaddirectory."/".$blog_id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $blog_pic;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
	
	header("Location:Blog-Surgical-List");
}	
//ADD SURGICAL VIDEO	
if(isset($_POST['video_publish'])){
	
	$videoTitle	= 	addslashes($_POST['video_title']);
	$txtRefId	= 	$admin_id;
	$videoUrl	= 	$_POST['video_link'];
	$videoDesc	= 	addslashes($_POST['video_Description']);
	$postkey	=	time();
	$getCode  	= 	str_replace("https://www.youtube.com/watch?v=", "", $videoUrl);
	$mainDesc='<p>'.$videoDesc.'</p>';
	
	$getDocDetails = mysqlSelect("a.ref_id as ref_id,b.hosp_id as hosp_id,c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
	$loginid=$txtRefId;
	$logintype="doc";
	
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $videoTitle;
	$arrFields[]= 'Login_User_Id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'post_description';
	$arrValues[]= $mainDesc;
	$arrFields[]= 'video_url';
	$arrValues[]= $videoUrl;
	$arrFields[]= 'video_id';
	$arrValues[]= $getCode;
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $logintype;
	$arrFields[]= 'post_type';
	$arrValues[]= "surgical";
	$arrFields[]= 'company_id';
	$arrValues[]= $getDocDetails[0]['company_id'];
	$arrFields[]= 'hosp_id';
	$arrValues[]= $getDocDetails[0]['hosp_id'];
	$arrFields[]= 'post_date';
	$arrValues[]= $curDate;
	
	
	$addblogs	=	mysqlInsert('home_posts',$arrFields,$arrValues);
	$blog_id	= 	$addblogs;
	
	
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $blog_id;
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Surgical";
	$arrFields1[]= 'company_id';
	$arrValues1[]= $getDocDetails[0]['company_id'];
	$arrFields1[]= 'hosp_id';
	$arrValues1[]= $getDocDetails[0]['hosp_id'];
	$arrFields1[]= 'Create_Date';
	$arrValues1[]= $curDate;
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	
	$searchTags=$_POST['searchTags'].",".$videoTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	$arrFields_search[]= 'type_id';
	$arrValues_search[]= $blog_id;
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Surgical";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	
	header("Location:Blog-Surgical-List");

}	
	
	
	
//CHANGE PASSWORD 
if(isset($_POST['change_password'])){
	 
	 $txtPass = md5($_POST['new_password']);
	 $txtRePass = md5($_POST['retype_password']);
	
	$result = mysqlSelect('ref_id','referal',"ref_id='".$_POST['Prov_Id']."'");
	if($txtPass==$txtRePass){
	
		
		$arrFields = array();
		$arrValues = array();		
		
		$arrFields[] = 'doc_password';
		$arrValues[] = $txtPass;
		
		
		$editrecord=mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$_POST['Prov_Id']."'");
		
						
		header('location:Password?response=password');
	}
	else{
	header('location:Password?response=error-password');	
	}

}
//SHARE LINK TO EMAIL(Inner Page)
if(isset($_POST['cmdshareinner'])){
	
	

	if($_POST['receiverMail']!="")
	{
				$page_url = 'share_post_link.php';
				$paturl = rawurlencode($page_url);
				$paturl .= "?sharelink=".urlencode($_POST['shareLink']);										
				$paturl .= "&receiverMail=".urlencode($_POST['receiverMail']);
				$paturl .= "&subject=".urlencode($_POST['mailsub']);		
				send_mail($paturl);
	
	$response="share-link-success";
		
		header('location:'.HOST_MAIN_URL.''.$_POST['currenturl'].'&response='.$response);
	}
	else{
		$_SESSION['status']="error";
		
		header('location:'.HOST_MAIN_URL.''.$_POST['currenturl']);
	}
}	
//SEND APPOINTMENT/OPINION LINK
 if(isset($_POST['sendappointment'])){
	$getDoc = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.ref_mail as ref_mail,c.hosp_name as hosp_name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'" ,"","","","");	
	$weblink=HOST_MAIN_URL."SendRequestLink/?d=".md5($getDoc[0]['ref_id']);	 
	//Send SMS to requested person
	if($_POST['pat_mobile']!="")
	{
		$mobile = $_POST['pat_mobile'];
		$msg = $getDoc[0]['ref_name']." - For Appointments/Opinion Please visit " . $weblink." - Thank you";
					
		send_msg($mobile,$msg);
	}	
	
	if($_POST['pat_email']!="")
	{
	$page_url = 'Custom_send_request_link.php';
						$paturl = rawurlencode($page_url);
						$paturl .= "?doclink=".urlencode($weblink);										
						$paturl .= "&custmail=".urlencode($_POST['pat_email']);
						$paturl .= "&hospName=".urlencode($getDoc[0]['hosp_name']);
						$paturl .= "&docEmail=".urlencode($getDoc[0]['ref_mail']);
						$paturl .= "&ccmail=".urlencode($ccmail);		
						send_mail($paturl);
	}
	$response="send";
	header("Location:Blogs-Offers-Events-List?response=".$response);			

 }

	
	
//RESCHEDULE APPOINTMENT	
if(isset($_POST['cmdreschedule'])){
	
	$visitDate = date('Y-m-d',strtotime($_POST['reschedule_date']));
	$slctTime = $_POST['selectTime'];
	
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['Pat_Trans_Id']."'");
		
		$getInfo1 = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['Pat_Trans_Id']."'" ,"","","","");	
		$getDoc = mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thx";
	send_msg($mobile,$responsemsg);
	$response="reschedule";
	header("Location:appointment_patient_history.php?pattransid=".$_POST['Pat_Trans_Id']."&response=".$response);			

}	
	
	
//Doctor reassign functionality
if(isset($_POST['cmdreassign'])){
	$patid = $_POST['patientid'];
	$SelectRef = $_POST['selectref'];
	
	
	$arrFields[]= 'ref_id';
	$arrValues[]= $SelectRef;
	$arrFields[]= 'timestamp';
	$arrValues[]= $curDate;
	
	$updatereferral=mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$_POST['oldrefid']."'");	

	$updateChatHistory=mysqlUpdate('chat_notification',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$_POST['oldrefid']."'");	
	
	//Get Reassigned Doctor details
	$getReassignedDoc=mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_spec as doc_spec,a.Total_Referred as Total_Referred,c.communication_status as communication_status,a.doc_photo as doc_photo,a.ref_address as ref_address,a.doc_state as doc_state,c.hosp_name as hosp_name,c.company_id as company_id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$SelectRef."'","","","","");	
	$getReassignedDocSpec=mysqlSelect("spec_name","specialization","spec_id='".$getReassignedDoc[0]['doc_spec']."'","","","","");
	$reassignmsg="Case has been re-assign to ".$getReassignedDoc[0]['ref_name'];
	$get_organisation=mysqlSelect("company_id,company_name,company_logo,email_id,mobile","compny_tab","company_id='".$getReassignedDoc[0]['company_id']."'","","","","");	
	
		
	
	$arrFields1 = array();
	$arrValues1 = array();
	$arrFields1[]= 'patient_id';
	$arrValues1[]= $patid;
	$arrFields1[]= 'ref_id';
	$arrValues1[]= $SelectRef;
	$arrFields1[]= 'status_id';
	$arrValues1[]= "2";
	$arrFields1[]= 'chat_note';
	$arrValues1[]= $reassignmsg;
	$arrFields1[]= 'TImestamp';
	$arrValues1[]= $curDate;
	$addoffers=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
	
	//Update Old Doctor No.of referral count, ie. we should decrement it by one
	//Get Reassigned Old Doctor details
	$getOldDoc=mysqlSelect("*","referal","ref_id='".$_POST['oldrefid']."'","","","","");
	$getUpdateCount=$getOldDoc[0]['Total_Referred']-1;//Decrement it by one
	
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[]= 'Total_Referred';
	$arrValues2[]= $getUpdateCount;
	
	$updatereferral1=mysqlUpdate('referal',$arrFields2,$arrValues2,"ref_id='".$_POST['oldrefid']."'");	

	
	//Update Ressigned Doctor No.of referral count, ie. we should increment it by one
	$getUpdateNewCount=$getReassignedDoc[0]['Total_Referred']+1;//Increment it by one
	
	$arrFields3[]= 'Total_Referred';
	$arrValues3[]= $getUpdateNewCount;
	
	$updatereferral2=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$SelectRef."'");
	
	if(!empty($_POST['patemail']) && $getReassignedDoc[0]['communication_status']!=0){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($getReassignedDoc[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$getReassignedDoc[0]['ref_id']."/".$getReassignedDoc[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getReassignedDocSpec[0]['spec_name']));
						$getDocName=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_name']));
						$getDocCity=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$getReassignedDoc[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$getReassignedDoc[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.$getReassignedDoc[0]['ref_id'];
						$compLogo=HOST_MAIN_URL.'Hospital/company_logo/'.$get_organisation[0]['company_id'].'/'.$get_organisation[0]['company_logo'];
	
											
						$url_page = 'Custom_after_reassign_pat_mail.php';
						$ccmail="medical@medisense.me";
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($getReassignedDoc[0]['ref_name']);
						$url .= "&docid=" . urlencode($getReassignedDoc[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getReassignedDoc[0]['spec_name']);					
						$url .= "&patid=" . urlencode($_POST['patientid']);
						$url .= "&patname=" . urlencode($_POST['patname']);					
						$url .= "&patmail=" . urlencode($_POST['patemail']);
						$url .= "&hospName=".urlencode($getReassignedDoc[0]['hosp_name']);
						$url .= "&compLogo=".urlencode($compLogo);
						$url .= "&compMail=".urlencode($get_organisation[0]['email_id']);
						$url .= "&ccmail=" . urlencode($ccmail);		
						//send_mail($url);		
					
					}
	
	//Message Notification to Partner
	$getPartnerInfo=mysqlSelect("a.partner_name as partner_name,a.cont_num1 as cont_num,a.Email_id as Email_id","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$_POST['sourceid']."'","","","","");
	$mobile = $getPartnerInfo[0]['cont_num'];
	$responsemsg = "Dear ".$getPartnerInfo[0]['partner_name'].", ".$_POST['patname']."(".$_POST['patientid']."), patient medical query has been reassigned to ".$getReassignedDoc[0]['ref_name'].". Thanks ".$getReassignedDoc[0]['hosp_name'];
	send_msg($mobile,$responsemsg);
	
	
	//Message Notification to patient
	//$mobile = $_POST['patmobile'];
	//$responsemsg = "Dear ".$_POST['patname'].", Your medical query has been reassigned to ".$getReassignedDoc[0]['ref_name'].". Thanks ".$getReassignedDoc[0]['hosp_name'];
	//send_msg($mobile,$responsemsg);
	
	$response="reassign";
	header("Location:Cases-Recieved?response=".$response);
	
}

	
//Search By Name,Email,Location & Contact No.
if(isset($_POST['postTextSrchCmd'])){
	$txtSearch = addslashes($_POST['postTextSrch']);
	header("Location:search.php?s=".$txtSearch);
	
}


//Add Offers & Events
if(isset($_POST['addOffers']) || isset($_POST['editOffers'])){

	$startDate= $_POST['startendDate'];
	$slctHosp= $_POST['selectHosp'];
	$docId= $_POST['selectref'];
	//$marketId= $_POST['selectmarket'];
	$offerTitle= addslashes($_POST['offer_title']);
	$eventType= $_POST['eventType'];
	$Descr= addslashes($_POST['descr']);
	$event_pic = basename($_FILES['txtPhoto']['name']);	
	$event_key=time();
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'event_key';
	$arrValues[]= $event_key;
	$arrFields[]= 'start_end_date';
	$arrValues[]= $startDate;
	$arrFields[]= 'oganiser_doc_id';
	$arrValues[]= $docId;
	$arrFields[]= 'organiser_market_id';
	$arrValues[]= $marketId;
	$arrFields[]= 'title';
	$arrValues[]= $offerTitle;
	$arrFields[]= 'description';
	$arrValues[]= $Descr;
	$arrFields[]= 'event_type';
	$arrValues[]= $eventType;
	$arrFields[]= 'company_id';
	$arrValues[]= $admin_id;	
	$arrFields[]= 'hosp_id';
	$arrValues[]= $slctHosp;
	$arrFields[]= 'photo';
	$arrValues[]= $event_pic;
	
	if(isset($_POST['addOffers'])){
	$addoffers=mysqlInsert('offers_events',$arrFields,$arrValues);
	$id= $addoffers;
	
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $id;
		if($eventType==1){
		$arrFields1[]= 'listing_type';
		$arrValues1[]= "Events";
		}
		else
		{
		$arrFields1[]= 'listing_type';
		$arrValues1[]= "Offers";	
		}
	$arrFields1[]= 'company_id';
	$arrValues1[]= $admin_id;
	$arrFields1[]= 'hosp_id';
	$arrValues1[]= $slctHosp;
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	$response="add";
	} else if(isset($_POST['editOffers'])){
		$updateOffer=mysqlUpdate('offers_events',$arrFields,$arrValues,"event_id='".$_POST['Event_Id']."'");	
			
	$id= $_POST['Event_Id'];
	$response="update";
	}
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"Hospital/Eventimages";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					

					// $uploaddirectory = realpath("../Hospital/Eventimages");
					// mkdir("../Hospital/Eventimages/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $event_pic;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
					//	echo "File uploaded.";
					// } else {
					//	echo "File cannot be uploaded";
					// }
				}
		//Here we need to Send Push notification to mapped partners
		$getrefPartlist = mysqlSelect("*","our_partners as a left join mapping_hosp_referrer as b on a.partner_id=b.partner_id","b.hosp_id='".$slctHosp."'","","","","");
	
		$msg="";
		$title=substr($offerTitle,0,20);
		$subtitle=substr($Descr,0,20);
		$tickerText="New Event";
		$type="2"; //For Event type 2
		$patientid="0";
		$docid=$admin_id;
			if(!empty($event_pic)){
			$largeimg=$hostname."/Hospital/Eventimages/".$id."/".$event_pic;
			}
			else
			{
			$largeimg='large_icon';	
			}	
			
			$smalimg=HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
		foreach($getrefPartlist as $PartList){
		$regid=$PartList['gcm_tokenid'];		
		push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$id,$patientid,$docid,$event_key);
		}
		
		//Push notification for Doctors
		//Retrieve all doctors gcm id
		$getDoclist = mysqlSelect("gcm_tokenid as GCM","referal","gcm_tokenid!=''","","","","");
		foreach($getDoclist as $DocList){
		$regid=$DocList['GCM'];		
		push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
		}
		//End Push notification functionality
		
	header("Location:Offers-Events?response=".$response);
	
}


	
if(isset($_POST['addRef'])){
	
	$txtRefId= $_POST['txtref'];

	if($txtRefId!=""){
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'patient_id';
	$arrValues1[]= $_POST['Pat_Id'];
	$arrFields1[]= 'ref_id';
	$arrValues1[]= $txtRefId;
	$arrFields1[]= 'email_status';
	$arrValues1[]= "1";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	$arrFields1[]= 'status2';
	$arrValues1[]= "2";
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= "2";
	$arrFields1[]= 'timestamp';
	$arrValues1[]= $Cur_Date;
	$chkreflist = mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$txtRefId."'","","","","");
	if($chkreflist==true){
		$errorMessage="Sorry '".$chkreflist[0]['ref_name']."' referal is already existed";
	}else{
		
			$getPatInfo = mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$getPatAttach= mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
			$getDepartment = mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			$getDocDept = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$txtRefId."'","","","","");
			
			if($getPatInfo[0]['patient_loc']=="" || $getPatInfo[0]['contact_person']=="" || $getPatInfo[0]['patient_mob']=="" || $getPatInfo[0]['pat_country']=="" || $getPatInfo[0]['patient_complaint']=="" || $getPatInfo[0]['patient_desc']=="" || $getPatInfo[0]['repnotattach']==0){
			
			echo '<script language="javascript">';
			echo 'alert("Please fill the required patient details properly")';
			echo '</script>'; 
			
			} else if($getPatAttach==true || $getPatInfo[0]['repnotattach']==1 ) {
		
						mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$_POST['Pat_Id']."'");	
						$patientRef=mysqlInsert('patient_referal',$arrFields1,$arrValues1);
						$ref_id=$patientRef;
						$pat_id = $_POST['Pat_Id'];
						$_SESSION['Ref_Id']=$txtRefId;
	
						
						if($getPatInfo[0]['patient_gen']==1){
							
							$Pat_Gen="Male";
						} else {
							$Pat_Gen="Female";
						}
						if($getPatInfo[0]['hyper_cond']==0){
							
							$Hyper_Cond="No";
						} else {
							$Hyper_Cond="Yes";
						}
						if($getPatInfo[0]['diabetes_cond']==0){
							
							$Diabetic_Cond="No";
						} else {
							$Diabetic_Cond="Yes";
						}
						if($getPatInfo[0]['lead_type']=="Hot"){
							
							$Lead_Cond="H";
							$Time="4hrs";
						} else if($getPatInfo[0]['lead_type']=="Warm"){
							$Lead_Cond="W";
							$Time="7hrs";
						} else {
							$Lead_Cond="O";
							$Time="24hrs";
						}
						if($getPatInfo[0]['qualification']==0){
							$pat_qualification="NS";
						} else {
							$pat_qualification=$getPatInfo[0]['qualification'];
						}
						
						if($getPatInfo[0]['pat_country']=="India"){
							$queryType="D";
						} else {
							$queryType="I";
						}
						
						if($getPatInfo[0]['repnotattach']==1)
						{
							$noreportmsg="No medical report attached";
						}
						
						if($get_pro[0]['communication_status']==1)
						{
							$docmail = $get_pro[0]['ref_mail'];
							$pro_contact=$get_pro[0]['contact_num'];
							
						} else if($get_pro[0]['communication_status']==2)
						{
							$docmail .= $get_pro[0]['hosp_email'] . ', ';
							$docmail .= $get_pro[0]['hosp_email1'] . ', ';
							$docmail .= $get_pro[0]['hosp_email2'] . ', ';
							$docmail .= $get_pro[0]['hosp_email3'] . ', ';
							$docmail .= $get_pro[0]['hosp_email4'];
							$pro_contact=$get_pro[0]['hosp_contact'];
						} else if($get_pro[0]['communication_status']==3)
						{
							$docmail .= $get_pro[0]['hosp_email'] . ', ';
							$docmail .= $get_pro[0]['hosp_email1'] . ', ';
							$docmail .= $get_pro[0]['hosp_email2'] . ', ';
							$docmail .= $get_pro[0]['hosp_email3'] . ', ';
							$docmail .= $get_pro[0]['hosp_email4'] . ', ';
							$docmail .= $get_pro[0]['ref_mail'];
							
							$pro_contact=$get_pro[0]['hosp_contact'];
							$doc_contact=$get_pro[0]['contact_num'];
						}
						
						if($getPatInfo[0]['transaction_status']=="TXN_SUCCESS"){
							$paid_msg="PAID QUERY- ";
						}
						
					if($_POST['contatInclude']==1){
						$patContactDet= "Patient Contact Details: <br>Contact No. :".$getPatInfo[0]['patient_mob']."<br>Email Address :".$getPatInfo[0]['patient_email'];
						$chk_prior="PRIORITY";
					}	
					$subject=$chk_prior." ".$paid_msg."[".$Lead_Cond."]- ".$Time."/ Ref. No.".$queryType." - ".$getPatInfo[0]['patient_id']." Patient Information";
									
										
					$url_page  = 'refdocmail.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?patid=".urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);
					$url .= "&patage=" . urlencode($getPatInfo[0]['patient_age']);
					$url .= "&patgend=" . urlencode($Pat_Gen);
					$url .= "&patweight=" . urlencode($getPatInfo[0]['weight']);
					$url .= "&patmerital=" . urlencode($getPatInfo[0]['merital_status']);
					$url .= "&pathyper=" . urlencode($Hyper_Cond);
					$url .= "&patdiabetes=" . urlencode($Diabetic_Cond);
					$url .= "&patloc=" . urlencode($getPatInfo[0]['patient_loc']);
					$url .= "&patState=" . urlencode($getPatInfo[0]['pat_state']);
					$url .= "&patCountry=" . urlencode($getPatInfo[0]['pat_country']);
					$url .= "&patcomp=" . urlencode($getPatInfo[0]['patient_complaint']);
					$url .= "&patdesc=" . urlencode($getPatInfo[0]['patient_desc']);
					$url .= "&patquery=" . urlencode($getPatInfo[0]['pat_query']);
					$url .= "&patqualification=" . urlencode($pat_qualification);
					$url .= "&patblood=" . urlencode($getPatInfo[0]['pat_blood']);
					$url .= "&patContactDet=". urlencode($patContactDet);
					$url .= "&patnoreportmsg=" . urlencode($noreportmsg);
					if(!empty($getPatAttach[0]['attach_id'])){
					$url .= "&patattachid1=" . urlencode($getPatAttach[0]['attach_id']);
					$url .= "&patattachname1=" . urlencode($getPatAttach[0]['attachments']);
					}
					if(!empty($getPatAttach[1]['attach_id'])){
					$url .= "&patattachid2=" . urlencode($getPatAttach[1]['attach_id']);
					$url .= "&patattachname2=" . urlencode($getPatAttach[1]['attachments']);
					}
					if(!empty($getPatAttach[2]['attach_id'])){
					$url .= "&patattachid3=" . urlencode($getPatAttach[2]['attach_id']);
					$url .= "&patattachname3=" . urlencode($getPatAttach[2]['attachments']);
					}
					if(!empty($getPatAttach[3]['attach_id'])){
					$url .= "&patattachid4=" . urlencode($getPatAttach[3]['attach_id']);
					$url .= "&patattachname4=" . urlencode($getPatAttach[3]['attachments']);
					}
					if(!empty($getPatAttach[4]['attach_id'])){
					$url .= "&patattachid5=" . urlencode($getPatAttach[4]['attach_id']);
					$url .= "&patattachname5=" . urlencode($getPatAttach[4]['attachments']);
					}
					if(!empty($getPatAttach[5]['attach_id'])){
					$url .= "&patattachid6=" . urlencode($getPatAttach[5]['attach_id']);
					$url .= "&patattachname6=" . urlencode($getPatAttach[5]['attachments']);
					}
					if(!empty($getPatAttach[6]['attach_id'])){
					$url .= "&patattachid7=" . urlencode($getPatAttach[6]['attach_id']);
					$url .= "&patattachname7=" . urlencode($getPatAttach[6]['attachments']);
					}
					if(!empty($getPatAttach[7]['attach_id'])){
					$url .= "&patattachid8=" . urlencode($getPatAttach[7]['attach_id']);
					$url .= "&patattachname8=" . urlencode($getPatAttach[7]['attachments']);
					}
					if(!empty($getPatAttach[8]['attach_id'])){
					$url .= "&patattachid9=" . urlencode($getPatAttach[8]['attach_id']);
					$url .= "&patattachname9=" . urlencode($getPatAttach[8]['attachments']);
					}
					if(!empty($getPatAttach[9]['attach_id'])){
					$url .= "&patattachid10=" . urlencode($getPatAttach[9]['attach_id']);
					$url .= "&patattachname10=" . urlencode($getPatAttach[9]['attachments']);
					}
					$url .= "&proname=" . urlencode($get_pro[0]['ref_name']);
					
					$url .= "&docmail=" . urlencode($docmail);
					
					$url .= "&ccmail=" . urlencode($ccmail);
							
					$url .= "&patcontact=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&patdepart=" . urlencode($getDepartment[0]['spec_name']);
					$url .= "&patprof=" . urlencode($getPatInfo[0]['profession']);
					$url .= "&subject=" . urlencode($subject);
					
							
					$ch = curl_init (); // setup a curl						
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
					$output = curl_exec ( $ch );				
					curl_close ( $ch );
					
					
					if(!empty($getPatInfo[0]['patient_email']) && $get_pro[0]['communication_status']!=0){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDocDept[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.$get_pro[0]['ref_id'];
						
						//TO CHECK MEDI ASSIST Source
						if($getPatInfo[0]['patient_src']=="11"){   //IF SO, THEN SEND MEDI ASSIST LOGO
						$mas_logo="<img src='".HOST_HEALTH_URL."new_assets/images/mediassist-logo-new.png' alt='Medi Assist' width='78' height='62'>";
						}
						else{
							$mas_logo="";
						}
						
						$url_page = 'After_refer_pat_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getDocDept[0]['spec_name']);					
						$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
						$url .= "&patmail=" . urlencode($getPatInfo[0]['patient_email']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
					}
					
					$Successmessage = "Referred to ".$get_pro[0]['ref_name']." Successfully";
				
					$arrFields = array();
					$arrValues = array();
					$arrFields[]= 'email_status';
					$arrValues[]= "1";
					$patientRef=mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$pat_id."'and ref_id='".$refer_id."'");
					
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields3 = array();
					$arrValues3 = array();
					$arrFields3[]= 'Total_Referred';
					$arrValues3[]= $Tot_ref;
					$updateCount=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro[0]['ref_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $getPatInfo[0]['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote1;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= '2';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//Medisense Note
					$msg="Refered to ".$get_pro[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields2 = array();
					$arrValues2 = array();
					$arrFields2[] = 'patient_id';
					$arrValues2[] = $getPatInfo[0]['patient_id'];
					$arrFields2[] = 'ref_id';
					$arrValues2[] = "0";
					$arrFields2[] = 'chat_note';
					$arrValues2[] = $msg;
					$arrFields2[] = 'user_id';
					$arrValues2[] = $admin_id;
					$arrFields2[] = 'TImestamp';
					$arrValues2[] = $Cur_Date;
					
					$usercraete=mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					
					//SMS notification to Refering Doctors only when messge_status is active
					if($get_pro[0]['message_status']==1 && $pro_contact!=""){
					$mobile = $pro_contact;
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
					
					send_msg($mobile,$msg);
					
					}
					
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact!="" && $get_pro[0]['message_status']==1)
					{
					$mobile = $doc_contact;
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
					
					send_msg($mobile,$msg);
					}
					
				//Here we need to Send Push notification to Doctors
				if($get_pro[0]['gcm_tokenid']!=""){
				$msg = "Dear Doctor, You have received a query of patient " . $getPatInfo[0]['patient_name'] . $getPatInfo[0]['patient_id'] ." - Many Thanks";
							
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Referral";
				$subtitle="New Referral";
				$tickerText="Leap new blog";
				$type="4"; //For Blog Type value is 1
				$largeimg='large_icon';
				$blog_id="0";
				$patientid=$getPatInfo[0]['patient_id'];
				$docid=$get_pro[0]['ref_id'];
				$postkey=time();
				push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
				
				//End Push notification functionality
				}	
					//SMS notification to Patient
					if($getPatInfo[0]['patient_mob']!="" && $get_pro[0]['communication_status']!=0){
					$mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query has been successfully referred to ".$get_pro[0]['ref_name']." Please check your mail for further detail. Medisensehealth.com";
					
					send_msg($mobile,$responsemsg);
					
					}
					
					
				}
					
		}
	
}//End If loop

}
	
	
//ADD / UPDATE MARKETING PERSON
if(isset($_POST['add_person']) || isset($_POST['edit_person'])){
	//Check Empty condition
	if(!empty($_POST['selectHosp']) || !empty($_POST['person_name']) || !empty($_POST['person_mobile']))
	{
	$selectHosp = $_POST['selectHosp'];
	$person_name = addslashes($_POST['person_name']);
	$person_mobile = $_POST['person_mobile'];
	$person_email = addslashes($_POST['person_email']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'person_name';
		$arrValues[] = $person_name;
		$arrFields[] = 'hosp_id';
		$arrValues[] = $selectHosp;
		$arrFields[] = 'person_mobile';
		$arrValues[] = $person_mobile;
		$arrFields[] = 'person_email';
		$arrValues[] = $person_email;
		
		if(isset($_POST['add_person'])){
			$personcreate=mysqlInsert('hosp_marketing_person',$arrFields,$arrValues);
			$person_id=$personcreate;
			header("Location:Add-Marketing-Persons?response=add");
				
		}
		else{	
			$updateProvider=mysqlUpdate('hosp_marketing_person',$arrFields,$arrValues,"person_id='".$_POST['Person_Id']."'");	
			header("Location:Add-Marketing-Persons?response=update");
		}
	
	}
	else
	{
	header("Location:Add-Marketing-Persons?response=error");	
	}
}	
	
//ADD / UPDATE REFERRING PARTNER
if(isset($_POST['add_referrer']) || isset($_POST['edit_referrer'])){
	//Check Empty condition
	if(!empty($_POST['refPartName']))
	{	
	$selectHosp = $_POST['selectHosp'];
	$selectPerson = $_POST['selectPerson'];
	$ref_name = addslashes($_POST['refPartName']);
	$partnertype = $_POST['selectType'];
	$partnercategory = $_POST['partner_cat'];
	$ref_mobile = $_POST['refPartMobile'];
	$ref_email = $_POST['refPartEmail'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	//Check Referrer mobile/email id exists in our partner table
	$chkPartner = mysqlSelect("*","our_partners","Email_id='".$ref_email."' or Email_id1='".$ref_email."' or cont_num1='".$ref_mobile."'","","","","");
	$getHosp = mysqlSelect("*","hosp_tab","hosp_id='".$selectHosp."'","","","","");
	$getDoc = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	//Check Referrer is already mapped in marketing person
	$chkMappedReferrer = mysqlSelect("*","mapping_hosp_referrer","partner_id='".$chkPartner[0]['partner_id']."' and hosp_id='".$selectHosp."' and doc_id='".$admin_id."'","","","","");
	$get_organisation = mysqlSelect('company_id as Comp_Id,company_name as Comp_name,mobile as Org_Contact,email_id as Comp_Email,company_logo as Logo','compny_tab',"company_id='".$_POST['CompId']."'");
	$compLogo=HOST_MAIN_URL.'Hospital/company_logo/'.$get_organisation[0]['Comp_Id'].'/'.$get_organisation[0]['Logo'];
		
	//$webLink=$hostname."/Refer/";  
	$webLink="www.medisensepractice.com";
	
	if($chkPartner==true && $chkMappedReferrer==true){
					
					header("Location:Add-Referring-Partner?response=error");
	}
	
	else if($chkPartner==true){
			
			$partner_id= $chkPartner[0]['partner_id'];
			$arrFields1 = array();
			$arrValues1 = array();
			
			$arrFields1[] = 'partner_id';
			$arrValues1[] = $partner_id;
			$arrFields1[] = 'partner_type';
			$arrValues1[] = $partnertype;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $selectHosp;
			$arrFields1[] = 'company_id';
			$arrValues1[] = $_POST['CompId'];
			$arrFields1[] = 'doc_id';
			$arrValues1[] = $admin_id;
			
			$personcreate=mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
					//Mail Notification to Referring Partner
					
					$usercredentials="Link :".$webLink."<br>User ID :".$chkPartner[0]['Email_id']." / ".$chkPartner[0]['cont_num1']."<br>Password: You have already registered. If you have forgotten password, then click forgot password in login page. <br><br>";
					
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&marketingmail=" . urlencode($getDoc[0]['ref_mail']);
					$url .= "&marketingmobile=".urlencode($getDoc[0]['contact_num']);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Pls use link www.medisensepractice.com to login. Pls check ".$ref_email." for further details. Thanks, ".$getDoc[0]['ref_name'];
					send_msg($mobile,$responsemsg);
					header("Location:Add-Referring-Partner?response=add");
	}
	else{
		
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'partner_name';
		$arrValues[] = $ref_name;
		$arrFields[] = 'contact_person';
		$arrValues[] = $ref_name;
		$arrFields[] = 'Email_id';
		$arrValues[] = $ref_email;
		$arrFields[] = 'cont_num1';
		$arrValues[] = $ref_mobile;
		$arrFields[] = 'password';
		$arrValues[] = $encypassword;
		$arrFields[] = 'reg_date';
		$arrValues[] = $curDate;
		$arrFields[] = 'Type';
		$arrValues[] = $partnercategory;
		
		if(isset($_POST['add_referrer'])){
			$personcreate=mysqlInsert('our_partners',$arrFields,$arrValues);
			$partner_id= $personcreate;
			
			//Insert Partner Id to Source List table
			$arrFields2[] = 'source_name';
			$arrValues2[] = $ref_name;
			$arrFields2[] = 'partner_id';
			$arrValues2[] = $partner_id;
		
			$createsource=mysqlInsert('source_list',$arrFields2,$arrValues2);
			
			
			$arrFields1 = array();
			$arrValues1 = array();
			
			$arrFields1[] = 'partner_id';
			$arrValues1[] = $partner_id;
			$arrFields1[] = 'partner_type';
			$arrValues1[] = $partnertype;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $selectHosp;
			$arrFields1[] = 'company_id';
			$arrValues1[] = $_POST['CompId'];
			$arrFields1[] = 'doc_id';
			$arrValues1[] = $admin_id;
			$personcreate=mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
			
			
					//Mail Notification to Referring Partner
					 $usercredentials="Link :".$webLink."<br>User ID :".$ref_email." / ".$ref_mobile."<br>Password: ".$password."<br>";
					
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&marketingmail=" . urlencode($getDoc[0]['ref_mail']);
					$url .= "&marketingmobile=".urlencode($getDoc[0]['contact_num']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Please use link www.medisensepractice.com to login with user ID : ".$ref_email." and password : ".$password.". Pls check ".$ref_email." for further details. Thanks, ".$getDoc[0]['ref_name'];
					send_msg($mobile,$responsemsg);
			
			header("Location:Add-Referring-Partner?response=add");
		}
		//EDIT DETAILS
		else{	
			$updatePartner=mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$_POST['Partner_Id']."'");	
			$arrFields1[] = 'market_person_id';
			$arrValues1[] = $selectPerson;
			$updateMapping=mysqlUpdate('mapping_hosp_referrer',$arrFields1,$arrValues1,"partner_id='".$_POST['Partner_Id']."' and company_id='".$admin_id."'");
			header("Location:Add-Referring-Partner?response=update");
		}
	
		}
	}
	//Send Error message
	else
	{
		header("Location:Add-Referring-Partner?response=error");
	}
}	
//ADD / UPDATE HOSPITAL DETAILS
if(isset($_POST['add_hospital']) || isset($_POST['edit_hospital'])){
	//Check Empty condition
	if(!empty($_POST['txtHospName']) || !empty($_POST['slctComm']))
	{
	$txtHospName = addslashes($_POST['txtHospName']);
	$txtAddress = addslashes($_POST['txtAddress']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtSuburb = addslashes($_POST['txtSuburb']);
	$txtCity = addslashes($_POST['txtCity']);	
	$txtOverview = addslashes($_POST['txtOverview']);
	$txtBeds = addslashes($_POST['txtBeds']);
	$txtAmbulance = addslashes($_POST['txtAmbulance']);
	$txtServices = addslashes($_POST['txtServices']);
	$txtPerson = addslashes($_POST['txtPerson']);
	$txtMobile = $_POST['txtMobile'];
	$txtEmail = addslashes($_POST['txtEmail']);
	$txtEmail1 = addslashes($_POST['txtEmail1']);
	$txtEmail2 = addslashes($_POST['txtEmail2']);
	$txtEmail3 = addslashes($_POST['txtEmail3']);
	$txtEmail4 = addslashes($_POST['txtEmail4']);
	$slctComm = addslashes($_POST['slctComm']);
	
	$txtrevisitcharge = addslashes($_POST['txtrevisitcharge']);
	$txtnewvisitcharge = addslashes($_POST['txtnewvisitcharge']);
	
	$slct_amenity = $_POST['slct_amenity'];
	$slctHosp = $_POST['slctHosp'];	
	$docImage = basename($_FILES['file-3']['name']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'hosp_name';
		$arrValues[] = $txtHospName;
		$arrFields[] = 'hosp_suburb';
		$arrValues[] = $txtSuburb;
		$arrFields[] = 'hosp_city';
		$arrValues[] = $txtCity;
		$arrFields[] = 'hosp_state';
		$arrValues[] = $slctState;
		$arrFields[] = 'hosp_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'hosp_contact_name';
		$arrValues[] = $txtPerson;
		$arrFields[] = 'hosp_contact';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'hosp_email';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'hosp_email1';
		$arrValues[] = $txtEmail1;
		$arrFields[] = 'hosp_email2';
		$arrValues[] = $txtEmail2;		
		$arrFields[] = 'hosp_email3';
		$arrValues[] = $txtEmail3;
		$arrFields[] = 'hosp_email4';
		$arrValues[] = $txtEmail4;
		$arrFields[] = 'hosp_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'communication_status';
		$arrValues[] = $slctComm;
		
		$arrFields[] = 'hosp_overview';
		$arrValues[] = $txtOverview;
		$arrFields[] = 'num_beds';
		$arrValues[] = $txtBeds;
		$arrFields[] = 'num_ambulance';
		$arrValues[] = $txtAmbulance;	
		$arrFields[] = 'hosp_services';
		$arrValues[] = $txtServices;
		$arrFields[] = 'revisit_charge';
		$arrValues[] = $txtrevisitcharge;
		$arrFields[] = 'newvist_charge';
		$arrValues[] = $txtnewvisitcharge;
		$arrFields[] = 'company_id';
		$arrValues[] = $_SESSION['user_id'];
					
				
	if(isset($_POST['add_hospital'])){
			$usercraete=mysqlInsert('hosp_tab',$arrFields,$arrValues);
			$hosp_id= $usercraete;
			
			foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
				$arrFields2 = array();
				$arrValues2 = array();

				$arrFields2[] = 'hosp_id';
				$arrValues2[] = $hosp_id;

				$arrFields2[] = 'amenity_id';
				$arrValues2[] = $amenValue;
				
				$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
			}
			
			
				
			//UPLOAD MULTIPLE IMAGES
			if(!empty($_FILES['file-3']['name'])){
			$errors= array();
			foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
			
				
			$file_name = $_FILES['file-3']['name'][$key];
			$file_size =$_FILES['file-3']['size'][$key];
			$file_tmp =$_FILES['file-3']['tmp_name'][$key];
			$file_type=$_FILES['file-3']['type'][$key];
			
			
				$arrFields3 = array();
				$arrValues3 = array();

				$arrFields3[] = 'hosp_id';
				$arrValues3[] = $hosp_id;

				$arrFields3[] = 'hosp_image';
				$arrValues3[] = $file_name;
				
					
					$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
					$id= $bslist_pht;


					//UPLOAD COMPRESSED IMAGE
					if ($_FILES["file-3"]["error"][$key] > 0) {
							$error = $_FILES["file-3"]["error"][$key];
					} 
					else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
					($_FILES['file-3']['type'][$key] == "image/jpeg") || 
					($_FILES['file-3']['type'][$key] == "image/png") || 
					($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
					{
						$folder_name	=	"Hosp_image";
						$sub_folder		=	$id;
						$filename		=	$_FILES["file-3"]["name"][$key];
						$file_url		=	$_FILES["file-3"]["tmp_name"][$key];
						fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

					 // $uploaddirectory = realpath("Hosp_image");
					 // $uploaddir = $uploaddirectory . "/" .$id;
					 
					 // /*Checking whether folder with add_hosp_picture id already exist or not. */
						// if (file_exists($uploaddir)) {
						//echo "The file $uploaddir exists";
						// } else {
						// $newdir = mkdir($uploaddirectory . "/" . $id, 0777);
						// }
					 
					 
							// $url = $uploaddir.'/'.$_FILES["file-3"]["name"][$key];

							// $filename = compress_image($_FILES["file-3"]["tmp_name"][$key], $url, 40);
							// $buffer = file_get_contents($url);

					}
					else {
							$error = "Uploaded image should be jpg or gif or png";
					}
				
					
				}
				//End of foreach
			} //End of Not Empty condition

			
			header("Location:Add-Hospital?response=add");
		}
		else if(isset($_POST['edit_hospital'])){	
		$updateProvider=mysqlUpdate('hosp_tab',$arrFields,$arrValues,"hosp_id='".$_POST['Hosp_Id']."'");	
					foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
						$arrFields2 = array();
						$arrValues2 = array();

						$arrFields2[] = 'hosp_id';
						$arrValues2[] = $_POST['Hosp_Id'];

						$arrFields2[] = 'amenity_id';
						$arrValues2[] = $amenValue;
						
						$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
					}
		
		
			
				//UPLOAD MULTIPLE IMAGES
				if(!empty($_FILES['file-3']['name'])){
				$errors= array();
				foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name )
				{	
				
					
				$file_name = $_FILES['file-3']['name'][$key];
				$file_size =$_FILES['file-3']['size'][$key];
				$file_tmp =$_FILES['file-3']['tmp_name'][$key];
				$file_type=$_FILES['file-3']['type'][$key];
				
				
					$arrFields3 = array();
					$arrValues3 = array();

					$arrFields3[] = 'hosp_id';
					$arrValues3[] = $_POST['Hosp_Id'];

					$arrFields3[] = 'hosp_image';
					$arrValues3[] = $file_name;
					
						
						$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
						$id	= $bslist_pht;


						//UPLOAD COMPRESSED IMAGE
						if ($_FILES["file-3"]["error"][$key] > 0) {
								$error = $_FILES["file-3"]["error"][$key];
						} 
						else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
						($_FILES['file-3']['type'][$key] == "image/jpeg") || 
						($_FILES['file-3']['type'][$key] == "image/png") || 
						($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
						{
							$folder_name	=	"Hosp_image";
							$sub_folder		=	$id;
							$filename		=	$_FILES["file-3"]["name"][$key];
							$file_url		=	$_FILES["file-3"]["tmp_name"][$key];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
							
							
							// $uploaddirectory = realpath("Hosp_image");
							// $uploaddir = $uploaddirectory . "/" .$id;
						 
						 // /*Checking whether folder with add_hosp_picture id already exist or not. */
							// if (file_exists($uploaddir)) {
						//	echo "The file $uploaddir exists";
							// } else {
							// $newdir = mkdir($uploaddirectory . "/" . $id, 0777);
							// }
						 
						 
								// $url = $uploaddir.'/'.$_FILES["file-3"]["name"][$key];

								// $filename = compress_image($_FILES["file-3"]["tmp_name"][$key], $url, 40);
								// $buffer = file_get_contents($url);

						}
						else 
						{
								$error = "Uploaded image should be jpg or gif or png";
						}
					
						
					}
				}
			//End of foreach
		header("Location:Add-Hospital?response=update");
		}
	}
	//Send Error message
	else
	{
		header("Location:Add-Hospital?response=error");
	}
}

//ADD / UPDATE HOPITAL DOCTOR
if(isset($_POST['edit_doctor'])){
	
	$txtDoc = addslashes($_POST['txtDoc']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtCity = $_POST['se_city'];
	$slctHosp = $_POST['selectHosp'];	
	$slctSpec = $_POST['slctSpec'];
	$txtQual = addslashes($_POST['txtQual']);
	$txtExp = $_POST['txtExp'];
	$txtMobile = $_POST['txtMobile'];
	$txtEmail = $_POST['txtEmail'];
	$txtWebsite = $_POST['txtWebsite'];
	$txtInterest = addslashes($_POST['txtInterest']);
	$txtContribute = addslashes($_POST['txtContribute']);
	$txtResearch = addslashes($_POST['txtResearch']);
	$txtPublication = addslashes($_POST['txtPublication']);
	$txtKeyword = addslashes($_POST['txtKeywords']);
	$txtNumOpinion = addslashes($_POST['numopinion']);
	$txtInOpcost = addslashes($_POST['inopcost']);
	$txtOnOpcost = addslashes($_POST['onopcost']);
	$txtConcharge = addslashes($_POST['conscharge']);
	$txtSecEmail = $_POST['txtSecEmail'];
	$txtSecPhone = $_POST['txtSecPhone'];
	$docImage = basename($_FILES['txtPhoto']['name']);	
	
	$teleOpCond = addslashes($_POST['teleop']);
		$telecontact = addslashes($_POST['teleopnumber']);
		$videoOpCond = addslashes($_POST['videoop']);
		$videocontact = addslashes($_POST['videoopnumber']);
		$teleoptiming = addslashes($_POST['televidop_time']);
		
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'ref_name';
		$arrValues[] = $txtDoc;
		if($slctSpec==""){
		$arrFields[] = 'doc_spec';
		$arrValues[] = "555";
		}
		else{
		$arrFields[] = 'doc_spec';
		$arrValues[] = $slctSpec;	
		}
		$arrFields[] = 'ref_mail';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'ref_web';
		$arrValues[] = $txtWebsite;		
		$arrFields[] = 'doc_qual';
		$arrValues[] = $txtQual;
		$arrFields[] = 'doc_type_val';
		$arrValues[] = "5";
		$arrFields[] = 'ref_address';
		$arrValues[] = $txtCity;
		$arrFields[] = 'doc_state';
		$arrValues[] = $slctState;
		$arrFields[] = 'doc_country';
		$arrValues[] = $txtCountry;

		$arrFields[] = 'ref_exp';
		$arrValues[] = $txtExp;
		$arrFields[] = 'doc_interest';
		$arrValues[] = $txtInterest;
		$arrFields[] = 'doc_research';
		$arrValues[] = $txtResearch;
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $txtContribute;
		$arrFields[] = 'doc_pub';
		$arrValues[] = $txtPublication;
		$arrFields[] = 'doc_keywords';
		$arrValues[] = $txtKeyword;
		$arrFields[] = 'contact_num';
		$arrValues[] = $txtMobile;
		
		$arrFields[] = 'numfreeop';
		$arrValues[] = $txtNumOpinion;
		$arrFields[] = 'in_op_cost';
		$arrValues[] = $txtInOpcost;
		$arrFields[] = 'on_op_cost';
		$arrValues[] = $txtOnOpcost;
		$arrFields[] = 'cons_charge';
		$arrValues[] = $txtConcharge;
		
		$arrFields[] = 'secretary_phone';
		$arrValues[] = $txtSecPhone;
		$arrFields[] = 'secretary_email';
		$arrValues[] = $txtSecEmail;	
		
		if(!empty($docImage)){
		$arrFields[] = 'doc_photo';
		$arrValues[] = $docImage;
		}
		$arrFields[] = 'message_status';
		$arrValues[] = "1";
		$arrFields[] = 'company_id';
		$arrValues[] = $admin_id;
		
		$arrFields[] = 'TImestamp';
		$arrValues[] = $curDate;
		
		$arrFields[] = 'tele_op';
		$arrValues[] = $teleOpCond;
		$arrFields[] = 'tele_op_contact';
		$arrValues[] = $telecontact;
		$arrFields[] = 'video_op';
		$arrValues[] = $videoOpCond;
		$arrFields[] = 'video_op_contact';
		$arrValues[] = $videocontact;	
		$arrFields[] = 'tele_video_op_timing';
		$arrValues[] = $teleoptiming;
		
	
		
		
	$updateProvider=mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$_POST['Prov_Id']."'");
	mysqlDelete('doc_specialization',"doc_id='".$_POST['Prov_Id']."'");

					foreach($slctSpec as $key => $value)
					{
					$arrFields_spec = array();
					$arrValues_spec = array();

					$arrFields_spec[] = 'doc_id';
					$arrValues_spec[] = $_POST['Prov_Id'];

					$arrFields_spec[] = 'spec_id';
					$arrValues_spec[] = $value;
											
					$insert_spec=mysqlInsert('doc_specialization',$arrFields_spec,$arrValues_spec);
					}	
	mysqlDelete('doctor_hosp',"doc_id='".$_POST['Prov_Id']."'");

					foreach($slctHosp as $key => $value)
					{
					$arrFields_hosp = array();
					$arrValues_hosp = array();

					$arrFields_hosp[] = 'doc_id';
					$arrValues_hosp[] = $_POST['Prov_Id'];

					$arrFields_hosp[] = 'hosp_id';
					$arrValues_hosp[] = $value;
											
					$insert_spec=mysqlInsert('doctor_hosp',$arrFields_hosp,$arrValues_hosp);
					}
	
	$arrFields1 = array();
	$arrValues1= array();
	$chkHosp = mysqlSelect("*","doctor_hosp ","doc_id='".$_POST['Prov_Id']."'","","","","");
	$id=$_POST['Prov_Id'];
	
	
				/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{

					$folder_name	=	"Doc";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

										// $uploaddirectory = realpath("../Doc");
					// mkdir("../Doc/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $docImage;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
					//	echo "File uploaded.";
					// } 
					// else 
					// {
					//	echo "File cannot be uploaded";
					// }
				}
		/*if($chkHosp==true){
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$updateProvider=mysqlUpdate('doctor_hosp',$arrFields1,$arrValues1,"doc_id='".$_POST['Prov_Id']."'");
		} else {
		$arrFields1 = array();
		$arrValues1= array();
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $_POST['Prov_Id'];
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$usercraete=mysqlInsert('doctor_hosp',$arrFields1,$arrValues1);
		}*/	
		
	
	header("Location:Profile?response=update");
	
	}
if(isset($_POST['edit_timings'])){	
mysqlDelete('doc_time_set',"doc_id='".$_POST['Prov_Id']."'");
	
					
	for($i=1; $i<=$_POST['limit_i']; $i++)
	{
		$Timing_id=$_POST['time_id' . $i];
		for($j=1; $j<=$_POST['limit_j']; $j++)
		{
			$day_id=$_POST['day_id'. $i . $j];
			$time_limit=$_POST['time'. $i . $j];
			if($time_limit!=0){
			$arrFields_time = array();
			$arrValues_time = array();

			$arrFields_time[] = 'doc_id';
			$arrValues_time[] = $_POST['Prov_Id'];
			
			$arrFields_time[] = 'time_id';
			$arrValues_time[] = $Timing_id;
			
			$arrFields_time[] = 'day_id';
			$arrValues_time[] = $day_id;
			
			$arrFields_time[] = 'time_set';
			$arrValues_time[] = $time_limit;
			
			$doctimecreate=mysqlInsert('doc_time_set',$arrFields_time,$arrValues_time);
					
			}
		}
	}
	header("Location:Set-Appointment?response=update");
}
if(isset($_POST['add_holiday'])){	
	
	$arrFields_holiday = array();
	$arrValues_holiday = array();
	
	$arrFields_holiday[] = 'doc_id';
	$arrValues_holiday[] = $_POST['Prov_Id'];

	$arrFields_holiday[] = 'doc_type';
	$arrValues_holiday[] = "1"; //1 for prime doctor
	
	$arrFields_holiday[] = 'holiday_date';
	$arrValues_holiday[] = date('Y-m-d',strtotime($_POST['dateadded']));
	
	$arrFields_holiday[] = 'reason';
	$arrValues_holiday[] = $_POST['txt_desc']; 
	
	$insertHoliday=mysqlInsert('doc_holidays',$arrFields_holiday,$arrValues_holiday);
	
	header("Location:Set-Appointment?response=holiday-update");
}	

//ADD / UPDATE HOPITAL AMENITIES
if(isset($_POST['add_amenity']) || isset($_POST['edit_amenity'])){
	$slct_amenity = $_POST['slct_amenity'];
	$slctHosp = $_POST['slctHosp'];	
	$docImage = basename($_FILES['file-3']['name']);
	
	foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
		$arrFields2 = array();
		$arrValues2 = array();

		$arrFields2[] = 'hosp_id';
		$arrValues2[] = $_POST['slctHosp'];

		$arrFields2[] = 'amenity_id';
		$arrValues2[] = $amenValue;
		
		$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
	}
	
	
	/* Add New Photoes to the perticular hosp_id */
	$chkHospPicture= mysqlSelect("*","add_hosp_picture","hosp_id='".$_POST['slctHosp']."'","","","","");

	
	//UPLOAD MULTIPLE IMAGES
	if($chkHospPicture==false){ 	
	
	$errors= array();
	foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
	
		
	$file_name = $_FILES['file-3']['name'][$key];
	$file_size =$_FILES['file-3']['size'][$key];
	$file_tmp =$_FILES['file-3']['tmp_name'][$key];
	$file_type=$_FILES['file-3']['type'][$key];
	
	
		$arrFields3 = array();
		$arrValues3 = array();

		$arrFields3[] = 'hosp_id';
		$arrValues3[] = $_POST['slctHosp'];

		$arrFields3[] = 'hosp_image';
		$arrValues3[] = $file_name;
		
			
			$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
			$id= $bslist_pht;


			//UPLOAD COMPRESSED IMAGE
			if ($_FILES["file-3"]["error"][$key] > 0) {
        			$error = $_FILES["file-3"]["error"][$key];
    		} 
    		else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
			($_FILES['file-3']['type'][$key] == "image/jpeg") || 
			($_FILES['file-3']['type'][$key] == "image/png") || 
			($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
			{
				
				$folder_name	=	"Hosp_image";
				$sub_folder		=	$id;
				$filename		=	$_FILES["file-3"]["name"][$key];
				$file_url		=	$_FILES["file-3"]["tmp_name"][$key];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

				// $uploaddirectory = realpath("../Hosp_image");
				// $uploaddir = $uploaddirectory . "/" .$id;
			 
				// /*Checking whether folder with add_hosp_picture id already exist or not. */
				// if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				// } else {
				// $newdir = mkdir($uploaddirectory . "/" . $id, 0777);
				// }
			 
			 
        			// $url = $uploaddir.'/'.$_FILES["file-3"]["name"][$key];

        			// $filename = compress_image($_FILES["file-3"]["tmp_name"][$key], $url, 40);
        			// $buffer = file_get_contents($url);

    		}
			else
			{
        			$error = "Uploaded image should be jpg or gif or png";
    		}
		
			
		}
		//End of foreach
		
	}//end if
	header("Location:Add-Hospital-Amenity?response=add");	
} //end main if

//ADD ACTIVITY
if(isset($_POST['addActivity']) && !empty($_POST['txtDesc'])){
					$txtDesc = addslashes($_POST['txtDesc']);
	
					$arrFields = array();
					$arrValues = array();
										
					$arrFields[]= 'patient_id';
					$arrValues[]= $_POST['patient_id'];
					$arrFields[]= 'ref_id';
					$arrValues[]= $_POST['doc_id'];
					$arrFields[]= 'chat_note';
					$arrValues[]= $txtDesc;
					$arrFields[]= 'user_id';
					$arrValues[]= "0";
					$arrFields[]= 'TImestamp';
					$arrValues[]= $curDate;
					$arrFields[]= 'msg_send_status';
					$arrValues[]= $_POST['patient_response_send'];
					
					$patientNote=mysqlInsert('chat_notification',$arrFields,$arrValues);
					
					//Change Status2 condition
					$getPatInfo= mysqlSelect("*","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","b.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
					$getStatus2=$getPatInfo[0]['status2']; //Get present patient status of perticular referral
					$getBucket=$getPatInfo[0]['bucket_status']; //Get present patient status of perticular referral
					if($getStatus2<5){  //Status2 will change only when present status remains in below respond level, ie. it must be in 'New'/Refered/P-Awating Status
						
						$getRef = mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
						//NO. OF RESPONDED COUNT INCREMENTED BY ONE
						$TotCount=$getRef[0]['Tot_responded'];
						$TotCount=$TotCount+1;
						
						$arrFields3[]= 'Tot_responded';
						$arrValues3[]= $TotCount;
						$updateCount=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['doc_id']."'");
						
						//Update response time 
						//RETREIVE DOCTOR'S FIRST REFERRED DATE
						$getDocResponse = mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","","","","");
													
						$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
						$datetime2 = new DateTime($curDate);
						$interval = $datetime1->diff($datetime2);
														
						$numdays=$interval->format('%a');
						$numhours=$interval->format('%H');
						$nummin=$interval->format('%i');
						$daystominute=$numdays*24*60;
						$hourstominute=$numhours*60;
						$totmin=$daystominute+$hourstominute+$nummin;
						
						$arrFields1[]= 'status2';
						$arrValues1[]= "5";
						$arrFields1[]= 'response_status';
						$arrValues1[]= "2";
						$arrFields1[]= 'response_time';
						$arrValues1[]= $totmin;
						
						//Bucket Status will update only when its below 5
						if($getBucket<5){
						$arrFields2[]= 'bucket_status';
						$arrValues2[]= "5";
						$updateBucket=mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$_POST['patient_id']."'");
					
						}
					}
					
					/*$arrFields1[]= 'bucket_status';
					$arrValues1[]= $_POST['Pro4_status2'];*/
					$patientRef=mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'");
					
					
					//Email Notification to patient
					//$getChatMsg = mysqlSelect("*","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","chat_id desc","","","");
					$getSpec = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
					if(!empty($getPatInfo[0]['doc_photo'])){
						$docimg=HOST_MAIN_URL."Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}	
					else{
						$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
					}
					
					
					$getPartnerRespSetting = mysqlSelect("*","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatInfo[0]['patient_src']."'","","","","");
					
					$getMarketPerson = mysqlSelect("*","hosp_marketing_person as a left join mapping_hosp_referrer as b on a.person_id=b.market_person_id","b.partner_id='".$getPartnerRespSetting[0]['partner_id']."' and b.hosp_id='".$getPatInfo[0]['hosp_id']."'","","","","");
					
					
					//Check Doctor response should go to partner / patient directly
			if($_POST['patient_response_send']==1){ // 1 for response should go to patient with a copy to partner & Point of contact(Marketing Person)
					$mailto .=$getPatInfo[0]['patient_email'] .", ";
					$mailto .=$getPartnerRespSetting[0]['Email_id'] .", ";
					$mailto .=$getMarketPerson[0]['person_email'] .", ";
					$patientnum =$getPatInfo[0]['patient_mob'];
					$partnernum =$getPartnerRespSetting[0]['cont_num1'];
					$marketnum =$getMarketPerson[0]['person_mobile'];
						
			}
			else if($_POST['patient_response_send']==0){ // 0 for response should go only to partner
					//$mailto .=$getPatInfo[0]['patient_email'] .", ";
					$mailto .=$getPartnerRespSetting[0]['Email_id'] .", ";
					$mailto .=$getMarketPerson[0]['person_email'] .", ";
					//$patientnum =$getPatInfo[0]['patient_mob'];
					$partnernum =$getPartnerRespSetting[0]['cont_num1'];
					$marketnum =$getMarketPerson[0]['person_mobile'];
			}
					
					$getDocName=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$getPatInfo[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_name']));
					$getDocHospAdd=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_addrs']));

					$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$getPatInfo[0]['ref_id'];
		
					/*$doctorresponse='';
						foreach($getChatMsg as $key=>$value){
						
						$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
						} */
					$doctorresponse ="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$txtDesc."<br><span style='float:right;color:#6b6b6b'>".date('d M Y H:i',strtotime($curDate))."</span></p></td></tr>";
							
					$url_page = 'Doc_pat_opinion.php';					
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($getPatInfo[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getPatInfo[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					//$param .= "&maslogo=".urlencode($mas_logo);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($mailto);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to patient
					if(!empty($patientnum)){
					
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") You have received the opinion from ".$getDocName." for your medical query. Check your registered email. Thx";
					send_msg($patientnum,$responsemsg);
					}
					//Message Notification to partners
					if(!empty($partnernum)){
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thx";
					send_msg($partnernum,$responsemsg);
					}
					
					//Push notification for partners		
					if(!empty($getPartnerRespSetting[0]['gcm_tokenid'])){			
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Many Thanks";
					$subtit= $getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].")";
					$pushDesc=strip_tags($responsemsg);
					$msg="";
					$title="Doctors Response";
					$subtitle=strip_tags($subtit);
					$tickerText="Doctors Response";
					$type="4"; //For Other Message
					$patientid=$getPatInfo[0]['patient_id'];
					$docid=$getPatInfo[0]['ref_id'];
					$blog_id="0";
					$largeimg='large_icon';	
					$regid=$getPartnerRespSetting[0]['gcm_tokenid'];
					$postkey=time();
					
					if(!empty($getPatInfo[0]['doc_photo'])){ 
					$smalimg=$hostname."Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}else{
					$smalimg=HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
					}
					
					push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$blog_id,$patientid,$docid,$postkey);
					
					}
					
					//Message Notification to Marketing person
					if(!empty($marketnum)){
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thx";
					send_msg($marketnum,$responsemsg);
					}
					
					$response=1;	
					header('location:patient-history?response='.$response.'&p='.$_POST['ency_patient_id']);					
					
					
}

?>