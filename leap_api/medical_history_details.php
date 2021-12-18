<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Medical History Details
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = (int)$_POST['patient_id'];
	$patient_name = $_POST['patient_name'];
	
	if($login_type == 1) {	
	
		$episode_details= array();
		$get_attachments= array();
		
		$getPatientResult = $objQuery->mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and patient_id='".$patient_id."'","patient_id DESC","","","");
		$getDrugAllery = $objQuery->mysqlSelect("*","doc_patient_drug_allergy_active","doc_id='".$admin_id."' and doc_type='1' and patient_id='".$patient_id."'","allergy_id DESC","","","");
		$getDrugAbuse= $objQuery->mysqlSelect("a.drug_active_id as drug_active_id, a.drug_abuse_id as drug_abuse_id, b.drug_abuse as drug_abuse, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.status as status","doc_patient_drug_active as a inner join drug_abuse_auto as b on a.drug_abuse_id = b.drug_abuse_id","a.doc_id='".$admin_id."' and a.doc_type='1' and a.patient_id='".$patient_id."'","a.drug_active_id DESC","","","");
		$getFamilyHistory= $objQuery->mysqlSelect("a.family_active_id as family_active_id, a.family_history_id as family_history_id, b.family_history as family_history, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.status as status","doc_patient_family_history_active as a inner join family_history_auto as b on a.family_history_id = b.family_history_id","a.doc_id='".$admin_id."' and a.doc_type='1' and a.patient_id='".$patient_id."'","a.family_active_id DESC","","","");

		$success = array('status' => "true","patient_details"=>$getPatientResult,"drug_allery_details"=>$getDrugAllery,"drug_abuse_details"=>$getDrugAbuse,"family_history_details"=>$getFamilyHistory);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "false");
		echo json_encode($success);
	}
		

	
}


?>