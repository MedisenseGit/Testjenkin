<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
// $ccmail="medical@medisense.me";
$ccmail="salmabanu.h@gmail.com";
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['login_type']) &&  isset($_POST['doctor_id']) && isset($_POST['patient_id'])  && isset($_POST['status_change']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$patient_id = $_POST['patient_id'];
	$doctor_id = $_POST['doctor_id'];
	$login_type = $_POST['login_type'];  // 1 - Hospital Doctor, 2 - MArketing Person
	$status_cahnge_val = $_POST['status_change'];
	
	$arrFields = array();
	$arrValues = array();
	
	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		$arrFields[]= 'status2';
		$arrValues[]= $status_cahnge_val;
		//Update Patient Status
		$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$patient_id."'and ref_id='".$doctor_id."'");
		//GET Patient Details
		$getPatient = $objQuery->mysqlSelect("a.patient_name as PatName,a.patient_loc as Pat_loc,a.patient_id as Pat_Id,b.status2 as Current_Status,a.patient_src as Patient_Src","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$patient_id."'and b.ref_id='".$doctor_id."'","","","","");
	
		//GET Partner Details
		$getPartner = $objQuery->mysqlSelect("a.partner_name as Partner_Name,a.Email_id as Partner_Email,a.cont_num1 as Partner_Mobile","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatient[0]['Patient_Src']."'","","","","");
	
		//GET Hospital Datails
		$getHospital = $objQuery->mysqlSelect("a.hosp_name as Hosp_Name,a.hosp_email as Hosp_Email","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$doctor_id."'","","","","");
		if($getPatient[0]['Current_Status']==13){
				$Current_Status="OP-Visited";
			}
		else if($getPatient[0]['Current_Status']==9){
				$Current_Status="IP-Treated";
			}
					//Mail Notification to Referred Parties
					$url_page = 'status_notification_partner.php';					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['PatName']);
					$url .= "&patplace=" . urlencode($getPatient[0]['Pat_loc']);
					$url .= "&patid=" . urlencode($getPatient[0]['Pat_Id']);
					$url .= "&currentstatus=".urlencode($Current_Status);
					$url .= "&partnername=".urlencode($getPartner[0]['Partner_Name']);
					$url .= "&partnermail=".urlencode($getPartner[0]['Partner_Email']);
					$url .= "&hospname=".urlencode($getHospital[0]['Hosp_Name']);
					$url .= "&hospmail=".urlencode($getHospital[0]['Hosp_Email']);
					send_mail($url);
					
					
					//Message Notification to Referred Parties
					$mobile = $getPartner[0]['Partner_Mobile'];
					$responsemsg = "Dear ".$getPartner[0]['Partner_Name'].", Status for patient ".$getPatient[0]['PatName']." changed to ".$Current_Status." Thanks, ".$getHospital[0]['Hosp_Name'];
					send_msg($mobile,$responsemsg);
	
		$success = array('status' => "true","status_change" => "Mail Sent");   
		echo json_encode($success);		
		
	}
	else if($login_type == 3) 	// Type-3 Marketing Person
	{
	}
	
}


?>