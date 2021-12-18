<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


	//Sending Push Notifications
	define('API_ACCESS_KEY','AAAAsqzNz9U:APA91bFmTCDOsPKPb0En196yR2LyJduIWBtdCz_Om2F9FoZUA_OFaZs7uvhMCFZo5yDgiQ16Q2lgEQXcUn132xD04UFq5bPTfOd6dRjB6_wsXcVBXCvYxS6nkHdJNUp7zRBUCkDoFwe2');
	$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
	//$token='767403347925';
 
	
	$org_doc_id = 3727;
	$org_patient_id = 19136;
	$org_doctor_name = 'John Meyers';
	$org_patient_name = 'Salma';
	$org_patient_city = 'Manipal';
	$org_patient_country = 'India';
	$org_appointment_txnID = '1627097395';
	$org_doc_video_call_link = "https://maayayoga.com/msvV2.0/index.php?ref_name=John Meyers&pat_name=Salma&type=1&r=3727_8_1627097395";
	
	$org_result_doctor = mysqlSelect("FCM_takenID","referal","ref_id='".$org_doc_id."'","","","","");		
	$org_token = $org_result_doctor[0]['FCM_takenID'];

 	$uniqueID = 1627097395;
	
	$doc_id = 3743;
	$patient_id = 19137;
	$doctor_name = 'Mathew Pinto';
	$patient_name = 'Salma';
	$patient_city = 'Manipal';
	$patient_country = 'India';
	$appointment_txnID = '1627097396';
	$doc_video_call_link = "https://maayayoga.com/msvV2.0/index.php?ref_name=Mathew Pinto&pat_name=Salma&type=1&r=3743_8_1627097396";
	
	$result_doctor = mysqlSelect("FCM_takenID","referal","ref_id='".$doc_id."'","","","","");		
	$token = $result_doctor[0]['FCM_takenID'];
	
	
	
    $headers = [
			'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
	];
	
	
	/* $extraNotificationData = ["title" => 'Premium - '.$patient_name.' has Booked Teleconsultation',"body" => $patient_name.' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];

	$extraData = ["doc_id" => $doc_id, "patient_id" => $patient_id, 'doctor_name' =>$doctor_name, 'patient_name' =>$patient_name, 'patient_city' =>$patient_city, 'patient_country' =>$patient_country, 'appointment_txnID' =>$appointment_txnID, 'consultation_docVideoLink' =>$doc_video_call_link];

    $fields = [
            'to'        => $token, //single token
            'notification' => $extraNotificationData,
			'data' => $extraData
    ];
	
	//$payload = json_encode($fields);
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
	echo $result;
	echo "<br></br>"; */
	
	// Sending for orinal doctor
	$extraNotificationDataOrg = ["title" => 'Premium - '.$org_patient_name.' has Booked Teleconsultation',"body" => $org_patient_name.' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];

	$extraDataOrg = ["doc_id" => $org_doc_id, "patient_id" => $org_patient_id, 'doctor_name' =>$org_doctor_name, 'patient_name' =>$org_patient_name, 'patient_city' =>$org_patient_city, 'patient_country' =>$org_patient_country, 'appointment_txnID' =>$org_appointment_txnID, 'consultation_docVideoLink' =>$org_doc_video_call_link];

    $fieldsOrg = [
            'to'        => $org_token, //single token
            'notification' => $extraNotificationDataOrg,
			'data' => $extraDataOrg
    ];
	
	//$payload = json_encode($fields);
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fieldsOrg));
    $result = curl_exec($ch);
    curl_close($ch);
	echo $result;
	echo "<br></br>";
	
	//sleep for 60 seconds
	sleep(60);
	$check_avilability = mysqlSelect("*","appointment_accept_reject","unique_trans_id='".$uniqueID."' AND patient_id='".$org_patient_id."'","","","","");	
	if($check_avilability[0]['consult_status'] == '2') { // Accepted by selected doctor
		$arrFieldsStatus_Default = array();
		$arrValuesStatus_Default = array();
		$arrFieldsStatus_Default[]='consult_status';
		$arrValuesStatus_Default[]='3';						// Rejected / DEclined by selected doctor
		$updateAppointTrans=mysqlUpdate('appointment_accept_reject',$arrFieldsStatus_Default,$arrValuesStatus_Default,"unique_trans_id='".$uniqueID."' AND patient_id='".$patient_id."'");
	
	}
	else {
		$arrFieldsStatus = array();
		$arrValuesStatus = array();
		$arrFieldsStatus[]='consult_status';
		$arrValuesStatus[]='3';						// Rejected / DEclined by selected doctor
		$updateAppointTrans=mysqlUpdate('appointment_accept_reject',$arrFieldsStatus,$arrValuesStatus,"unique_trans_id='".$uniqueID."' AND patient_id='".$org_patient_id."'");
		

		$arrFieldsStatus_Default = array();
		$arrValuesStatus_Default = array();
		$arrFieldsStatus_Default[]='consult_status';
		$arrValuesStatus_Default[]='1';						// Accepted by default doctor
		$arrFieldsStatus_Default[]='accepted_by';
		$arrValuesStatus_Default[]='2';
		$updateAppointTrans=mysqlUpdate('appointment_accept_reject',$arrFieldsStatus_Default,$arrValuesStatus_Default,"unique_trans_id='".$uniqueID."' AND patient_id='".$patient_id."'");
					
	}
	
	
	$extraNotificationData = ["title" => 'Premium - '.$patient_name.' has Booked Teleconsultation',"body" => $patient_name.' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];

	$extraData = ["doc_id" => $doc_id, "patient_id" => $patient_id, 'doctor_name' =>$doctor_name, 'patient_name' =>$patient_name, 'patient_city' =>$patient_city, 'patient_country' =>$patient_country, 'appointment_txnID' =>$appointment_txnID, 'consultation_docVideoLink' =>$doc_video_call_link];

    $fields = [
            'to'        => $token, //single token
            'notification' => $extraNotificationData,
			'data' => $extraData
    ];
	
	//$payload = json_encode($fields);
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
	echo $result;
	echo "<br></br>";

?>
