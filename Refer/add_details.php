<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];
$username = $_SESSION['company_name'];
$ccmail="medical@medisense.me";

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


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

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

if(isset($_POST['ref_appointment'])){

	$chkInDate = $_POST['check_date'];
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
	
			$arrFields_patient = array();
			$arrValues_patient = array();
						
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;

			$arrFields_patient[] = 'patient_age';
			$arrValues_patient[] = $txtAge;

			$arrFields_patient[] = 'patient_email';
			$arrValues_patient[] = $txtMail;

			$arrFields_patient[] = 'patient_gen';
			$arrValues_patient[] = $txtGen;

		
		
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

			$arrFields_patient[] = 'partner_id';
			$arrValues_patient[] = $admin_id;

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = date('Y-m-d');
			
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = date('Y-m-d H:i:s');
		

		
		$patientcreate=$objQuery->mysqlInsert('my_patient',$arrFields_patient,$arrValues_patient);
		$patientid = mysql_insert_id();  //Get Patient Id
		
		//Insert to new_hospvisitor_details table
				$arrFields = array();
				$arrValues = array();
				
				$arrFields[] = 'appoint_trans_id';
				$arrValues[] = $transid;
				$arrFields[] = 'pref_doc';
				$arrValues[] = $admin_id;
				$arrFields[] = 'department';
				$arrValues[] = $docspec;
				$arrFields[] = 'Visiting_date';
				$arrValues[] = $chkInDate;
				$arrFields[] = 'Visiting_time';
				$arrValues[] = $chkInTime;
				$arrFields[] = 'patient_name';
				$arrValues[] = $txtName;
				$arrFields[] = 'Mobile_no';
				$arrValues[] = $txtMob;
				$arrFields[] = 'Email_address';
				$arrValues[] = $txtMail;
				$arrFields[] = 'pay_status';
				$arrValues[] = "Pending";
				$arrFields[] = 'visit_status';
				$arrValues[] = "new_visit";
				$arrFields[] = 'Time_stamp';
				$arrValues[] = $curDate;
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
				
					
				$createvisitor=$objQuery->mysqlInsert('partner_appointment_transaction',$arrFields,$arrValues);
				$newvisitorid= mysql_insert_id();
				$getPatInfo = $objQuery->mysqlSelect("*","my_patient","patient_id='".$patientid."'" ,"","","","");
				$getTime= $objQuery->mysqlSelect("*","timings","Timing_id='".$chkInTime."'","","","","");
				
	//Message notification to patient			
	$get_pro = $objQuery->mysqlSelect('partner_id,cont_num1,Email_id,contact_person,partner_name','our_partners',"partner_id='".$docid."'");
	$msg="Appointment Confirmed, TransactionID ". $transid . " | Patient Name: ". $txtName . " | Doctor: ".$_SESSION['company_name']." | Date & Time: ".$chkInDate." | ".$getTime[0]['Timing']." Thanks";
	send_msg($txtMob,$msg);
	
				//Here we need to Send Push notification to Doctors
				if($get_pro[0]['gcm_tokenid']!=""){
				$msg = "Dear Doctor, ".$getPatInfo[0]['patient_name']."( Ph: ".$getPatInfo[0]['patient_mob']." )has expressed interest to meet you in person. For more info please login into your medisense leap dash board. Many Thanks";
							
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Appointment Request";
				$subtitle="New Appointment Request";
				$tickerText="New Appointment Request";
				$type="4"; //For Blog Type value is 1
				$largeimg='large_icon';
				$blog_id="0";
				$patientid=$getPatInfo[0]['patient_id'];
				$docid=$get_pro[0]['partner_id'];
				$postkey=time();
				push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
				
				//End Push notification functionality
				}
	
	$getTime=$objQuery->mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
	
	//Patient Info EMAIL notification Sent to Doctor
		if(!empty($get_pro[0]['Email_id'])){
		$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
		
					$url_page = 'Doc_pat_info.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatInfo[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatInfo[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatInfo[0]['patient_email']);
					$url .= "&patContactName=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&prefDate=" . urlencode($chkInDate);
					$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
					$url .= "&docname=" . urlencode($get_pro[0]['contact_person']);
					$url .= "&docmail=" . urlencode($get_pro[0]['Email_id']);
					$url .= "&ccmail=" . urlencode($ccmail);		
					//send_mail($url);	
		}
				

	
	$response="appointment-success";
	header("Location:Appointments?response=".$response);

	
}	
	
	
//Post Comment section
if(isset($_POST['CommentBtn'])){
	$topicId = $_POST['topicId'];
	$partnerId = $_POST['partnerId'];
	$topicType = $_POST['topicType'];
	$userComment = addslashes($_POST['userComment']);
	if(!empty($userComment)){
	$arrFields=array();
	$arrValues=array();
	
	$arrFields[]="login_id";
	$arrValues[]=$partnerId;
	$arrFields[]="login_User_Type";
	$arrValues[]="1";
	$arrFields[]="topic_id";
	$arrValues[]=$topicId;
	$arrFields[]="topic_type";
	$arrValues[]=$topicType;
	$arrFields[]="comments";
	$arrValues[]=$userComment;
	$arrFields[]="post_date";
	$arrValues[]=date('Y-m-d H:i:s');
	
	$createComment=$objQuery->mysqlInsert('home_post_comments',$arrFields,$arrValues);
	header('location:'.HOST_MAIN_URL.''.$_POST['currenturl'].'&response=comment-success');
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

//SHARE LINK TO EMAIL
if(isset($_POST['cmdshare'])){
	
	
	$weblink=HOST_MAIN_URL."".$_POST['currenturl'];
	if($_POST['receiverMail']!="")
	{
				$page_url = 'share_post_link.php';
				$paturl = rawurlencode($page_url);
				$paturl .= "?sharelink=".urlencode($weblink);										
				$paturl .= "&receiverMail=".urlencode($_POST['receiverMail']);
				$paturl .= "&subject=".urlencode($_POST['mailsub']);		
				send_mail($paturl);
	
	$_SESSION['status']="success";
		
		header('location:'.HOST_MAIN_URL.''.$_POST['currenturl']);
	}
	else{
		$_SESSION['status']="error";
		
		header('location:'.HOST_MAIN_URL.''.$_POST['currenturl']);
	}
}
	
//SEND APPOINTMENT/OPINION LINK
 if(isset($_POST['sendappointment'])){
	$getDoc = $objQuery->mysqlSelect("partner_id,contact_person,partner_name,cont_num1,Email_id","our_partners","partner_id='".$admin_id."'" ,"","","","");	
	$weblink=HOST_MAIN_URL."SendRequestLink/RefLink?d=".md5($getDoc[0]['partner_id']);	 
	//Send SMS to requested person
	if(empty($_POST['pat_mobile']) && empty($_POST['pat_email']))
	{
		$response="error-link";
		header("Location:Blogs-Offers-Events-List?response=".$response);
	}
	else{
	
		if(!empty($_POST['pat_mobile']))
		{
			$mobile = $_POST['pat_mobile'];
			$msg = $getDoc[0]['partner_name']." - For Appointments Please visit " . $weblink." - Thank you";
						
			send_msg($mobile,$msg);
		}	
		
		if(!empty($_POST['pat_email']))
		{
		$page_url = 'Custom_send_request_link.php';
							$paturl = rawurlencode($page_url);
							$paturl .= "?doclink=".urlencode($weblink);										
							$paturl .= "&custmail=".urlencode($_POST['pat_email']);
							$paturl .= "&hospName=".urlencode($getDoc[0]['partner_name']);
							$paturl .= "&docEmail=".urlencode($getDoc[0]['Email_id']);
							//$paturl .= "&ccmail=".urlencode($ccmail);		
							send_mail($paturl);
		}
	
	$response="send";
	header("Location:Blogs-Offers-Events-List?response=".$response);
	}

 }

//APPLY JOB
if(isset($_POST['addJobRequest'])){

	$coverNote = addslashes($_POST['coverNote']);
	$event_id = $_POST['event_id'];
	$partner_id = $_POST['partner_id'];
	$attachment = basename($_FILES['txtAttach']['name']);
	
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'applicant_id';
	$arrValues[] = $partner_id;
	$arrFields[] = 'job_id';
	$arrValues[] = $event_id;
	$arrFields[] = 'cover_note';
	$arrValues[] = $coverNote;
	$arrFields[] = 'resume';
	$arrValues[] = $attachment;
	$arrFields[] = 'TImestamp';
	$arrValues[] = $curDate;
	
	$createJob=$objQuery->mysqlInsert('job_application',$arrFields,$arrValues);
	$getDocMail = $objQuery->mysqlSelect("a.email_id as Comp_mail,a.company_name as OrgName","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
	
	$getApplicantDetails = $objQuery->mysqlSelect("a.contact_person as Doc_Name,a.Email_id as Email_id,a.cont_num1 as Contact_num,b.spec_name as Specialization","our_partners as a left join specialization as b on a.specialisation=b.spec_id","a.partner_id='".$admin_id."'" ,"","","","");
	$getEventName= $objQuery->mysqlSelect("title","offers_events","event_id='".$event_id."'" ,"","","","");
	$id= mysql_insert_id();
	/* Uploading image file */ 
				if(basename($_FILES['txtAttach']['name']!=="")){ 
					$uploaddirectory = realpath("Resume");
					mkdir("Resume/". "/" . $id, 0777);
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
				$downloadlink=HOST_MAIN_URL."Refer/download-Attachments.php?appid=".$id."&resume=".$attachment;
					$tomail=$getDocMail[0]['Comp_mail'];
					
						$url_page = 'job_application_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?tomail=" . urlencode($tomail);
						$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
						$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
						$url .= "&specialisation=" . urlencode($getApplicantDetails[0]['Specialization']);
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
				
		header("location:offers.php?s=Jobs&id=".$_POST['id']."&response=job-success");
}	
 
//PARTNER FEEDBACK
if(isset($_POST['addFeedback'])){
	
	$reg_num = $_POST['reg_num'];
	$speaker = addslashes($_POST['speaker']);
	$rating = $_POST['rating'];
	$comment = addslashes($_POST['comment']);
	$event_id = $_POST['event_id'];
	$partner_id = $_POST['partner_id'];
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'partner_id';
	$arrValues[] = $partner_id;
	$arrFields[] = 'doc_id';
	$arrValues[] = $speaker;
	$arrFields[] = 'event_id';
	$arrValues[] = $event_id;
	$arrFields[] = 'event_reg_num';
	$arrValues[] = $reg_num;
	$arrFields[] = 'quality_rating';
	$arrValues[] = $rating;
	$arrFields[] = 'comments';
	$arrValues[] = $comment;
	$arrFields[] = 'Timestamp';
	$arrValues[] = $curDate;
	
	$doctimecreate=$objQuery->mysqlInsert('partner_feedback',$arrFields,$arrValues);
	
	$getDocMail = $objQuery->mysqlSelect("ref_name,ref_mail","referal","ref_id='".$speaker."'" ,"","","","");
		
						$url_page = 'event_feedback_mail.php';
						$url = "https://referralio.com/EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?docname=".urlencode($getDocMail[0]['ref_name']);
						$url .= "&docmail=" . urlencode($getDocMail[0]['ref_mail']);		
						$url .= "&username=" . urlencode($username);
						$url .= "&rating=" . urlencode($rating);	
						$url .= "&comments=" . urlencode($comment);						
								
						$ch = curl_init (); // setup a curl						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );				
						curl_close ( $ch );
	header("location:offers.php?s=Events&id=".$_POST['id']."&response=success");
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
		
		$patientRef=$objQuery->mysqlUpdate('partner_appointment_transaction',$arrFields,$arrValues,"appoint_trans_id='".$_POST['Pat_Trans_Id']."'");
		
		$getInfo1 = $objQuery->mysqlSelect("*","partner_appointment_transaction","appoint_trans_id='".$_POST['Pat_Trans_Id']."'" ,"","","","");	
		$getDoc = $objQuery->mysqlSelect("*","our_partners","partner_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = $objQuery->mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['contact_person']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks";
	send_msg($mobile,$responsemsg);
	$response="reschedule";
	header("Location:appointment_patient_history.php?pattransid=".$_POST['Pat_Trans_Id']."&response=".$response);			

}	
	

//CHANGE PASSWORD 
if(isset($_POST['change_password'])){
	 
	 $txtPass = md5($_POST['new_password']);
	 $txtRePass = md5($_POST['retype_password']);
	
	//$result = $objQuery->mysqlSelect('ref_id','referal',"ref_id='".$_POST['Prov_Id']."'");
	if($txtPass==$txtRePass){
	
		
		$arrFields = array();
		$arrValues = array();		
		
		$arrFields[] = 'password';
		$arrValues[] = $txtPass;
		
		
		$editrecord=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$admin_id."'");
		
						
		header('location:view-profile?response=password');
	}
	else{
	header('location:view-profile?response=error-password');	
	}

}
	
	
//ADD / UPDATE HOPITAL DOCTOR
if(isset($_POST['edit_profile'])){
	
	$txtDoc = addslashes($_POST['txtDoc']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtCity = $_POST['txtCity'];
	$slctHosp = addslashes($_POST['selectHosp']);	
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

		$arrFields[] = 'contact_person';
		$arrValues[] = $txtDoc;
		$arrFields[] = 'partner_name';
		$arrValues[] = $slctHosp;
		if($slctSpec==""){
		$arrFields[] = 'specialisation';
		$arrValues[] = "555";
		}
		else{
		$arrFields[] = 'specialisation';
		$arrValues[] = $slctSpec;	
		}
		$arrFields[] = 'Email_id';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'website';
		$arrValues[] = $txtWebsite;		
		$arrFields[] = 'doc_qual';
		$arrValues[] = $txtQual;
		
		$arrFields[] = 'location';
		$arrValues[] = $txtCity;
		$arrFields[] = 'state';
		$arrValues[] = $slctState;
		$arrFields[] = 'country';
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
		
		$arrFields[] = 'cont_num1';
		$arrValues[] = $txtMobile;
		
		
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
		
	
		
		
	$updateProvider=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$admin_id."'");
	
	$id=$admin_id;	
	
	
				/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!=="")){ 
					$uploaddirectory = realpath("partnerProfilePic");
					mkdir("partnerProfilePic/". "/" . $id, 0777);
					$uploaddir = $uploaddirectory."/".$id;
					$dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					$photo = $docImage;
					$uploadfile = $uploaddir . "/" . $photo;			
				
							
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}
			
		
	$objQuery->mysqlDelete('ref_doc_time_set',"doc_id='".$admin_id."'");
	
					
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
			$arrValues_time[] = $admin_id;
			
			$arrFields_time[] = 'time_id';
			$arrValues_time[] = $Timing_id;
			
			$arrFields_time[] = 'day_id';
			$arrValues_time[] = $day_id;
			
			$arrFields_time[] = 'time_set';
			$arrValues_time[] = $time_limit;
			
			$doctimecreate=$objQuery->mysqlInsert('ref_doc_time_set',$arrFields_time,$arrValues_time);
					
			}
		}
	}
		
		
	header("Location:view-profile?response=update");
	
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
		
		$patientRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['Pat_Trans_Id']."'");
		
		$getInfo1 = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['Pat_Trans_Id']."'" ,"","","","");	
		$getDoc = $objQuery->mysqlSelect("*","our_partners","partner_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = $objQuery->mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['contact_person']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thx";
	send_msg($mobile,$responsemsg);
	$response="reschedule";
	header("Location:appointment_patient_history.php?pattransid=".$_POST['Pat_Trans_Id']."&response=".$response);			

}	
	
