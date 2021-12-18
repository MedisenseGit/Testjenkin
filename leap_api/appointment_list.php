<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Appointment List
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 

	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($logintype == 1)			// Hospital Doctor
	{

	//$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.pref_doc='".$admin_id."'","a.Visiting_date desc","","","");
	$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.pay_status!='Cancelled'","a.Visiting_date desc","","","");
	$success = array('status' => "true","appointment_details" => $appointmentResult);
	echo json_encode($success);
	}
	else if($logintype == 2)	// Partner
	{
	//$appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id","a.pref_doc='".$admin_id."'","a.Visiting_date desc","","","");
	$appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.pay_status!='Cancelled'","a.Visiting_date desc","","","");
	
	$success = array('status' => "true","appointment_details" => $appointmentResult);
	echo json_encode($success);		
	}
	else if($logintype == 3)	// Marketing Person
	{
	$marketingid=$admin_id;	 //Holds Marketing Person Id
	
	$getHospId = $objQuery->mysqlSelect("hosp_id","hosp_marketing_person","person_id='".$marketingid."'","","","","");
		//To check requested marketing person has mapped to perticular care partners
		$checkMapMarket = $objQuery->mysqlSelect("hosp_id","mapping_hosp_referrer","market_person_id='".$marketingid."'","","","","");
		if($checkMapMarket==true){
		$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,e.ref_name as ref_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","b.hosp_id='".$getHospId[0]['hosp_id']."' and a.pay_status!='Cancelled'","a.Visiting_date desc","","","");
		}
		else{
		$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,e.ref_name as ref_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","b.hosp_id='".$getHospId[0]['hosp_id']."' and a.pay_status!='Cancelled'","a.Visiting_date desc","","","");
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