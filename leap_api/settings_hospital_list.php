<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Doctor Hospital List
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {  // Premium LOgin
	
		$doc_hospital = $objQuery->mysqlSelect('*','doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id',"a.doc_id='".$admin_id."'","a.doc_hosp_id ASC","","","");

		$success = array('status' => "true","doc_hospital"=> $doc_hospital);
		echo json_encode($success);
		
	}
	else {
		$success = array('status' => "false");
		echo json_encode($success);
	} 
		

	
}


?>