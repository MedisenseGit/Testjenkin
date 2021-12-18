<?php

function send_push_notifications($FCMTokenID, $extraNotificationData, $extraData)
{
	//Sending Push Notifications
	define('API_ACCESS_KEY','AAAAsqzNz9U:APA91bFmTCDOsPKPb0En196yR2LyJduIWBtdCz_Om2F9FoZUA_OFaZs7uvhMCFZo5yDgiQ16Q2lgEQXcUn132xD04UFq5bPTfOd6dRjB6_wsXcVBXCvYxS6nkHdJNUp7zRBUCkDoFwe2');
	$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
	
	$headers = [
			'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
	];
	
	$fields = [
            'to'        => $FCMTokenID, //single token
            'notification' => $extraNotificationData,
			'data' => $extraData
    ];
	
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
	// echo $result;

}

/*
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


	//Sending Push Notifications
	define('API_ACCESS_KEY','AAAAsqzNz9U:APA91bFmTCDOsPKPb0En196yR2LyJduIWBtdCz_Om2F9FoZUA_OFaZs7uvhMCFZo5yDgiQ16Q2lgEQXcUn132xD04UFq5bPTfOd6dRjB6_wsXcVBXCvYxS6nkHdJNUp7zRBUCkDoFwe2');
	$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
	//$token='767403347925';
 
	$doc_id = 3727;
	$patient_id = 19999000;
	$doctor_name = 'John Meyers';
	$patient_name = 'Salma';
	$patient_city = 'Manipal';
	$patient_country = 'India';
	$appointment_txnID = '16242572333282';

 	$result_doctor = mysqlSelect("FCM_takenID","referal","ref_id='".$doc_id."'","","","","");		
	$token = $result_doctor[0]['FCM_takenID'];
	
    $headers = [
			'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
	];
	
	
	$extraNotificationData = ["title" => 'Premium - '.$patient_name.' has Booked Teleconsultation',"body" => $patient_name.' booked an instant teleconsultation with you.', 'icon' =>'http://128.199.207.75/assets/img/nova_logo.png'];

	$extraData = ["doc_id" => $doc_id, "patient_id" => $patient_id, 'doctor_name' =>$doctor_name, 'patient_name' =>$patient_name, 'patient_city' =>$patient_city, 'patient_country' =>$patient_country, 'appointment_txnID' =>$appointment_txnID];

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
*/
?>
