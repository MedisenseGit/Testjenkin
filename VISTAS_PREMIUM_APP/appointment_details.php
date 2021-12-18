<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');


$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}



$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

/*if(!empty($doctor_id) && !empty($finalHash)) {
	
	// $result = $objQuery->mysqlSelect('accessToken','referal',"ref_id='".$doctor_id."'");
	/* $result_access = $objQuery->mysqlSelect('id, accessToken','referal_sessions',"doc_id='".$doctor_id."' AND device_id='".$device_id."'","id DESC","","","1");
	
	
	if(!empty($result_access[0]['accessToken'])) {
		  $hash1 = hmacHashFunction($timestamp, $result_access[0]['accessToken']);
		  $finalHash = hmacHashFunction($hash1, json_encode($postdata)); 	// Body is empty bcoz Its a GET Request
	} */
	
	/*if($finalHash == $hashKey) {*/
		$patient_details= array();
		$get_attachments= array();
		
		$appoint_id = $_POST['appoint_id'];
		$get_AppointDetails = mysqlSelect('id, appoint_trans_id, patient_id, hosp_id, member_id, pref_doc','appointment_transaction_detail',"id ='".$appoint_id."'","","","","");
		$patient_id		=	$get_AppointDetails[0]['patient_id'];
		
		$get_MyPatient = mysqlSelect('*','doc_my_patient',"patient_id ='".$patient_id."'","","","","");
		foreach($get_MyPatient as $listget_MyPatient)
		{
			$getEpiList['patient_id']			=	$listget_MyPatient['patient_id'];
			$getEpiList['patient_name']			=	$listget_MyPatient['patient_name'];
			$getEpiList['patient_gen']			=	$listget_MyPatient['patient_gen'];
			$getEpiList['patient_age']			=	$listget_MyPatient['patient_age'];
			$getEpiList['height']				=	$listget_MyPatient['height'];
			$getEpiList['weight']				=	$listget_MyPatient['weight'];
			$getEpiList['hyper_cond']			=	$listget_MyPatient['hyper_cond'];
			$getEpiList['smoking']				=	$listget_MyPatient['smoking'];
			$getEpiList['alcoholic']			=	$listget_MyPatient['alcoholic'];
			$getEpiList['diabetes_cond']		=	$listget_MyPatient['diabetes_cond'];
			$getEpiList['prev_inter']			=	$listget_MyPatient['prev_inter'];
			$getEpiList['neuro_issue']			=	$listget_MyPatient['neuro_issue'];
			$getEpiList['kidney_issue']			=	$listget_MyPatient['kidney_issue'];
			$getEpiList['other_details']		=	$listget_MyPatient['other_details'];			
			$getEpiList['pat_blood']			=	$listget_MyPatient['pat_blood'];	
			$getEpiList['pat_bp']				=	$listget_MyPatient['pat_bp'];				// 1-No, 2-Yes, 0-NOt mentioned
			$getEpiList['pat_thyroid']			=	$listget_MyPatient['pat_thyroid'];			// 1-No, 2-Yes, 0-NOt mentioned
			$getEpiList['pat_cholestrole']		=	$listget_MyPatient['pat_cholestrole'];		// 1-No, 2-Yes, 0-NOt mentioned
			$getEpiList['pat_epilepsy']			=	$listget_MyPatient['pat_epilepsy'];			// 1-No, 2-Yes, 0-NOt mentioned
			$getEpiList['pat_asthama']			=	$listget_MyPatient['pat_asthama'];			// 1-No, 2-Yes, 0-NOt mentioned
			$getEpiList['doc_video_link']		=	$listget_MyPatient['doc_video_link'];	
			$getEpiList['teleconsult_status']	=	$listget_MyPatient['teleconsult_status'];	// 1- Accepted, 2-Decline/No Response
		
			$getDrugAllery = mysqlSelect("*","doc_patient_drug_allergy_active"," patient_id='".$listget_MyPatient['patient_id']."'","allergy_id DESC","","","");
			$getEpiList['drug_allergy_result'] = $getDrugAllery;
			
			$getDrugAbuse= mysqlSelect("a.drug_active_id as drug_active_id, a.drug_abuse_id as drug_abuse_id, b.drug_abuse as drug_abuse, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.status as status","doc_patient_drug_active as a inner join drug_abuse_auto as b on a.drug_abuse_id = b.drug_abuse_id"," a.patient_id='".$listget_MyPatient['patient_id']."'","a.drug_active_id DESC","","","");
			$getEpiList['drug_abuse_result'] 	= $getDrugAbuse;
			
			$getFamilyHistory= mysqlSelect("a.family_active_id as family_active_id, a.family_history_id as family_history_id, b.family_history as family_history, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.status as status","doc_patient_family_history_active as a inner join family_history_auto as b on a.family_history_id = b.family_history_id"," a.patient_id='".$listget_MyPatient['patient_id']."'","a.family_active_id DESC","","","");
			$getEpiList['family_history_result'] = $getFamilyHistory;
			
			
			$login_user = mysqlSelect('b.civil_id as civil_id','user_family_member as a inner join login_user as b on b.login_id = a.user_id',"a.member_id='".$listget_MyPatient['member_id']."'","","","","");
			$getEpiList['civil_id']=$login_user;
			
			
				$reports_details= array();
				$response["rep_details"] = array();
				$doc_patient_reports 	 = mysqlSelect("*","doc_my_patient_reports","patient_id = '".$listget_MyPatient['patient_id']."' ","report_folder desc","","","");
		
				foreach($doc_patient_reports as $doc_patient_reports)
				{	
						
					$getReportList['report_id']		=	$doc_patient_reports['report_id'];
					$getReportList['patient_id']	=	$doc_patient_reports['patient_id'];
					$getReportList['report_folder']	=	$doc_patient_reports['report_folder'];
					$getReportList['user_id']		=	$doc_patient_reports['user_id'];
					$getReportList['user_type']		=	$doc_patient_reports['user_type'];
					$getReportList['date_added']	=	$doc_patient_reports['date_added'];
					
					$get_reports = mysqlSelect("*","doc_my_patient_reports","report_folder = '".$doc_patient_reports['report_folder']."'","","","","");
					if($get_reports[0]['user_type']=='1')
					{
						$patient_tab = mysqlSelect("*","doc_my_patient","patient_id='".$get_reports[0]['user_id']."'","","","","");
						$username					=	$patient_tab[0]['patient_name'];
						$getReportList['username']	=	$username;
					}
					if($get_reports[0]['user_type']=='2')
					{
						$get_doc_details = mysqlSelect("*","referal","ref_id='".$get_reports[0]['user_id']."'","","","","");
		
							$username					=	$get_doc_details[0]['ref_name'];
							$getReportList['username']	=	$username;
					}
					if($get_reports[0]['user_type']=='3')
					{
						$get_daignosis = mysqlSelect("diagnosis_name","Diagnostic_center","diagnostic_id = '".$get_reports[0]['user_id']."'","","","","");
						$username					=	$get_daignosis[0]['diagnosis_name'];
						$getReportList['username']	=	$username;
					}
					
					$getReportList['user_type']		=	$doc_patient_reports['user_type'];
					$getReportList['attachments']	=	$doc_patient_reports['attachments'];
					
				
					array_push($reports_details, $getReportList);
					$getEpiList['reports_details']=$reports_details;
				}
				
			$healthReports_details= array();
			$health_files_repo = mysqlSelect("b.id as attach_id, b.attachment_name as attachment_name, b.report_id as report_id, a.title as title, a.description as description, a.report_date as report_date, a.created_date as created_date","health_app_healthfile_reports as a inner join health_app_healthfile_report_attachments as b on b.report_id = a.id","a.member_id = '".$listget_MyPatient['member_id']."' ","a.id DESC","","","");
		
			foreach($health_files_repo as $health_files_reports)
			{	
						
				$getHealthFilesReportList['attach_id']		=	$health_files_reports['attach_id'];
				$getHealthFilesReportList['attachment_name']=	$health_files_reports['attachment_name'];
				$getHealthFilesReportList['report_id']		=	$health_files_reports['report_id'];
				$getHealthFilesReportList['title']			=	$health_files_reports['title'];
				$getHealthFilesReportList['description']	=	$health_files_reports['description'];
				$getHealthFilesReportList['report_date']	=	$health_files_reports['report_date'];
				$getHealthFilesReportList['created_date']	=	$doc_patient_reports['created_date'];
				
				array_push($healthReports_details, $getHealthFilesReportList);
				$getEpiList['health_files_reports_details']=$healthReports_details;
			} 

			$get_Episodes = mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id'," patient_id ='".$listget_MyPatient['patient_id']."'","episode_id desc","","","");
			$episode_id=$get_Episodes[0]['episode_id'];
		
				foreach($get_Episodes as $listEpisode)
				{
					$getEpiList['episode_id']		=	$listEpisode['episode_id'];
					$getEpiList['emr_type']			=	$listEpisode['emr_type'];
					$getEpiList['admin_id']			=	$listEpisode['admin_id'];
					$getEpiList['patient_id']		=	$listget_MyPatient['patient_id'];
					$getEpiList['examination']		=	$listEpisode['examination'];
					$getEpiList['treatment']		=	$listEpisode['treatment'];
					$getEpiList['date_time']		=	$listEpisode['date_time'];
					$getEpiList['ref_name']			=	$listEpisode['ref_name'];
					$getEpiList['ref_id']			=	$listEpisode['ref_id'];
					$getEpiList['prescription_note']=	$listEpisode['prescription_note'];
					$getEpiList['diagnosis_details']=	$listEpisode['diagnosis_details'];
					$getEpiList['treatment_details']=	$listEpisode['treatment_details'];
					$getEpiList['episode_medical_complaint']	=	$listEpisode['episode_medical_complaint'];
					$getEpiList['next_followup_date']			=	$listEpisode['next_followup_date'];
					
					if($listEpisode['emr_type'] == 1)
					{
						$get_consultation_fee = mysqlSelect('*','payment_transaction',"patient_id='".$listget_MyPatient['patient_id']."'","","","","");
						$getEpiList['consultation_fees'] = $get_consultation_fee[0]['amount'];
						
						$chief_medical_complaint_result  = mysqlSelect('a.symptoms_id as symptoms_autoid, a.symptoms as symptoms_id, b.symptoms as symptoms_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['chief_medical_complaint_result'] = $chief_medical_complaint_result;
						
						$investigation_result = mysqlSelect('*','patient_temp_investigation',"episode_id='".$listEpisode['episode_id']."' and patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['investigation_result'] = $investigation_result;
						
						$examination_result = mysqlSelect('a.examination_id as examination_autoid, a.examination as examination_id, b.examination as examination_name, a.exam_result as exam_result, a.findings as findings, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_examination_active as a inner join examination as b on a.examination = b.examination_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['examination_result']=$examination_result;
						
						$diagnosis_result = mysqlSelect('a.patient_diagnosis_id as diagnosis_autoid, a.icd_id as icd_id, b.icd_code as icd_code_name, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.episode_id as episode_id','patient_diagnosis as a inner join icd_code as b on a.icd_id = b.icd_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['diagnosis_result']=$diagnosis_result;
						
						$treatment_result = mysqlSelect('a.treatment_id as treatment_autoid, a.dft_id as treatment_id, b.treatment as treatment_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_treatment_active as a inner join doctor_frequent_treatment as b on a.dft_id = b.dft_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['treatment_result']=$treatment_result;
						
						$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id='".$listEpisode['episode_id']."'","","","","");
						$prescription 	= array();
						foreach($prescription_result as $list)
						{
							$getList['episode_prescription_id']	=$list['episode_prescription_id'];
							$getList['episode_id']				=$list['episode_id'];
							$getList['prescription_template']	=$list['prescription_template'];
							$getList['doc_id']					=$list['doc_id'];
							$getList['pp_id']					=$list['pp_id'];
							$getList['prescription_trade_name']	=$list['prescription_trade_name'];
							$getList['prescription_generic_name']=$list['prescription_generic_name'];
							$getList['prescription_dosage_name']=$list['prescription_dosage_name'];
							
							$getList['med_frequency_morning']	=$list['med_frequency_morning'];
							$getList['med_frequency_noon']		=$list['med_frequency_noon'];
							$getList['med_frequency_night']		=$list['med_frequency_night'];
							$getList['prescription_route']		=$list['prescription_route'];
							$getList['prescription_frequency']	=$list['prescription_frequency'];
							$getList['timing']					=$list['timing'];
							$getList['duration']				=$list['duration'];
							$getList['med_duration_type']		=$list['med_duration_type'];
							$getList['prescription_seq']		=$list['prescription_seq'];
							$getList['prescription_priceValue']	=$list['prescription_priceValue'];
							$getList['prescription_date_time']	=$list['prescription_date_time'];
							$getList['login_id']				=$list['login_id'];
							
							$prescription_result = mysqlSelect('english','doc_medicine_timing_language',"priority='".$list['timing']"'","","","","");
							
							$getList['english']	=	$list['english'];
							
							array_push($prescription, $getList);
						}
						$getEpiList['prescription_result']=$prescription;
						
						$trends_result = mysqlSelect("*","trend_analysis","patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['trends_result']=$trends_result;
					
					}
					else if($listEpisode['emr_type'] == 2)
					{
						$get_Ophthal_Details = mysqlSelect('*','examination_opthal_spectacle_prescription',"doc_id='".$listEpisode['admin_id']."' and episode_id ='".$listEpisode['episode_id']."'","spectacle_id desc","","","");
						
						$getEpiList['distVisionRE'] = $get_Ophthal_Details[0]['distacnce_vision_right'];
						$getEpiList['distVisionLE'] = $get_Ophthal_Details[0]['distance_vision_left'];
						$getEpiList['nearVisionRE'] = $get_Ophthal_Details[0]['near_vision_right'];
						$getEpiList['nearVisionLE'] = $get_Ophthal_Details[0]['near_vision_left'];
						
						$getEpiList['refractionRE_value1'] = $get_Ophthal_Details[0]['refraction_right_value1'];
						$getEpiList['refractionRE_value2'] = $get_Ophthal_Details[0]['refraction_right_value2'];
						$getEpiList['refractionLE_value1'] = $get_Ophthal_Details[0]['refraction_left_value1'];
						$getEpiList['refractionLE_value2'] = $get_Ophthal_Details[0]['refraction_left_value2'];
						
						$getEpiList['DvSphereRE'] = $get_Ophthal_Details[0]['dvSphereRE'];
						$getEpiList['DvCylRE']    = $get_Ophthal_Details[0]['DvCylRE'];
						$getEpiList['DvAxisRE']   = $get_Ophthal_Details[0]['DvAxisRE'];
						
						$getEpiList['DvSpeherLE'] = $get_Ophthal_Details[0]['DvSpeherLE'];
						$getEpiList['DvCylLE']    = $get_Ophthal_Details[0]['DvCylLE'];
						$getEpiList['DvAxisLE']   = $get_Ophthal_Details[0]['DvAxisLE'];
						
						$getEpiList['NvSpeherRE'] = $get_Ophthal_Details[0]['NvSpeherRE'];
						$getEpiList['NvCylRE']    = $get_Ophthal_Details[0]['NvCylRE'];
						$getEpiList['NvAxisRE']   = $get_Ophthal_Details[0]['NvAxisRE'];
						
						$getEpiList['NvSpeherLE'] = $get_Ophthal_Details[0]['NvSpeherLE'];
						$getEpiList['NvCylLE']    = $get_Ophthal_Details[0]['NvCylLE'];
						$getEpiList['NvAxisLE']   = $get_Ophthal_Details[0]['NvAxisLE'];
						
						$getEpiList['IpdRE'] 	  = $get_Ophthal_Details[0]['IpdRE'];
						$getEpiList['IpdLE']      = $get_Ophthal_Details[0]['IpdLE'];
						
						$get_consultation_fee = mysqlSelect('*','payment_transaction',"patient_name='".$patient_name."' and trans_date='".$listEpisode['date_time']."'","","","","");
						$getEpiList['consultation_fees'] = $get_consultation_fee[0]['amount'];
						
						$chief_medical_complaint_result = mysqlSelect('a.symptoms_id as symptoms_autoid, a.symptoms as symptoms_id, b.symptoms as symptoms_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['chief_medical_complaint_result']=$chief_medical_complaint_result;
						
						$investigation_result = mysqlSelect('*','patient_temp_investigation',"episode_id='".$listEpisode['episode_id']."' and patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['investigation_result']=$investigation_result;
						
						$diagnosis_result = mysqlSelect('a.patient_diagnosis_id as diagnosis_autoid, a.icd_id as icd_id, b.icd_code as icd_code_name, a.patient_id as patient_id, a.doc_id as doc_id, a.doc_type as doc_type, a.episode_id as episode_id','patient_diagnosis as a inner join icd_code as b on a.icd_id = b.icd_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['diagnosis_result']=$diagnosis_result;
						
						$treatment_result = mysqlSelect('a.treatment_id as treatment_autoid, a.dft_id as treatment_id, b.treatment as treatment_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_treatment_active as a inner join doctor_frequent_treatment as b on a.dft_id = b.dft_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['treatment_result']=$treatment_result;
						
						$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id='".$listEpisode['episode_id']."'","","","","");
						$getEpiList['prescription_result']=$prescription_result;
						
						$lids_result = mysqlSelect('a.lids as lids_id, b.lids_name as lids_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_type as right_eye','doc_patient_lids_active as a inner join examination_ophthal_lids as b on a.lids = b.lids_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['lids_result'] = $lids_result;
						
						$conjuctiva_result = mysqlSelect('a.conjuctiva as conjuctiva_id, b.conjuctiva_name as conjuctiva_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_conjuctiva_active as a inner join examination_ophthal_conjuctiva as b on a.conjuctiva = b.conjuctiva_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['conjuctiva_result'] = $conjuctiva_result;
						
						$sclera_result = mysqlSelect('a.sclera as sclera_id, b.scelra_name as scelra_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_sclera_active as a inner join examination_ophthal_sclera as b on a.sclera = b.sclera_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['sclera_result'] = $sclera_result;
					
						$cornea_anterior_result = mysqlSelect('a.cornea_ant as cornea_ant_id, b.cornea_ant_name as cornea_ant_name, b.doc_id as doc_id, b.doc_type as doc_type, a.eye_side as right_eye, b.left_eye as left_eye','doc_patient_cornea_ant_active as a inner join examination_ophthal_cornea_anterior as b on a.cornea_ant = b.cornea_ant_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['cornea_anterior_result'] = $cornea_anterior_result;
					
						$cornea_posterior_result = mysqlSelect('a.cornea_post as cornea_post_id, b.cornea_post_name as cornea_post_name, b.doc_id as doc_id, b.doc_type as doc_type, a.eye_side as right_eye, b.left_eye as left_eye','doc_patient_cornea_post_active as a inner join examination_ophthal_cornea_posterior as b on a.cornea_post = b.cornea_post_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['cornea_posterior_result'] = $cornea_posterior_result;
					
						$anterior_chamber_result = mysqlSelect('a.chamber as chamber_id, b.chamber_name as chamber_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_anterior_chamber_active as a inner join examination_ophthal_chamber as b on a.chamber = b.chamber_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['anterior_chamber_result'] = $anterior_chamber_result;
					
						$iris_result = mysqlSelect('a.iris as iris_id, b.iris_name as iris_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_iris_active as a inner join examination_ophthal_iris as b on a.iris = b.iris_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['iris_result'] = $iris_result;
					
						$pupil_result = mysqlSelect('a.pupil as pupil_id, b.pupil_name as pupil_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_pupil_active as a inner join examination_ophthal_pupil as b on a.pupil = b.pupil_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['pupil_result'] = $pupil_result;
					
						$angle_result = mysqlSelect('a.angle as angle_id, b.angle_name as angle_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_angle_active as a inner join examination_ophthal_angle as b on a.angle = b.angle_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['angle_result'] = $angle_result;
						
						$lens_result = mysqlSelect('a.lens as lens_id, b.lens_name as lens_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_lens_active as a inner join examination_ophthal_lens as b on a.lens = b.lens_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['lens_result'] = $lens_result;
					
						$viterous_result = mysqlSelect('a.viterous as viterous_id, b.viterous_name as viterous_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_viterous_active as a inner join examination_ophthal_viterous as b on a.viterous = b.viterous_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['viterous_result'] = $viterous_result;
						
						$fundus_result = mysqlSelect('a.fundus as fundus_id, b.fundus_name as fundus_name, b.doc_id as doc_id, b.doc_type as doc_type, b.left_eye as left_eye, a.eye_side as right_eye','doc_patient_fundus_active as a inner join examination_ophthal_fundus as b on a.fundus = b.fundus_id',"a.episode_id='".$listEpisode['episode_id']."' and a.patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['fundus_result'] = $fundus_result;
					
						$trends_ophthal_result = mysqlSelect("*","trend_analysis_ophthal","patient_id='".$listEpisode['patient_id']."'","","","","");
						$getEpiList['trends_ophthal_result']=$trends_ophthal_result;
					}
					
					array_push($patient_details, $getEpiList);
				}
				
			
		} 
		
		$success = array('status' => "true", "patient_details"=>$patient_details, 'err_msg' => '');
		echo json_encode($success);
	/*}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}*/

?>