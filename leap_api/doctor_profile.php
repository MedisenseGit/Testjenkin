<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['doctor_id']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$doctor_id = $_POST['doctor_id'];
	$login_type = $_POST['login_type'];
	
	if($login_type==1)   //login type 1 for doctors
	{
	
		$getSourceId= $objQuery->mysqlSelect("hosp_id","doctor_hosp","doc_id='".$doctor_id."'","","","","");
		$hospital_id=$getSourceId[0]['hosp_id'];
		
		$hosp_result = $objQuery->mysqlSelect('hosp_name,hosp_addrs,hosp_city,hosp_state,hosp_country','hosp_tab',"	hosp_id='".$hospital_id."'","","","","");
		
		$result = $objQuery->mysqlSelect('a.ref_id as ref_id, a.ref_name as ref_name, a.doc_qual as doc_qual, a.doc_city as doc_city, a.doc_state as doc_state, a.doc_country as doc_country, a.ref_address as ref_address, a.ref_exp as ref_exp,a.doc_photo as doc_photo, a.doc_interest as doc_interest, a.doc_research as doc_research, a.doc_contribute as doc_contribute, b.spec_name as spec_name','referal as a inner join specialization as b on a.doc_spec = b.spec_id',"ref_id='".$doctor_id."'","","","","");
		
		if($result == true)
		{
			$success = array('status' => "true","doc_profile" => $result,"hosp_address" => $hosp_result);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","doc_profile" => $result,"hosp_address" => $hosp_result);     
			echo json_encode($success);
		}
	}
	else if($login_type==2)   //login type 2 for partenr
	{
	
		$getSourceId= $objQuery->mysqlSelect("hosp_id","doctor_hosp","doc_id='".$doctor_id."'","","","","");
		$hospital_id=$getSourceId[0]['hosp_id'];
		
		$hosp_result = $objQuery->mysqlSelect('hosp_name,hosp_addrs,hosp_city,hosp_state,hosp_country','hosp_tab',"	hosp_id='".$hospital_id."'","","","","");
		
		$result = $objQuery->mysqlSelect('a.ref_id as ref_id, a.ref_name as ref_name, a.doc_qual as doc_qual, a.doc_city as doc_city, a.doc_state as doc_state, a.doc_country as doc_country, a.ref_address as ref_address, a.ref_exp as ref_exp,a.doc_photo as doc_photo, a.doc_interest as doc_interest, a.doc_research as doc_research, a.doc_contribute as doc_contribute, b.spec_name as spec_name','referal as a inner join specialization as b on a.doc_spec = b.spec_id',"ref_id='".$doctor_id."'","","","","");
		
		if($result == true)
		{
			$success = array('status' => "true","doc_profile" => $result,"hosp_address" => $hosp_result);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","doc_profile" => $result,"hosp_address" => $hosp_result);     
			echo json_encode($success);
		}
	}
	else if($login_type==3) //login type 3 for marketing
	{
		//$getSourceId= $objQuery->mysqlSelect("hosp_id","doctor_hosp","doc_id='".$doctor_id."'","","","","");
		//$hospital_id=$getSourceId[0]['hosp_id'];
		
		//$hosp_result = $objQuery->mysqlSelect('hosp_name,hosp_addrs,hosp_city,hosp_state,hosp_country','hosp_tab',"	hosp_id='".$hospital_id."'","","","","");
		
		$result = $objQuery->mysqlSelect('a.partner_id as ref_id, a.contact_person as ref_name, a.doc_qual as doc_qual,a.state as doc_state, a.country as doc_country,a.location as doc_city, a.Address as ref_address, a.ref_exp as ref_exp,a.doc_photo as doc_photo, a.doc_interest as doc_interest, a.doc_research as doc_research, a.doc_contribute as doc_contribute, b.spec_name as spec_name','our_partners as a inner join specialization as b on a.specialisation=b.spec_id',"a.partner_id='".$doctor_id."'","","","","");
		
		if($result == true)
		{
			$success = array('status' => "true","doc_profile" => $result,"hosp_address" => "");
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","doc_profile" => $result);     
			echo json_encode($success);
		}
	}
	
}


?>