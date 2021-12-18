<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Episode List
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {	
	
		$episode_details= array();
		$get_attachments= array();
	
		//$get_diagnostics = $objQuery->mysqlSelect('*','Diagnostic_center',"","diagnostic_id desc","","","");
		
		$get_diagnostics = $objQuery->mysqlSelect('a.diagnostic_id as diagnostic_id, a.diagnosis_name as diagnosis_name, a.diagnosis_city as diagnosis_city, a.diagnosis_state as diagnosis_state, a.diagnosis_country as diagnosis_country, a.diagnosis_contact_person as diagnosis_contact_person, a.diagnosis_contact_num as diagnosis_contact_num, a.diagnosis_email as diagnosis_email, a.diagnosis_password as diagnosis_password ','Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id',"b.doc_id='".$admin_id."'","a.diagnosis_name ASC","","","");
	
		
		$success = array('status' => "true","diagnostics_details"=>$get_diagnostics);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "false");
		echo json_encode($success);
	}
		

	
}


?>