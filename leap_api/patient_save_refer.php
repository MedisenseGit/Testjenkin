<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
//$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();

//Random Password Generator
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


// PATIENT SAVE AND REFER
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['patient_name']) || isset($_POST['patient_age'])|| isset($_POST['patient_gender'])|| isset($_POST['patient_location'])|| isset($_POST['patient_specialization'])|| 
	isset($_POST['patient_mobile'])|| isset($_POST['patient_chiefmedcomplaint']) || isset($_POST['patient_weight']) || isset($_POST['patient_maritalstatus']) || isset($_POST['patient_profession']) || 
	isset($_POST['patient_hypertesnsion']) || isset($_POST['patient_diabetes']) || isset($_POST['patient_contactperson']) || isset($_POST['patient_email']) || isset($_POST['patient_address']) || 
	isset($_POST['patient_city']) || isset($_POST['patient_state']) || isset($_POST['patient_country']) || isset($_POST['patient_current_treating_doctor']) || isset($_POST['patient_current_treating_hospital']) || 
	isset($_POST['patient_brief_description']) || isset($_POST['patient_querytodoctor']) || isset($_POST['doctor_id'])  || isset($_POST['user_id']) )
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	

	$txtName = addslashes($_POST['patient_name']);
	$txtMail = addslashes($_POST['patient_email']);
	$txtAge = $_POST['patient_age'];
	$txtGen = $_POST['patient_gender'];
	$txtContact = $_POST['patient_contactperson'];
	$txtMob = $_POST['patient_mobile'];
	$txtCountry = $_POST['patient_country'];
	$txtState = $_POST['patient_state'];
	$txtLoc = $_POST['patient_location'];
	$txtAddress = addslashes($_POST['patient_address']);
	$txtWeight = $_POST['patient_weight'];	
	$hyperCond = $_POST['patient_hypertesnsion'];
	$diabetesCond = $_POST['patient_diabetes'];
	$patDept = $_POST['patient_specialization'];
	$txtTreatDoc = addslashes($_POST['patient_current_treating_doctor']);
	$txtTreatHosp = addslashes($_POST['patient_current_treating_hospital']);
	
	$txtCheifMedComplaint = addslashes($_POST['patient_chiefmedcomplaint']);
	$txtBriefDescription = addslashes($_POST['patient_brief_description']);
	$txtQueryToDoctor = addslashes($_POST['patient_querytodoctor']);
	$docid = $_POST['doctor_id'];
	$refpartner = $_POST['user_id'];
	$cur_Date = date("Y-m-d");
	
	//Get Source Id from Our Partner table
	$getSourceId= $objQuery->mysqlSelect("*","our_partners as a left join source_list as b on a.partner_id=b.partner_id","a.partner_id='".$refpartner."'","","","","");
	//print_r($getSourceId);
	$PatientSource=$getSourceId[0]['source_id'];
	$arrFields = array();
	$arrValues = array();	

	$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;
		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;
		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;
		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		$arrFields[] = 'hyper_cond';
		$arrValues[] = $hyperCond;
		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $diabetesCond;
		
		$arrFields[] = 'contact_person';
		$arrValues[] = $txtContact;
		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMob;
		$arrFields[] = 'patient_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtLoc;
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;
		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'patient_src';
		$arrValues[] = $PatientSource;		
	
		$arrFields[] = 'currentTreatDoc';
		$arrValues[] = $txtTreatDoc;
		$arrFields[] = 'currentTreatHosp';
		$arrValues[] = $txtTreatHosp;
		$arrFields[] = 'medDept';
		$arrValues[] = $patDept;		

		$arrFields[] = 'patient_complaint';
		$arrValues[] = $txtCheifMedComplaint;
		$arrFields[] = 'patient_desc';
		$arrValues[] = $txtBriefDescription;
		$arrFields[] = 'pat_query';
		$arrValues[] = $txtQueryToDoctor;
		$arrFields[] = 'assigned_to';
		$arrValues[] = '0';
		$arrFields[] = 'user_id';
		$arrValues[] = '9';
	//	$arrFields[] = 'company_id';
	//	$arrValues[] = $admin_id;
		$arrFields[] = 'system_date';
		$arrValues[] = $cur_Date;
		
	//	$arrFields[] = 'transaction_id';
	//	$arrValues[] = $TransId;	
	
	$getcount = $objQuery->mysqlSelect('count(patient_mob) AS NumberOfPatient','patient_tab',"patient_mob='".$txtMob."' and system_date='".$cur_Date."'","","","","");
	if($getcount == true)
	{
		if( $getcount[0]['NumberOfPatient'] >0)
		{
			$getcount = array('status' => "false","patient_create" => "already_exists", "count" => $getcount[0]['NumberOfPatient'] );      // patient insert failed
			echo json_encode($getcount);
		}
		else {
			$patientCreate=$objQuery->mysqlInsert('patient_tab',$arrFields,$arrValues);
			$pid= mysql_insert_id();
			if($patientCreate == true)
			{
				$getPatientDetail = $objQuery->mysqlSelect('*','patient_tab',"patient_id ='".$pid."'","","","","");
	
				$success = array('status' => "true","patient_create" => $patientCreate,"patient_detail" => $getPatientDetail);    	//  patient created resume
				echo json_encode($success);
			}
			else {
				$success = array('status' => "false","patient_create" => $patientCreate);      // patient insert failed
				echo json_encode($success);
			}
		}
		
	}
	else {
		$success = array('status' => "false","patient_create" => $getcount);      // patient insert failed
		echo json_encode($success);
	}

}


?>