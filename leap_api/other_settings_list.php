<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Other Settings Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type']; // 1 -  Premium Login, 2 - Standard Login, 3 - MArketing Person

	if($login_type == 1) {		// 1 -  Premium Login
		
		$checkSetting= $objQuery->mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
		
		$result = array('status' => "true",'settings_details' => $checkSetting);
		echo json_encode($result);
	}
	else {	
			$result = array('status' => "false",'settings_details' => $checkSetting);
			echo json_encode($result);
	}
		
}


?>