<?php
//var_dump $user_id;
//exit();
ob_start();
error_reporting(0);
session_start();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');
$curDateSlot=date('H:i');


// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

// get posted data
$data = json_decode(file_get_contents("php://input"));

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
include('../premium/send_mail_function.php');
include("../premium/send_text_message.php");
include('../premium/short_url.php');
include('../VISTAS_HEALTH_APP/send_push_notifications.php');
$ccmail = "medical@medisense.me";

 /*$success_consults = array('result' => $data ->api_key);
	echo json_encode($success_consults);
	exit();*/

// Book Consultation	
if(HEALTH_API_KEY == $data ->api_key)
{

	$user_id = $data ->user_id;
	$member_id = $data ->member_id;
	
	$txtMob = $data ->mobileNumber;
	$txtMail = $data ->emailID;

	$doc_id = $data ->doc_id;
	$consultation_type = addslashes($data ->consultation_type);
	$txtName = $data ->txtName;
	$txtGen = $data ->txtGen; // 1- Male, 2-Female, 3-Other, 0-Not Mentioned
	$txtAge = $data ->txtAge;
	$member_height = $data ->member_height;
	$member_weight = $data ->member_weight;
	$member_blood_group = $data ->member_blood_group;
	


	$member_bp = $data ->member_bp;
	$member_thyroid = $data ->member_thyroid;
	$member_hypertension = $data ->member_hypertension;
	$member_asthama = $data ->member_asthama;
	$member_cholestrol = $data ->member_cholestrol;
	$member_epilepsy = $data ->member_epilepsy;
	$member_diabetic = $data ->member_diabetic;
	$member_allergies = $data ->member_allergies;
	$member_allergies_text = $data ->member_allergies_text;

	$member_smoking = $data ->member_smoking;
	$member_alcohol = $data ->member_alcohol;
	
	$get_user_country = mysqlSelect("*","login_user","login_id='".$user_id."'" ,"","","","");
	$origin_country = $get_user_country[0]['sub_country'];
	
	$txtAddress 	= $get_user_country[0]['sub_address'];
	$txtLoc 		= $get_user_country[0]['sub_city']; 
	$txtCountry 	= $get_user_country[0]['sub_country']; 
	$txtState 		= $get_user_country[0]['sub_state']; 
	
	$get_doctor_hospital = mysqlSelect("*","doctor_hosp","doc_id='".$doc_id."'" ,"","","","");
	$hospital_id = $get_doctor_hospital[0]['hosp_id'];
	
	// $hospital_id = $data ->hospital_id; 
	$consultation_currency_type = $data ->consultation_currency_type;
	$uniqueIDTeleconsultStatus = time();
	$txtAppointType = 3;  				// 0 - Walkin, 1- Appointment, 2-Teleconsultation, 3 - Instant Video Consult
	
	if($member_hypertension == 2) {		  // 1-NO, 2-YES, 0-NOT MENTIONED
		$txtHypercondition = 1;
	}
	else {
		$txtHypercondition = 0;
	}
	
	if($member_diabetic == 2) {			  // 1-NO, 2-YES, 0-NOT MENTIONED
		$txtDiabetic = 1;
	}
	else {
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
	if(empty($check_patient)){
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
			$patient_id	=	$insert_patient;
	}
	else
	{
		
			$patient_id	=	$check_patient[0]['patient_id'];
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
			
			$add_transaction = mysqlInsert('patients_transactions',$arrFieldsTrans,$arrValuesTrans);
			$patient_trans_id	=	$add_transaction;
			
			// Update video call links
			$video_link_doctor = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$txtName."&type=1&r=".$doc_id."_".$member_id."_".$transid;
			$video_link_patient = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$txtName."&type=2&r=".$doc_id."_".$member_id."_".$transid;
			
			$agora_video_link_doctor = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$patient_trans_id."&t=1";  // t=1 for doctor link, 2 -for patient link
			$agora_video_link_patient = "https://medisensemd.com/AgoraWebSDK/Demo/basicMute/index.php?ch=".$patient_trans_id."&t=2"; // t=1 for doctor link, 2 -for patient link
	
			
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
			$arrValues_token = array();
			$arrFields_token[] = 'patient_trans_id';
			$arrValues_token[] = $patient_trans_id;
			$arrFields_token[] = 'token_no';
			$arrValues_token[] = "555"; //For Online Booking
			$arrFields_token[] = 'created_date';
			$arrValues_token[] = $curDate;
			$createToken = mysqlInsert('patients_token_system',$arrFields_token,$arrValues_token); // appointment_token_system to patients_token_system
			
			// Add to Health App Notification Section
			$arrFieldsNotify=array();	
			$arrValuesNotify=array();
			
			$title ="Dear ".$txtName.", you have booked an appointment with Dr.".$get_pro[0]['ref_name'];
			$description = "Your Consultation link will be activated once the payment is confirmed. \nConsultation Link: ".$agora_video_link_patient. " \n";
			
			$arrFieldsNotify[]='title';
			$arrValuesNotify[]=$title;
			$arrFieldsNotify[]='description';
			$arrValuesNotify[]=$description;
			$arrFieldsNotify[]='video_link';
			$arrValuesNotify[]=$agora_video_link_patient;
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
			$insertCallStatus = mysqlInsert('appointment_accept_reject',$arrFields_CallStatus,$arrValues_CallStatus);
	
	
			//Send Push Notification To Doctors Starts
			$FCMTokenID = $get_pro[0]['FCM_takenID'];
			$extraNotificationData = ["title" => 'Premium - '.$txtName.' has Booked Teleconsultation',"body" => $txtName.' booked an instant teleconsultation with you.', 'icon' => HOST_CRM_URL.'assets/img/nova_logo.png'];
			$extraData = ["notification_type" => '1', "doc_id" => $doc_id, "patient_id" => $patient_id, 'doctor_name' =>$get_pro[0]['ref_name'], 'patient_name' =>$txtName, 'patient_city' =>$txtLoc , 'patient_state' =>$txtState , 'patient_country' =>$txtCountry , 'appointment_ID' =>$patient_trans_id,'consultation_docVideoLink' =>$agora_video_link_doctor];
		
			if(!empty($FCMTokenID)) {
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
				$arrValues_MedBackUpdate[] = $member_allergies_text;
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

	

	$success_consults = array('result' => "success", 'status' => '1',  'patient_id' => $patient_id, 'appointment_TransactionID' => $transid, 'appointment_consultCharge' => $get_pro[0]['consult_charge'], 'appointment_consultChargeType' => $get_pro[0]['cons_charge_currency_type'], 'doc_id' => $doc_id, 'doc_name' => $get_pro[0]['ref_name'], 'doc_hospital_id' => $hospital_id, 'appointment_id' => $patient_trans_id, "member_medical_background"=>$result_medBackground, "family_details"=>$result_family, 'message' => "Consultation Booked Successfully !!! \nYou will receive an Email/SMS with payment link to confirm the consultation.", 'err_msg' => '');
	echo json_encode($success_consults);
}
else {
    $success_consults = array('result' => "failed", 'err_msg' => "You have not permitted to access the account !!!");
	echo json_encode($success_consults);
}
?>