//ADD COMMENT

if(isset($_POST['addComment']) && !empty($_POST['txtComment'])){
					$txtComment = addslashes($_POST['txtComment']);
					$slctCondition = addslashes($_POST['slctCondition']);
	
					$arrFields = array();
					$arrValues = array();
									
					$arrFields[]= 'patient_id';
					$arrValues[]= $_POST['patient_id'];
					$arrFields[]= 'ref_id';
					$arrValues[]= $_POST['doc_id'];
					$arrFields[]= 'chat_note';
					$arrValues[]= $txtComment;
					$arrFields[]= 'TImestamp';
					$arrValues[]= $curDate;
					if($slctCondition==1){
						$arrFields[]= 'msg_send_status';
						$arrValues[]= "1";
						
					}
					
					$doctorNote=$objQuery->mysqlInsert('chat_notification',$arrFields,$arrValues);
					if($slctCondition==1){
					
					}
					//Email Notification to patient
					/*$getChatMsg = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","chat_id desc","","","");
					$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
					if(!empty($getPatInfo[0]['doc_photo'])){
						$docimg="https://medisensecrm.com/Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}	
					else{
						$docimg="https://medisensecrm.com/images/doc_icon.jpg";
					}
					
					$getDocName=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$getPatInfo[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_name']));
					$getDocHospAdd=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_addrs']));

					$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$getPatInfo[0]['ref_id'];
		
					$doctorresponse='';
						foreach($getChatMsg as $key=>$value){
						
						$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
						}
						
										
					$url_page = 'Doc_pat_opinion.php';					
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($getPatInfo[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getPatInfo[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($getPatInfo[0]['patient_email']);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					*/
					
					//Message Notification to patient
					$mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." You have received the opinion for your medical query. Check your registered email. Thx, Medisensehealth.com";
					send_msg($mobile,$responsemsg);
					
					$response="1";
					header('location:patient-history?response='.$response);
}	
	
	
	
