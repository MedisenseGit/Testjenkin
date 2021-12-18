<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Patient list
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$user_ref_id = $_POST['userid'];	
	$logintype = $_POST['login_type'];	
	
	if($logintype == 1) //Login Type 1 for Doctor
	{
		$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"c.ref_id='".$user_ref_id."'","a.patient_id desc","","","0,20");
		
		$success = array('status' => "true","patient_details" => $getHospital);
		echo json_encode($success);
	}
	else if($logintype == 2) //Login Type 2 for Partner
	{
		$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."'","a.patient_id desc","","","0,20");
		$success = array('status' => "true","patient_details" => $getPartner);
		echo json_encode($success);
	}
	else if($logintype == 3) //Login Type 3 for Marketing
	{
		$getMarket = $objQuery->mysqlSelect(' a.patient_id AS pat_id, a.patient_name AS pat_name, a.patient_age AS pat_age, a.patient_loc AS pat_loc, d.status2 AS pat_status, e.ref_name AS pat_doc_name, e.ref_id AS pat_doc_id, b.source_name AS pat_refered_by,f.TImestamp as pat_status_time','patient_tab AS a INNER JOIN source_list AS b ON a.patient_src = b.source_id INNER JOIN mapping_hosp_referrer AS c ON c.partner_id = b.partner_id INNER JOIN patient_referal AS d ON d.patient_id = a.patient_id INNER JOIN referal AS e ON e.ref_id = d.ref_id inner join chat_notification as f on f.ref_id=e.ref_id',"c.market_person_id='".$user_ref_id."'","a.patient_id desc","","","0,20");
	
		$success = array('status' => "true","patient_details" => $getMarket);
		echo json_encode($success);
	}
	else {
		
		
		$success = array('status' => "false","patient_details" => $getPartner);
		echo json_encode($success);
	}
	
		
}


?>