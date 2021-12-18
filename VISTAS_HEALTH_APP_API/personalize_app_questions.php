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

//$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

// Get all personalize app question & answers
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
			
			$getQuestions = mysqlSelect("*","personalize_app_questions","","id ASC","","","");
			
			$questions_details= array();
			foreach($getQuestions as $getQuestionsLists) {
				$getQuestion['id']=$getQuestionsLists['id'];
				$getQuestion['questions']=$getQuestionsLists['questions'];
				$getQuestion['question_type']=$getQuestionsLists['question_type'];		// 0-Single Choice, 1- Multi choice, 2- statement
				
				$answers = mysqlSelect('*','personalize_app_answers',"question_id='".$getQuestionsLists['id']."'","","","","");
				$getQuestion['answers']= $answers;
				
				array_push($questions_details, $getQuestion);
			}
				
		
			$success = array('status' => "true", "question_answer_details" => $questions_details, 'err_msg' => '' );
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
