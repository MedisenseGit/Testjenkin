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
include('../premium/short_url.php');
include('send_push_notifications.php');
$ccmail = "medical@medisense.me";

$ip 		= $_SERVER['REMOTE_ADDR']; // find time zone
$ipInfo 	= file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo 	= json_decode($ipInfo);
$timezone	= $ipInfo->timezone;
date_default_timezone_set($timezone);
if($timezone=="")
{
	$timezone='Asia/Kolkata';
}
$headers 	= apache_request_headers();
if($headers)
{
    $user_id 	= $headers['user-id'];
	$timestamp 	= $headers['x-timestamp'];
	$hashKey	= $headers['x-hash'];
	$device_id  = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);



// Book Consultation
if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey)
	{
		$user_id 			= 	$user_id;
		$member_id 			= 	$_POST['member_id'];
		$doc_id 			= 	$_POST['doc_id'];
		$consultation_type  = 	$_POST['consultation_type'];
		$txtName 			= 	$_POST['member_name'];
		$txtGen 			= 	$_POST['member_gender'];			 // 1- Male, 2-Female, 3-Other, 0-Not Mentioned
		$txtAge 			= 	$_POST['member_age'];
		$member_height 		= 	$_POST['member_height'];
		$member_weight 		= 	$_POST['member_weight'];
		$member_blood_group = 	$_POST['member_blood_group'];
		$member_bp 			= 	$_POST['member_bp'];
		$member_thyroid 	= 	$_POST['member_thyroid'];
		
		$member_asthama 	= 	$_POST['member_asthama'];
		$member_cholestrol 	= 	$_POST['member_cholestrol'];
		$member_epilepsy 	= 	$_POST['member_epilepsy'];
		$member_diabetic 	= 	$_POST['member_diabetic'];
		$member_allergies 	= 	$_POST['member_allergies'];
		$member_smoking 	= 	$_POST['member_smoking'];
		$member_alcohol 	= 	$_POST['member_alcohol'];
		
		$member_doc_origin 	= 	$_POST['member_doc_origin'];
		$hospital_id 		= 	$_POST['doc_hospital_id'];
		$consult_charge 	= 	$_POST['consultation_charge'];
		
		$txtMob 			= 	$_POST['contact_num'];
		$txtMail 			= 	$_POST['user_email'];
		$txtAppointType 	= 	3;  // 0 - Walkin, 1- Appointment, 2-Teleconsultation, 3- Instant Video Consult
		$member_hypertension 		= 	$_POST['member_hypertension'];
		$consultation_currency_type = 	$_POST['consultation_currency_type'];
		$member_consult_lang_id 	= 	$_POST['member_consult_lang_id'];
		$member_consult_lang_name 	= 	$_POST['member_consult_lang_name'];
		
		if($member_hypertension == 2)
		{		  
			$txtHypercondition = 1; // 1-NO, 2-YES, 0-NOT MENTIONED
		}
		else 
		{
			$txtHypercondition = 0;
		}
		
		if($member_diabetic == 2) 
		{			  
			$txtDiabetic = 1; // 1-NO, 2-YES, 0-NOT MENTIONED
		}
		else 
		{
			$txtDiabetic = 0;
		}
		
		//Local to UTC time 
		$dateTime = $curDateSlot;
		$tz_from = ('Asia/Kolkata');
		$newDateTime = new DateTime($dateTime, new DateTimeZone($tz_from));
		$newDateTime->setTimezone(new DateTimeZone("UTC"));
		$dateTimeUTC = $newDateTime->format("h:i A");
			
		$transid = time();
		$chkInDate = date('Y-m-d'); //Current Date
		$status = "Pending";
		$get_pro = mysqlSelect('*','referal',"ref_id='".$doc_id."'");
		
		$check_patient = mysqlSelect('*','patients_appointment',"member_id='".$member_id."' OR patient_mobile='".$txtMob."'");
		if(empty($check_patient))
		{
			// Add new patient
			$arrFields = array();
			$arrValues = array();
					
			$arrFields[] = 'patient_name';
			$arrValues[] = $txtName;
			$arrFields[] = 'member_id';
			$arrValues[] = $member_id;
			$arrFields[] = 'login_id';
			$arrValues[] = $user_id;
			$arrFields[] = 'patient_email';
			$arrValues[] = $txtMail;		
			$arrFields[] = 'patient_mobile';
			$arrValues[] = $txtMob;
			$arrFields[] = 'patient_gender';
			$arrValues[] = $txtGen;
			$arrFields[] = 'created_date';
			$arrValues[] = $curDate;;
			
			$insert_patient = mysqlInsert('patients_appointment',$arrFields,$arrValues);
			$patient_id		= $insert_patient;
		}
		else
		{
			$patient_id		= $check_patient[0]['patient_id'];
		}
		
		//Add data to transaction table
		$arrFieldsTrans = array();
		$arrValuesTrans = array();
				
		$arrFieldsTrans[] = 'patient_id';
		$arrValuesTrans[] = $patient_id;
		
		$arrFieldsTrans[] = 'service_type';
		$arrValuesTrans[] = '3';
		
		$arrFieldsTrans[] = 'transaction_id';
		$arrValuesTrans[] = $transid;
		
		$arrFieldsTrans[] = 'doc_id';
		$arrValuesTrans[] = $doc_id;
		
		$arrFieldsTrans[] = 'hosp_id';
		$arrValuesTrans[] = $hospital_id;
		
		$arrFieldsTrans[] = 'contact_person';
		$arrValuesTrans[] = $txtName;
		
		$arrFieldsTrans[] = 'patient_age';
		$arrValuesTrans[] = $txtAge;
		
		$arrFieldsTrans[] = 'address';
		$arrValuesTrans[] = $txtAddress;
		
		$arrFieldsTrans[] = 'city';
		$arrValuesTrans[] = $txtLoc;
		
		$arrFieldsTrans[] = 'state';
		$arrValuesTrans[] = $txtState;	
		
		$arrFieldsTrans[] = 'country';
		$arrValuesTrans[] = $txtCountry;
		
		$arrFieldsTrans[] = 'height_cms';
		$arrValuesTrans[] = $member_height;
		
		$arrFieldsTrans[] = 'weight';
		$arrValuesTrans[] = $member_weight;
		
		$arrFieldsTrans[] = 'hyper_cond';
		$arrValuesTrans[] = $member_hypertension;
		
		$arrFieldsTrans[] = 'diabetes_cond';
		$arrValuesTrans[] = $member_diabetic;
		
		$arrFieldsTrans[] = 'smoking';
		$arrValuesTrans[] = $member_smoking;
		
		$arrFieldsTrans[] = 'alcoholic';
		$arrValuesTrans[] = $member_alcohol;
		
		$arrFieldsTrans[] = 'blood_group';
		$arrValuesTrans[] = $member_blood_group;
		
		$arrFieldsTrans[] = 'pat_bp';
		$arrValuesTrans[] = $member_bp;
		
		$arrFieldsTrans[] = 'pat_thyroid';
		$arrValuesTrans[] = $member_thyroid;
		
		$arrFieldsTrans[] = 'pat_cholestrole';
		$arrValuesTrans[] = $member_cholestrol;
		
		$arrFieldsTrans[] = 'pat_epilepsy';
		$arrValuesTrans[] = $member_epilepsy;
		
		$arrFieldsTrans[] = 'pat_asthama';
		$arrValuesTrans[] = $member_asthama;
		
		$arrFieldsTrans[] = 'allergies_any';
		$arrValuesTrans[] = $member_allergies;
		
		$arrFieldsTrans[] = 'visiting_date';
		$arrValuesTrans[] = date('Y-m-d',strtotime($chkInDate));
		
		$arrFieldsTrans[] = 'visiting_time';
		$arrValuesTrans[] = '0';						
		
		$arrFieldsTrans[] = 'time_slot';
		$arrValuesTrans[] = $dateTimeUTC;
		
		$arrFieldsTrans[] = 'amount';
		$arrValuesTrans[] = $get_pro[0]['consult_charge'];
		
		$arrFieldsTrans[] = 'currency_type';
		$arrValuesTrans[] = $get_pro[0]['cons_charge_currency_type'];
		
		$arrFieldsTrans[] = 'pay_status';
		$arrValuesTrans[] = $status;
		
		$arrFieldsTrans[] = 'visit_status';
		$arrValuesTrans[] = 'new_visit';
		
		$arrFieldsTrans[] = 'created_date';
		$arrValuesTrans[] = $curDate;
		
		$add_transaction  = mysqlInsert('patients_transactions',$arrFieldsTrans,$arrValuesTrans);
		$patient_trans_id =	$add_transaction;
		
		// Update video call links
		$video_link_doctor 	= "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$txtName."&type=1&r=".$doc_id."_".$member_id."_".$transid;
		$video_link_patient = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$txtName."&type=2&r=".$doc_id."_".$member_id."_".$transid;
		
		$agora_video_link_doctor = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$patient_trans_id."&t=1";  
		// t=1 for doctor link, 2 -for patient link
		$agora_video_link_patient = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$patient_trans_id."&t=2"; 
		// t=1 for doctor link, 2 -for patient link

		$arrFieldsVid = array();
		$arrValuesVid = array();
		
		$arrFieldsVid[] = 'doc_video_link';
		$arrValuesVid[] = $video_link_doctor;
		
		$arrFieldsVid[] = 'pat_video_link';
		$arrValuesVid[] = $video_link_patient;
		
		$arrFieldsVid[] = 'doc_agora_link';
		$arrValuesVid[] = $agora_video_link_doctor;
		
		$arrFieldsVid[] = 'pat_agora_link';
		$arrValuesVid[] = $agora_video_link_patient; 
		
		$updateAppointTrans=mysqlUpdate('patients_transactions',$arrFieldsVid,$arrValuesVid,"patient_trans_id='".$patient_trans_id."'");
		
		//Insert data to Patient Token System
		$arrFields_token  = array();
		$arrValues_token  = array();
		
		$arrFields_token[] = 'patient_trans_id';
		$arrValues_token[] = $patient_trans_id;
		
		$arrFields_token[] = 'token_no';
		$arrValues_token[] = "555"; //For Online Booking
		
		$arrFields_token[] = 'created_date';
		$arrValues_token[] = $curDate;
		
		$createToken 	   = mysqlInsert('patients_token_system',$arrFields_token,$arrValues_token); // appointment_token_system to patients_token_system

		// Add to Health App Notification Section
		$arrFieldsNotify=array();	
		$arrValuesNotify=array();
		
		$title ="Dear ".$txtName.", you have booked an appointment with Dr.".$get_pro[0]['ref_name'];
		$description = "Your Consultation link will be activated once the payment is confirmed. \nConsultation Link: ".$agora_video_link_patient. " \n";
		
		$arrFieldsNotify[]	='title';
		$arrValuesNotify[]	=$title;
		$arrFieldsNotify[]	='description';
		$arrValuesNotify[]	=$description;
		$arrFieldsNotify[]	='video_link';
		$arrValuesNotify[]	=$agora_video_link_patient;
		$arrFieldsNotify[]	='patient_login_id';
		$arrValuesNotify[]	=$user_id;			// Patient Login User ID
		$arrFieldsNotify[]	='doc_id';
		$arrValuesNotify[]	=$doc_id;
		$arrFieldsNotify[]	='notify_type';
		$arrValuesNotify[]	='2';					// 1-Normal msg, 2-Video Call Link
		$arrFieldsNotify[]	='visibility';
		$arrValuesNotify[]	='1';					// 1-unread, 0-read
		$arrFieldsNotify[]	='created_date';
		$arrValuesNotify[]	=$curDate;
		$app_notify			=mysqlInsert('health_app_notifications',$arrFieldsNotify,$arrValuesNotify);
		
		//Update to Accept/Reject table 
		$arrFields_CallStatus = array();
		$arrValues_CallStatus = array();
			
		$arrFields_CallStatus[] = 'doc_id';
		$arrValues_CallStatus[] = $doc_id;
		$arrFields_CallStatus[] = 'login_id';
		$arrValues_CallStatus[] = $user_id;
		$arrFields_CallStatus[] = 'patient_id';
		$arrValues_CallStatus[] = $patient_id;
		$arrFields_CallStatus[] = 'appoint_trans_id';
		$arrValues_CallStatus[] = $transid;
		$arrFields_CallStatus[] = 'consult_status';
		$arrValues_CallStatus[] = '1';						// 1- Request Sent, 2-Accpeted, 3-Rejected/Decline
		$arrFields_CallStatus[] = 'created_date';
		$arrValues_CallStatus[] = $curDate;
		$arrFields_CallStatus[] = 'unique_trans_id';
		$arrValues_CallStatus[] = $patient_trans_id;
		$insertCallStatus 		= mysqlInsert('appointment_accept_reject',$arrFields_CallStatus,$arrValues_CallStatus);
		
		
		//Send Push Notification To Doctors Starts
		$FCMTokenID 	= $get_pro[0]['FCM_takenID'];
		
		$extraNotificationData = ["title" => 'Premium - '.$txtName.' has Booked Teleconsultation',"body" => $txtName.' booked an instant teleconsultation with you.', 'icon' => HOST_CRM_URL.'assets/img/nova_logo.png'];
		
		$extraData = ["notification_type" => '1', "doc_id" => $doc_id, "patient_id" => $patient_id, 'doctor_name' =>$get_pro[0]['ref_name'], 'patient_name' =>$txtName, 'patient_city' =>$txtLoc , 'patient_state' =>$txtState , 'patient_country' =>$txtCountry , 'appointment_ID' =>$patient_trans_id,'consultation_docVideoLink' =>$agora_video_link_doctor];
		
		if(!empty($FCMTokenID))
		{
			$sendPushNotificationToDoctor= send_push_notifications($FCMTokenID, $extraNotificationData, $extraData);
			
			// Add to Appointment Tracking
			$arrFieldsTrack = array();
			$arrValuesTrack = array();
					
			$arrFieldsTrack[] = 'doc_id';
			$arrValuesTrack[] = $doc_id;
			$arrFieldsTrack[] = 'patient_id';
			$arrValuesTrack[] = $patient_id;
			$arrFieldsTrack[] = 'appoint_trans_id';
			$arrValuesTrack[] = $patient_trans_id;
			$arrFieldsTrack[] = 'message';
			$arrValuesTrack[] = 'Appointment Request has been sent';
			$arrFieldsTrack[] = 'status';
			$arrValuesTrack[] = '2';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
			$arrFieldsTrack[] = 'created_date';
			$arrValuesTrack[] = $curDate;
			$insertTrack = mysqlInsert('appointment_tracking',$arrFieldsTrack,$arrValuesTrack);
		}
		//Send Push Notification To Doctors Ends
		
		// Medical Background General Health Updates
		$get_MedicalBackground = mysqlSelect('*','user_family_general_health',"member_id ='".$member_id."'","","","","");
		if($get_MedicalBackground==true)
		{
				
			$arrFields_MedBackUpdate = array();
			$arrValues_MedBackUpdate = array();
			
			$arrFields_MedBackUpdate[] = 'bp';
			$arrValues_MedBackUpdate[] = $member_bp;
			$arrFields_MedBackUpdate[] = 'hypertension';
			$arrValues_MedBackUpdate[] = $member_hypertension;
			$arrFields_MedBackUpdate[] = 'cholesterol';
			$arrValues_MedBackUpdate[] = $member_cholestrol;
			$arrFields_MedBackUpdate[] = 'diabetic';
			$arrValues_MedBackUpdate[] = $member_diabetic;
			$arrFields_MedBackUpdate[] = 'thyroid';
			$arrValues_MedBackUpdate[] = $member_thyroid;
			$arrFields_MedBackUpdate[] = 'asthama';
			$arrValues_MedBackUpdate[] = $member_asthama;
			$arrFields_MedBackUpdate[] = 'epilepsy';
			$arrValues_MedBackUpdate[] = $member_epilepsy;
			$arrFields_MedBackUpdate[] = 'allergies_any';
			$arrValues_MedBackUpdate[] = $member_allergies_text;
			$arrFields_MedBackUpdate[] = 'smoking';
			$arrValues_MedBackUpdate[] = $member_smoking;
			$arrFields_MedBackUpdate[] = 'alcohol';
			$arrValues_MedBackUpdate[] = $member_alcohol;
			$arrFields_MedBackUpdate[] = 'created_date';
			$arrValues_MedBackUpdate[] = $curDate;
		
			$updateMedBackground=mysqlUpdate('user_family_general_health',$arrFields_MedBackUpdate,$arrValues_MedBackUpdate,"member_id='".$member_id."'");		
		}
		else 
		{
			$arrFields_MedBack = array();
			$arrValues_MedBack = array();
		
			$arrFields_MedBack[] = 'member_id';
			$arrValues_MedBack[] = $member_id;
			$arrFields_MedBack[] = 'user_id';
			$arrValues_MedBack[] = $user_id;
			$arrFields_MedBack[] = 'bp';
			$arrValues_MedBack[] = $member_bp;
			$arrFields_MedBack[] = 'hypertension';
			$arrValues_MedBack[] = $member_hypertension;
			$arrFields_MedBack[] = 'cholesterol';
			$arrValues_MedBack[] = $member_cholestrol;
			$arrFields_MedBack[] = 'diabetic';
			$arrValues_MedBack[] = $member_diabetic;
			$arrFields_MedBack[] = 'thyroid';
			$arrValues_MedBack[] = $member_thyroid;
			$arrFields_MedBack[] = 'asthama';
			$arrValues_MedBack[] = $member_asthama;
			$arrFields_MedBack[] = 'epilepsy';
			$arrValues_MedBack[] = $member_epilepsy;
			$arrFields_MedBack[] = 'allergies_any';
			$arrValues_MedBack[] = $member_allergies_text;
			$arrFields_MedBack[] = 'smoking';
			$arrValues_MedBack[] = $member_smoking;
			$arrFields_MedBack[] = 'alcohol';
			$arrValues_MedBack[] = $member_alcohol;
			$arrFields_MedBack[] = 'created_date';
			$arrValues_MedBack[] = $curDate;
			$insertMedBackground = mysqlInsert('user_family_general_health',$arrFields_MedBack,$arrValues_MedBack);
		}
		//Update family member details
		$get_Members = mysqlSelect('*','user_family_member',"member_id ='".$member_id."'","","","","");
		if($get_Members==true)
		{
			$arrFields_Member = array();
			$arrValues_Member = array();
			
			$arrFields_Member[] = 'member_name';
			$arrValues_Member[] = $txtName;
			$arrFields_Member[] = 'gender';
			$arrValues_Member[] = $txtGen;
			$arrFields_Member[] = 'age';
			$arrValues_Member[] = $txtAge;
			$arrFields_Member[] = 'height';
			$arrValues_Member[] = $member_height;	
			$arrFields_Member[] = 'weight';
			$arrValues_Member[] = $member_weight;
			$arrFields_Member[] = 'blood_group';
			$arrValues_Member[] = $member_blood_group;
			
			$updateMember=mysqlUpdate('user_family_member',$arrFields_Member,$arrValues_Member,"member_id='".$member_id."'");		
		}
		
		
		$getPatInfo	= mysqlSelect('a.patient_id as patient_id,a.patient_name AS patient_name,a.patient_mobile AS patient_mob,a.patient_email AS patient_email,b.address as patient_addrs,b.city as patient_loc,b.state AS pat_state,b.country AS pat_country,b.contact_person AS contact_person','patients_appointment AS a INNER JOIN patients_transactions AS b ON a.patient_id = b.patient_id',"a.patient_id ='".$patient_id."'","","","","");
		
		//Patient Info EMAIL notification Sent to Doctor
		if(!empty($get_pro[0]['ref_mail']))
		{
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
			$url .= "&prefTime=" . urlencode($dateTimeUTC);
			$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
			$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
			$url .= "&ccmail=" . urlencode($ccmail);	
			$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
			send_mail($url);	
		}
		
		$success_consults = array('result' => "success", 'status' => '1', 'doc_my_patientID' => $patient_id, 'appointment_TransactionID' => $transid, 'appointment_consultCharge' => $get_pro[0]['consult_charge'], 'appointment_consultChargeType' => $get_pro[0]['cons_charge_currency_type'], 'doc_id' => $doc_id, 'doc_name' => $get_pro[0]['ref_name'], 'doc_hospital_id' => $hospital_id,'appointment_id' => $patient_trans_id,   "member_medical_background"=>$result_medBackground, "family_details"=>$result_family, 'message' => "Consultation Booked Successfully !!! \nYou will receive an Email/SMS with payment link to confirm the consultation.", 'err_msg' => '');
		echo json_encode($success_consults);

		
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
