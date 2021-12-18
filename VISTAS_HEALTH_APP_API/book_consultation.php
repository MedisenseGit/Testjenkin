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
		$txtAppointType 	= 	3;  				// 0 - Walkin, 1- Appointment, 2-Teleconsultation, 3- Instant Video Consult
		$member_hypertension 		= 	$_POST['member_hypertension'];
		$consultation_currency_type = 	$_POST['consultation_currency_type'];
		$member_consult_lang_id 	= 	$_POST['member_consult_lang_id'];
		$member_consult_lang_name 	= 	$_POST['member_consult_lang_name'];
		
		if($member_hypertension == 2)
		{		  // 1-NO, 2-YES, 0-NOT MENTIONED
			$txtHypercondition = 1;
		}
		else 
		{
			$txtHypercondition = 0;
		}
		
		if($member_diabetic == 2) 
		{			  // 1-NO, 2-YES, 0-NOT MENTIONED
			$txtDiabetic = 1;
		}
		else 
		{
			$txtDiabetic = 0;
		}
		
		$uniqueIDTeleconsultStatus = time();
		
		$transid = time();
		$get_pro = mysqlSelect('*','referal',"ref_id='".$doc_id."'");
		
		$chkInDate = date('Y-m-d'); //Current Date
		$status = "Pending";
		
		$day_val	=	date('D', strtotime($chkInDate));
		
		/*$getday_id 	= mysqlSelect("*","seven_days","da_name='".$day_val."'","","","","");
		
		$GetTimeSlot = mysqlSelect("b.time_id as time_id,a.utc_slots as utc_slots,a.categoty as categoty ","appointment_utc_slots AS a INNER JOIN doctor_appointment_slots_set AS b ON a.id = b.time_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hospital_id."' AND b.day_id = '".$getday_id[0]['day_id']."'","","","","");
		
		$slot_details = array();
		if(!empty($GetTimeSlot))
		{
			foreach($GetTimeSlot as $TimeSlot)
			{ 
				$utc_slot = $TimeSlot['utc_slots'];
				$UTCObj   = new DateTime($utc_slot, new DateTimeZone("UTC"));
				$LocalObj = $UTCObj;
				$LocalObj->setTimezone(new DateTimeZone($timezone));
				$categoty = $TimeSlot['categoty'];
				$timeId   = $TimeSlot['time_id'];
				$dtA = new DateTime($Cur_Date);
				$dtB = new DateTime($LocalObj->format("g:i A"));
				
					
				$time_slot 	= 	$LocalObj->format("g:i A");
				$timeId		=	$timeId;
					
				
				$getTimeSlotList['time_slot']	=	$time_slot;
				$getTimeSlotList['timeId']		=	$timeId;
				$getTimeSlotList['categoty']	=	$categoty;	 //1- morning , 2 -afternoon ,3 - evening ,4 - night
				array_push($slot_details, $getTimeSlotList);
			}
		
		}*/
		
		
		
		
		$GetTiming= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","b.time_id desc","","","");
		foreach($GetTiming as $TimeList)
		{
			$chkDocTimeSlot = mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$doc_id."' and doc_type='1' and hosp_id = '".$hospital_id."'","","","","");
				
			//echo $chkDocTimeSlot[0]['num_patient_hour'];
			$countPrevAppBook = mysqlSelect("COUNT(id) as Appoint_Count","appointment_transaction_detail","pref_doc='".$doc_id."' and hosp_id = '".$hospital_id."' and Visiting_date = '".$chkInDate."' and Visiting_time = '".$TimeList["time_id"]."'","","","","");
				if($countPrevAppBook[0]['Appoint_Count']<$chkDocTimeSlot[0]['num_patient_hour'])
				{
					$chkInTime = $TimeList["time_id"];	
				}
		}
		
		$video_link_doctor = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$txtName."&type=1&r=".$doc_id."_".$member_id."_".$transid;
		$video_link_patient = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$txtName."&type=2&r=".$doc_id."_".$member_id."_".$transid;
		
		$agora_video_link_doctor = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$doc_id."_".$member_id."_".$transid."&t=1";				// t=1 for doctor link, 2 -for patient link
		$agora_video_link_patient = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$doc_id."_".$member_id."_".$transid."&t=2";				// t=1 for doctor link, 2 -for patient link
	
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;

			$arrFields_patient[] = 'patient_age';
			$arrValues_patient[] = $txtAge; 

			$arrFields_patient[] = 'patient_email';
			$arrValues_patient[] = $txtMail;

			$arrFields_patient[] = 'patient_gen';
			$arrValues_patient[] = $txtGen;
				
			$arrFields_patient[] = 'patient_mob';
			$arrValues_patient[] = $txtMob;

			$arrFields_patient[] = 'pat_country';
			$arrValues_patient[] = $member_doc_origin;

			$arrFields_patient[] = 'doc_id';
			$arrValues_patient[] = $doc_id;

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = date('Y-m-d');
				
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = $curDate;
			
			$arrFields_patient[] = 'transaction_id';
			$arrValues_patient[] = $transid;
			
			$arrFields_patient[] = 'hyper_cond';
			$arrValues_patient[] = $txtHypercondition;
			
			$arrFields_patient[] = 'diabetes_cond';
			$arrValues_patient[] = $txtDiabetic;
			
			$arrFields_patient[] = 'height';
			$arrValues_patient[] = $member_height;
			
			$arrFields_patient[] = 'weight';
			$arrValues_patient[] = $member_weight;
			
			$arrFields_patient[] = 'pat_blood';
			$arrValues_patient[] = $member_blood_group;
			
			$arrFields_patient[] = 'smoking';
			$arrValues_patient[] = $member_smoking;
			
			$arrFields_patient[] = 'alcoholic';
			$arrValues_patient[] = $member_alcohol;
			
			$arrFields_patient[] = 'tele_communication';
			$arrValues_patient[] = '1';
			
			$arrFields_patient[] = 'member_id';
			$arrValues_patient[] = $member_id;
			
			$arrFields_patient[] = 'pat_bp';
			$arrValues_patient[] = $member_bp;
			
			$arrFields_patient[] = 'pat_thyroid';
			$arrValues_patient[] = $member_thyroid;
			
			$arrFields_patient[] = 'pat_cholestrole';
			$arrValues_patient[] = $member_cholestrol;
			
			$arrFields_patient[] = 'pat_epilepsy';
			$arrValues_patient[] = $member_epilepsy;
			
			$arrFields_patient[] = 'pat_asthama';
			$arrValues_patient[] = $member_asthama;
			
			$arrFields_patient[] = 'doc_video_link';
			$arrValues_patient[] = $video_link_doctor;
			
			$arrFields_patient[] = 'pat_video_link';
			$arrValues_patient[] = $video_link_patient;
			
			$arrFields_patient[] = 'doc_agora_link';
			$arrValues_patient[] = $agora_video_link_doctor;
			
			$arrFields_patient[] = 'pat_agora_link';
			$arrValues_patient[] = $agora_video_link_patient;
			
			$patientcreate=mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
			$patientid = $patientcreate;  //Get Patient Id
			
			$getPatInfo = mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
			
			$arrFields1 = array();
			$arrValues1 = array();
					
			$arrFields1[] = 'appoint_trans_id';
			$arrValues1[] = $transid;
			$arrFields1[] = 'patient_id';
			$arrValues1[] = $patientid;
			$arrFields1[] = 'pref_doc';
			$arrValues1[] = $doc_id;
			$arrFields1[] = 'member_id';
			$arrValues1[] = $member_id;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $hospital_id;
			
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
			$arrValues1[] = $status;
			$arrFields1[] = 'visit_status';
			$arrValues1[] = "new_visit";
			$arrFields1[] = 'Time_stamp';
			$arrValues1[] = $curDate;
			$arrFields1[] = 'src_type';
			$arrValues1[] = '1';			// 1 - Medisense Health Src
			$arrFields1[] = 'appointment_type';
			$arrValues1[] = $txtAppointType;
			$arrFields1[] = 'tele_communication';
			$arrValues1[] = '1';
					
			$createappointment=mysqlInsert('appointment_transaction_detail',$arrFields1,$arrValues1);
			$appointTransid = $createappointment; 
			
			$getTime=mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
			
			$arrFieldsAppSlot = array();
			$arrValuesAppSlot = array();
			
			$arrFieldsAppSlot[] = 'token_no';
			$arrValuesAppSlot[] = "555"; //For Online Booking
			
			$arrFieldsAppSlot[] = 'patient_id';
			$arrValuesAppSlot[] = $patientid;
			$arrFieldsAppSlot[] = 'appoint_trans_id';
			$arrValuesAppSlot[] = $transid;
			$arrFieldsAppSlot[] = 'patient_name';
			$arrValuesAppSlot[] = $txtName;
			$arrFieldsAppSlot[] = 'doc_id';
			$arrValuesAppSlot[] = $doc_id;
			$arrFieldsAppSlot[] = 'doc_type';
			$arrValuesAppSlot[] = "1";
			$arrFieldsAppSlot[] = 'hosp_id';
			$arrValuesAppSlot[] = $hospital_id;
			$arrFieldsAppSlot[] = 'status';
			$arrValuesAppSlot[] = $status;
			$arrFieldsAppSlot[] = 'app_date';
			$arrValuesAppSlot[] = date('Y-m-d',strtotime($chkInDate));
			$arrFieldsAppSlot[] = 'app_time';
			$arrValuesAppSlot[] = $getTime[0]['Timing'];				
			$arrFieldsAppSlot[] = 'created_date';
			$arrValuesAppSlot[] = $curDate;
			$createappointment=mysqlInsert('appointment_token_system',$arrFieldsAppSlot,$arrValuesAppSlot);
			$appointTokenid = $createappointment; 
			
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
		
			
				// Add to Health App Notification Section
				$arrFieldsNotify=array();	
				$arrValuesNotify=array();
				
				$title ="Dear ".$getPatInfo[0]['patient_name'].", your appointment with Dr.".$get_pro[0]['ref_name']." is confirmed. ";
				$description = "Your Consultation link will be activated once the payment is confirmed. \nConsultation Link: ".$video_link_patient. " \n";
				
				$arrFieldsNotify[]='title';
				$arrValuesNotify[]=$title;
				$arrFieldsNotify[]='description';
				$arrValuesNotify[]=$description;
				$arrFieldsNotify[]='video_link';
				$arrValuesNotify[]=$video_link_patient;
				$arrFieldsNotify[]='patient_login_id';
				$arrValuesNotify[]=$user_id;			// Patient Login User ID
				$arrFieldsNotify[]='doc_id';
				$arrValuesNotify[]=$doc_id;
				$arrFieldsNotify[]='notify_type';
				$arrValuesNotify[]='2';					// 1-Normal msg, 2-Video Call Link
				$arrFieldsNotify[]='visibility';
				$arrValuesNotify[]='1';					// 1-unread, 0-read
				$arrFieldsNotify[]='created_date';
				$arrValuesNotify[]=$curDate;
				$app_notify= mysqlInsert('health_app_notifications',$arrFieldsNotify,$arrValuesNotify);
			
			//Send SMS to patient
			$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
			//$link = "https://medisensecrm.com/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
			
			//Get Shorten Urltransid
			//$getUrl= get_shorturl($longurl);	
			$patient_profile_link = "http://128.199.207.75/premium/Patient-Profile-Details?d=" . md5($doc_id)."&p=" . md5($getPatInfo[0]['patient_id'])."&t=".$transid;
		
				
			//$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
			//$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". If you have any reports, upload here:".$getUrl." Thanks";
			$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with Dr.".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". To view/update/upload your medical details or reports click here ".$patient_profile_link." \n\nThanks ";
			send_msg($txtMob,$msg);
			
			//Patient Info / Consultation EMAIL notification Sent to Patient
			if(!empty($get_pro[0]['ref_mail'])){
				$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
			
				$url_page = 'consultation_booking_info_to_patient.php';
				$url = rawurlencode($url_page);
				$url .= "?patname=".urlencode($getPatInfo[0]['patient_name']);
				$url .= "&patID=".urlencode($getPatInfo[0]['patient_id']);
				$url .= "&patAddress=".urlencode($PatAddress);
				$url .= "&patContact=".urlencode($getPatInfo[0]['patient_mob']);
				$url .= "&patEmail=".urlencode($getPatInfo[0]['patient_email']);
				$url .= "&patProfileLink=" . urlencode($patient_profile_link);
				$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
				$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
				$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
				$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
				$url .= "&ccmail=" . urlencode($ccmail);	
				$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
				send_mail($url);	
			}
			
			$getAppointmentTransID=mysqlSelect('*','appointment_transaction_detail',"patient_id='".$patientid."'");
			$appointmentTransactionID= $getAppointmentTransID[0]['appoint_trans_id'];
			$consultationCharge = $get_pro[0]['cons_charge'];
			$consultationChargeType = $get_pro[0]['cons_charge_currency_type'];
			$consultationDocName = $get_pro[0]['ref_name'];
			$consultationDocHospitalID = $hospital_id;
			$consultationDocVideoLink = $getPatInfo[0]['doc_video_link'];
			
			//Update to Accept/Reject table 
			$arrFields_CallStatus = array();
			$arrValues_CallStatus = array();
				
			$arrFields_CallStatus[] = 'doc_id';
			$arrValues_CallStatus[] = $doc_id;
			$arrFields_CallStatus[] = 'login_id';
			$arrValues_CallStatus[] = $user_id;
			$arrFields_CallStatus[] = 'patient_id';
			$arrValues_CallStatus[] = $patientid;
			$arrFields_CallStatus[] = 'appoint_trans_id';
			$arrValues_CallStatus[] = $appointmentTransactionID;
			$arrFields_CallStatus[] = 'consult_status';
			$arrValues_CallStatus[] = '1';						// 1- Request Sent, 2-Accpeted, 3-Rejected/Decline
			$arrFields_CallStatus[] = 'created_date';
			$arrValues_CallStatus[] = $curDate;
			$arrFields_CallStatus[] = 'unique_trans_id';
			$arrValues_CallStatus[] = $uniqueIDTeleconsultStatus;
			$insertCallStatus = mysqlInsert('appointment_accept_reject',$arrFields_CallStatus,$arrValues_CallStatus);
			
			// Add to Appointment Tracking
			$arrFieldsTrack = array();
			$arrValuesTrack = array();
					
			$arrFieldsTrack[] = 'doc_id';
			$arrValuesTrack[] = $doc_id;
			$arrFieldsTrack[] = 'patient_id';
			$arrValuesTrack[] = $patientid;
			$arrFieldsTrack[] = 'appoint_trans_id';
			$arrValuesTrack[] = $appointmentTransactionID;
			$arrFieldsTrack[] = 'message';
			$arrValuesTrack[] = 'Booked an appointment';
			$arrFieldsTrack[] = 'status';
			$arrValuesTrack[] = '1';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
			$arrFieldsTrack[] = 'created_date';
			$arrValuesTrack[] = $curDate;
			$insertTrack = mysqlInsert('appointment_tracking',$arrFieldsTrack,$arrValuesTrack);
			
			//Send Push Notification To Doctors Starts
			$FCMTokenID = $get_pro[0]['FCM_takenID'];
			$extraNotificationData = ["title" => 'Premium - '.$getPatInfo[0]['patient_name'].' has Booked Teleconsultation',"body" => $getPatInfo[0]['patient_name'].' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];
			$extraData = ["notification_type" => '1', "doc_id" => $doc_id, "patient_id" => $patientid, 'doctor_name' =>$get_pro[0]['ref_name'], 'patient_name' =>$getPatInfo[0]['patient_name'], 'patient_city' =>$getPatInfo[0]['patient_loc'], 'patient_state' =>$getPatInfo[0]['pat_state'], 'patient_country' =>$getPatInfo[0]['pat_country'], 'appointment_txnID' =>$appointmentTransactionID,  'uniqueTeleconsultID' =>$uniqueIDTeleconsultStatus, 'consultation_docVideoLink' =>$consultationDocVideoLink];
		
			if(!empty($FCMTokenID)) {
				$sendPushNotificationToDoctor= send_push_notifications($FCMTokenID, $extraNotificationData, $extraData);
				
				// Add to Appointment Tracking
				$arrFieldsTrack = array();
				$arrValuesTrack = array();
						
				$arrFieldsTrack[] = 'doc_id';
				$arrValuesTrack[] = $doc_id;
				$arrFieldsTrack[] = 'patient_id';
				$arrValuesTrack[] = $patientid;
				$arrFieldsTrack[] = 'appoint_trans_id';
				$arrValuesTrack[] = $appointmentTransactionID;
				$arrFieldsTrack[] = 'message';
				$arrValuesTrack[] = 'Appointment Request has been sent';
				$arrFieldsTrack[] = 'status';
				$arrValuesTrack[] = '2';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
				$arrFieldsTrack[] = 'created_date';
				$arrValuesTrack[] = $curDate;
				$insertTrack = mysqlInsert('appointment_tracking',$arrFieldsTrack,$arrValuesTrack);
				
			}
			//Send Push Notification To Doctors Ends
			
			
		/*	// Hide Wallet Session for now
			$getWalletBalance = mysqlSelect("*","health_app_wallet","login_id ='".$user_id."'","id DESC","","","1");
			$deduct_amount = $get_pro[0]['cons_charge'];
			
			if($getWalletBalance[0]['amount_currency_type'] == $consultation_currency_type) {
				if(empty($getWalletBalance)){
					$payment_status = 0;		// Payment Pending
					$payment_type = $consultation_currency_type;	// Payment Mode Type
				}
				else {
					if($getWalletBalance[0]['Total_Amount'] >= $get_pro[0]['cons_charge']) {
						$payment_status = 1;					// Payment Done
						$current_balance = $getWalletBalance[0]['Total_Amount'];
						$deduct_amount = $get_pro[0]['cons_charge'];
						$remaining_balance = $current_balance - $deduct_amount;
						$currency_type = $get_pro[0]['cons_charge_currency_type'];
						
						$arrFieldsWallet=array();	
						$arrValuesWallet=array();
						
						$arrFieldsWallet[]='tansaction_id';
						$arrValuesWallet[]=$transid;
						$arrFieldsWallet[]='login_id';
						$arrValuesWallet[]=$user_id;
						$arrFieldsWallet[]='amount_deducted';
						$arrValuesWallet[]=$deduct_amount;
						$arrFieldsWallet[]='Total_Amount';
						$arrValuesWallet[]=$remaining_balance;
						$arrFieldsWallet[]='amount_currency_type';
						$arrValuesWallet[]=$currency_type;
						$arrFieldsWallet[]='created_date';
						$arrValuesWallet[]=$curDate;
						$app_notify= mysqlInsert('health_app_wallet',$arrFieldsWallet,$arrValuesWallet);
						$pay_status = "VC Confirmed";
						
						// Update Payment Status in  Appointment Transaction Detail Table
						$arrFieldsAppointTrans = array();
						$arrValuesAppointTrans = array();
						$arrFieldsAppointTrans[]='pay_status';
						$arrValuesAppointTrans[]=$pay_status;
						$updateAppointTrans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppointTrans,$arrValuesAppointTrans,"id='".$appointTransid."'");
					
						// Update Payment Status in  Appointment appointment_token_system Table
						$arrFieldsAppointToken = array();
						$arrValuesAppointToken = array();
						$arrFieldsAppointToken[]='pay_status';
						$arrValuesAppointToken[]=$pay_status;
						$updateAppointTrans=mysqlUpdate('appointment_token_system',$arrFieldsAppointToken,$arrValuesAppointToken,"token_id='".$appointTokenid."'");
					
						// Update Payment Transaction Table
						$arrFieldsPayment = array();
						$arrValuesPayment = array();
						$arrFieldsPayment[]='patient_name';
						$arrValuesPayment[]=$txtName;
						$arrFieldsPayment[]='patient_id';
						$arrValuesPayment[]=$patientid;
						$arrFieldsPayment[]='trans_date';
						$arrValuesPayment[]=$curDate;
						$arrFieldsPayment[]='narration';
						$arrValuesPayment[]='Consultation Charge';
						$arrFieldsPayment[]='amount';
						$arrValuesPayment[]=$deduct_amount;
						$arrFieldsPayment[]='currency_type';
						$arrValuesPayment[]=$currency_type;
						$arrFieldsPayment[]='user_id';
						$arrValuesPayment[]=$doc_id;
						$arrFieldsPayment[]='user_type';
						$arrValuesPayment[]='1';
						$arrFieldsPayment[]='hosp_id';
						$arrValuesPayment[]=$hospital_id;
						$arrFieldsPayment[]='payment_status';
						$arrValuesPayment[]='PAID';
						$arrFieldsPayment[]='pay_method';
						$arrValuesPayment[]='Health Wallet';
						$arrFieldsPayment[]='appoint_trans_id';
						$arrValuesPayment[]=$transid;
						$arrFieldsPayment[]='login_uer_id';
						$arrValuesPayment[]=$user_id;
						$payment_add= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
					}
					else {
						$payment_status = 0;		// Payment Pending
						$payment_type = $consultation_currency_type;	// Payment Mode Type
					}
				}
			}
			else {
				$payment_status = 0;		// Payment Pending
				$payment_type = $consultation_currency_type;	// Payment Mode Type
			}
			
			// Hide Wallet Session for now
			*/
			
						
		/*$payment_status = 0;		// Payment Pending
		$payment_type = $consultation_currency_type;	// Payment Mode Type
		$patientid = '19170';
		$deduct_amount = '100';
		$doc_id = '2031'; */
		
		
		// Medical Background General Health Updates
		$get_MedicalBackground = mysqlSelect('*','user_family_general_health',"member_id ='".$member_id."'","","","","");
				
			if($get_MedicalBackground==true){
				
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
				$arrValues_MedBackUpdate[] = $member_allergies;
				$arrFields_MedBackUpdate[] = 'smoking';
				$arrValues_MedBackUpdate[] = $member_smoking;
				$arrFields_MedBackUpdate[] = 'alcohol';
				$arrValues_MedBackUpdate[] = $member_alcohol;
				$arrFields_MedBackUpdate[] = 'created_date';
				$arrValues_MedBackUpdate[] = $curDate;
			
				$updateMedBackground=mysqlUpdate('user_family_general_health',$arrFields_MedBackUpdate,$arrValues_MedBackUpdate,"member_id='".$member_id."'");		
			}
			else {
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
				$arrValues_MedBack[] = $member_allergies;
				$arrFields_MedBack[] = 'smoking';
				$arrValues_MedBack[] = $member_smoking;
				$arrFields_MedBack[] = 'alcohol';
				$arrValues_MedBack[] = $member_alcohol;
				$arrFields_MedBack[] = 'created_date';
				$arrValues_MedBack[] = $curDate;
				$insertMedBackground = mysqlInsert('user_family_general_health',$arrFields_MedBack,$arrValues_MedBack);
			}
		
		$result_medBackground = mysqlSelect("*","user_family_general_health","user_id ='".$user_id."'","id ASC","","","");
		$patient_payment_PayTM_link = "http://128.199.207.75/premium/patient_profile_payment.php?d=" . md5($doc_id)."&p=" . md5($getPatInfo[0]['patient_id'])."&t=".$transid;		
		
		
		$get_Members = mysqlSelect('*','user_family_member',"member_id ='".$member_id."'","","","","");
		if($get_Members==true){
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
		
		
		// Book Appointment for Default Doctors Starts *****************************************************************************
		$status1 = "Pending";
		
		$get_default_doctor = mysqlSelect('*','referal',"nova_default_doctor=1","ref_id DESC","","","0,1");
		$getDefaultDocID = $get_default_doctor[0]['ref_id'];
		$get_default_hospital = mysqlSelect('*','referal as a inner join doctor_hosp as b on b.doc_id = a.ref_id',"a.ref_id='".$getDefaultDocID."'","","","","");
		$getDefaultHospitalID = $get_default_hospital[0]['hosp_id'];
		
		$transid1 = time();
		
		$day_val=date('D', strtotime($chkInDate));
		$GetTiming= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$getDefaultDocID."' and b.hosp_id='".$getDefaultHospitalID."' and a.da_name='".$day_val."'","b.time_id desc","","","");
		foreach($GetTiming as $TimeList) {
			$chkDocTimeSlot = mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$getDefaultDocID."' and doc_type='1' and hosp_id = '".$getDefaultHospitalID."'","","","","");
				
			//echo $chkDocTimeSlot[0]['num_patient_hour'];
			$countPrevAppBook = mysqlSelect("COUNT(id) as Appoint_Count","appointment_transaction_detail","pref_doc='".$getDefaultDocID."' and hosp_id = '".$getDefaultHospitalID."' and Visiting_date = '".$chkInDate."' and Visiting_time = '".$TimeList["time_id"]."'","","","","");
				if($countPrevAppBook[0]['Appoint_Count']<$chkDocTimeSlot[0]['num_patient_hour'])
				{
					$chkInTime = $TimeList["time_id"];	
				}
		}
		
		$video_link_doctor_default = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_default_doctor[0]['ref_name']."&pat_name=".$txtName."&type=1&r=".$getDefaultDocID."_".$member_id."_".$transid1;
		$video_link_patient_default = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_default_doctor[0]['ref_name']."&pat_name=".$txtName."&type=2&r=".$getDefaultDocID."_".$member_id."_".$transid1;
		
		$agora_video_link_doctor_default = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$getDefaultDocID."_".$member_id."_".$transid1."&t=1";				// t=1 for doctor link, 2 -for patient link
		$agora_video_link_patient_default = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$getDefaultDocID."_".$member_id."_".$transid1."&t=2";				// t=1 for doctor link, 2 -for patient link
	
			$arrFields_patient1[] = 'patient_name';
			$arrValues_patient1[] = $txtName;

			$arrFields_patient1[] = 'patient_age';
			$arrValues_patient1[] = $txtAge; 

			$arrFields_patient1[] = 'patient_email';
			$arrValues_patient1[] = $txtMail;

			$arrFields_patient1[] = 'patient_gen';
			$arrValues_patient1[] = $txtGen;
				
			$arrFields_patient1[] = 'patient_mob';
			$arrValues_patient1[] = $txtMob;

			$arrFields_patient1[] = 'pat_country';
			$arrValues_patient1[] = $member_doc_origin;

			$arrFields_patient1[] = 'doc_id';
			$arrValues_patient1[] = $getDefaultDocID;

			$arrFields_patient1[] = 'system_date';
			$arrValues_patient1[] = date('Y-m-d');
				
			$arrFields_patient1[] = 'TImestamp';
			$arrValues_patient1[] = $curDate;
			
			$arrFields_patient1[] = 'transaction_id';
			$arrValues_patient1[] = $transid1;
			
			$arrFields_patient1[] = 'hyper_cond';
			$arrValues_patient1[] = $txtHypercondition;
			
			$arrFields_patient1[] = 'diabetes_cond';
			$arrValues_patient1[] = $txtDiabetic;
			
			$arrFields_patient1[] = 'height';
			$arrValues_patient1[] = $member_height;
			
			$arrFields_patient1[] = 'weight';
			$arrValues_patient1[] = $member_weight;
			
			$arrFields_patient1[] = 'pat_blood';
			$arrValues_patient1[] = $member_blood_group;
			
			$arrFields_patient1[] = 'smoking';
			$arrValues_patient1[] = $member_smoking;
			
			$arrFields_patient1[] = 'alcoholic';
			$arrValues_patient1[] = $member_alcohol;
			
			$arrFields_patient1[] = 'tele_communication';
			$arrValues_patient1[] = '1';
			
			$arrFields_patient1[] = 'member_id';
			$arrValues_patient1[] = $member_id;
			
			$arrFields_patient1[] = 'pat_bp';
			$arrValues_patient1[] = $member_bp;
			
			$arrFields_patient1[] = 'pat_thyroid';
			$arrValues_patient1[] = $member_thyroid;
			
			$arrFields_patient1[] = 'pat_cholestrole';
			$arrValues_patient1[] = $member_cholestrol;
			
			$arrFields_patient1[] = 'pat_epilepsy';
			$arrValues_patient1[] = $member_epilepsy;
			
			$arrFields_patient1[] = 'pat_asthama';
			$arrValues_patient1[] = $member_asthama;
			
			$arrFields_patient1[] = 'doc_video_link';
			$arrValues_patient1[] = $video_link_doctor_default;
			
			$arrFields_patient1[] = 'pat_video_link';
			$arrValues_patient1[] = $video_link_patient_default;
			
			$arrFields_patient1[] = 'allergies_any';
			$arrValues_patient1[] = $member_allergies;
			
			$arrFields_patient1[] = 'doc_agora_link';
			$arrValues_patient1[] = $agora_video_link_doctor_default;
			
			$arrFields_patient1[] = 'pat_agora_link';
			$arrValues_patient1[] = $agora_video_link_patient_default;
				
			$patientcreate1=mysqlInsert('doc_my_patient',$arrFields_patient1,$arrValues_patient1);
			$patientidDefault = $patientcreate1;  //Get Patient Id
			
			$getPatientInfo = mysqlSelect("*","doc_my_patient","patient_id='".$patientidDefault."'" ,"","","","");
			
			$arrFields11 = array();
			$arrValues11 = array();
					
			$arrFields11[] = 'appoint_trans_id';
			$arrValues11[] = $transid1;
			$arrFields11[] = 'patient_id';
			$arrValues11[] = $patientidDefault;
			$arrFields11[] = 'pref_doc';
			$arrValues11[] = $getDefaultDocID;
			$arrFields11[] = 'member_id';
			$arrValues11[] = $member_id;
			$arrFields11[] = 'hosp_id';
			$arrValues11[] = $getDefaultHospitalID;
			$arrFields11[] = 'Visiting_date';
			$arrValues11[] = date('Y-m-d',strtotime($chkInDate));
			$arrFields11[] = 'Visiting_time';
			$arrValues11[] = $chkInTime;
			$arrFields11[] = 'patient_name';
			$arrValues11[] = $txtName;
			$arrFields11[] = 'Mobile_no';
			$arrValues11[] = $txtMob;
			$arrFields11[] = 'Email_address';
			$arrValues11[] = $txtMail;
					
			$arrFields11[] = 'pay_status';
			$arrValues11[] = $status1;
			$arrFields11[] = 'visit_status';
			$arrValues11[] = "new_visit";
			$arrFields11[] = 'Time_stamp';
			$arrValues11[] = $curDate;
			$arrFields11[] = 'src_type';
			$arrValues11[] = '1';			// 1 - Medisense Health Src
			$arrFields11[] = 'appointment_type';
			$arrValues11[] = $txtAppointType;
			$arrFields11[] = 'tele_communication';
			$arrValues11[] = '1';
					
			$createappointment1=mysqlInsert('appointment_transaction_detail',$arrFields11,$arrValues11);
			$appointTransidDefault = $createappointment1; 
			
			$getTime1=mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
			
			$arrFieldsAppSlot1 = array();
			$arrValuesAppSlot1 = array();
			
			$arrFieldsAppSlot1[] = 'token_no';
			$arrValuesAppSlot1[] = "555"; //For Online Booking
			
			$arrFieldsAppSlot1[] = 'patient_id';
			$arrValuesAppSlot1[] = $patientidDefault;
			$arrFieldsAppSlot1[] = 'appoint_trans_id';
			$arrValuesAppSlot1[] = $transid1;
			$arrFieldsAppSlot1[] = 'patient_name';
			$arrValuesAppSlot1[] = $txtName;
			$arrFieldsAppSlot1[] = 'doc_id';
			$arrValuesAppSlot1[] = $getDefaultDocID;
			$arrFieldsAppSlot1[] = 'doc_type';
			$arrValuesAppSlot1[] = "1";
			$arrFieldsAppSlot1[] = 'hosp_id';
			$arrValuesAppSlot1[] = $getDefaultHospitalID;
			$arrFieldsAppSlot1[] = 'status';
			$arrValuesAppSlot1[] = $status1;
			$arrFieldsAppSlot1[] = 'app_date';
			$arrValuesAppSlot1[] = date('Y-m-d',strtotime($chkInDate));
			$arrFieldsAppSlot1[] = 'app_time';
			$arrValuesAppSlot1[] = $getTime1[0]['Timing'];				
			$arrFieldsAppSlot1[] = 'created_date';
			$arrValuesAppSlot1[] = $curDate;
			$createappointmentToken1=mysqlInsert('appointment_token_system',$arrFieldsAppSlot1,$arrValuesAppSlot1);
			$appointTokenidDefault = $createappointmentToken1; 
			
			//Patient Info EMAIL notification Sent to Doctor
			if(!empty($get_default_doctor[0]['ref_mail'])){
				$PatAddress1=$getPatientInfo[0]['patient_addrs'].",<br>".$getPatientInfo[0]['patient_loc'].", ".$getPatientInfo[0]['pat_state'].", ".$getPatientInfo[0]['pat_country'];
			
				$url_page = 'pat_appointment_info.php';
				$url = rawurlencode($url_page);
				$url .= "?patname=".urlencode($getPatientInfo[0]['patient_name']);
				$url .= "&patID=".urlencode($getPatientInfo[0]['patient_id']);
				$url .= "&patAddress=".urlencode($PatAddress1);
				$url .= "&patContact=".urlencode($getPatientInfo[0]['patient_mob']);
				$url .= "&patEmail=".urlencode($getPatientInfo[0]['patient_email']);
				$url .= "&patContactName=" . urlencode($getPatientInfo[0]['contact_person']);
				$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
				$url .= "&prefTime=" . urlencode($getTime1[0]['Timing']);
				$url .= "&docname=" . urlencode($get_default_doctor[0]['ref_name']);
				$url .= "&docmail=" . urlencode($get_default_doctor[0]['ref_mail']);
				$url .= "&ccmail=" . urlencode($ccmail);	
				$url .= "&replymail=" . urlencode($getPatientInfo[0]['patient_email']);						
				send_mail($url);	
			}
			
				// Add to Health App Notification Section
				$arrFieldsNotify1=array();	
				$arrValuesNotify1=array();
				
				$title ="Dear ".$getPatientInfo[0]['patient_name'].", your appointment with Dr.".$get_default_doctor[0]['ref_name']." is confirmed. ";
				$description = "Your Consultation link will be activated once the payment is confirmed. \nConsultation Link: ".$video_link_patient_default. " \n";
				
				$arrFieldsNotify1[]='title';
				$arrValuesNotify1[]=$title;
				$arrFieldsNotify1[]='description';
				$arrValuesNotify1[]=$description;
				$arrFieldsNotify1[]='video_link';
				$arrValuesNotify1[]=$video_link_patient_default;
				$arrFieldsNotify1[]='patient_login_id';
				$arrValuesNotify1[]=$user_id;			// Patient Login User ID
				$arrFieldsNotify1[]='doc_id';
				$arrValuesNotify1[]=$getDefaultDocID;
				$arrFieldsNotify1[]='notify_type';
				$arrValuesNotify1[]='2';					// 1-Normal msg, 2-Video Call Link
				$arrFieldsNotify1[]='visibility';
				$arrValuesNotify1[]='1';					// 1-unread, 0-read
				$arrFieldsNotify1[]='created_date';
				$arrValuesNotify1[]=$curDate;
				$app_notify= mysqlInsert('health_app_notifications',$arrFieldsNotify1,$arrValuesNotify1);
				
			//Send SMS to patient
			$longurl = "/premium/Patient-Attachments?d=" . md5($getPatientInfo[0]['patient_id']);
			
			$patient_profile_link1 = "http://128.199.207.75/premium/Patient-Profile-Details?d=" . md5($getDefaultDocID)."&p=" . md5($getPatientInfo[0]['patient_id'])."&t=".$transid1;
		
			$msg= "Hello ".$getPatientInfo[0]['patient_name']." Your appointment with Dr.".$get_default_doctor[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime1[0]['Timing'].". To view/update/upload your medical details or reports click here ".$patient_profile_link1." \n\nThanks ";
			send_msg($txtMob,$msg);
			
			//Patient Info / Consultation EMAIL notification Sent to Patient
			if(!empty($get_default_doctor[0]['ref_mail'])){
				$PatAddress1=$getPatientInfo[0]['patient_addrs'].",<br>".$getPatientInfo[0]['patient_loc'].", ".$getPatientInfo[0]['pat_state'].", ".$getPatientInfo[0]['pat_country'];
			
				$url_page = 'consultation_booking_info_to_patient.php';
				$url = rawurlencode($url_page);
				$url .= "?patname=".urlencode($getPatientInfo[0]['patient_name']);
				$url .= "&patID=".urlencode($getPatientInfo[0]['patient_id']);
				$url .= "&patAddress=".urlencode($PatAddress1);
				$url .= "&patContact=".urlencode($getPatientInfo[0]['patient_mob']);
				$url .= "&patEmail=".urlencode($getPatientInfo[0]['patient_email']);
				$url .= "&patProfileLink=" . urlencode($patient_profile_link1);
				$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
				$url .= "&prefTime=" . urlencode($getTime1[0]['Timing']);
				$url .= "&docname=" . urlencode($get_default_doctor[0]['ref_name']);
				$url .= "&docmail=" . urlencode($get_default_doctor[0]['ref_mail']);
				$url .= "&ccmail=" . urlencode($ccmail);	
				$url .= "&replymail=" . urlencode($getPatientInfo[0]['patient_email']);						
				send_mail($url);	
			}
			
			$getAppointmentTransIDDefault=mysqlSelect('*','appointment_transaction_detail',"patient_id='".$patientidDefault."'");
			$appointmentTransactionIDDefault= $getAppointmentTransIDDefault[0]['appoint_trans_id'];
			$consultationChargeDefault = $get_default_doctor[0]['cons_charge'];
			$consultationChargeTypeDefault = $get_default_doctor[0]['cons_charge_currency_type'];
			$consultationDocNameDefault = $get_default_doctor[0]['ref_name'];
			$consultationDocHospitalIDDefault = $getDefaultHospitalID;
			$consultationDocVideoLinkDefault = $getPatientInfo[0]['doc_video_link'];
			
			//Update to Accept/Reject table 
			$arrFields_CallStatus = array();
			$arrValues_CallStatus = array();
				
			$arrFields_CallStatus[] = 'doc_id';
			$arrValues_CallStatus[] = $getDefaultDocID;
			$arrFields_CallStatus[] = 'login_id';
			$arrValues_CallStatus[] = $user_id;
			$arrFields_CallStatus[] = 'patient_id';
			$arrValues_CallStatus[] = $patientidDefault;
			$arrFields_CallStatus[] = 'appoint_trans_id';
			$arrValues_CallStatus[] = $appointmentTransactionIDDefault;
			$arrFields_CallStatus[] = 'consult_status';
			$arrValues_CallStatus[] = '1';						// 1- Request Sent, 2-Accpeted, 3-Rejected/Decline
			$arrFields_CallStatus[] = 'created_date';
			$arrValues_CallStatus[] = $curDate;
			$arrFields_CallStatus[] = 'unique_trans_id';
			$arrValues_CallStatus[] = $uniqueIDTeleconsultStatus;
			$insertCallStatus = mysqlInsert('appointment_accept_reject',$arrFields_CallStatus,$arrValues_CallStatus);
			
			// Add to Appointment Tracking
			$arrFieldsTrackDefault = array();
			$arrValuesTrackDefault = array();
				
			$arrFieldsTrackDefault[] = 'doc_id';
			$arrValuesTrackDefault[] = $getDefaultDocID;
			$arrFieldsTrackDefault[] = 'patient_id';
			$arrValuesTrackDefault[] = $patientidDefault;
			$arrFieldsTrackDefault[] = 'appoint_trans_id';
			$arrValuesTrackDefault[] = $appointmentTransactionIDDefault;
			$arrFieldsTrackDefault[] = 'message';
			$arrValuesTrackDefault[] = 'Booked an appointment';
			$arrFieldsTrackDefault[] = 'status';
			$arrValuesTrackDefault[] = '1';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
			$arrFieldsTrackDefault[] = 'created_date';
			$arrValuesTrackDefault[] = $curDate;
			$insertTrack = mysqlInsert('appointment_tracking',$arrFieldsTrackDefault,$arrValuesTrackDefault);
		
		/*	//Send Push Notification To Default Doctors Starts
			$FCMTokenID1 = $get_default_doctor[0]['FCM_takenID'];
			$extraNotificationData1 = ["title" => 'Premium - '.$getPatientInfo[0]['patient_name'].' has Booked Teleconsultation',"body" => $getPatientInfo[0]['patient_name'].' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];
			$extraData1 = ["notification_type" => '1', "doc_id" => $getDefaultDocID, "patient_id" => $patientidDefault, 'doctor_name' =>$get_default_doctor[0]['ref_name'], 'patient_name' =>$getPatientInfo[0]['patient_name'], 'patient_city' =>$getPatientInfo[0]['patient_loc'], 'patient_state' =>$getPatientInfo[0]['pat_state'], 'patient_country' =>$getPatientInfo[0]['pat_country'], 'appointment_txnID' =>$appointmentTransactionIDDefault, 'consultation_docVideoLink' =>$consultationDocVideoLinkDefault];
		
			if(!empty($FCMTokenID1)) {
				$sendPushNotificationToDefaultDoctor= send_push_notifications($FCMTokenID1, $extraNotificationData1, $extraData1);
			} 
			//Send Push Notification To Default Doctors Ends */
		
		// Book Appointment for Default Doctors Ends  *****************************************************************************
		
		$result_family = mysqlSelect("*","user_family_member","user_id ='".$user_id."'","member_id ASC","","","");
		
		
		//$success_consults = array('result' => "success", 'status' => '1', 'payment_status' => $payment_status, 'payment_type' => $payment_type, 'doc_my_patientID' => $patientid, 'appointment_TransactionID' => $appointTransid, 'appointment_TokenID' => $appointTokenid,  'appointment_consultCharge' => $deduct_amount, 'doc_id' => $doc_id, "member_medical_background"=>$result_medBackground, "patient_payment_PayTM_link"=>$patient_payment_PayTM_link, "family_details"=>$result_family, 'message' => "Consultation Booked Successfully !!! \nYou will receive an Email/SMS with payment link to confirm the consultation.", 'err_msg' => '');
		
		$success_consults = array('result' => "success", 'status' => '1', 'doc_my_patientID' => $patientid, 'appointment_TransactionID' => $appointmentTransactionID, 'appointment_consultCharge' => $consultationCharge, 'appointment_consultChargeType' => $consultationChargeType, 'doc_id' => $doc_id, 'doc_name' => $consultationDocName, 'doc_hospital_id' => $consultationDocHospitalID, 'doc_my_patientDefaultID' => $patientidDefault, 'appointment_TransactionIDDefault' => $appointmentTransactionIDDefault, 'appointment_consultChargeDefault' => $consultationChargeDefault, 'appointment_consultChargeTypeDefault' => $consultationChargeTypeDefault, 'default_doc_id' => $getDefaultDocID, 'default_doc_name' => $consultationDocNameDefault, 'default_doc_hospital_id' => $consultationDocHospitalIDDefault, 'uniqueIDTeleconsultStatus' => $uniqueIDTeleconsultStatus, "member_medical_background"=>$result_medBackground, "family_details"=>$result_family, 'message' => "Consultation Booked Successfully !!! \nYou will receive an Email/SMS with payment link to confirm the consultation.", 'err_msg' => '');
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
