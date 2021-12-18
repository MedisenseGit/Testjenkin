<?php
ob_start();
session_start();
error_reporting(0);  


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Practice Doctor Lists
 if(API_KEY == $_POST['API_KEY']) {
	 
	$partner_id = $_POST['partner_id'];
	$login_type = $_POST['login_type'];
	$doctor_type = $_POST['doctor_type'];		//doctor_type for 1 = UNIVERSAL Doctors, 2 = My Connections, 3 - Diagnostics, 4 - Online Parmacy, 5 - Specialization
	$spec_id = $_POST['spec_id'];
	
		$getMappedDoctors = $objQuery->mysqlSelect("*","mapping_hosp_referrer","partner_id='".$partner_id."'","","","","");
		if($getMappedDoctors == true) {
			foreach($getMappedDoctors as $hospList){
				$result_doctor = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp,a.ref_address as ref_address, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","(a.doc_spec!=555 and a.anonymous_status!=1) and (c.hosp_id='".$hospList['hosp_id']."')","a.doc_type_val asc","","","0,20");

			}
			$success = array('status' => "true","doctor_list" => $result_doctor);
			echo json_encode($success);
		}
		/*else{
			
			$result_doctor = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp,a.ref_address as ref_address, a.doc_photo as doc_photo,a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","0,20");

			$success = array('status' => "true","doctor_list" => $result_doctor);
			echo json_encode($success);
		}*/
		
	/* if($doctor_type == 1) {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","0,10");
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	}
	else if($doctor_type == 2) {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$partner_id."'","a.doc_type_val asc","","","0,10");
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	} 
	else if($doctor_type == 3) {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and a.doc_spec='89'","a.doc_type_val asc","","","0,10");
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	} 
	else if($doctor_type == 4) {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and a.doc_spec='90'","a.doc_type_val asc","","","0,10");
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	} 
	else if($doctor_type == 5) {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and a.doc_spec='".$spec_id."'","a.doc_type_val asc","","","0,10");
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	} 
	else if($doctor_type == 6) {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1 and c.hosp_id='".$spec_id."'","a.doc_type_val asc","","","0,10");
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	} 
	else{
		$success = array('status' => "false","doctor_list" => $result_doctor);
		echo json_encode($success);
	}
		
	*/
	

}


?>