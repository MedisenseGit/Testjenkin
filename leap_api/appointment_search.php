<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//PATIENT SEARCH
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['search_string']) && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 

	$search_string = $_POST['search_string'];
	$user_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	$hospital_id = $_POST['hosp_id'];
	
	
	 if($login_type == 1)		// Type-1 Premium Login
	{
			//$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$user_id."' and (a.id ='".$search_string."' or a.Mobile_no ='".$search_string."' or a.patient_name LIKE '%".$search_string."%' or a.Email_address ='".$search_string."' or a.visit_status LIKE '%".$search_string."%')","a.Visiting_date desc","","","");
			
			$appointmentResult = $objQuery->mysqlSelect("b.token_id as token_id, b.token_no as token_no, b.patient_id as patient_id, b.appoint_trans_id as appoint_trans_id, b.patient_name as patient_name, b.doc_id as doc_id, b.doc_type as doc_type, b.hosp_id as hosp_id, b.status as status, b.app_date as app_date, b.app_time as app_time, b.created_date as created_date, a.Email_address as patient_email, a.Mobile_no as patient_mob","appointment_transaction_detail as a inner join appointment_token_system as b on b.appoint_trans_id = a.appoint_trans_id","a.pref_doc='".$user_id."' and a.hosp_id='".$hospital_id."' and (a.Mobile_no ='".$search_string."' or a.patient_name LIKE '%".$search_string."%' or a.Email_address ='".$search_string."' or a.visit_status LIKE '%".$search_string."%')","a.id DESC","","","");
			
			$success = array('status' => "true","appointment_details" => $appointmentResult);
			echo json_encode($success);
	}
	else if($login_type == 2)		// Type-2 Referring Partners
	{
		$appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$user_id."' and (a.appoint_id ='".$search_string."'or a.Mobile_no ='".$search_string."'or a.patient_name LIKE '%".$search_string."%' or a.Email_address ='".$search_string."' or a.visit_status LIKE '%".$search_string."%')","a.Visiting_date desc","","","");
		$success = array('status' => "true","appointment_details" => $appointmentResult);
		echo json_encode($success);
	} 
	else if($login_type == 3)		// Type-3 Marketing Person
	{
		$marketingid=$user_id;	 //Holds Marketing Person Id
		$getHospId = $objQuery->mysqlSelect("hosp_id","hosp_marketing_person","person_id='".$marketingid."'","","","","");
		//To check requested marketing person has mapped to perticular care partners
		$checkMapMarket = $objQuery->mysqlSelect("hosp_id","mapping_hosp_referrer","market_person_id='".$marketingid."'","","","","");
		if($checkMapMarket==true){
		$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","b.hosp_id='".$getHospId[0]['hosp_id']."' and (a.id ='".$search_string."'or a.Mobile_no ='".$search_string."'or a.patient_name LIKE '%".$search_string."%' or a.Email_address ='".$search_string."' or a.visit_status LIKE '%".$search_string."%')","a.Visiting_date desc","","","");
		}
		else{
		$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","b.hosp_id='".$getHospId[0]['hosp_id']."' and (a.id ='".$search_string."'or a.Mobile_no ='".$search_string."'or a.patient_name LIKE '%".$search_string."%' or a.Email_address ='".$search_string."' or a.visit_status LIKE '%".$search_string."%')","a.Visiting_date desc","","","");
		}
		$success = array('status' => "true","appointment_details" => $appointmentResult);
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","appointment_details" => $getPartner);
		echo json_encode($success);
	}
		
}


?>