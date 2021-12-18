<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


include('send_push_notifications.php');


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//Teleconsult Status Check
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$user_id = $user_id; 
		$uniqueTransID = $_POST['uniqueTransID'];
		$defaultDocID = $_POST['doc_id'];
		$defaultDocMyPatientID = $_POST['docMyPatientID'];
		$patientid = $_POST['patientID'];					// Selected doctor patient ID
		$appointmentTransactionIDDefault = $_POST['appointTransID'];
		$appointmentTransactionIDSelected = $_POST['selectedDocAppointTransID'];
		
		$get_default_doctor = mysqlSelect('*','referal',"ref_id='".$defaultDocID."'","","","","");
		$getPatientInfo = mysqlSelect("*","doc_my_patient","patient_id='".$defaultDocMyPatientID."'" ,"","","","");
		
		$check_avilability = mysqlSelect("*","appointment_accept_reject","unique_trans_id='".$uniqueTransID."' AND patient_id='".$patientid."'","","","","");	
		if($check_avilability[0]['consult_status'] != '2') {
			
			//Send Push Notification To Default Doctors Starts
			$FCMTokenID1 = $get_default_doctor[0]['FCM_takenID'];
			$extraNotificationData1 = ["title" => 'Premium - '.$getPatientInfo[0]['patient_name'].' has Booked Teleconsultation',"body" => $getPatientInfo[0]['patient_name'].' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];
			$extraData1 = ["notification_type" => '2', "doc_id" => $defaultDocID, "patient_id" => $defaultDocMyPatientID, 'doctor_name' =>$get_default_doctor[0]['ref_name'], 'patient_name' =>$getPatientInfo[0]['patient_name'], 'patient_city' =>$getPatientInfo[0]['patient_loc'], 'patient_state' =>$getPatientInfo[0]['pat_state'], 'patient_country' =>$getPatientInfo[0]['pat_country'], 'appointment_txnID' =>$appointmentTransactionIDDefault, 'uniqueTeleconsultID' =>$uniqueTransID, 'consultation_docVideoLink' =>$getPatientInfo[0]['doc_video_link']];
		
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
					
		$success_status = array('result' => "success", 'message' => "Notify ststus updated !!!", 'err_msg' => '');
		echo json_encode($success_status);
		
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
