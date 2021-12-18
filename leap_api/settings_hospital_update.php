<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('send_mail_function.php');
include('send_text_message.php');

// Update Hospital
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	$hosp_id=$_POST['hosp_id'];	
	$hosp_name=$_POST['hosp_name'];	
	$hosp_address=$_POST['hosp_address'];
	$hosp_city=$_POST['hosp_city'];
	$hosp_state=$_POST['hosp_state'];
	$hosp_country=$_POST['hosp_country'];
	
	if($login_type == 1) {  // Premium LOgin
	
		$arrFields[] = 'hosp_name';
		$arrValues[] = $hosp_name;
		$arrFields[] = 'hosp_city';
		$arrValues[] = $hosp_city;
		$arrFields[] = 'hosp_state';
		$arrValues[] = $hosp_state;
		$arrFields[] = 'hosp_addrs';
		$arrValues[] = $hosp_address;
		$arrFields[] = 'hosp_country';
		$arrValues[] = $hosp_country;
		
		$update_hospital=$objQuery->mysqlUpdate('hosp_tab',$arrFields,$arrValues,"hosp_id='".$hosp_id."'");

		$doc_hospital = $objQuery->mysqlSelect('*','doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id',"a.doc_id='".$admin_id."'","a.doc_hosp_id ASC","","","");

		$success = array('result' => "success","doc_hospital"=> $doc_hospital);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "failure");
		echo json_encode($success);
	} 
		

	
}


?>