<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();
ob_start();
$curDate=date('Y-m-d H:i:s');

 if(API_KEY == $_POST['API_KEY']) {	
	
	$topicId = $_POST['post_id'];
	$partnerId = $_POST['user_id'];
	$topicType = $_POST['post_type'];
	$userComment = $_POST['comment_text'];
	
	if(!empty($userComment)){
	$arrFields=array();
	$arrValues=array();
	
	$arrFields[]="login_id";
	$arrValues[]=$partnerId;
	$arrFields[]="login_User_Type";
	$arrValues[]="1";
	$arrFields[]="topic_id";
	$arrValues[]=$topicId;
	$arrFields[]="topic_type";
	$arrValues[]=$topicType;
	$arrFields[]="comments";
	$arrValues[]=$userComment;
	$arrFields[]="post_date";
	$arrValues[]=date('Y-m-d H:i:s');
	
	$createComment=$objQuery->mysqlInsert('home_post_comments',$arrFields,$arrValues);
	// header('location:https://medisensecrm.com'.$_POST['currenturl'].'&response=comment-success');
		$result = array("result" => "success");
		echo json_encode($result);
	}
	else {
		$result = array("result" => "failure");
		echo json_encode($result);
	}
 }
?>