//TURN TO DIRECT APPOINTMENT
if(isset($_POST['sendAppReq'])){
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //GET TRANSACTION ID
	
$chkRefInfo = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$txtRefId."' and patient_id='".$patientID."'","","","","");
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
$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$patientID."' and ref_id='".$txtRefId."'");
$arrFields1 = array();
$arrValues1 = array();
$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "STAGED"
$arrValues1[]= "7";
$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patientID."'");

}

$chkPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patientID."'","","","","");	
$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
									
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
						
						$url_page = 'Turn_to_Appointment.php';
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
						$url .= "&ccmail=" . urlencode($ccmail);		
						send_mail($url);		
					
						}	
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an appointment. Thx, ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $patientID;
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $txtRefId;
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'status_id';
					$arrValues1[]= "7";
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $curDate;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
										
					$Successmessage="Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
	$response="Appointment-Success";
	header("Location:patient-history?p=".$_SESSION['patientid']."&c=".$_SESSION['adminid']."&response=".$response);			
}	

	
//Update Partner Mail notification Settings
if(isset($_POST['updateMailNotification'])){
$checkOption = $_POST['iCheck'];
$partner_id = $_POST['partner_id'];

$arrFields = array();
$arrValues = array();
$arrFields[]='resp_query_setting';
$arrValues[]=$checkOption;

$updatePartnerSetting=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$partner_id."'");	
$response="update";
header("Location:settings?response=".$response);
}	
	
	
//Add Doctor to Favourite Section
if(isset($_POST['addFavour'])){
	$doc_id = $_POST['doc_id'];
	$user_id = $_POST['partner_id'];
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'user_id';
	$arrValues[]= $user_id;
	$arrFields[]= 'doc_id';
	$arrValues[]= $doc_id;
	$arrFields[]= 'user_type';
	$arrValues[]= "1"; //User type is Ref. partner
	
	$addFavour=$objQuery->mysqlInsert('add_favourite_doctor',$arrFields,$arrValues);
	
	$response="added";
	header("Location:Doctors-List?response=".$response);
	
}	
	
