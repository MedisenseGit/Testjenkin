<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Viterous Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {						// Premium LoginType
		
		
		$viterous_list = $objQuery->mysqlSelect("*","examination_ophthal_viterous","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","viterous_name ASC","","","");			
								
		$success = array('status' => "true", "viterous_details" => $viterous_list);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>