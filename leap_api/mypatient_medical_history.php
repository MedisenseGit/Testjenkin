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
		$patient_id = $_POST['se_patient_id'];
		$txtWeight = $_POST['se_weight'];
		$txtHypertension = $_POST['se_hypertension'];
		$txtDiabetes = $_POST['se_diabetes'];
		$txtSmoking = $_POST['se_smoking'];
		$txtAlcohol = $_POST['se_alcohol'];
		$txtDrugAbuse = $_POST['se_drug_abuse'];
		$txtOtherDetails = $_POST['se_other_details'];
		$txtFamilyHistory = $_POST['se_family_history'];
		$txtPrevIntervention = $_POST['se_prev_interventions'];
		$txtNeuro = $_POST['se_nuero'];
		$txtKidney = $_POST['se_kidney'];
		
		$arrFields = array();
		$arrValues = array();

		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;

		$arrFields[] = 'hyper_cond';
		$arrValues[] = $txtHypertension;

		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $txtDiabetes;

		$arrFields[] = 'smoking';
		$arrValues[] = $txtSmoking;
		
		$arrFields[] = 'alcoholic';
		$arrValues[] = $txtAlcohol;

		$arrFields[] = 'drug_abuse';
		$arrValues[] = $txtDrugAbuse;
		
		$arrFields[] = 'other_details';
		$arrValues[] = $txtOtherDetails;

		$arrFields[] = 'family_history';
		$arrValues[] = $txtFamilyHistory;

		$arrFields[] = 'prev_inter';
		$arrValues[] = $txtPrevIntervention;
		
		$arrFields[] = 'neuro_issue';
		$arrValues[] = $txtNeuro;
		
		$arrFields[] = 'kidney_issue';
		$arrValues[] = $txtKidney;

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
			$usercraete=$objQuery->mysqlUpdate('doc_my_patient',$arrFields,$arrValues, " patient_id = '". $patient_id ."'");
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