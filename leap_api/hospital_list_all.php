<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//hospital details
 if((API_KEY == $_POST['API_KEY'] ) && isset($_POST['userid']) && isset($_POST['login_type']) ){
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 
	$getInstituteType = $objQuery->mysqlSelect("d.institution_type as Institute_type,d.company_id as company_id","referal as a inner join doctor_hosp as b on a.ref_id = b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id","a.ref_id='".$_POST['userid']."'","","","","");
	
	$login_type = $_POST['login_type'];
	$user_id = $_POST['userid'];
	
	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		if($getInstituteType[0]['Institute_type']=="2"){  //If Institute_type 1 is for Institutional doctor then display only perticular institutional doctor, And 2 is for Individual doctor then display all doctors
		$getHospital = $objQuery->mysqlSelect("hosp_id, hosp_name, hosp_city, hosp_state","hosp_tab","","","","","");
		} else {	
		$getHospital = $objQuery->mysqlSelect("hosp_id, hosp_name, hosp_city, hosp_state","hosp_tab","company_id='".$getInstituteType[0]['company_id']."'","hosp_name asc","","","");
		}
		
		if($getHospital == true)
				{
					$success = array('status' => "true","hospital_details" => $getHospital);
					echo json_encode($success);
				}
			else {
					$success = array('status' => "false","hospital_details" => $getHospital);
					echo json_encode($success);
				}
	}
	else if($login_type == 2)	// Type-2 Referring Partners
	{
		$getHospital = $objQuery->mysqlSelect("hosp_id as hosp_id, hosp_name as hosp_name, hosp_city as hosp_city, hosp_state as hosp_state","hosp_tab","","hosp_name asc","","","");
			if($getHospital == true)
				{
					$success = array('status' => "true","hospital_details" => $getHospital);
					echo json_encode($success);
				}
			else {
					$success = array('status' => "false","hospital_details" => $getHospital);
					echo json_encode($success);
				}
	}
	else if($login_type == 3)	// Type-3 Marketing Person
	{
		$getComapny = $objQuery->mysqlSelect("b.company_id as company_id","hosp_marketing_person as a inner join hosp_tab as b on b.hosp_id=a.hosp_id","a.person_id='".$user_id."'","","","","");
		
		$getHospital = $objQuery->mysqlSelect("hosp_id, hosp_name, hosp_city, hosp_state","hosp_tab","company_id='".$getComapny[0]['company_id']."'","","","","");

			if($getHospital == true)
				{
					$success = array('status' => "true","hospital_details" => $getHospital);
					echo json_encode($success);
				}
			else {
					$success = array('status' => "false","hospital_details" => $getHospital);
					echo json_encode($success);
				}
	}
	
	
}

?>