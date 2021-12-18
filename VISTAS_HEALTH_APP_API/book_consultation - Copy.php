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
$ccmail = "medical@medisense.me";

// Book Consultation
if(API_KEY == $_POST['API_KEY']) {

	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON

	
	$user_id = $_POST['user_id'];
	$member_id = $_POST['member_id'];

	$doc_id = $_POST['doc_id'];
	$consultation_type = $_POST['consultation_type'];
	$txtName = $_POST['member_name'];
	$txtGen = $_POST['member_gender'];			 // 1- Male, 2-Female, 3-Other, 0-Not Mentioned
	$txtAge = $_POST['member_age'];
	$member_height = $_POST['member_height'];
	$member_weight = $_POST['member_weight'];
	$member_blood_group = $_POST['member_blood_group'];
	$member_bp = $_POST['member_bp'];
	$member_thyroid = $_POST['member_thyroid'];
	$member_hypertension = $_POST['member_hypertension'];
	$member_asthama = $_POST['member_asthama'];
	$member_cholestrol = $_POST['member_cholestrol'];
	$member_epilepsy = $_POST['member_epilepsy'];
	$member_diabetic = $_POST['member_diabetic'];
	$member_allergies = $_POST['member_allergies'];
	$member_smoking = $_POST['member_smoking'];
	$member_alcohol = $_POST['member_alcohol'];
	$member_consult_lang_id = $_POST['member_consult_lang_id'];
	$member_consult_lang_name = $_POST['member_consult_lang_name'];
	$member_doc_origin = $_POST['member_doc_origin'];
	$hospital_id = $_POST['doc_hospital_id'];
	$consult_charge = $_POST['consultation_charge'];
	$consultation_currency_type = $_POST['consultation_currency_type'];
	$txtMob = $_POST['contact_num'];
	$txtMail = $_POST['user_email'];
	$txtAppointType = 2;  				// 0 - Walkin, 1- Appointment, 2-Teleconsultation
	
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
	
	$transid = time();
	$get_pro = mysqlSelect('*','referal',"ref_id='".$doc_id."'");
	
	$chkInDate = date('Y-m-d'); //Current Date
	$status = "Pending";
	
	$day_val=date('D', strtotime($chkInDate));
	$GetTiming= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","b.time_id desc","","","");
	foreach($GetTiming as $TimeList) {
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
		$appointTransid =$createappointment; 
		
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
		$appointTokenid =$createappointment; 
		
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
	$result_family = mysqlSelect("*","user_family_member","user_id ='".$user_id."'","member_id ASC","","","");
	
	$success_consults = array('result' => "success", 'status' => '1', 'payment_status' => $payment_status, 'payment_type' => $payment_type, 'doc_my_patientID' => $patientid, 'appointment_TransactionID' => $appointTransid, 'appointment_TokenID' => $appointTokenid,  'appointment_consultCharge' => $deduct_amount, 'doc_id' => $doc_id, "member_medical_background"=>$result_medBackground, "patient_payment_PayTM_link"=>$patient_payment_PayTM_link, "family_details"=>$result_family, 'message' => "Consultation Booked Successfully !!! \nYou will receive an Email/SMS with payment link to confirm the consultation.", 'err_msg' => '');
	echo json_encode($success_consults);

}
else {
    $success_consults = array('result' => "failed", 'err_msg' => "You have not permitted to access the account !!!");
	echo json_encode($success_consults);
}
?>
