<?php ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


//Update Firebase Token ID
if(API_KEY == $_POST['API_KEY'] || !empty($_POST['txt_firebase_tokenID'])) {

	$token_id = $_POST['txt_firebase_tokenID'];
	$user_id = $_POST['userid'];
	
	$arrFields[] = 'FCM_takenID';
	$arrValues[] = $token_id;
	$getUser=$objQuery->mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$user_id."'");

	
	$success = array('status' => "true","update_tokenID" => "success");     
	echo json_encode($success);
	

}	