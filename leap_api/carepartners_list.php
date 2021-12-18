<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// CARE PARTNERS - LIST
if(API_KEY == $_POST['API_KEY']) {
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	

	$admin_id = $_POST['user_id'];
	
	$getDoc = $objQuery->mysqlSelect("a.ref_id as Doc_Id,a.ref_name as Doc_name,c.hosp_id as Hosp_Id,c.company_id as Comp_Id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
	$busResult = $objQuery->mysqlSelect("a.partner_id as Partner_Id,a.contact_person as Partner_name,d.hosp_name as Hosp_Name,a.login_status as Login_Status","our_partners as a inner join mapping_hosp_referrer as b on a.partner_id=b.partner_id inner join hosp_tab as d on d.hosp_id=b.hosp_id","b.doc_id='".$admin_id."' and d.company_id='".$getDoc[0]['Comp_Id']."'","a.partner_id desc","","","0,15");

	if($busResult==true){
					
		$success = array('status' => "true","care_partners" => $busResult);     
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","care_partners" => $busResult);      // partner insert failed
		echo json_encode($success);
	}
	
}


?>