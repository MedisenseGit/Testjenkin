<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Appointment List
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
 

	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($logintype == 1)			// Hospital Doctor
	{

		$patientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."'","patient_id desc","","","");
		$success = array('status' => "true","mypatient_details" => $patientResult);
		echo json_encode($success);	
	
	}
	else if($logintype == 2)	// Partner
	{
	
	// $appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."'","a.Visiting_date desc","","","");
	
	$patientResult = $objQuery->mysqlSelect("*","my_patient","partner_id='".$admin_id."'","patient_id desc","","","");
	
	
	$success = array('status' => "true","mypatient_details" => $patientResult);
	echo json_encode($success);		
	}
	else if($logintype == 3)	// Marketing Person
	{
		
	
	}
	else {
		$success = array('status' => "false","mypatient_details" => $patientResult);
		echo json_encode($success);
	}
}


?>