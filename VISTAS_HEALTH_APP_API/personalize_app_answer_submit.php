<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Update personalize app question & answers
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$check_submit = mysqlSelect("*","personalize_app_user_results","login_id='".$user_id."'","","","","");
		if(empty($check_submit)) {				
		
			while (list($key, $val) = each($_POST['question_id'])) {
				$question_id = $_POST['question_id'][$key];
				$answer_id = $_POST['answer_id'][$key];
				$answer_note = $_POST['answer_note'][$key];
				
				
				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[]='question_id';
				$arrValues[]=$question_id;

				$arrFileds[]='answer_id';
				$arrValues[]=$answer_id;
				
				$arrFileds[]='answer_comments';
				$arrValues[]=$answer_note;
				
				$arrFileds[]='login_id';
				$arrValues[]=$user_id;
				
				$insert_qc = mysqlInsert('personalize_app_user_results',$arrFileds,$arrValues);
				
			}
		
			$success = array('status' => "true", 'message' => 'Data updated', 'err_msg' => '' );
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false", 'message' => 'You are already submitted the response !!!', 'err_msg' => '' );
			echo json_encode($success);
		}
		
		
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
