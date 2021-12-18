<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

$ToDay=date('Y-m-d');

 if(API_KEY == $_POST['API_KEY']) {	
	
		$admin_id = $_POST['userid'];
		$login_type = $_POST['login_type'];
		$today_date = $_POST['appt_today'];
		$date_from = $_POST['appt_date_from'];
		$date_to = $_POST['appt_date_to'];
		$filter_type = $_POST['appt_filter_type']; //appt_filter_type is 1 for Today & 2 Between date & 3 for selected date(one date)
		if($login_type == 1) {    //$login_type is 1 for Hospital Doctor, 2 for Care Partners, 3 for marketing professionals
			
			if($filter_type == 1){
			$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date ='".$ToDay."'","a.Visiting_date desc","","","");
			} else if($filter_type == 2){
			$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date BETWEEN '".$date_from."' and '".$date_to."'","a.Visiting_date desc","","","");
			} else if($filter_type == 3){
			$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date ='".$today_date."'","a.Visiting_date desc","","","");
			} 
			$success = array('status' => "true","appointment_details" => $appointmentResult);
			echo json_encode($success);
		
		}
		else if($login_type == 3) { //For marketing professionals
		$marketingid=$_POST['userid'];	 //Holds Marketing Person Id
		$getHospId = $objQuery->mysqlSelect("hosp_id","hosp_marketing_person","person_id='".$marketingid."'","","","","");
	
			if($filter_type == 1){
			$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","b.hosp_id='".$getHospId[0]['hosp_id']."'and a.Visiting_date ='".$ToDay."'","a.Visiting_date desc","","","");
		
			} else if($filter_type == 2){
			$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","b.hosp_id='".$getHospId[0]['hosp_id']."' and a.Visiting_date BETWEEN '".$date_from."' and '".$date_to."'","a.Visiting_date desc","","","");
			} else if($filter_type == 3){
			$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","b.hosp_id='".$getHospId[0]['hosp_id']."' and a.Visiting_date ='".$today_date."'","a.Visiting_date desc","","","");
			} 
			$success = array('status' => "true","appointment_details" => $appointmentResult);
			echo json_encode($success);
		}
		else if($login_type == 2) {
			$account = "As a Partner";
			if($filter_type == 1){
			$appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date ='".$ToDay."'","a.Visiting_date desc","","","");
			} else if($filter_type == 2){
			$appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date BETWEEN '".$date_from."' and '".$date_to."'","a.Visiting_date desc","","","");
			} else if($filter_type == 3){
			$appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date ='".$today_date."'","a.Visiting_date desc","","","");
			} 
			$success = array('status' => "true","appointment_details" => $appointmentResult);
			echo json_encode($success);
		}
		
	
 }
?>
