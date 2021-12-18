<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";

ob_start();
$curDate=date('Y-m-d H:i:s');



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
		$topicId = $_POST['post_id'];
		$userId = $doctor_id;
		$topicType = $_POST['post_type'];
		$userComment = $_POST['comment_text'];
		
		$loginuserType="2";
		$arrFields = array();
		$arrValues = array();

		$arrFields[]= 'login_id';
		$arrValues[]= $userId;
		$arrFields[]= 'login_User_Type';
		$arrValues[]= $loginuserType;
		$arrFields[]= 'topic_id';
		$arrValues[]= $topicId;
		$arrFields[]= 'topic_type';
		$arrValues[]= $topicType;
		$arrFields[]= 'comments';
		$arrValues[]= $userComment;
		$arrFields[]= 'post_date';
		$arrValues[]= time();
		
		$addComment=mysqlInsert('home_post_comments',$arrFields,$arrValues);
		
		$result = array("result" => "success");
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
