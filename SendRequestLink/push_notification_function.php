<?php 

function push_notification($regid,$message,$title,$subtitle,$tickerText,$type,$largeimg,$postid,$patientid,$docid,$postkey){
//Initialise PUSH API Key	
$apikey="AAAAND37GLM:APA91bGkB_ULfstuJsYUvZf7NdBddBKAiUdjaDT_tRRSc5X1jvRFOH8dmjbgw59xzx5MwMmYZD1LLbMo_EisdvxFdopedCq3MQda1VGTD8_AvOJwBv3gXiNCHLZa5h5tbbTnmVIU-xCD";

//Initialise User Mobile GCM ID
$registrationIds = array( $regid );
$small_icon = "https://medisensecrm.com/Hospital/images/leap_push_icon.png";
			
// prep the bundle
$msg = array
(
	'postType' => $type,
	'postId' => $postid,
	'patientId' => $patientid,
	'docId' => $docid,
	'postKey' => $postkey,
	'message' 	=> $message,
	'title'		=> $title,
	'subtitle'	=> $subtitle,
	'tickerText'	=> $tickerText,
	'vibrate'	=> 1,
	'sound'		=> 1,
	'largeIcon'	=> $largeimg,
	'smallIcon'	=> 'https://medisensecrm.com/Hospital/images/leap_push_icon.png'
);
$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . $apikey,
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
//echo $result; 
 
}

//Push notification functionality for Practice standard user
function push_notification_refer($regid,$message,$title,$subtitle,$tickerText,$type,$largeimg,$smallimg,$postid,$patientid,$docid,$postkey){
//Initialise PUSH API Key	
$apikey="AIzaSyCZ5pPJAcw1mr65NEdnLjBcJCUa5hN8fxU";

//Initialise User Mobile GCM ID
$registrationIds = array( $regid );
			
// prep the bundle
$msg = array
(
	'postType' => $type,
	'postId' => $postid,
	'patientId' => $patientid,
	'docId' => $docid,
	'postKey' => $postkey,
	'message' 	=> $message,
	'title'		=> $title,
	'subtitle'	=> $subtitle,
	'tickerText'	=> $tickerText,
	'vibrate'	=> 1,
	'sound'		=> 1,
	'largeIcon'	=> $largeimg,
	'smallIcon'	=> $smallimg
);
$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . $apikey,
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
//echo $result; 
 
}

//Push notification functionality for aster partners
function push_notification_aster_refer($regid,$message,$title,$subtitle,$tickerText,$type,$largeimg,$smallimg,$postid,$patientid,$docid,$postkey){
//Initialise PUSH API Key	
$apikey="AIzaSyDaVMjmrt7azwDROlqSFaOr7SHOfY7E8_g";

//Initialise User Mobile GCM ID
$registrationIds = array( $regid );
			
// prep the bundle
$msg = array
(
	'postType' => $type,
	'postId' => $postid,
	'patientId' => $patientid,
	'docId' => $docid,
	'postKey' => $postkey,
	'message' 	=> $message,
	'title'		=> $title,
	'subtitle'	=> $subtitle,
	'tickerText'	=> $tickerText,
	'vibrate'	=> 1,
	'sound'		=> 1,
	'largeIcon'	=> $largeimg,
	'smallIcon'	=> $smallimg
);
$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . $apikey,
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
//echo $result; 
 
}

//Push notification functionality for Premium Doctors
function push_notification_prem_doc($regid,$message,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey){
//Initialise LEAP PUSH API Key	
//$apikey="AIzaSyCgzKZYRacGXtw533U3plVp1Y8v6WBEivQ";
$apikey="AIzaSyDlqdIHomUrQXWhX0Qqu79f4qeR75dyGdk";

//Initialise User Mobile GCM ID
$registrationIds = array( $regid );
			
// prep the bundle
$msg = array
(
	'postType' => $type,
	'postId' => $postid,
	'patientId' => $patientid,
	'docId' => $docid,
	'postKey' => $postkey,
	'message' 	=> $message,
	'title'		=> $title,
	'subtitle'	=> $subtitle,
	'tickerText'	=> $tickerText,
	'vibrate'	=> 1,
	'sound'		=> 1,
	'largeIcon'	=> $largeimg,
	'smallIcon'	=> $smalimg
);
$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . $apikey,
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
//echo $result; 
 
}

 ?>