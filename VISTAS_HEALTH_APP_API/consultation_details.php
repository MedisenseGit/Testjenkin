<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//My Consultation Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$login_id 	= $user_id;
		$patient_id = $_POST['patientID'];
		
		// Get Latest Consultation Deatils
		$get_Episodes = mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"a.patient_id ='".$patient_id."'","a.episode_id DESC","","","0,1");
		
		
		
		
		$episode_id	=	$get_Episodes[0]['episode_id'];
		$patient_id	=	$get_Episodes[0]['patient_id'];
		
		$episode_details= array();
		$getEpiList['episode_id']	=	$get_Episodes[0]['episode_id'];
		$getEpiList['emr_type']		=	$get_Episodes[0]['emr_type'];
		$getEpiList['admin_id']		=	$get_Episodes[0]['admin_id'];
		$getEpiList['patient_id']	=	$get_Episodes[0]['patient_id'];
		
		$getEpiList['examination']	=	$get_Episodes[0]['examination'];
		$getEpiList['treatment']	=	$get_Episodes[0]['treatment'];
		
		$getEpiList['date_time']	=	$get_Episodes[0]['date_time'];
		$getEpiList['ref_name']		=	$get_Episodes[0]['ref_name'];
		$getEpiList['ref_id']		=	$get_Episodes[0]['ref_id'];
		$getEpiList['prescription_note']	=	$get_Episodes[0]['prescription_note'];
		$getEpiList['diagnosis_details']	=	$get_Episodes[0]['diagnosis_details'];
		$getEpiList['treatment_details']	=	$get_Episodes[0]['treatment_details'];
		$getEpiList['next_followup_date']	=	$get_Episodes[0]['next_followup_date'];
		$getEpiList['episode_medical_complaint']	=	$get_Episodes[0]['episode_medical_complaint'];
		
		
		//diagnosis_details_list
		$last_five_icd = mysqlSelect("b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.doc_id='".$get_Episodes[0]['ref_id']."' and a.doc_type='1'","a.freq_count DESC","","","");
		$diagnosis_list_array =	array();
		foreach($last_five_icd as $diagnosis_list)
		{
			
			$getdiagnosis_list['diagnosis_list'] =	$diagnosis_list['icd_code'];
			array_push($diagnosis_list_array, $getdiagnosis_list);
		}
		$getEpiList['diagnosis_details_list']	=	$diagnosis_list_array;
		
		
		//examination_details_list
		$last_five_examination = mysqlSelect("b.examination as examination_list_val","doctor_frequent_examination as a left join examination as b on a.examination_id=b.examination_id","(a.doc_id='".$get_Episodes[0]['ref_id']."' and a.doc_type='1') or (a.doc_id='0' and a.doc_type='0')","","","","");
		
		$examination_details_array =	array();
		foreach($last_five_examination as $five_examination )
		{
			
			$getexamination_list['examination_detail']	=	$five_examination['examination_list_val'];
			array_push($examination_details_array, $getexamination_list);
		}
		$getEpiList['examination_details_list']	=	$examination_details_array;
		
		//treatment_details_list
		$last_five_treatment = mysqlSelect("treatment as treatment_list","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$get_Episodes[0]['ref_id']."' and doc_type='1')","freq_count DESC","","","");
		
		
		$treatment_details_array =	array();
		foreach($last_five_treatment as $five_treatment)
		{
			
			$gettreatment_list['treatment_list']	=	$five_treatment['treatment_list'];
			array_push($treatment_details_array, $gettreatment_list);
		}
		$getEpiList['treatment_details_list']	=	$treatment_details_array;
		
		
		$getpatientName	=	mysqlSelect('patient_name','doc_my_patient',"patient_id='".$patient_id."'","","","","");
		$getEpiList['patient_name']=$getpatientName[0]['patient_name'];
		
		
		$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id='".$get_Episodes[0]['episode_id']."'","","","","");
		$prescription_details= array();
		foreach($prescription_result as $listPrescriptionList)
		{
			$getPrescList['episode_prescription_id']	=	$listPrescriptionList['episode_prescription_id'];
			$getPrescList['episode_id']					=	$listPrescriptionList['episode_id'];
			$getPrescList['prescription_trade_name']	=	$listPrescriptionList['prescription_trade_name'];
			$getPrescList['prescription_generic_name']	=	$listPrescriptionList['prescription_generic_name'];
			$getPrescList['prescription_frequency']		=	$listPrescriptionList['prescription_frequency'];
			$getPrescList['duration']					=	$listPrescriptionList['duration'];
			$getPrescList['med_duration_type']			=	$listPrescriptionList['med_duration_type'];
			//$getPrescList['timing']=$listPrescriptionList['timing'];
			$getPrescList['doc_id']						=	$listPrescriptionList['doc_id'];
			$getPrescList['pp_id']						=	$listPrescriptionList['pp_id'];
			
			$prescription_timings = mysqlSelect('*','doc_medicine_timing_language',"language_id='".$listPrescriptionList['timing']."'","","","","");
			$getPrescList['timing']=$prescription_timings[0]['english'];
			
			array_push($prescription_details, $getPrescList);
		}
		
		$getEpiList['prescription_result']=$prescription_details;
		
		$investigation_result = mysqlSelect('*','patient_temp_investigation',"episode_id='".$get_Episodes[0]['episode_id']."' and patient_id='".$get_Episodes[0]['patient_id']."'","","","","");
		$getEpiList['investigation_result']=$investigation_result;
		
		$chief_medical_complaint_result = mysqlSelect('a.symptoms_id as symptoms_autoid, a.symptoms as symptoms_id, b.symptoms as symptoms_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id',"a.episode_id='".$get_Episodes[0]['episode_id']."' and a.patient_id='".$get_Episodes[0]['patient_id']."'","","","","");
		$getEpiList['chief_medical_complaint_result']=$chief_medical_complaint_result;
		
		array_push($episode_details, $getEpiList);
		
					
		$success_wallet = array('result' => "success", "patient_id"=>$patient_id, "episode_details"=>$episode_details, 'message' => "Your Consulatations !!!", 'err_msg' => '');
		echo json_encode($success_wallet);
	}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else 
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}
?>
