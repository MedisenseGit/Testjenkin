<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
include('../VISTAS_HEALTH_APP/send_push_notifications.php');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();

$ip     = $_SERVER['REMOTE_ADDR']; // find time zone
$ipInfo = file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;
date_default_timezone_set($timezone);
if(empty($timezone))
{
    $timezone ='Asia/Kolkata'; // this is for local
}

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key && isset($data ->patient_id) && isset($data ->appointment_trans_id))
{
		$user_id 		= $data ->login_id; 
		$uniqueTransID 	= $data ->appointment_id;
		$patient_id 	= $data ->patient_id;					// Selected doctor patient ID
		$appointmentTransactionIDSelected = $data ->appointment_trans_id; 
		$doc_id 		= $data ->doc_id;


	
		
		$get_default_doctor = mysqlSelect('ref_id, ref_name, ref_mail, cons_charge, cons_charge_currency_type,	contact_num, doc_photo, FCM_takenID, nova_default_doctor, last_active_timestamp, active_status','referal',"nova_default_doctor=1","ref_id DESC","","","0,1");
		
		$getDefaultDocID = $get_default_doctor[0]['ref_id'];
	
		$get_default_hospital = mysqlSelect('*','referal as a inner join doctor_hosp as b on b.doc_id = a.ref_id',"a.ref_id='".$getDefaultDocID."'","","","","");
		$getDefaultHospitalID = $get_default_hospital[0]['hosp_id'];
		
		$check_avilability = mysqlSelect("*","appointment_accept_reject","unique_trans_id='".$uniqueTransID."' AND patient_id='".$patient_id."'","","","","");

		$get_patient_info = mysqlSelect('a.patient_id as patient_id, a.patient_name as patient_name, b.address as address, b.city as city, b.state as state, b.country as country','patients_appointment as a inner join patients_transactions as b on b.patient_id = a.patient_id',"a.patient_id='".$patient_id."'");		
		
		

			//Last Active Time Update for default doctor
			$last_active_time   = $get_default_doctor[0]['last_active_timestamp'];
			$dateTime    = $Cur_Date;
			$tz_from     = $timezone;
			$newDateTime = new DateTime($dateTime, new DateTimeZone($tz_from));
			$newDateTime->setTimezone(new DateTimeZone("UTC"));
			$dateTimeUTC = $newDateTime->format("Y-m-d H:i:s");

		

			$minutes_to_add = 3;
			$time = new DateTime($last_active_time);
			$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
			$stamp = $time->format('Y-m-d H:i:s');
			
			$arry_Field     =   array();
			$arry_Value     =   array();
			if($dateTimeUTC <= $stamp)
			{
				$arry_Field[]   =   "active_status";
				$arry_Value[]   =   '1';
			}
			else
			{
				$arry_Field[]   =   "active_status";
				$arry_Value[]   =   '0';
			}
			$result_doctor_default = mysqlUpdate('referal',$arry_Field,$arry_Value,"ref_id='".$get_default_doctor[0]['ref_id']."'");
			
			
			
		
			//Last Active Time Update for selected doctor
			$get_selcted_doctor = mysqlSelect('ref_id, ref_name, ref_mail, cons_charge, cons_charge_currency_type,	contact_num, doc_photo, FCM_takenID, nova_default_doctor, last_active_timestamp, active_status','referal',"ref_id='".$doc_id."'","ref_id DESC","","","");
			$last_active_time_selected   = $get_selcted_doctor[0]['last_active_timestamp'];
			$dateTime1    = $Cur_Date;
			$tz_from1     = $timezone;
			$newDateTime1 = new DateTime($dateTime1, new DateTimeZone($tz_from1));
			$newDateTime1->setTimezone(new DateTimeZone("UTC"));
			$dateTimeUTC1 = $newDateTime1->format("Y-m-d H:i:s");
			$minutes_to_add1 = 3;
			$time1 = new DateTime($last_active_time_selected);
			$time1->add(new DateInterval('PT' . $minutes_to_add1 . 'M'));
			$stamp1 = $time1->format('Y-m-d H:i:s');
			$arry_Field1     =   array();
			$arry_Value1     =   array();
			if($dateTimeUTC1 <= $stamp1)
			{
				$arry_Field1[]   =   "active_status";
				$arry_Value1[]   =   '1';
			}
			else
			{
				$arry_Field1[]   =   "active_status";
				$arry_Value1[]   =   '0';
			}
			$result_doctor_selected = mysqlUpdate('referal',$arry_Field1,$arry_Value1,"ref_id=".$doc_id);


			

			
		//Check availabiliy of doctors
		if($check_avilability[0]['consult_status'] == '2') 	
		{ // Accepted Status	
		
			$result_accept = mysqlSelect("*","appointment_accept_reject","login_id ='".$user_id."' AND unique_trans_id ='".$uniqueTransID."' AND consult_status=2","id ASC","","","0,1");
			$accepted_doctor = $result_accept[0]['doc_id'];	
			$accepted_patient = $result_accept[0]['patient_id'];
			$accepted_appoint_transID = $result_accept[0]['appoint_trans_id'];	
	
			$result_price = mysqlSelect("*","referal","ref_id ='".$accepted_doctor."' ","","","","");
			$accepted_doctor_name = $result_price[0]['ref_name'];
			$accepted_consult_charge = $result_price[0]['cons_charge'];
			$accepted_consult_charge_type = $result_price[0]['cons_charge_currency_type'];
			
			$result_hosp = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id ='".$accepted_appoint_transID."' ","","","","");
			$accepted_hospital = $result_hosp[0]['hosp_id'];

			$success_status = array('status' => "true", 'appointment_status' => "1", 'accepted_doctor' => $accepted_doctor, 'accepted_patient' => $accepted_patient, 'accepted_appoint_transID' => $accepted_appoint_transID, 'accepted_consult_charge' => $accepted_consult_charge, 'accepted_consult_charge_type' => $accepted_consult_charge_type, 'accepted_hospital' => $accepted_hospital, 'accepted_doctor_name' => $accepted_doctor_name,'appointment_id' => $uniqueTransID);
			echo json_encode($success_status);  
			
		}
		else 
		{		// Requent Sent, Reject or Decline

		
			
		
			if($dateTimeUTC <= $stamp)			// Online status
			{

				$get_trans_details = mysqlSelect("*","patients_transactions","patient_id='".$patient_id."'","","","","");
				$transid = time();
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
				$arrValuesTrans[] =	$getDefaultDocID;
				
				$arrFieldsTrans[] = 'hosp_id';
				$arrValuesTrans[] = $getDefaultHospitalID;
				
				$arrFieldsTrans[] = 'contact_person';
				$arrValuesTrans[] = $get_trans_details[0]['contact_person'];
				
				$arrFieldsTrans[] = 'patient_age';
				$arrValuesTrans[] = $get_trans_details[0]['patient_age'];
				
				$arrFieldsTrans[] = 'address';
				$arrValuesTrans[] = $get_trans_details[0]['address'];
				
				$arrFieldsTrans[] = 'city';
				$arrValuesTrans[] = $get_trans_details[0]['city'];
				
				$arrFieldsTrans[] = 'state';
				$arrValuesTrans[] = $get_trans_details[0]['state'];
				
				$arrFieldsTrans[] = 'country';
				$arrValuesTrans[] = $get_trans_details[0]['country'];
				
				$arrFieldsTrans[] = 'height_cms';
				$arrValuesTrans[] = $get_trans_details[0]['height_cms'];
				
				$arrFieldsTrans[] = 'weight';
				$arrValuesTrans[] = $get_trans_details[0]['weight'];
				
				$arrFieldsTrans[] = 'hyper_cond';
				$arrValuesTrans[] = $get_trans_details[0]['hyper_cond'];
				
				$arrFieldsTrans[] = 'diabetes_cond';
				$arrValuesTrans[] = $get_trans_details[0]['diabetes_cond'];
				
				$arrFieldsTrans[] = 'smoking';
				$arrValuesTrans[] = $get_trans_details[0]['smoking'];
				
				$arrFieldsTrans[] = 'alcoholic';
				$arrValuesTrans[] = $get_trans_details[0]['alcoholic'];
				
				$arrFieldsTrans[] = 'blood_group';
				$arrValuesTrans[] = $get_trans_details[0]['blood_group'];
				
				$arrFieldsTrans[] = 'pat_bp';
				$arrValuesTrans[] = $get_trans_details[0]['pat_bp'];
				
				$arrFieldsTrans[] = 'pat_thyroid';
				$arrValuesTrans[] = $get_trans_details[0]['pat_thyroid'];
				
				$arrFieldsTrans[] = 'pat_cholestrole';
				$arrValuesTrans[] = $get_trans_details[0]['pat_cholestrole'];
				
				$arrFieldsTrans[] = 'pat_epilepsy';
				$arrValuesTrans[] = $get_trans_details[0]['pat_epilepsy'];
				
				$arrFieldsTrans[] = 'pat_asthama';
				$arrValuesTrans[] = $get_trans_details[0]['pat_asthama'];
				
				$arrFieldsTrans[] = 'allergies_any';
				$arrValuesTrans[] = $get_trans_details[0]['allergies_any'];
				
				$arrFieldsTrans[] = 'visiting_date';
				$arrValuesTrans[] = $get_trans_details[0]['visiting_date'];
				
				$arrFieldsTrans[] = 'visiting_time';
				$arrValuesTrans[] = $get_trans_details[0]['visiting_time'];						
				
				$arrFieldsTrans[] = 'time_slot';
				$arrValuesTrans[] = $get_trans_details[0]['time_slot'];	
				
				$arrFieldsTrans[] = 'amount';
				$arrValuesTrans[] = $get_default_doctor[0]['consult_charge'];
				
				$arrFieldsTrans[] = 'currency_type';
				$arrValuesTrans[] = $get_default_doctor[0]['cons_charge_currency_type'];
				
				$arrFieldsTrans[] = 'pay_status';
				$arrValuesTrans[] = $get_trans_details[0]['pay_status'];	
				
				$arrFieldsTrans[] = 'visit_status';
				$arrValuesTrans[] = $get_trans_details[0]['visit_status'];
				
				$arrFieldsTrans[] = 'created_date';
				$arrValuesTrans[] = $get_trans_details[0]['created_date'];
				
				$add_transaction = mysqlInsert('patients_transactions',$arrFieldsTrans,$arrValuesTrans);
				$patient_trans_id	=	$add_transaction;

				$check_patient = mysqlSelect('patient_id,patient_name,member_id,patient_mobile','patients_appointment',"patient_id='".$patient_id."'");


				// Update video call links
				$video_link_doctor = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$get_trans_details[0]['contact_person']."&type=1&r=".$getDefaultDocID."_".$check_patient[0]['member_id']."_".$transid;
				$video_link_patient = "https://maayayoga.com/msvV2.0/index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$get_trans_details[0]['contact_person']."&type=2&r=".$getDefaultDocID."_".$check_patient[0]['member_id']."_".$transid;
				
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
				$arrFields_token  	= array();
				$arrValues_token 	= array();
				$arrFields_token[] 	= 'patient_trans_id';
				$arrValues_token[] 	= $patient_trans_id;
				$arrFields_token[] 	= 'token_no';
				$arrValues_token[] 	= "555"; //For Online Booking
				$arrFields_token[] 	= 'created_date';
				$arrValues_token[]	= $curDate;
				$createToken = mysqlInsert('patients_token_system',$arrFields_token,$arrValues_token); // appointment_token_system to patients_token_system

				// Add to Health App Notification Section
				$arrFieldsNotify	=	array();	
				$arrValuesNotify	=	array();
				
				$title ="Dear ".$get_trans_details[0]['contact_person'].", you have booked an appointment with Dr.".$get_default_doctor[0]['ref_name'];
				$description = "Your selected doctor is currently not available so consultation scheduled with system selected Dr.".$get_default_doctor[0]['ref_name']. " Your Consultation link will be activated once the payment is confirmed. \nConsultation Link: ".$agora_video_link_patient. " \n";
				
				$arrFieldsNotify[]='title';
				$arrValuesNotify[]=$title;
				$arrFieldsNotify[]='description';
				$arrValuesNotify[]=$description;
				$arrFieldsNotify[]='video_link';
				$arrValuesNotify[]=$agora_video_link_patient;
				$arrFieldsNotify[]='patient_login_id';
				$arrValuesNotify[]=$user_id;			// Patient Login User ID
				$arrFieldsNotify[]='doc_id';
				$arrValuesNotify[]=$getDefaultDocID;
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
				$arrValues_CallStatus[] = $getDefaultDocID;
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
				$FCMTokenID = $get_default_doctor[0]['FCM_takenID'];

				$extraNotificationData = ["title" => 'Premium - '.$get_trans_details[0]['contact_person'].' has Booked Teleconsultation',"body" => $get_trans_details[0]['contact_person'].' booked an instant teleconsultation with you.', 'icon' => HOST_CRM_URL.'assets/img/nova_logo.png'];
				$extraData = ["notification_type" => '1', "doc_id" => $getDefaultDocID, "patient_id" => $patient_id, 'doctor_name' =>$get_default_doctor[0]['ref_name'], 'patient_name' =>$get_trans_details[0]['contact_person'], 'patient_city' =>$get_trans_details[0]['city'] , 'patient_state' =>$get_trans_details[0]['state'] , 'patient_country' =>$get_trans_details[0]['country'], 'appointment_ID' =>$patient_trans_id,'consultation_docVideoLink' =>$agora_video_link_doctor];


				if(!empty($FCMTokenID)) 
				{
					$sendPushNotificationToDoctor= send_push_notifications($FCMTokenID, $extraNotificationData, $extraData);
					
					// Add to Appointment Tracking
					$arrFieldsTrack = array();
					$arrValuesTrack = array();
							
					$arrFieldsTrack[] = 'doc_id';
					$arrValuesTrack[] = $getDefaultDocID;
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
				
				$success_status = array('status' => "true", 'appointment_status' => "2", 'accepted_doctor' => $get_default_doctor[0]['ref_name'], 'accepted_patient' => $get_trans_details[0]['contact_person'], 'accepted_appoint_transID' => $transid, 'accepted_consult_charge' => $get_default_doctor[0]['consult_charge'], 'accepted_consult_charge_type' => $get_default_doctor[0]['cons_charge_currency_type'], 'accepted_hospital' => $get_default_hospital[0]['hosp_name'], 'accepted_doctor_name' => $get_default_doctor[0]['ref_name'],'appointment_id' => $uniqueTransID);

				echo json_encode($success_status);


			}
			else		// Offline status
			{
				$arrFieldsTrans	  =	array();
				$arrValuesTrans	  =	array();

				$arrFieldsTrans[] = 'pay_status';
				$arrValuesTrans[] = 'Cancelled';

				$updateAppointTrans=mysqlUpdate('patients_transactions',$arrFieldsTrans,$arrValuesTrans,"patient_id='".$patient_id."'");
				
				$success_status = array('status' => "true", 'appointment_status' => "3", 'accepted_doctor' => $accepted_doctor, 'accepted_patient' => $accepted_patient, 'appointment_id' => $uniqueTransID);
				echo json_encode($success_status);
			}
			
			
		}

	
		
		/*
		$check_avilability = mysqlSelect("*","appointment_accept_reject","unique_trans_id='".$uniqueTransID."' AND patient_id='".$patient_id."'","","","","");	
		if($check_avilability[0]['consult_status'] != '2') 
		{
			
			//Send Push Notification To Default Doctors Starts
			$FCMTokenID1 = $get_default_doctor[0]['FCM_takenID'];
			$extraNotificationData1 = ["title" => 'Premium - '.$getPatientInfo[0]['patient_name'].' has Booked Teleconsultation',"body" => $getPatientInfo[0]['patient_name'].' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];
			$extraData1 = ["notification_type" => '2', "doc_id" => $get_default_doctor[0]['ref_id'], "patient_id" => $getPatientInfo[0]['patient_id'], 'doctor_name' =>$get_default_doctor[0]['ref_name'], 'patient_name' =>$getPatientInfo[0]['patient_name'], 'patient_city' =>$getPatientInfo[0]['patient_loc'], 'patient_state' =>$getPatientInfo[0]['pat_state'], 'patient_country' =>$getPatientInfo[0]['pat_country'], 'appointment_txnID' =>$appointmentTransactionIDDefault, 'uniqueTeleconsultID' =>$check_avilability[0]['unique_trans_id'], 'consultation_docVideoLink' =>$getPatientInfo[0]['doc_video_link']];
		
			if(!empty($FCMTokenID1)) {
				$sendPushNotificationToDefaultDoctor= send_push_notifications($FCMTokenID1, $extraNotificationData1, $extraData1);
				
				// Add to Appointment Tracking
				$arrFieldsTrack = array();
				$arrValuesTrack = array();
						
				$arrFieldsTrack[] = 'doc_id';
				$arrValuesTrack[] = $get_default_doctor[0]['ref_id'];
				$arrFieldsTrack[] = 'patient_id';
				$arrValuesTrack[] = $getPatientInfo[0]['patient_id'];
				$arrFieldsTrack[] = 'appoint_trans_id';
				$arrValuesTrack[] = $appointmentTransactionIDDefault;
				$arrFieldsTrack[] = 'message';
				$arrValuesTrack[] = 'Appointment Request has been sent';
				$arrFieldsTrack[] = 'status';
				$arrValuesTrack[] = '2';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
				$arrFieldsTrack[] = 'created_date';
				$arrValuesTrack[] = $Cur_Date;
				$insertTrack = mysqlInsert('appointment_tracking',$arrFieldsTrack,$arrValuesTrack);
				
			} 
			//Send Push Notification To Default Doctors Ends
			
			$arrFieldsStatus = array();
			$arrValuesStatus = array();
			$arrFieldsStatus[]='consult_status';
			$arrValuesStatus[]='3';						// Rejected / DEclined by selected doctor
			$updateAppointTrans=mysqlUpdate('appointment_accept_reject',$arrFieldsStatus,$arrValuesStatus,"unique_trans_id='".$uniqueTransID."' AND patient_id='".$patientid."'");
			

			$arrFieldsStatus_Default = array();
			$arrValuesStatus_Default = array();
			$arrFieldsStatus_Default[]='consult_status';
			$arrValuesStatus_Default[]='2';						// Accepted by default doctor
			$arrFieldsStatus_Default[]='accepted_by';
			$arrValuesStatus_Default[]='2';
			$updateAppointTrans=mysqlUpdate('appointment_accept_reject',$arrFieldsStatus_Default,$arrValuesStatus_Default,"unique_trans_id='".$uniqueTransID."' AND patient_id='".$defaultDocMyPatientID."'");
			
			$arrFieldsDocMyPatStatus = array();
			$arrValuesDocMyPatStatus = array();
			$arrFieldsDocMyPatStatus[]='teleconsult_status';
			$arrValuesDocMyPatStatus[]='1';						// Accepted by default doctor
			$updateAppointTrans=mysqlUpdate('doc_my_patient',$arrFieldsDocMyPatStatus,$arrValuesDocMyPatStatus,"transaction_id='".$appointmentTransactionIDDefault."' AND patient_id='".$defaultDocMyPatientID."'");
			
			$arrFieldsDocMyPatStatus1 = array();
			$arrValuesDocMyPatStatus1 = array();
			$arrFieldsDocMyPatStatus1[]='teleconsult_status';
			$arrValuesDocMyPatStatus1[]='2';						// Rejected / Declined / No response by selected doctor
			$updateAppointTrans=mysqlUpdate('doc_my_patient',$arrFieldsDocMyPatStatus1,$arrValuesDocMyPatStatus1,"transaction_id='".$appointmentTransactionIDSelected."' AND patient_id='".$patientid."'");
			
			$arrFieldsAppointStatus = array();
			$arrValuesAppointStatus = array();
			$arrFieldsAppointStatus[]='teleconsult_status';
			$arrValuesAppointStatus[]='1';						// Accepted by default doctor
			$updateAppointTrans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppointStatus,$arrValuesAppointStatus,"appoint_trans_id='".$appointmentTransactionIDDefault."' AND patient_id='".$defaultDocMyPatientID."'");
			
			$arrFieldsAppointStatus1 = array();
			$arrValuesAppointStatus1 = array();
			$arrFieldsAppointStatus1[]='teleconsult_status';
			$arrValuesAppointStatus1[]='2';						// Rejected / Declined / No response by selected doctor
			$updateAppointTrans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppointStatus1,$arrValuesAppointStatus1,"appoint_trans_id='".$appointmentTransactionIDSelected."' AND patient_id='".$patientid."'");
			
		}
		else {
			
			$arrFieldsDocMyPatStatus = array();
			$arrValuesDocMyPatStatus = array();
			$arrFieldsDocMyPatStatus[]='teleconsult_status';
			$arrValuesDocMyPatStatus[]='2';						// Rejected / Declined / No response by default doctor  
			$updateAppointTrans=mysqlUpdate('doc_my_patient',$arrFieldsDocMyPatStatus,$arrValuesDocMyPatStatus,"transaction_id='".$appointmentTransactionIDDefault."' AND patient_id='".$defaultDocMyPatientID."'");
			
			$arrFieldsDocMyPatStatus1 = array();
			$arrValuesDocMyPatStatus1 = array();
			$arrFieldsDocMyPatStatus1[]='teleconsult_status';
			$arrValuesDocMyPatStatus1[]='1';					// Accepted by selected doctor
			$updateAppointTrans=mysqlUpdate('doc_my_patient',$arrFieldsDocMyPatStatus1,$arrValuesDocMyPatStatus1,"transaction_id='".$appointmentTransactionIDSelected."' AND patient_id='".$patientid."'");
			
			$arrFieldsAppointStatus = array();
			$arrValuesAppointStatus = array();
			$arrFieldsAppointStatus[]='teleconsult_status';
			$arrValuesAppointStatus[]='2';						// Accepted by selected doctor
			$updateAppointTrans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppointStatus,$arrValuesAppointStatus,"appoint_trans_id='".$appointmentTransactionIDDefault."' AND patient_id='".$defaultDocMyPatientID."'");
			
			$arrFieldsAppointStatus1 = array();
			$arrValuesAppointStatus1 = array();
			$arrFieldsAppointStatus1[]='teleconsult_status';
			$arrValuesAppointStatus1[]='1';						// Rejected / Declined / No response by default doctor 
			$updateAppointTrans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppointStatus1,$arrValuesAppointStatus1,"appoint_trans_id='".$appointmentTransactionIDSelected."' AND patient_id='".$patientid."'");
			
		}


		$result_accept = mysqlSelect("*","appointment_accept_reject","login_id ='".$user_id."' AND unique_trans_id ='".$uniqueTransID."' AND consult_status=2","id ASC","","","0,1");
		$accepted_doctor = $result_accept[0]['doc_id'];	
		$accepted_patient = $result_accept[0]['patient_id'];
		$accepted_appoint_transID = $result_accept[0]['appoint_trans_id'];	

		$result_price = mysqlSelect("*","referal","ref_id ='".$accepted_doctor."' ","","","","");
		$accepted_doctor_name = $result_price[0]['ref_name'];
		$accepted_consult_charge = $result_price[0]['cons_charge'];
		$accepted_consult_charge_type = $result_price[0]['cons_charge_currency_type'];
		
		$result_hosp = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id ='".$accepted_appoint_transID."' ","","","","");
		$accepted_hospital = $result_hosp[0]['hosp_id'];
					 
		$success_status = array('status' => "true", 'accepted_doctor' => $accepted_doctor, 'accepted_patient' => $accepted_patient, 'accepted_appoint_transID' => $accepted_appoint_transID, 'accepted_consult_charge' => $accepted_consult_charge, 'accepted_consult_charge_type' => $accepted_consult_charge_type, 'accepted_hospital' => $accepted_hospital, 'accepted_doctor_name' => $accepted_doctor_name);
		//$success_status = array('status' => "true", 'user_id' => $user_id, 'uniqueTransID' => $uniqueTransID, 'defaultDocID' => $defaultDocID, 'defaultDocMyPatientID' => $defaultDocMyPatientID, 'appointmentTransactionIDDefault' => $appointmentTransactionIDDefault, 'appointmentTransactionIDSelected' => $appointmentTransactionIDSelected);
		echo json_encode($success_status);		   */
					
}
else 
{
			
		$response["status"] = "false";
		echo(json_encode($response));
}
?>


