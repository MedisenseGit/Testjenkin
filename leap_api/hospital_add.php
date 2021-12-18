<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('send_mail_function.php');
include('send_text_message.php');

// Add New Hospital
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	$hosp_name=$_POST['hosp_name'];	
	$hosp_email=$_POST['hosp_email'];
	$hosp_contact=$_POST['hosp_mobile'];
	$hosp_address=$_POST['hosp_address'];
	$hosp_city=$_POST['hosp_city'];
	$hosp_state=$_POST['hosp_state'];
	$hosp_country=$_POST['hosp_country'];
	
	if($login_type == 1) {  // Premium LOgin
	
		$arrFields[] = 'hosp_name';
		$arrValues[] = $hosp_name;
		$arrFields[] = 'hosp_email';
		$arrValues[] = $hosp_email;
		$arrFields[] = 'hosp_contact';
		$arrValues[] = $hosp_contact;
		$arrFields[] = 'hosp_city';
		$arrValues[] = $hosp_city;
		$arrFields[] = 'hosp_state';
		$arrValues[] = $hosp_state;
		$arrFields[] = 'hosp_addrs';
		$arrValues[] = $hosp_address;
		$arrFields[] = 'hosp_country';
		$arrValues[] = $hosp_country;
		$arrFields[] = 'communication_status';
		$arrValues[] = "1";  
		
		$hospcreate=$objQuery->mysqlInsert('hosp_tab',$arrFields,$arrValues);
		$hosp_id = mysql_insert_id();	
		$arrFields_docHosp[] = 'doc_id';
		$arrValues_docHosp[] = $admin_id;
		$arrFields_docHosp[] = 'hosp_id';
		$arrValues_docHosp[] = $hosp_id;
			
		$dochospcreate=$objQuery->mysqlInsert('doctor_hosp',$arrFields_docHosp,$arrValues_docHosp);	
		
		$doc_hospital = $objQuery->mysqlSelect('*','doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id',"a.doc_id='".$admin_id."'","a.doc_hosp_id ASC");

		
		$success = array('result' => "success","doc_hospital"=> $doc_hospital);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "failure");
		echo json_encode($success);
	} 
		

	
}


?>