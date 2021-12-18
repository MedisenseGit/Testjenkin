<?php
ob_start();
error_reporting(0);
session_start();


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

require_once("../classes/querymaker.class.php");

ob_start();

include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Contact Us
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$login_id = $user_id;
		$feedback_text = $_POST['comments'];
		
		$ownermail="medical@medisense.me";

		$getDocDet = mysqlSelect('*','login_user',"login_id='".$login_id."'");
			$account = "Medisense Health Care";
			$mailsubject = "Medisense Health Care Help Request !!!";
			$mailformat="Dear Team<br><br>We got one new Medisense Health Care Help Request. Please go through below details <br> </br>
						<b>Name :</b> ".$getDocDet[0]['sub_name']."<br><b>Email :</b>".$getDocDet[0]['sub_email']."<br><b>Mobile : </b>".$getDocDet[0]['sub_contact']."<br><b>FeedbackFrom :</b> ".$account."<br><b>Feedback Content :</b> ".$feedback_text."<br><br><b>Many Thanks</b>";
						
						//Registration Email notification to Primary members
																				
							$url_page1 = 'feedback_request.php';
							$url = rawurlencode($url_page1);
							$url .= "?mailSubject=" . urlencode($mailsubject);
							$url .= "&mailformat=".urlencode($mailformat);
							$url .= "&recipientMail=".urlencode($ownermail);
							$url .= "&fromName=".urlencode($getDocDet[0]['sub_name']);
							$url .= "&fromMail=".urlencode($getDocDet[0]['sub_email']);		
							send_mail($url);
							
			$result = array('result' => "success", 'message' => "Thank you for your query. \n We will get back to you in 24-48hrs.", 'err_msg' => '');		
			echo json_encode($result);
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