<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Patient list
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) && isset($_POST['patient_filter']) ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$user_ref_id = $_POST['userid'];	
	$logintype = $_POST['login_type'];	
	$patient_filter = $_POST['patient_filter'];
	
	if($logintype == 1) //Login Type 1 for Doctor
	{
		if($patient_filter == 0) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"c.ref_id='".$user_ref_id."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 1) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 3) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 4) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 5) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 6) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 7) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 8) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 9) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 10) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 11) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 12) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 13) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		else if($patient_filter == 14) {
			$getHospital = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"b.ref_id='".$user_ref_id."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getHospital);
			echo json_encode($success);
		}
		
	}
	else if($logintype == 2) //Login Type 2 for Partner
	{
		if($patient_filter == 0) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 1) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 2) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 3) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 4) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 5) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 6) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 7) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 8) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 9) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 10) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 11) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 12) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 13) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
		else if($patient_filter == 14) {
			$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,a.TImestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_ref_id."' and c.status2 = '".$patient_filter."'","a.patient_id desc","","","");
			$success = array('status' => "true","patient_details" => $getPartner);
			echo json_encode($success);
		}
	}
	else if($logintype == 3) //Login Type 3 for Marketing
	{
		$marketingid = $_POST['userid']; //Holds Marketing Person Id
		$getHospId = $objQuery->mysqlSelect("hosp_id","hosp_marketing_person","person_id='".$marketingid."'","","","","");
		//To check requested marketing person has mapped to perticular care partners
		$getMapPatient = $objQuery->mysqlSelect("DISTINCT(b.patient_id) AS Pat_Id","patient_tab AS a INNER JOIN patient_referal AS b ON b.patient_id = a.patient_id INNER JOIN source_list AS c ON c.source_id = a.patient_src INNER JOIN doctor_hosp AS e ON e.doc_id = b.ref_id INNER JOIN mapping_hosp_referrer AS d ON c.partner_id = d.partner_id","d.market_person_id =  '".$marketingid."' AND e.hosp_id =  '".$getHospId[0]['hosp_id']."'","b.patient_id desc","","","");
		if($getMapPatient==true){
			$getPatientList["patient_details"]=array();
			if($patient_filter == 0) {
					
					foreach($getMapPatient as $patientList){
					$stuff=array();
					//$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_id='".$patientList['Pat_Id']."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					 $getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
			}
			else if($patient_filter == 1) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					//$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 2) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					//$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
					
				else if($patient_filter == 3) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 4) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 5) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 6) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 7) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 8) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 9) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 10) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 11) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 12) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 13) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				else if($patient_filter == 14) {
					foreach($getMapPatient as $patientList){
					$stuff=array();
					$getPartnerSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$marketList['partner_id']."'","","","","");
					
					$getPatient = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, b.status2 as pat_status, c.ref_name as pat_doc_name, c.ref_id as pat_doc_id, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src inner join doctor_hosp as e on e.doc_id=c.ref_id',"a.patient_src='".$getPartnerSource[0]['source_id']."' and e.hosp_id='".$getHospId[0]['hosp_id']."' and b.status2 = '".$patient_filter."'","a.patient_id desc","","","0,20");
					$stuff['pat_id']=$getPatient[0]['pat_id'];
					$stuff['pat_name']=$getPatient[0]['pat_name'];
					$stuff['pat_age']=$getPatient[0]['pat_age'];
					$stuff['pat_loc']=$getPatient[0]['pat_loc'];
					$stuff['pat_status']=$getPatient[0]['pat_status'];
					$stuff['pat_doc_name']=$getPatient[0]['pat_doc_name'];
					$stuff['pat_doc_id']=$getPatient[0]['pat_doc_id'];
					$stuff['pat_refered_by']=$getPatient[0]['pat_refered_by'];
					$stuff['pat_status_time']=$getPatient[0]['pat_status_time'];
					
					$getPatientList["status"] = "true";
					array_push($getPatientList["patient_details"],$stuff);
					}
					echo(json_encode($getPatientList));
					
				}
				
			
		} 
		
		else{
			$success = array('status' => "true","patient_details" => $getMapPatient);
			echo json_encode($success);
		}
		
		
		//$getMarket = $objQuery->mysqlSelect(' a.patient_id AS pat_id, a.patient_name AS pat_name, a.patient_age AS pat_age, a.patient_loc AS pat_loc, d.status2 AS pat_status, e.ref_name AS pat_doc_name, e.ref_id AS pat_doc_id, b.source_name AS pat_refered_by,f.TImestamp as pat_status_time','patient_tab AS a INNER JOIN source_list AS b ON a.patient_src = b.source_id INNER JOIN mapping_hosp_referrer AS c ON c.partner_id = b.partner_id INNER JOIN patient_referal AS d ON d.patient_id = a.patient_id INNER JOIN referal AS e ON e.ref_id = d.ref_id inner join chat_notification as f on f.ref_id=e.ref_id',"c.market_person_id='".$user_ref_id."'","a.patient_id desc","","","");
	
		//$success = array('status' => "true","patient_details" => $getMarket);
		//echo json_encode($success);
	}
	else {
		
		
		$success = array('status' => "false","patient_details" => $getPartner);
		echo json_encode($success);
	}
	
		
}


?>