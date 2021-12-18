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
		$login_type = $_POST['login_type'];	
		$admin_id = $doctor_id;
		$patient_id = (int)$_POST['patient_id'];
		$patient_name = $_POST['patient_name'];

		$episode_details= array();
		$get_attachments= array();
	
		$get_Episodes = mysqlSelect('*','doc_patient_episodes',"admin_id='".$admin_id."' and patient_id ='".$patient_id."'","episode_id desc","","","");
		$episode_id=$get_Episodes[0]['episode_id'];
		
		foreach($get_Episodes as $listEpisode){
			$getEpiList['episode_id']=$listEpisode['episode_id'];
			$getEpiList['admin_id']=$listEpisode['admin_id'];
			$getEpiList['patient_id']=$listEpisode['patient_id'];
			$getEpiList['next_followup_date']=$listEpisode['next_followup_date'];
			$getEpiList['diagnosis_details']=$listEpisode['diagnosis_details'];
			$getEpiList['treatment_details']=$listEpisode['treatment_details'];
			$getEpiList['prescription_note']=$listEpisode['prescription_note'];
			$getEpiList['patient_education']=$listEpisode['patient_education'];
			$getEpiList['episode_medical_complaint']=$listEpisode['episode_medical_complaint'];
			$getEpiList['date_time']=$listEpisode['date_time'];
			
			$get_consultation_fee = mysqlSelect('*','payment_transaction',"patient_name='".$patient_name."' and trans_date='".$listEpisode['date_time']."'","","","","");
			$getEpiList['consultation_fees'] = $get_consultation_fee[0]['amount'];
			
			$chief_medical_complaint_result = mysqlSelect('a.symptoms_id as symptoms_autoid, a.symptoms as symptoms_id, b.symptoms as symptoms_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['chief_medical_complaint_result']=$chief_medical_complaint_result;
			
			$investigation_result = mysqlSelect('*','patient_temp_investigation',"episode_id='".$listEpisode['episode_id']."' and patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['investigation_result']=$investigation_result;
			
			$examination_result = mysqlSelect('a.examination_id as examination_autoid, a.examination as examination_id, b.examination as examination_name, a.exam_result as exam_result, a.findings as findings, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_examination_active as a inner join examination as b on a.examination = b.examination_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['examination_result']=$examination_result;
			
			$diagnosis_result = mysqlSelect('a.patient_diagnosis_id as diagnosis_autoid, a.icd_id as icd_id, b.icd_code as icd_code_name, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.episode_id as episode_id','patient_diagnosis as a inner join icd_code as b on a.icd_id = b.icd_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['diagnosis_result']=$diagnosis_result;
			
			$treatment_result = mysqlSelect('a.treatment_id as treatment_autoid, a.dft_id as treatment_id, b.treatment as treatment_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_treatment_active as a inner join doctor_frequent_treatment as b on a.dft_id = b.dft_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['treatment_result']=$treatment_result;
			
			$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id='".$listEpisode['episode_id']."'","","","","");
			$getEpiList['prescription_result']=$prescription_result;
		
			
			array_push($episode_details, $getEpiList);
		}
		
		$success = array('status' => "success","old_visit_details"=>$episode_details);
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