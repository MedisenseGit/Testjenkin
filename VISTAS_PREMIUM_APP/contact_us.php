<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");



$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		$admin_id = $doctor_id;
		$feedback_text = $_POST['txtSupport'];
		$ownermail="medical@medisense.me,medisensedev@medisense.me";
		
		$getDocDet = mysqlSelect('*','referal',"ref_id='".$admin_id."'");
		$account = "PRACTICE PREMIUM";
		$mailsubject = "PRACTICE PREMIUM Help Request";
		$mailformat="Dear Team<br><br>We got one new PRACTICE PREMIUM Help Request. Please go through below details <br> </br>
					<b>Name :</b> ".$getDocDet[0]['ref_name']."<br><b>Email :</b>".$getDocDet[0]['ref_mail']."<br><b>Mobile : </b>".$getDocDet[0]['contact_num']."<br><b>FeedbackFrom :</b> ".$account."<br><b>Help Need for :</b> ".$feedback_text."<br><br><b>Many Thanks</b>";
					
					//Registration Email notification to Primary members
																			
						$url_page1 = 'feedback_request.php';
						$url = rawurlencode($url_page1);
						$url .= "?mailSubject=" . urlencode($mailsubject);
						$url .= "&mailformat=".urlencode($mailformat);
						$url .= "&recipientMail=".urlencode($ownermail);
						$url .= "&fromName=".urlencode($getDocDet[0]['ref_name']);
						$url .= "&fromMail=".urlencode($getDocDet[0]['ref_mail']);		
						send_mail($url);
						
			$result = array('status' => "true",'support_res' => "We will get back to you shortly. ");
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