<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['doctor_partnerid']) && isset($_POST['login_type'])) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$partner_id = $_POST['doctor_partnerid'];
	$login_type = $_POST['login_type'];
	
	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		$getHospital = $objQuery->mysqlSelect("b.hosp_id as hosp_id","referal as a inner join doctor_hosp as b on a.ref_id = b.doc_id","a.ref_id='".$partner_id."'","","","","");
		$hospital_id = $getHospital[0]['hosp_id'];
		
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","c.hosp_id='".$hospital_id."'","a.doc_type_val asc","","","");

		$success = array('status' => "true","doctor_list" => $result_doctor);     
		echo json_encode($success);
	}
	else if($login_type == 2) 	// Type-2 Referring Partners
	{
		/*	$getMappedDoctors = $objQuery->mysqlSelect("*","mapping_hosp_referrer","partner_id='".$partner_id."'","","","","");
		if($getMappedDoctors == true) {
			foreach($getMappedDoctors as $hospList){
				$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","(a.doc_spec!=555 and a.anonymous_status!=1) and (c.hosp_id='".$hospList['hosp_id']."')","a.doc_type_val asc","","","");

			}
			$success = array('status' => "true","doctor_list" => $result_doctor);
			echo json_encode($success);
		}  */

		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","");
		
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	}
	else if($login_type == 3)		// Type-3 Marketing Person
	{
			$getHospital = $objQuery->mysqlSelect("a.hosp_id","hosp_marketing_person as a inner join hosp_tab as b on a.hosp_id = b.hosp_id","a.person_id='".$partner_id."'","","","","");
			$hospital_id = $getHospital[0]['hosp_id'];
		
			$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","c.hosp_id='".$hospital_id."'","a.doc_type_val asc","","","");

			$success = array('status' => "true","doctor_list" => $result_doctor);     
			echo json_encode($success);
	}
	else {
		$success = array('status' => "false","doctor_list" => $result_doctor);     
		echo json_encode($success);
	}
	
}


?>