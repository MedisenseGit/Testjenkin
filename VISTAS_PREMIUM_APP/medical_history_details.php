<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) {
		$admin_id = $doctor_id;
		$patient_id = (int)$_POST['patient_id'];

		$episode_details= array();
		$get_attachments= array();
		
		$getPatientResult = mysqlSelect("*","doc_my_patient","doc_id='".$admin_id."' and patient_id='".$patient_id."'","patient_id DESC","","","");
		$getDrugAllery = mysqlSelect("*","doc_patient_drug_allergy_active","doc_id='".$admin_id."' and doc_type='1' and patient_id='".$patient_id."'","allergy_id DESC","","","");
		$getDrugAbuse= mysqlSelect("a.drug_active_id as drug_active_id, a.drug_abuse_id as drug_abuse_id, b.drug_abuse as drug_abuse, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.status as status","doc_patient_drug_active as a inner join drug_abuse_auto as b on a.drug_abuse_id = b.drug_abuse_id","a.doc_id='".$admin_id."' and a.doc_type='1' and a.patient_id='".$patient_id."'","a.drug_active_id DESC","","","");
		$getFamilyHistory= mysqlSelect("a.family_active_id as family_active_id, a.family_history_id as family_history_id, b.family_history as family_history, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.status as status","doc_patient_family_history_active as a inner join family_history_auto as b on a.family_history_id = b.family_history_id","a.doc_id='".$admin_id."' and a.doc_type='1' and a.patient_id='".$patient_id."'","a.family_active_id DESC","","","");

		$success = array('status' => "true","patient_details"=>$getPatientResult,"drug_allery_details"=>$getDrugAllery,"drug_abuse_details"=>$getDrugAbuse,"family_history_details"=>$getFamilyHistory);
		echo json_encode($success);
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}

?>