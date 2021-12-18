<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Appointment List
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type'])  && isset($_POST['transaction_id']) ) {
	 
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$transid = $_POST['transaction_id'];
	
	if($logintype == 1)			// Hospital Doctor
	{

	$appointmentResult = $objQuery->mysqlSelect("id,Hosp_patient_Id,appoint_trans_id,Time_stamp,pref_doc,department,Visiting_date,Visiting_time,patient_name,Mobile_no,Email_address,pay_status,visit_status","appointment_transaction_detail","appoint_trans_id='".$transid."'","","","","");
	$appointmentNewResult = $objQuery->mysqlSelect("Father_name,Mother_name,Husband_wife_name,pat_age,pat_gen,Religion,Occupation,City,State,Country,Address","new_hospvisitor_details","Transaction_id='".$transid."'","","","","");

	$success = array('status' => "true","appoint_details" => $appointmentResult,"appoint_new" => $appointmentNewResult);
	echo json_encode($success);
	}
	else if($logintype == 2)	// Partner
	{
	$appointmentResult = $objQuery->mysqlSelect("appoint_id,appoint_trans_id,Visiting_date,Visiting_time,patient_name,Mobile_no,Email_address,City,State,Country,Address,Husband_wife_name,pat_age,pat_gen,	pay_status,department,pref_doc","partner_appointment_transaction","appoint_trans_id='".$transid."'","","","","");
	$success = array('status' => "true","appoint_details" => $appointmentResult);
	echo json_encode($success);
	}
	else if($logintype == 3)	// Marketing Person
	{
	$appointmentResult = $objQuery->mysqlSelect("id,Hosp_patient_Id,appoint_trans_id,Time_stamp,pref_doc,department,Visiting_date,Visiting_time,patient_name,Mobile_no,Email_address,pay_status,visit_status","appointment_transaction_detail","appoint_trans_id='".$transid."'","","","","");
	$appointmentNewResult = $objQuery->mysqlSelect("Father_name,Mother_name,Husband_wife_name,pat_age,pat_gen,Religion,Occupation,City,State,Country,Address","new_hospvisitor_details","Transaction_id='".$transid."'","","","","");

	$success = array('status' => "true","appoint_details" => $appointmentResult,"appoint_new" => $appointmentNewResult);
	echo json_encode($success);
	
	}
	else {
		$success = array('status' => "false","appointment_details" => $getPartner);
		echo json_encode($success);
	}
}


?>