<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Lens Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {						// Premium LoginType
		
		
		$lens_list = $objQuery->mysqlSelect("*","examination_ophthal_lens","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","lens_name ASC","","","");			
								
		$success = array('status' => "true", "lens_details" => $lens_list);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>