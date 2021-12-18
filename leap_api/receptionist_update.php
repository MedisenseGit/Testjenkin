<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$objQuery = new CLSQueryMaker();

// Receptionist Update
 if(API_KEY == $_POST['API_KEY'] ) {
	
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type']; // 1 -  Premium Login, 2 - Standard Login, 3 - MArketing Person
	$recept_username = $_POST['reception_username'];
	$recept_password = $_POST['reception_password'];

	if($login_type == 1) {
			$arrFields = array();
			$arrValues = array();
			$arrFields[] = 'secretary_username';
			$arrValues[] = $recept_username;
			$arrFields[] = 'secretary_password';
			$arrValues[] = md5($recept_password);
			
			$updateProvider=$objQuery->mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$admin_id."'");
				
			$result = array('status' => "true",'username' => $recept_username,'password' => md5($recept_password),'reception_result' => "Successfully updated receptionist login credentials. ");
			echo json_encode($result);
	}
	else {	
			$result = array('status' => "false",'reception_result' => "Failed to update receptionist login credentials. ");
			echo json_encode($result);
	}
		
}


?>