//Search By Docor Name,Location, Address, Keywords etc.
if(isset($_POST['postTextSrchCmd'])){
	$txtSearch = addslashes($_POST['postTextSrch']);
	$searchType=$_POST['searchType'];
	header("Location:Doctors-List?s=".$txtSearch."&type=".$searchType);
	
}
//Search Patient etc.
if(isset($_POST['patientSrchCmd'])){
	$txtSearch = addslashes($_POST['postTextSrch']);
	$searchType=$_POST['searchType'];
	header("Location:srch?s=".$txtSearch."&type=".$searchType);
	
}

//Add Offers & Events
if(isset($_POST['addOffers']) || isset($_POST['editOffers'])){

	$startDate= $_POST['startendDate'];
	$docId= $_POST['selectref'];
	$marketId= $_POST['selectmarket'];
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
	$arrFields[]= 'photo';
	$arrValues[]= $event_pic;
	
	if(isset($_POST['addOffers'])){
	$addoffers=$objQuery->mysqlInsert('offers_events',$arrFields,$arrValues);
	$id= mysql_insert_id();
	$response="add";
	} else if(isset($_POST['editOffers'])){
		$updateOffer=$objQuery->mysqlUpdate('offers_events',$arrFields,$arrValues,"event_id='".$_POST['Event_Id']."'");	
			
	$id= $_POST['Event_Id'];
	$response="update";
	}
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!=="")){ 
					$uploaddirectory = realpath("Eventimages");
					mkdir("Eventimages/". "/" . $id, 0777);
					$uploaddir = $uploaddirectory."/".$id;
					$dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					$photo = $event_pic;
					$uploadfile = $uploaddir . "/" . $photo;			
				
							
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}
	header("Location:Offers-Events?response=".$response);
	
}

