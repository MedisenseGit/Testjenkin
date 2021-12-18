<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Old Visits Episode List
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = (int)$_POST['patient_id'];
	$patient_name = $_POST['patient_name'];
	
	if($login_type == 1) {	
	
		$episode_details= array();
		$get_attachments= array();
	
		$get_Episodes = $objQuery->mysqlSelect('*','doc_patient_episodes',"admin_id='".$admin_id."' and patient_id ='".$patient_id."'","episode_id desc","","","");
		$episode_id=$get_Episodes[0]['episode_id'];
		
		$get_Ophthal_Details = $objQuery->mysqlSelect('*','examination_opthal_spectacle_prescription',"doc_id='".$admin_id."' and episode_id ='".$episode_id."'","spectacle_id desc","","","");
		
		
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
			
			$getEpiList['distVisionRE'] = $get_Ophthal_Details[0]['distacnce_vision_right'];
			$getEpiList['distVisionLE'] = $get_Ophthal_Details[0]['distance_vision_left'];
			$getEpiList['nearVisionRE'] = $get_Ophthal_Details[0]['near_vision_right'];
			$getEpiList['nearVisionLE'] = $get_Ophthal_Details[0]['near_vision_left'];
			
			$getEpiList['refractionRE_value1'] = $get_Ophthal_Details[0]['refraction_right_value1'];
			$getEpiList['refractionRE_value2'] = $get_Ophthal_Details[0]['refraction_right_value2'];
			$getEpiList['refractionLE_value1'] = $get_Ophthal_Details[0]['refraction_left_value1'];
			$getEpiList['refractionLE_value2'] = $get_Ophthal_Details[0]['refraction_left_value2'];
			
			$getEpiList['DvSphereRE'] = $get_Ophthal_Details[0]['dvSphereRE'];
			$getEpiList['DvCylRE'] = $get_Ophthal_Details[0]['DvCylRE'];
			$getEpiList['DvAxisRE'] = $get_Ophthal_Details[0]['DvAxisRE'];
			
			$getEpiList['DvSpeherLE'] = $get_Ophthal_Details[0]['DvSpeherLE'];
			$getEpiList['DvCylLE'] = $get_Ophthal_Details[0]['DvCylLE'];
			$getEpiList['DvAxisLE'] = $get_Ophthal_Details[0]['DvAxisLE'];
			
			$getEpiList['NvSpeherRE'] = $get_Ophthal_Details[0]['NvSpeherRE'];
			$getEpiList['NvCylRE'] = $get_Ophthal_Details[0]['NvCylRE'];
			$getEpiList['NvAxisRE'] = $get_Ophthal_Details[0]['NvAxisRE'];
			
			$getEpiList['NvSpeherLE'] = $get_Ophthal_Details[0]['NvSpeherLE'];
			$getEpiList['NvCylLE'] = $get_Ophthal_Details[0]['NvCylLE'];
			$getEpiList['NvAxisLE'] = $get_Ophthal_Details[0]['NvAxisLE'];
			
			$getEpiList['IpdRE'] = $get_Ophthal_Details[0]['IpdRE'];
			$getEpiList['IpdLE'] = $get_Ophthal_Details[0]['IpdLE'];
			
			$get_consultation_fee = $objQuery->mysqlSelect('*','payment_transaction',"patient_name='".$patient_name."' and trans_date='".$listEpisode['date_time']."'","","","","");
			$getEpiList['consultation_fees'] = $get_consultation_fee[0]['amount'];
			
			$chief_medical_complaint_result = $objQuery->mysqlSelect('a.symptoms_id as symptoms_autoid, a.symptoms as symptoms_id, b.symptoms as symptoms_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['chief_medical_complaint_result']=$chief_medical_complaint_result;
			
			$investigation_result = $objQuery->mysqlSelect('*','patient_temp_investigation',"episode_id='".$listEpisode['episode_id']."' and patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['investigation_result']=$investigation_result;
			
			$diagnosis_result = $objQuery->mysqlSelect('a.patient_diagnosis_id as diagnosis_autoid, a.icd_id as icd_id, b.icd_code as icd_code_name, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.episode_id as episode_id','patient_diagnosis as a inner join icd_code as b on a.icd_id = b.icd_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['diagnosis_result']=$diagnosis_result;
			
			$treatment_result = $objQuery->mysqlSelect('a.treatment_id as treatment_autoid, a.dft_id as treatment_id, b.treatment as treatment_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_treatment_active as a inner join doctor_frequent_treatment as b on a.dft_id = b.dft_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['treatment_result']=$treatment_result;
			
			$prescription_result = $objQuery->mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id='".$listEpisode['episode_id']."'","","","","");
			$getEpiList['prescription_result']=$prescription_result;
			
			$lids_result = $objQuery->mysqlSelect('a.lids as lids_id, b.lids_name as lids_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_type as right_eye','doc_patient_lids_active as a inner join examination_ophthal_lids as b on a.lids = b.lids_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['lids_result'] = $lids_result;
			
			$conjuctiva_result = $objQuery->mysqlSelect('a.conjuctiva as conjuctiva_id, b.conjuctiva_name as conjuctiva_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_conjuctiva_active as a inner join examination_ophthal_conjuctiva as b on a.conjuctiva = b.conjuctiva_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['conjuctiva_result'] = $conjuctiva_result;
			
			$sclera_result = $objQuery->mysqlSelect('a.sclera as sclera_id, b.scelra_name as scelra_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_sclera_active as a inner join examination_ophthal_sclera as b on a.sclera = b.sclera_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['sclera_result'] = $sclera_result;
		
			$cornea_anterior_result = $objQuery->mysqlSelect('a.cornea_ant as cornea_ant_id, b.cornea_ant_name as cornea_ant_name, b.doc_id as doc_id, b.doc_type as doc_type, a.eye_side as right_eye, b.left_eye as left_eye','doc_patient_cornea_ant_active as a inner join examination_ophthal_cornea_anterior as b on a.cornea_ant = b.cornea_ant_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['cornea_anterior_result'] = $cornea_anterior_result;
		
			$cornea_posterior_result = $objQuery->mysqlSelect('a.cornea_post as cornea_post_id, b.cornea_post_name as cornea_post_name, b.doc_id as doc_id, b.doc_type as doc_type, a.eye_side as right_eye, b.left_eye as left_eye','doc_patient_cornea_post_active as a inner join examination_ophthal_cornea_posterior as b on a.cornea_post = b.cornea_post_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['cornea_posterior_result'] = $cornea_posterior_result;
		
			$anterior_chamber_result = $objQuery->mysqlSelect('a.chamber as chamber_id, b.chamber_name as chamber_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_anterior_chamber_active as a inner join examination_ophthal_chamber as b on a.chamber = b.chamber_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['anterior_chamber_result'] = $anterior_chamber_result;
		
			$iris_result = $objQuery->mysqlSelect('a.iris as iris_id, b.iris_name as iris_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_iris_active as a inner join examination_ophthal_iris as b on a.iris = b.iris_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['iris_result'] = $iris_result;
		
			$pupil_result = $objQuery->mysqlSelect('a.pupil as pupil_id, b.pupil_name as pupil_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_pupil_active as a inner join examination_ophthal_pupil as b on a.pupil = b.pupil_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['pupil_result'] = $pupil_result;
		
			$angle_result = $objQuery->mysqlSelect('a.angle as angle_id, b.angle_name as angle_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_angle_active as a inner join examination_ophthal_angle as b on a.angle = b.angle_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['angle_result'] = $angle_result;
			
			$lens_result = $objQuery->mysqlSelect('a.lens as lens_id, b.lens_name as lens_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_lens_active as a inner join examination_ophthal_lens as b on a.lens = b.lens_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['lens_result'] = $lens_result;
		
			$viterous_result = $objQuery->mysqlSelect('a.viterous as viterous_id, b.viterous_name as viterous_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_viterous_active as a inner join examination_ophthal_viterous as b on a.viterous = b.viterous_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['viterous_result'] = $viterous_result;
			
			$fundus_result = $objQuery->mysqlSelect('a.fundus as fundus_id, b.fundus_name as fundus_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_fundus_active as a inner join examination_ophthal_fundus as b on a.fundus = b.fundus_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['fundus_result'] = $fundus_result;
			
			array_push($episode_details, $getEpiList);
		}
		
		$success = array('status' => "success","old_visit_details"=>$episode_details);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "failure");
		echo json_encode($success);
	}
		

	
}


?>