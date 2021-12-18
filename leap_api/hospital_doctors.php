<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['doctor_partnerid']) && isset($_POST['login_type']) && isset($_POST['hosp_id'])) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$partner_id = $_POST['doctor_partnerid'];		// user id
	$login_type = $_POST['login_type'];
	$hosp_id = $_POST['hosp_id'];
	
	
	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","c.hosp_id='".$hosp_id."'","a.doc_type_val asc","","","");

		$success = array('status' => "true","doctor_list" => $result_doctor);     
		echo json_encode($success);
	}
	else if($login_type == 2) 	// Type-2 Partners
	{
		
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1 and c.hosp_id='".$hosp_id."'","a.doc_type_val asc","","","");
		
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
		
	}
	else if($login_type == 3)	// Type-3 Marketing
	{
			
			$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","c.hosp_id='".$hosp_id."'","a.doc_type_val asc","","","");

			$success = array('status' => "true","doctor_list" => $result_doctor);     
			echo json_encode($success);
	}
	else {
		$success = array('status' => "false","doctor_list" => $result_doctor);     
		echo json_encode($success);
	}

}


?>