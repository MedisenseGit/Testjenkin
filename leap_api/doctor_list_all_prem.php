<?php
ob_start();
session_start();
error_reporting(0);  


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Practice Doctor Lists
 if(API_KEY == $_POST['API_KEY']) {
	 
	$doctor_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	$doctor_type = $_POST['doctor_type'];		// 1 = UNIVERSAL Doctors, 2 = My Connections, 3 - Diagnostics, 4 - Online Parmacy, 5 - Specialization
	$spec_id = $_POST['spec_id'];
	
	//echo $partner_id;
	//echo $doctor_type;
	if($login_type==1)   //login type 1 for doctors
	{
	$getHospital = $objQuery->mysqlSelect("b.hosp_id as hosp_id,d.institution_type as Institute_type","referal as a inner join doctor_hosp as b on a.ref_id = b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id","a.ref_id='".$doctor_id."'","","","","");
	$hospital_id = $getHospital[0]['hosp_id'];
		
	 if($doctor_type == 1) {
		if($getHospital[0]['Institute_type']=="2"){  //If Institute_type 1 is for Institutional doctor then display only perticular institutional doctor, And 2 is for Individual doctor then display all doctors
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name, a.ref_address as ref_address","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","a.anonymous_status!=1","a.doc_type_val asc","","","");
		} else {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,  a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name, a.ref_address as ref_address","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","a.anonymous_status!=1 and c.hosp_id='".$hospital_id."'","a.doc_type_val asc","","","");
		}	
		$success = array('status' => "true","doctor_list" => $result_doctor);     
		echo json_encode($success);
	}
	
	else if($doctor_type == 5) {
		if($getHospital[0]['Institute_type']=="2"){  //If Institute_type 1 is for Institutional doctor then display only perticular institutional doctor, And 2 is for Individual doctor then display all doctors
		
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name, a.ref_address as ref_address","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and a.doc_spec='".$spec_id."'","a.doc_type_val asc","","","");
		} else {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name, a.ref_address as ref_address","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and a.doc_spec='".$spec_id."' and c.hosp_id='".$hospital_id."'","a.doc_type_val asc","","","");
		}	
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	} 
	else if($doctor_type == 6) {
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as doc_name,a.doc_age as doc_age, a.doc_type_val as doc_type_val,a.ref_exp as doc_exp,a.ref_address as ref_address,a.doc_state as doc_state, a.doc_photo as doc_photo,a.doc_qual as doc_qual, b.spec_id as spec_id, b.spec_name as spec_name, a.ref_address as ref_address","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1 and c.hosp_id='".$spec_id."'","a.doc_type_val asc","","","");
		$success = array('status' => "true","doctor_list" => $result_doctor);
		echo json_encode($success);
	} 
	else{
		$success = array('status' => "false","doctor_list" => $result_doctor);
		echo json_encode($success);
	}
 }	
	else if($login_type==3)  //login type 3 for marketing person
	{
				
			$result_doctor = $objQuery->mysqlSelect("a.partner_id as ref_id,a.contact_person as doc_name, a.doc_age as doc_age, a.ref_exp as doc_exp, a.doc_photo as doc_photo,a.doc_qual as doc_qual,b.spec_id as spec_id, b.spec_name as spec_name, a.location as ref_address","our_partners as a inner join specialization as b on a.specialisation=b.spec_id inner join mapping_hosp_referrer as c on c.partner_id=a.partner_id","c.market_person_id='".$doctor_id."'","","","","");
			
			if($result_doctor==true){
			$success = array('status' => "true","doctor_list" => $result_doctor);     
			echo json_encode($success);
			}
			else{
			$success = array('status' => "true","doctor_list" => $result_doctor);     
			echo json_encode($success);
			}
		
	}

}


?>