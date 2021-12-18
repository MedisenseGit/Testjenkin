<?php 

function push_notification($regid,$message,$title,$subtitle,$tickerText,$type,$largeimg,$postid,$patientid,$docid,$postkey){
//Initialise PUSH API Key	
$apikey="AIzaSyDaVMjmrt7azwDROlqSFaOr7SHOfY7E8_g";

//$regid = "cwRG4YMAO-8:APA91bGJKTONlCvZIR6UcsI5jtwlu-zgppAvhFVAzH0bxyp26Ulug3IWNRsJu9WuutX01yxt-mOmyCJiCL-R8ZR3HTtAmNdH8Vhi0OTSz01JCd6NS1xRuS5DsrjPJCxwCzogbeZ6QkdM";

//Initialise User Mobile GCM ID
$registrationIds = array( $regid );
$small_icon = HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
			
// prep the bundle
$msg = array
(
	'postType' => $type,
	'postId' => 1,
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
	'smallIcon'	=> HOST_MAIN_URL.'Hospital/images/leap_push_icon.png'
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

function push_notification_refer($regid,$message,$title,$subtitle,$tickerText,$type,$largeimg,$smallimg,$postid,$patientid,$docid,$postkey){
//Initialise PUSH API Key	
$apikey="AAAAND37GLM:APA91bGkB_ULfstuJsYUvZf7NdBddBKAiUdjaDT_tRRSc5X1jvRFOH8dmjbgw59xzx5MwMmYZD1LLbMo_EisdvxFdopedCq3MQda1VGTD8_AvOJwBv3gXiNCHLZa5h5tbbTnmVIU-xCD";

//Initialise User Mobile GCM ID
$registrationIds = array( $regid );
$small_icon = HOST_MAIN_URL."Hospital/images/leap.png";
			
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
function push_notification_Aster_refer($regid,$message,$title,$subtitle,$tickerText,$type,$largeimg,$smallimg,$postid,$patientid,$docid,$postkey){
//Initialise PUSH API Key	
$apikey="AIzaSyDaVMjmrt7azwDROlqSFaOr7SHOfY7E8_g";

//Initialise User Mobile GCM ID
$registrationIds = array( $regid );
$small_icon = HOST_MAIN_URL."Hospital/images/leap.png";
			
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

 ?>