//Add Blog Post
if(isset($_POST['cmdBlog'])){
	$blogTitle= addslashes($_POST['blog_title']);
	$txtRefId= $_POST['selectref'];
	$blogDesc= addslashes($_POST['descr']);
	$postkey=time();
	if(!empty($txtRefId)){
		$loginid=$txtRefId;
		$logintype="doc";
	}
	else{
		$loginid=$admin_id;
		$logintype="user";
	}
	
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $blogTitle;
	$arrFields[]= 'Login_User_Id';
	$arrValues[]= $loginid;
	$arrFields[]= 'post_description';
	$arrValues[]= $blogDesc;
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $logintype;
	$arrFields[]= 'company_id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'post_date';
	$arrValues[]= $curDate;
	$addblogs=$objQuery->mysqlInsert('home_posts',$arrFields,$arrValues);
	header("Location:Blogs");
}
	
if(isset($_POST['addRef'])){
	
	$txtRefId= $_POST['txtref']; //It containes doc_id
	
	//Check not empty condition of Doctor
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
	$chkreflist = $objQuery->mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$txtRefId."'","","","","");
	if($chkreflist==true){
		$errorMessage="Sorry '".$chkreflist[0]['ref_name']."' referal is already existed";
	}else{
		
			$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
			$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			$getDocDept = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$txtRefId."'","","","","");
			
			if($getPatInfo[0]['patient_loc']=="" || $getPatInfo[0]['contact_person']=="" || $getPatInfo[0]['patient_mob']=="" || $getPatInfo[0]['pat_country']=="" || $getPatInfo[0]['patient_complaint']=="" || $getPatInfo[0]['patient_desc']=="" || $getPatInfo[0]['repnotattach']==0){
			
			echo '<script language="javascript">';
			echo 'alert("Please fill the required patient details properly")';
			echo '</script>'; 
			
			} else if($getPatAttach==true || $getPatInfo[0]['repnotattach']==1 ) {
		
						$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$_POST['Pat_Id']."'");	
						$patientRef=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
						$ref_id=mysql_insert_id();
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
					$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$pat_id."'and ref_id='".$refer_id."'");
					
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields3 = array();
					$arrValues3 = array();
					$arrFields3[]= 'Total_Referred';
					$arrValues3[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
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
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
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
					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					
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
	


//ADD / UPDATE HOPITAL DOCTOR
if(isset($_POST['add_doctor']) || isset($_POST['edit_doctor'])){
	//Check Empty condition
	if(!empty($_POST['txtDoc']) || !empty($_POST['txtCountry']) || !empty($_POST['slctState']) || !empty($_POST['txtCity']) || !empty($_POST['selectHosp']) || !empty($_POST['slctSpec']))
	{
	$txtDoc = addslashes($_POST['txtDoc']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtCity = $_POST['txtCity'];
	$slctHosp = addslashes($_POST['selectHosp']);	
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
	$txtNumOpinion = addslashes($_POST['numopinion']);
	$txtInOpcost = addslashes($_POST['inopcost']);
	$txtOnOpcost = addslashes($_POST['onopcost']);
	$txtConcharge = addslashes($_POST['conscharge']);
	$txtSecEmail = $_POST['txtSecEmail'];
	$txtSecPhone = $_POST['txtSecPhone'];
	$docImage = basename($_FILES['txtPhoto']['name']);	
	
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
		
	/* IF $_POST['add_doctor'] VALUE IS TRUE, THEN ONLY DOCTORS RECORDS WILL INSERTED */
	
	if(isset($_POST['add_doctor'])){
	$usercraete=$objQuery->mysqlInsert('referal',$arrFields,$arrValues);
		$id = mysql_insert_id();
		
		/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!=="")){ 
					$uploaddirectory = realpath("../Doc");
					mkdir("../Doc/". "/" . $id, 0777);
					$uploaddir = $uploaddirectory."/".$id;
					$dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					$photo = $docImage;
					$uploadfile = $uploaddir . "/" . $photo;			
				
						
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}
		$arrFields1 = array();
		$arrValues1= array();
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $id;
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$usercreate=$objQuery->mysqlInsert('doctor_hosp',$arrFields1,$arrValues1);	
	header("Location:Add-Hospital-Doctors?response=add");
	}
	/* IF $_POST['edit_doctor'] VALUE IS TRUE, THEN ONLY DOCTORS RECORDS WILL UPDATED */
	else{	
	$updateProvider=$objQuery->mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$_POST['Prov_Id']."'");
	$arrFields1 = array();
	$arrValues1= array();
	$chkHosp = $objQuery->mysqlSelect("*","doctor_hosp ","doc_id='".$_POST['Prov_Id']."'","","","","");
	$id=$_POST['Prov_Id'];
				/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!=="")){ 
					$uploaddirectory = realpath("../Doc");
					mkdir("../Doc/". "/" . $id, 0777);
					$uploaddir = $uploaddirectory."/".$id;
					$dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					$photo = $docImage;
					$uploadfile = $uploaddir . "/" . $photo;			
				
							
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}
		if($chkHosp==true){
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$updateProvider=$objQuery->mysqlUpdate('doctor_hosp',$arrFields1,$arrValues1,"doc_id='".$_POST['Prov_Id']."'");
		} else {
		$arrFields1 = array();
		$arrValues1= array();
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $_POST['Prov_Id'];
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$usercraete=$objQuery->mysqlInsert('doctor_hosp',$arrFields1,$arrValues1);
		}	
	header("Location:Add-Hospital-Doctors?response=update");
	}
	}
	//Send Error message
	else
	{
		header("Location:Add-Hospital-Doctors?response=error");
	}
}

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
					
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields,$arrValues);
					
					//Change Status2 condition
					$getPatInfo= $objQuery->mysqlSelect("*","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","b.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
					$getStatus2=$getPatInfo[0]['status2']; //Get present patient status of perticular referral
					if($getStatus2<5){  //Status2 will change only when present status remains in below respond level, ie. it must be in 'New'/Refered/P-Awating Status
						$arrFields1[]= 'status2';
						$arrValues1[]= "5";
					}
					
					/*$arrFields1[]= 'bucket_status';
					$arrValues1[]= $_POST['Pro4_status2'];*/
					$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'");
					
					
					//Email Notification to patient
					$getChatMsg = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","chat_id desc","","","");
					$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
					if(!empty($getPatInfo[0]['doc_photo'])){
						$docimg=HOST_MAIN_URL."Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}	
					else{
						$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
					}
					
					$getDocName=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$getPatInfo[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_name']));
					$getDocHospAdd=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_addrs']));

					$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$getPatInfo[0]['ref_id'];
		
					$doctorresponse='';
						foreach($getChatMsg as $key=>$value){
						
						$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
						}
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
					$url .= "&patmail=" . urlencode($getPatInfo[0]['patient_email']);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to patient
					$mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." You have received the opinion for your medical query. Check your registered email. Thx";
					send_msg($mobile,$responsemsg);
					$response=1;	
					header('location:patient-history?response='.$response.'&p='.$_POST['ency_patient_id'].'&c='.$_POST['ency_admin_id']);					
					
					
}


?>