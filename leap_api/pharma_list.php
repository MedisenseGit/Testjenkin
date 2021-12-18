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
	
	
		$get_pharma = $objQuery->mysqlSelect('a.pharma_id as pharma_id, a.pharma_name as pharma_name, a.pharma_city as pharma_city, a.pharma_state as pharma_state, a.pharma_country as pharma_country, a.phrama_contact_person as phrama_contact_person, a.pharma_contact_num as pharma_contact_num, a.pharma_email as pharma_email, a.pharma_password as pharma_password','pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id',"b.doc_id='".$admin_id."'","a.pharma_name ASC","","","");
		
		$success = array('status' => "true","pharma_details"=>$get_pharma);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "false");
		echo json_encode($success);
	}
		

	
}


?>