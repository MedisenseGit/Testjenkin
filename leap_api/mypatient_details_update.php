<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//My Patients create
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$TransId=time();
	
	if($login_type == 1) {						// Premium LoginType
		$txtName = $_POST['se_pat_name'];
		$txtGen = $_POST['se_gender'];
		$txtLoc = $_POST['se_city'];
		$txtCountry = $_POST['se_country'];
		$txtAge = $_POST['se_pat_age'];
		$txtMob = $_POST['se_phone_no'];
		$txtMail = $_POST['se_email'];
		$txtAddress = addslashes($_POST['se_address']);
		$patient_id = $_POST['se_patient_id'];
		$txtState = $_POST['se_state'];
		
		$arrFields = array();
		$arrValues = array();

		
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;

		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;

		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;

		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
		
		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMob;

		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtLoc;
		
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;

		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;

		$arrFields[] = 'patient_addrs';
		$arrValues[] = $txtAddress;

		$arrFields[] = 'doc_id';
		$arrValues[] = $admin_id;

		$arrFields[] = 'system_date';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'TImestamp';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'transaction_id';
		$arrValues[] = $TransId;
		
		$patientprofile = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$patient_id."'","","","","");
		$patient_id = (int)$patientprofile[0]['patient_id'];
		
		if ($patient_id > 0)
		{
			$usercraete=$objQuery->mysqlUpdate('doc_my_patient',$arrFields,$arrValues, " patient_id = '". $patient_id ."' ");
			$success = array('result' => "success");
			echo json_encode($success);
		}
		else
		{
			$success = array('result' => "failure");
			echo json_encode($success);
		}		
	}
	else {
			$success = array('result' => "failure");
			echo json_encode($success);
	}	
		
}


?>