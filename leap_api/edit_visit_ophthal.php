<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

// Edit Ophthal Visit
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];
	
	$patient_id = (int)$_POST['patient_id'];
	$consultation_fees =  $_POST['consultation_fees'];
	$episode_id =  $_POST['episode_id'];
	$diagnosis_details =  $_POST['diagnosis_details'];
	$treatment_details =  $_POST['treatment_details'];
	$prescription_note =  $_POST['prescription_note'];
	$visit_entry_date =  $_POST['visit_entry_date'];
	$visit_chiefMedComplaint_sufferings =  $_POST['chiefMedComplaint_sufferings'];
	$chkInvestSaveTemplate =  $_POST['investigation_template_save'];
	$invest_template_name =  $_POST['investigation_template_name'];
	$patient_education =  $_POST['patient_education'];
	
	// Ophthal Examination DistanceVision
	$txt_distancevision_num_right =  $_POST['slctDistVisionRE'];
	$txt_distancevision_num_left =  $_POST['slctDistVisionLE'];

	$txt_nearvision_num_right =  $_POST['slctNearVisionRE'];
	$txt_nearvision_num_left =  $_POST['slctNearVisionLE'];

	$txt_refractionRE_value1 =  $_POST['se_refractionRE_value1'];
	$txt_refractionRE_value2 =  $_POST['se_refractionRE_value2'];
	$txt_refractionLE_value1 =  $_POST['se_refractionLE_value1'];
	$txt_refractionLE_value2 =  $_POST['se_refractionLE_value2'];
	
	$txt_DvSpeherRE =  $_POST['DvSpeherRE'];
	$txt_DvCylRE =  $_POST['DvCylRE'];
	$txt_DvAxisRE =  $_POST['DvAxisRE'];
	$txt_DvSpeherLE =  $_POST['DvSpeherLE'];
	$txt_DvCylLE =  $_POST['DvCylLE'];
	$txt_DvAxisLE =  $_POST['DvAxisLE'];
	$txt_NvSpeherRE =  $_POST['NvSpeherRE'];
	$txt_NvCylRE =  $_POST['NvCylRE'];
	$txt_NvAxisRE =  $_POST['NvAxisRE'];
	$txt_NvSpeherLE =  $_POST['NvSpeherLE'];
	$txt_NvCylLE =  $_POST['NvCylLE'];
	$txt_NvAxisLE =  $_POST['NvAxisLE'];
	$txt_IpdRE =  $_POST['IpdRE'];
	$txt_IpdLE =  $_POST['IpdLE'];

	
	if($login_type == 1) {						// Premium LoginType
	
		$check_episode = $objQuery->mysqlSelect("*","doc_patient_episodes","episode_id='".$episode_id."' and admin_id='".$admin_id."' and patient_id='".$patient_id."'","","","","");
		$arrFieldsEpisode = array();
		$arrValuesEpisode = array();
		if(count($check_episode)>0){
				if(!empty($_POST['diagnosis_details'])) {
					$arrFieldsEpisode[] = 'diagnosis_details';
					$arrValuesEpisode[] = $diagnosis_details;
				}
				if(!empty($_POST['treatment_details'])) {
					$arrFieldsEpisode[] = 'treatment_details';
					$arrValuesEpisode[] = $treatment_details;
				}
				if(!empty($_POST['prescription_note'])) {
					$arrFieldsEpisode[] = 'prescription_note';
					$arrValuesEpisode[] = $prescription_note;
				}

				if(!empty($_POST['visit_entry_date'])) {
					$arrFieldsEpisode[] = 'date_time';
					$arrValuesEpisode[] = date('Y-m-d H:i:s',strtotime($_POST['visit_entry_date']));
				}
				
				if(!empty($_POST['chiefMedComplaint_sufferings'])) {
					$arrFieldsEpisode[] = 'episode_medical_complaint';
					$arrValuesEpisode[] = $visit_chiefMedComplaint_sufferings;
				}
			
				if(!empty($patient_education)) {
					$arrFieldsEpisode[] = 'patient_education';
					$arrValuesEpisode[] = $patient_education;
				}
				
				$update_episod=$objQuery->mysqlUpdate('doc_patient_episodes',$arrFieldsEpisode,$arrValuesEpisode,"episode_id='".$episode_id."' and admin_id='".$admin_id."' and patient_id='".$patient_id."'");
		}
		
		$check_spectacle = $objQuery->mysqlSelect("*","examination_opthal_spectacle_prescription","episode_id='".$episode_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
		if(count($check_spectacle)>0) {
			// Update Examination Ophthal Other Details - Spectacle Prescriptions
			$arrFieldsDV = array();
			$arrValuesDV = array();
			
			if(!empty($txt_distancevision_num_right)) {
				$arrFieldsDV[] = 'distacnce_vision_right';
				$arrValuesDV[] = $txt_distancevision_num_right;
			}
			
			if(!empty($txt_distancevision_num_left)) {
				$arrFieldsDV[] = 'distance_vision_left';
				$arrValuesDV[] = $txt_distancevision_num_left;
			}
			
			if(!empty($txt_nearvision_num_right)) {
				$arrFieldsDV[] = 'near_vision_right';
				$arrValuesDV[] = $txt_nearvision_num_right;
			}
			
			if(!empty($txt_nearvision_num_left)) {
				$arrFieldsDV[] = 'near_vision_left';
				$arrValuesDV[] = $txt_nearvision_num_left;
			}
			
			if(!empty($txt_refractionRE_value1)) {
				$arrFieldsDV[] = 'refraction_right_value1';
				$arrValuesDV[] = $txt_refractionRE_value1;
			}
			
			if(!empty($txt_refractionRE_value2)) {
				$arrFieldsDV[] = 'refraction_right_value2';
				$arrValuesDV[] = $txt_refractionRE_value2;
			}
			
			if(!empty($txt_refractionLE_value1)) {
				$arrFieldsDV[] = 'refraction_left_value1';
				$arrValuesDV[] = $txt_refractionLE_value1;
			}
			
			if(!empty($txt_refractionLE_value2)) {
				$arrFieldsDV[] = 'refraction_left_value2';
				$arrValuesDV[] = $txt_refractionLE_value2;
			}
			
			if(!empty($txt_DvSpeherRE)) {
				$arrFieldsDV[] = 'dvSphereRE';
				$arrValuesDV[] = $txt_DvSpeherRE;
			}
			
			if(!empty($txt_DvCylRE)) {
				$arrFieldsDV[] = 'DvCylRE';
				$arrValuesDV[] = $txt_DvCylRE;
			}
			
			if(!empty($txt_DvAxisRE)) {
				$arrFieldsDV[] = 'DvAxisRE';
				$arrValuesDV[] = $txt_DvAxisRE;
			}
			
			if(!empty($txt_DvSpeherLE)) {
				$arrFieldsDV[] = 'DvSpeherLE';
				$arrValuesDV[] = $txt_DvSpeherLE;
			}
			
			if(!empty($txt_DvCylLE)) {
				$arrFieldsDV[] = 'DvCylLE';
				$arrValuesDV[] = $txt_DvCylLE;
			}
			
			if(!empty($txt_DvAxisLE)) {
				$arrFieldsDV[] = 'DvAxisLE';
				$arrValuesDV[] = $txt_DvAxisLE;
			}
			
			if(!empty($txt_NvSpeherRE)) {
				$arrFieldsDV[] = 'NvSpeherRE';
				$arrValuesDV[] = $txt_NvSpeherRE;
			}
			
			if(!empty($txt_NvCylRE)) {
				$arrFieldsDV[] = 'NvCylRE';
				$arrValuesDV[] = $txt_NvCylRE;
			}
			
			if(!empty($txt_NvAxisRE)) {
				$arrFieldsDV[] = 'NvAxisRE';
				$arrValuesDV[] = $txt_NvAxisRE;
			}
			
			if(!empty($txt_NvSpeherLE)) {
				$arrFieldsDV[] = 'NvSpeherLE';
				$arrValuesDV[] = $txt_NvSpeherLE;
			}
			
			if(!empty($txt_NvCylLE)) {
				$arrFieldsDV[] = 'NvCylLE';
				$arrValuesDV[] = $txt_NvCylLE;
			}
			
			if(!empty($txt_NvAxisLE)) {
				$arrFieldsDV[] = 'NvAxisLE';
				$arrValuesDV[] = $txt_NvAxisLE;
			}
			
			if(!empty($txt_IpdRE)) {
				$arrFieldsDV[] = 'IpdRE';
				$arrValuesDV[] = $txt_IpdRE;
			}
			
			if(!empty($txt_IpdLE)) {
				$arrFieldsDV[] = 'IpdLE'; 
				$arrValuesDV[] = $txt_IpdLE; 
			}
			
			$update_spectacle = $objQuery->mysqlUpdate('examination_opthal_spectacle_prescription',$arrFieldsDV,$arrValuesDV,"episode_id='".$episode_id."' and doc_id='".$admin_id."' and doc_type='1'");
		
		}
	
		//Delete all Episode deatils
		$objQuery->mysqlDelete('doc_patient_symptoms_active',"episode_id='".$episode_id."' and doc_type='1'");
		$objQuery->mysqlDelete('patient_temp_investigation',"episode_id='".$episode_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('patient_diagnosis',"episode_id='".$episode_id."' and doc_type='1'");  
		$objQuery->mysqlDelete('doc_patient_treatment_active',"episode_id='".$episode_id."' and doc_type='1'");  
		$objQuery->mysqlDelete('doc_patient_episode_prescriptions',"episode_id='".$episode_id."' and doc_id='".$admin_id."'");
		
		$objQuery->mysqlDelete('doc_patient_lids_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_conjuctiva_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_sclera_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_cornea_ant_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_cornea_post_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_anterior_chamber_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_iris_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_pupil_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_angle_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_lens_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_viterous_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		$objQuery->mysqlDelete('doc_patient_fundus_active',"episode_id='".$episode_id."' and patient_id ='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1'"); 
		
			
		/* Add Chief Medical Complaints */	
		if(!empty($_POST['chiefcomplaint_id'])) {
			while (list($key, $val) = each($_POST['chiefcomplaint_id'])) {
				
				$chief_Med_compID = $_POST['chiefcomplaint_id'][$key];
				$chief_Med_comp_name = $_POST['chiefcomplaint_name'][$key];
				$chief_Med_comp_docid = $_POST['chiefcomplaint_docid'][$key];
				$chief_Med_comp_doctype = $_POST['chiefcomplaint_doctype'][$key];
				
				if($chief_Med_compID == 0) {
					$arrFileds_symp = array();
					$arrValues_symp = array();
					
					$arrFileds_symp[]='symptoms';
					$arrValues_symp[]=$chief_Med_comp_name;
					$arrFileds_symp[]='doc_id';
					$arrValues_symp[]=$admin_id;
					$arrFileds_symp[]='doc_type';
					$arrValues_symp[]='1';
					
					$insert_symptoms=$objQuery->mysqlInsert('chief_medical_complaints',$arrFileds_symp,$arrValues_symp);
					$symp_id = mysql_insert_id(); //Get Patient Id
				}
				else {
					$symp_id = $chief_Med_compID;
				}
				
				// ADD new Symptoms Data
				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[]='symptoms';
				$arrValues[]=$symp_id;
									
				$arrFileds[]='patient_id';
				$arrValues[]=$patient_id;
				
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
				
				$arrFileds[]='doc_type';
				$arrValues[]="1";
				
				$arrFileds[]='episode_id';
				$arrValues[]=$episode_id;
				
				$arrFileds[]='status';
				$arrValues[]="0";
				
				$insert_symptoms=$objQuery->mysqlInsert('doc_patient_symptoms_active',$arrFileds,$arrValues);
				
				$check_symp = $objQuery->mysqlSelect("*","doctor_frequent_symptoms","symptoms_id='".$symp_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_symp[0]['freq_count']+1; //Count will increment by one
				$arrFieldsSYMPFREQ = array();
				$arrValuesSYMPFREQ = array();
				if(count($check_symp)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"dfs_id = '".$check_symp[0]['dfs_id']."'");
				}
				else{
						$arrFieldsSYMPFREQ[] = 'symptoms_id';
						$arrValuesSYMPFREQ[] = $symp_id;
						$arrFieldsSYMPFREQ[] = 'doc_id';
						$arrValuesSYMPFREQ[] = $admin_id;
						$arrFieldsSYMPFREQ[] = 'doc_type';
						$arrValuesSYMPFREQ[] = "1";
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);	
				}
			} 
		}

		/* Add Investigations */
		if(!empty($_POST['investigation_id']))	{	 
			while (list($key, $val) = each($_POST['investigation_id'])) {
				$investigation_id = $_POST['investigation_id'][$key];
				$test_id = $_POST['test_id'][$key];
				$group_test_id = $_POST['grouptest_id'][$key];
				$test_name = $_POST['test_name'][$key];
				$normal_range = $_POST['normalRange'][$key];
				$actual_value = $_POST['actualRange'][$key];
				$right_eye = $_POST['rightEyeRange'][$key];
				$left_eye = $_POST['leftEyeRange'][$key];
				$department = $_POST['departmentRange'][$key];
				
				if($test_id == "" && $group_test_id == "" && $department == 5) {
					$arrFileds_test[]='test_id';
					$arrValues_test[]= time();
					$arrFileds_test[]='doc_id';
					$arrValues_test[]=$admin_id;
					$arrFileds_test[]='doc_type';
					$arrValues_test[]="1";
					$arrFileds_test[]='test_name_site_name';
					$arrValues_test[]=$test_name;
					$arrFileds_test[]='group_test';
					$arrValues_test[]="N";
					$arrFileds_test[]='department';
					$arrValues_test[]="5";
					
					$insert_new_val=$objQuery->mysqlInsert('patient_diagnosis_tests',$arrFileds_test,$arrValues_test);
					$invest_id = mysql_insert_id();
					$getDiagnosisDetails= $objQuery->mysqlSelect("test_id,test_name_site_name","patient_diagnosis_tests","id='".$invest_id."'","","","","");	
					$test_id = $getDiagnosisDetails[0]['test_id'];
					$group_test_id = $getDiagnosisDetails[0]['test_id'];	
				}
				
				$arrFileds = array();
				$arrValues = array();
		
				$arrFileds[]='main_test_id';
				$arrValues[]=$test_id;
				
				$arrFileds[]='group_test_id';
				$arrValues[]=$group_test_id;	
				
				$arrFileds[]='test_name';
				$arrValues[]=$test_name;
				
				if($normal_range != 'null') {
					$arrFileds[]='normal_range';
					$arrValues[]=$normal_range;
				}
				
				$arrFileds[]='test_actual_value';
				$arrValues[]=$actual_value;
				
				$arrFileds[]='right_eye';
				$arrValues[]=$right_eye;
				
				$arrFileds[]='left_eye';
				$arrValues[]=$left_eye;
				
				$arrFileds[]='department';
				$arrValues[]=$department;
				
				$arrFileds[]='patient_id';
				$arrValues[]=$patient_id;
				
				$arrFileds[]='episode_id';
				$arrValues[]=$episode_id;
				
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
				
				$arrFileds[]='doc_type';
				$arrValues[]="1";
				$arrFileds[]='status';
				$arrValues[]="0";
				
				$insert_temp_value=$objQuery->mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
				
				$check_invest = $objQuery->mysqlSelect("*","doctor_frequent_investigations","main_test_id='".$investigation_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_invest)>0){
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfi_id = '".$check_invest[0]['dfi_id']."'");
					}
					else{
						$arrFieldsINVESTFREQ[] = 'main_test_id';
						$arrValuesINVESTFREQ[] = $investigation_id;
						$arrFieldsINVESTFREQ[] = 'doc_id';
						$arrValuesINVESTFREQ[] = $admin_id;
						$arrFieldsINVESTFREQ[] = 'doc_type';
						$arrValuesINVESTFREQ[] = "1";
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
					}
			}
		}
			
		/* Add Examinations LIDS */
		if(!empty($_POST['lids_id'])) {
			while (list($key, $val) = each($_POST['lids_id'])) {

				$lids_id = $_POST['lids_id'][$key];
				$lids_name = $_POST['lids_name'][$key];
				$lids_docid = $_POST['lids_docid'][$key];
				$lids_doctype = $_POST['lids_doctype'][$key];
				$lids_leftEye = $_POST['lids_leftEye'][$key];
				$lids_rightEye = $_POST['lids_rightEye'][$key];
				$lids_userid = $_POST['lids_userid'][$key];
				$lids_loginType = $_POST['lids_loginType'][$key];

				if($lids_id == 0) {
					$arrFileds_lids = array();
					$arrValues_lids = array();

					$arrFileds_lids[]='lids_name';
					$arrValues_lids[]=$lids_name;
					$arrFileds_lids[]='doc_id';
					$arrValues_lids[]=$admin_id;
					$arrFileds_lids[]='doc_type';
					$arrValues_lids[]='1';
					
					if($lids_rightEye == 1) {
						$arrFileds_lids[]='right_eye';
						$arrValues_lids[]='1';
						$arrFileds_lids[]='eye_type';
						$arrValues_lids[]="1";
					} 
					else if($lids_leftEye == 2) {
						$arrFileds_lids[]='left_eye';
						$arrValues_lids[]='1';
						$arrFileds_lids[]='eye_type';
						$arrValues_lids[]="2";
					}
					
					$check_exists_lids = $objQuery->mysqlSelect("*","examination_ophthal_lids","lids_name='".$lids_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_lids)==0){ //To prevent double entry
						$insert_lids=$objQuery->mysqlInsert('examination_ophthal_lids',$arrFileds_lids,$arrValues_lids);
						$lids_id = mysql_insert_id(); //Get Patient Id
					}
					else {
						$lids_id = $check_exists_lids[0]['lids_id'];
					}
				}
				else {
					$lids_id = $lids_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'lids';
				$arrValues[] = $lids_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($lids_rightEye == 1) {
					$arrFileds[]='eye_type';
					$arrValues[]="1";
					$eye_type = "1";
				} else if($lids_leftEye == 2) {
					$arrFileds[]='eye_type';
					$arrValues[]="2";
					$eye_type = "2";
				}
	
				$check_active_lids = $objQuery->mysqlSelect("lids_id","doc_patient_lids_active","lids='".$lids_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='".$eye_type."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_lids)==0){ //To prevent double entry
					$insert_lids=$objQuery->mysqlInsert('doc_patient_lids_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_lids_active',"episode_id='".$episode_id."' and 	lids='".$lids_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='".$eye_type."' and patient_id='".$patient_id."'");
					$insert_lids=$objQuery->mysqlInsert('doc_patient_lids_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations CONJUCTIVA */
		if(!empty($_POST['conjuctiva_id'])) {
			while (list($key, $val) = each($_POST['conjuctiva_id'])) {

				$conjuctiva_id = $_POST['conjuctiva_id'][$key];
				$conjuctiva_name = $_POST['conjuctiva_name'][$key];
				$conjuctiva_docid = $_POST['conjuctiva_docid'][$key];
				$conjuctiva_doctype = $_POST['conjuctiva_doctype'][$key];
				$conjuctiva_leftEye = $_POST['conjuctiva_leftEye'][$key];
				$conjuctiva_rightEye = $_POST['conjuctiva_rightEye'][$key];
				$conjuctiva_userid = $_POST['conjuctiva_userid'][$key];
				$conjuctiva_loginType = $_POST['conjuctiva_loginType'][$key];

				if($conjuctiva_id == 0) {
					$arrFileds_conjuctiva = array();
					$arrValues_conjuctiva = array();

					$arrFileds_conjuctiva[]='conjuctiva_name';
					$arrValues_conjuctiva[]=$conjuctiva_name;
					$arrFileds_conjuctiva[]='doc_id';
					$arrValues_conjuctiva[]=$admin_id;
					$arrFileds_conjuctiva[]='doc_type';
					$arrValues_conjuctiva[]='1';
					
					if($conjuctiva_rightEye == 1) {
						$arrFileds_conjuctiva[]='right_eye';
						$arrValues_conjuctiva[]='1';
						$arrFileds_conjuctiva[]='eye_side';
						$arrValues_conjuctiva[]="1";
					} 
					else if($conjuctiva_leftEye == 2) {
						$arrFileds_conjuctiva[]='left_eye';
						$arrValues_conjuctiva[]='1';
						$arrFileds_conjuctiva[]='eye_side';
						$arrValues_conjuctiva[]="2";
					}
					
					$check_exists_conjuctiva = $objQuery->mysqlSelect("*","examination_ophthal_conjuctiva","conjuctiva_name='".$conjuctiva_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_conjuctiva)==0){ //To prevent double entry
						$insert_conjuctiva = $objQuery->mysqlInsert('examination_ophthal_conjuctiva',$arrFileds_conjuctiva,$arrValues_conjuctiva);
						$conjuctiva_id = mysql_insert_id(); //Get Id
					}
					else {
						$conjuctiva_id = $check_exists_conjuctiva[0]['conjuctiva_id'];
					}
				}
				else {
					$conjuctiva_id = $conjuctiva_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'conjuctiva';
				$arrValues[] = $conjuctiva_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($conjuctiva_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($conjuctiva_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_conjuctiva = $objQuery->mysqlSelect("conjuctiva_id","doc_patient_conjuctiva_active","conjuctiva='".$conjuctiva_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_conjuctiva)==0){ //To prevent double entry
					$insert_conjuctiva = $objQuery->mysqlInsert('doc_patient_conjuctiva_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_conjuctiva_active',"episode_id='".$episode_id."' and conjuctiva='".$conjuctiva_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_conjuctiva = $objQuery->mysqlInsert('doc_patient_conjuctiva_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations SCLERA */
		if(!empty($_POST['sclera_id'])) {
			while (list($key, $val) = each($_POST['sclera_id'])) {

				$sclera_id = $_POST['sclera_id'][$key];
				$sclera_name = $_POST['sclera_name'][$key];
				$sclera_docid = $_POST['sclera_docid'][$key];
				$sclera_doctype = $_POST['sclera_doctype'][$key];
				$sclera_leftEye = $_POST['sclera_leftEye'][$key];
				$sclera_rightEye = $_POST['sclera_rightEye'][$key];
				$sclera_userid = $_POST['sclera_userid'][$key];
				$sclera_loginType = $_POST['sclera_loginType'][$key];

				if($sclera_id == 0) {
					$arrFileds_sclera = array();
					$arrValues_sclera = array();

					$arrFileds_sclera[]='scelra_name';
					$arrValues_sclera[]=$sclera_name;
					$arrFileds_sclera[]='doc_id';
					$arrValues_sclera[]=$admin_id;
					$arrFileds_sclera[]='doc_type';
					$arrValues_sclera[]='1';
					
					if($sclera_rightEye == 1) {
						$arrFileds_sclera[]='right_eye';
						$arrValues_sclera[]='1';
						$arrFileds_sclera[]='eye_side';
						$arrValues_sclera[]="1";
					} 
					else if($sclera_leftEye == 2) {
						$arrFileds_sclera[]='left_eye';
						$arrValues_sclera[]='1';
						$arrFileds_sclera[]='eye_side';
						$arrValues_sclera[]="2";
					}
					
					$check_exists_sclera = $objQuery->mysqlSelect("*","examination_ophthal_sclera","scelra_name='".$sclera_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_sclera)==0){ //To prevent double entry
						$insert_sclera = $objQuery->mysqlInsert('examination_ophthal_sclera',$arrFileds_sclera,$arrValues_sclera);
						$sclera_id = mysql_insert_id(); //Get Id
					}
					else {
						$sclera_id = $check_exists_sclera[0]['sclera_id'];
					}
				}
				else {
					$sclera_id = $sclera_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'sclera';
				$arrValues[] = $sclera_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($sclera_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($sclera_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_sclera = $objQuery->mysqlSelect("sclera_id","doc_patient_sclera_active","sclera='".$sclera_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_sclera)==0){ //To prevent double entry
					$insert_sclera = $objQuery->mysqlInsert('doc_patient_sclera_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_sclera_active',"episode_id='".$episode_id."' and sclera='".$sclera_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_sclera = $objQuery->mysqlInsert('doc_patient_sclera_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations CORNEA ANTERIOR SURFACE */
		if(!empty($_POST['cornea_anterior_id'])) {
			while (list($key, $val) = each($_POST['cornea_anterior_id'])) {

				$cornea_anterior_id = $_POST['cornea_anterior_id'][$key];
				$cornea_anterior_name = $_POST['cornea_anterior_name'][$key];
				$cornea_anterior_docid = $_POST['cornea_anterior_docid'][$key];
				$cornea_anterior_doctype = $_POST['cornea_anterior_doctype'][$key];
				$cornea_anterior_leftEye = $_POST['cornea_anterior_leftEye'][$key];
				$cornea_anterior_rightEye = $_POST['cornea_anterior_rightEye'][$key];
				$cornea_anterior_userid = $_POST['cornea_anterior_userid'][$key];
				$cornea_anterior_loginType = $_POST['cornea_anterior_loginType'][$key];

				if($cornea_anterior_id == 0) {
					$arrFileds_ant = array();
					$arrValues_ant = array();

					$arrFileds_ant[]='cornea_ant_name';
					$arrValues_ant[]=$cornea_anterior_name;
					$arrFileds_ant[]='doc_id';
					$arrValues_ant[]=$admin_id;
					$arrFileds_ant[]='doc_type';
					$arrValues_ant[]='1';
					
					if($cornea_anterior_rightEye == 1) {
						$arrFileds_ant[]='right_eye';
						$arrValues_ant[]='1';
						$arrFileds_ant[]='eye_side';
						$arrValues_ant[]="1";
					} 
					else if($cornea_anterior_leftEye == 2) {
						$arrFileds_ant[]='left_eye';
						$arrValues_ant[]='1';
						$arrFileds_ant[]='eye_side';
						$arrValues_ant[]="2";
					}
					
					$check_exists_ant = $objQuery->mysqlSelect("*","examination_ophthal_cornea_anterior","cornea_ant_name='".$cornea_anterior_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_ant)==0){ //To prevent double entry
						$insert_ant = $objQuery->mysqlInsert('examination_ophthal_cornea_anterior',$arrFileds_ant,$arrValues_ant);
						$cornea_anterior_id = mysql_insert_id(); //Get Id
					}
					else {
						$cornea_anterior_id = $check_exists_ant[0]['cornea_ant_id'];
					}
				}
				else {
					$cornea_anterior_id = $cornea_anterior_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'cornea_ant';
				$arrValues[] = $cornea_anterior_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($cornea_anterior_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($cornea_anterior_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_ant = $objQuery->mysqlSelect("cornea_ant_id","doc_patient_cornea_ant_active","cornea_ant='".$cornea_anterior_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_ant)==0){ //To prevent double entry
					$insert_ant = $objQuery->mysqlInsert('doc_patient_cornea_ant_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_cornea_ant_active',"episode_id='".$episode_id."' and cornea_ant='".$cornea_anterior_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_ant = $objQuery->mysqlInsert('doc_patient_cornea_ant_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations CORNEA POSTERIOR SURFACE */
		if(!empty($_POST['cornea_posterior_id'])) {
			while (list($key, $val) = each($_POST['cornea_posterior_id'])) {

				$cornea_posterior_id = $_POST['cornea_posterior_id'][$key];
				$cornea_posterior_name = $_POST['cornea_posterior_name'][$key];
				$cornea_posterior_docid = $_POST['cornea_posterior_docid'][$key];
				$cornea_posterior_doctype = $_POST['cornea_posterior_doctype'][$key];
				$cornea_posterior_leftEye = $_POST['cornea_posterior_leftEye'][$key];
				$cornea_posterior_rightEye = $_POST['cornea_posterior_rightEye'][$key];
				$cornea_posterior_userid = $_POST['cornea_posterior_userid'][$key];
				$cornea_posterior_loginType = $_POST['cornea_posterior_loginType'][$key];

				if($cornea_posterior_id == 0) {
					$arrFileds_post = array();
					$arrValues_post = array();

					$arrFileds_post[]='cornea_post_name';
					$arrValues_post[]=$cornea_posterior_name;
					$arrFileds_post[]='doc_id';
					$arrValues_post[]=$admin_id;
					$arrFileds_post[]='doc_type';
					$arrValues_post[]='1';
					
					if($cornea_posterior_rightEye == 1) {
						$arrFileds_post[]='right_eye';
						$arrValues_post[]='1';
						$arrFileds_post[]='eye_side';
						$arrValues_post[]="1";
					} 
					else if($cornea_posterior_leftEye == 2) {
						$arrFileds_post[]='left_eye';
						$arrValues_post[]='1';
						$arrFileds_post[]='eye_side';
						$arrValues_post[]="2";
					}
					
					$check_exists_post = $objQuery->mysqlSelect("*","examination_ophthal_cornea_posterior","cornea_post_name='".$cornea_posterior_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_post)==0){ //To prevent double entry
						$insert_post = $objQuery->mysqlInsert('examination_ophthal_cornea_posterior',$arrFileds_post,$arrValues_post);
						$cornea_posterior_id = mysql_insert_id(); //Get Id
					}
					else {
						$cornea_posterior_id = $check_exists_post[0]['cornea_post_id'];
					}
				}
				else {
					$cornea_posterior_id = $cornea_posterior_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'cornea_post';
				$arrValues[] = $cornea_posterior_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($cornea_posterior_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($cornea_posterior_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_post = $objQuery->mysqlSelect("cornea_ant_id","doc_patient_cornea_post_active","cornea_post='".$cornea_posterior_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_post)==0){ //To prevent double entry
					$insert_post = $objQuery->mysqlInsert('doc_patient_cornea_post_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_cornea_post_active',"episode_id='".$episode_id."' and cornea_post='".$cornea_posterior_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_post = $objQuery->mysqlInsert('doc_patient_cornea_post_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations ANTERIOR CHAMBER */
		if(!empty($_POST['anterior_chamber_id'])) {
			while (list($key, $val) = each($_POST['anterior_chamber_id'])) {

				$anterior_chamber_id = $_POST['anterior_chamber_id'][$key];
				$anterior_chamber_name = $_POST['anterior_chamber_name'][$key];
				$anterior_chamber_docid = $_POST['anterior_chamber_docid'][$key];
				$anterior_chamber_doctype = $_POST['anterior_chamber_doctype'][$key];
				$anterior_chamber_leftEye = $_POST['anterior_chamber_leftEye'][$key];
				$anterior_chamber_rightEye = $_POST['anterior_chamber_rightEye'][$key];
				$anterior_chamber_userid = $_POST['anterior_chamber_userid'][$key];
				$anterior_chamber_loginType = $_POST['anterior_chamber_loginType'][$key];

				if($anterior_chamber_id == 0) {
					$arrFileds_chamber = array();
					$arrValues_chamber = array();

					$arrFileds_chamber[]='chamber_name';
					$arrValues_chamber[]=$anterior_chamber_name;
					$arrFileds_chamber[]='doc_id';
					$arrValues_chamber[]=$admin_id;
					$arrFileds_chamber[]='doc_type';
					$arrValues_chamber[]='1';
					
					if($anterior_chamber_rightEye == 1) {
						$arrFileds_chamber[]='right_eye';
						$arrValues_chamber[]='1';
						$arrFileds_chamber[]='eye_side';
						$arrValues_chamber[]="1";
					} 
					else if($anterior_chamber_leftEye == 2) {
						$arrFileds_chamber[]='left_eye';
						$arrValues_chamber[]='1';
						$arrFileds_chamber[]='eye_side';
						$arrValues_chamber[]="2";
					}
					
					$check_exists_chamber = $objQuery->mysqlSelect("*","examination_ophthal_chamber","chamber_name='".$anterior_chamber_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_chamber)==0){ //To prevent double entry
						$insert_chamber = $objQuery->mysqlInsert('examination_ophthal_chamber',$arrFileds_chamber,$arrValues_chamber);
						$anterior_chamber_id = mysql_insert_id(); //Get Id
					}
					else {
						$anterior_chamber_id = $check_exists_chamber[0]['chamber_id'];
					}
				}
				else {
					$anterior_chamber_id = $anterior_chamber_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'chamber';
				$arrValues[] = $anterior_chamber_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($anterior_chamber_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($anterior_chamber_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_chamber = $objQuery->mysqlSelect("chamber_id","doc_patient_anterior_chamber_active","chamber='".$anterior_chamber_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_chamber)==0){ //To prevent double entry
					$insert_chamber = $objQuery->mysqlInsert('doc_patient_anterior_chamber_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_anterior_chamber_active',"episode_id='".$episode_id."' and chamber='".$anterior_chamber_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_chamber = $objQuery->mysqlInsert('doc_patient_anterior_chamber_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations IRIS */
		if(!empty($_POST['iris_id'])) {
			while (list($key, $val) = each($_POST['iris_id'])) {

				$iris_id = $_POST['iris_id'][$key];
				$iris_name = $_POST['iris_name'][$key];
				$iris_docid = $_POST['iris_docid'][$key];
				$iris_doctype = $_POST['iris_doctype'][$key];
				$iris_leftEye = $_POST['iris_leftEye'][$key];
				$iris_rightEye = $_POST['iris_rightEye'][$key];
				$iris_userid = $_POST['iris_userid'][$key];
				$iris_loginType = $_POST['iris_loginType'][$key];

				if($iris_id == 0) {
					$arrFileds_iris = array();
					$arrValues_iris = array();

					$arrFileds_iris[]='iris_name';
					$arrValues_iris[]=$iris_name;
					$arrFileds_iris[]='doc_id';
					$arrValues_iris[]=$admin_id;
					$arrFileds_iris[]='doc_type';
					$arrValues_iris[]='1';
					
					if($iris_rightEye == 1) {
						$arrFileds_iris[]='right_eye';
						$arrValues_iris[]='1';
						$arrFileds_iris[]='eye_side';
						$arrValues_iris[]="1";
					} 
					else if($iris_leftEye == 2) {
						$arrFileds_iris[]='left_eye';
						$arrValues_iris[]='1';
						$arrFileds_iris[]='eye_side';
						$arrValues_iris[]="2";
					}
					
					$check_exists_iris = $objQuery->mysqlSelect("*","examination_ophthal_iris","iris_name='".$iris_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_iris)==0){ //To prevent double entry
						$insert_chamber = $objQuery->mysqlInsert('examination_ophthal_iris',$arrFileds_iris,$arrValues_iris);
						$iris_id = mysql_insert_id(); //Get Id
					}
					else {
						$iris_id = $check_exists_iris[0]['iris_id'];
					}
				}
				else {
					$iris_id = $iris_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'iris';
				$arrValues[] = $iris_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($iris_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($iris_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_iris = $objQuery->mysqlSelect("iris_id","doc_patient_iris_active","iris='".$iris_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_iris)==0){ //To prevent double entry
					$insert_iris = $objQuery->mysqlInsert('doc_patient_iris_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_iris_active',"episode_id='".$episode_id."' and iris='".$iris_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_iris = $objQuery->mysqlInsert('doc_patient_iris_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations PUPIL */
		if(!empty($_POST['pupil_id'])) {
			while (list($key, $val) = each($_POST['pupil_id'])) {

				$pupil_id = $_POST['pupil_id'][$key];
				$pupil_name = $_POST['pupil_name'][$key];
				$pupil_docid = $_POST['pupil_docid'][$key];
				$pupil_doctype = $_POST['pupil_doctype'][$key];
				$pupil_leftEye = $_POST['pupil_leftEye'][$key];
				$pupil_rightEye = $_POST['pupil_rightEye'][$key];
				$pupil_userid = $_POST['pupil_userid'][$key];
				$pupil_loginType = $_POST['pupil_loginType'][$key];

				if($pupil_id == 0) {
					$arrFileds_pupil = array();
					$arrValues_pupil = array();

					$arrFileds_pupil[]='pupil_name';
					$arrValues_pupil[]=$pupil_name;
					$arrFileds_pupil[]='doc_id';
					$arrValues_pupil[]=$admin_id;
					$arrFileds_pupil[]='doc_type';
					$arrValues_pupil[]='1';
					
					if($pupil_rightEye == 1) {
						$arrFileds_pupil[]='right_eye';
						$arrValues_pupil[]='1';
						$arrFileds_pupil[]='eye_side';
						$arrValues_pupil[]="1";
					} 
					else if($pupil_leftEye == 2) {
						$arrFileds_pupil[]='left_eye';
						$arrValues_pupil[]='1';
						$arrFileds_pupil[]='eye_side';
						$arrValues_pupil[]="2";
					}
					
					$check_exists_pupil = $objQuery->mysqlSelect("*","examination_ophthal_pupil","pupil_name='".$pupil_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_pupil)==0){ //To prevent double entry
						$insert_pupil = $objQuery->mysqlInsert('examination_ophthal_pupil',$arrFileds_pupil,$arrValues_pupil);
						$pupil_id = mysql_insert_id(); //Get Id
					}
					else {
						$pupil_id = $check_exists_pupil[0]['pupil_id'];
					}
				}
				else {
					$pupil_id = $pupil_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'pupil';
				$arrValues[] = $pupil_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($pupil_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($pupil_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_pupil = $objQuery->mysqlSelect("pupil_id","doc_patient_pupil_active","pupil='".$pupil_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_pupil)==0){ //To prevent double entry
					$insert_pupil = $objQuery->mysqlInsert('doc_patient_pupil_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_pupil_active',"episode_id='".$episode_id."' and pupil='".$pupil_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_pupil = $objQuery->mysqlInsert('doc_patient_pupil_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations ANGLE OF ANTERIOR CHAMBER */
		if(!empty($_POST['angle_id'])) {
			while (list($key, $val) = each($_POST['angle_id'])) {

				$angle_id = $_POST['angle_id'][$key];
				$angle_name = $_POST['angle_name'][$key];
				$angle_docid = $_POST['angle_docid'][$key];
				$angle_doctype = $_POST['angle_doctype'][$key];
				$angle_leftEye = $_POST['angle_leftEye'][$key];
				$angle_rightEye = $_POST['angle_rightEye'][$key];
				$angle_userid = $_POST['angle_userid'][$key];
				$angle_loginType = $_POST['angle_loginType'][$key];

				if($angle_id == 0) {
					$arrFileds_angle = array();
					$arrValues_angle = array();

					$arrFileds_angle[]='angle_name';
					$arrValues_angle[]=$angle_name;
					$arrFileds_angle[]='doc_id';
					$arrValues_angle[]=$admin_id;
					$arrFileds_angle[]='doc_type';
					$arrValues_angle[]='1';
					
					if($angle_rightEye == 1) {
						$arrFileds_angle[]='right_eye';
						$arrValues_angle[]='1';
						$arrFileds_angle[]='eye_side';
						$arrValues_angle[]="1";
					} 
					else if($angle_leftEye == 2) {
						$arrFileds_angle[]='left_eye';
						$arrValues_angle[]='1';
						$arrFileds_angle[]='eye_side';
						$arrValues_angle[]="2";
					}
					
					$check_exists_angle = $objQuery->mysqlSelect("*","examination_ophthal_angle","angle_name='".$angle_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_angle)==0){ //To prevent double entry
						$insert_angle = $objQuery->mysqlInsert('examination_ophthal_angle',$arrFileds_angle,$arrValues_angle);
						$angle_id = mysql_insert_id(); //Get Id
					}
					else {
						$angle_id = $check_exists_angle[0]['angle_id'];
					}
				}
				else {
					$angle_id = $angle_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'angle';
				$arrValues[] = $angle_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($angle_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($angle_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_angle = $objQuery->mysqlSelect("angle_id","doc_patient_angle_active","angle='".$angle_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_angle)==0){ //To prevent double entry
					$insert_angle = $objQuery->mysqlInsert('doc_patient_angle_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_angle_active',"episode_id='".$episode_id."' and angle='".$angle_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_angle = $objQuery->mysqlInsert('doc_patient_angle_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations LENS */
		if(!empty($_POST['lens_id'])) {
			while (list($key, $val) = each($_POST['lens_id'])) {

				$lens_id = $_POST['lens_id'][$key];
				$lens_name = $_POST['lens_name'][$key];
				$lens_docid = $_POST['lens_docid'][$key];
				$lens_doctype = $_POST['lens_doctype'][$key];
				$lens_leftEye = $_POST['lens_leftEye'][$key];
				$lens_rightEye = $_POST['lens_rightEye'][$key];
				$lens_userid = $_POST['lens_userid'][$key];
				$lens_loginType = $_POST['lens_loginType'][$key];

				if($lens_id == 0) {
					$arrFileds_lens = array();
					$arrValues_lens = array();

					$arrFileds_lens[]='lens_name';
					$arrValues_lens[]=$lens_name;
					$arrFileds_lens[]='doc_id';
					$arrValues_lens[]=$admin_id;
					$arrFileds_lens[]='doc_type';
					$arrValues_lens[]='1';
					
					if($lens_rightEye == 1) {
						$arrFileds_lens[]='right_eye';
						$arrValues_lens[]='1';
						$arrFileds_lens[]='eye_side';
						$arrValues_lens[]="1";
					} 
					else if($lens_leftEye == 2) {
						$arrFileds_lens[]='left_eye';
						$arrValues_lens[]='1';
						$arrFileds_lens[]='eye_side';
						$arrValues_lens[]="2";
					}
					
					$check_exists_lens = $objQuery->mysqlSelect("*","examination_ophthal_lens","lens_name='".$lens_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_lens)==0){ //To prevent double entry
						$insert_lens = $objQuery->mysqlInsert('examination_ophthal_lens',$arrFileds_lens,$arrValues_lens);
						$lens_id = mysql_insert_id(); //Get Id
					}
					else {
						$lens_id = $check_exists_lens[0]['lens_id'];
					}
				}
				else {
					$lens_id = $lens_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'lens';
				$arrValues[] = $lens_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($lens_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($lens_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_lens = $objQuery->mysqlSelect("lens_id","doc_patient_lens_active","lens='".$lens_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_lens)==0){ //To prevent double entry
					$insert_lens = $objQuery->mysqlInsert('doc_patient_lens_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_lens_active',"episode_id='".$episode_id."' and lens='".$lens_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_lens = $objQuery->mysqlInsert('doc_patient_lens_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations VITEROUS */
		if(!empty($_POST['viterous_id'])) {
			while (list($key, $val) = each($_POST['viterous_id'])) {

				$viterous_id = $_POST['viterous_id'][$key];
				$viterous_name = $_POST['viterous_name'][$key];
				$viterous_docid = $_POST['viterous_docid'][$key];
				$viterous_doctype = $_POST['viterous_doctype'][$key];
				$viterous_leftEye = $_POST['viterous_leftEye'][$key];
				$viterous_rightEye = $_POST['viterous_rightEye'][$key];
				$viterous_userid = $_POST['viterous_userid'][$key];
				$viterous_loginType = $_POST['viterous_loginType'][$key];

				if($viterous_id == 0) {
					$arrFileds_viterous = array();
					$arrValues_viterous = array();

					$arrFileds_viterous[]='viterous_name';
					$arrValues_viterous[]=$viterous_name;
					$arrFileds_viterous[]='doc_id';
					$arrValues_viterous[]=$admin_id;
					$arrFileds_viterous[]='doc_type';
					$arrValues_viterous[]='1';
					
					if($viterous_rightEye == 1) {
						$arrFileds_viterous[]='right_eye';
						$arrValues_viterous[]='1';
						$arrFileds_viterous[]='eye_side';
						$arrValues_viterous[]="1";
					} 
					else if($viterous_leftEye == 2) {
						$arrFileds_viterous[]='left_eye';
						$arrValues_viterous[]='1';
						$arrFileds_viterous[]='eye_side';
						$arrValues_viterous[]="2";
					}
					
					$check_exists_viterous = $objQuery->mysqlSelect("*","examination_ophthal_viterous","viterous_name='".$viterous_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_viterous)==0){ //To prevent double entry
						$insert_viterous = $objQuery->mysqlInsert('examination_ophthal_viterous',$arrFileds_viterous,$arrValues_viterous);
						$viterous_id = mysql_insert_id(); //Get Id
					}
					else {
						$viterous_id = $check_exists_viterous[0]['viterous_id'];
					}
				}
				else {
					$viterous_id = $viterous_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'viterous';
				$arrValues[] = $viterous_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($viterous_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($viterous_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_viterous = $objQuery->mysqlSelect("viterous_id","doc_patient_viterous_active","viterous='".$viterous_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_viterous)==0){ //To prevent double entry
					$insert_viterous = $objQuery->mysqlInsert('doc_patient_viterous_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_viterous_active',"episode_id='".$episode_id."' and viterous='".$viterous_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_viterous = $objQuery->mysqlInsert('doc_patient_viterous_active',$arrFileds,$arrValues);
				}
			}
		}
		
		/* Add Examinations FUNDUS */
		if(!empty($_POST['fundus_id'])) {
			while (list($key, $val) = each($_POST['fundus_id'])) {

				$fundus_id = $_POST['fundus_id'][$key];
				$fundus_name = $_POST['fundus_name'][$key];
				$fundus_docid = $_POST['fundus_docid'][$key];
				$fundus_doctype = $_POST['fundus_doctype'][$key];
				$fundus_leftEye = $_POST['fundus_leftEye'][$key];
				$fundus_rightEye = $_POST['fundus_rightEye'][$key];
				$fundus_userid = $_POST['fundus_userid'][$key];
				$fundus_loginType = $_POST['fundus_loginType'][$key];

				if($fundus_id == 0) {
					$arrFileds_fundus = array();
					$arrValues_fundus = array();

					$arrFileds_fundus[]='fundus_name';
					$arrValues_fundus[]=$fundus_name;
					$arrFileds_fundus[]='doc_id';
					$arrValues_fundus[]=$admin_id;
					$arrFileds_fundus[]='doc_type';
					$arrValues_fundus[]='1';
					
					if($fundus_rightEye == 1) {
						$arrFileds_fundus[]='right_eye';
						$arrValues_fundus[]='1';
						$arrFileds_fundus[]='eye_side';
						$arrValues_fundus[]="1";
					} 
					else if($fundus_leftEye == 2) {
						$arrFileds_fundus[]='left_eye';
						$arrValues_fundus[]='1';
						$arrFileds_fundus[]='eye_side';
						$arrValues_fundus[]="2";
					}
					
					$check_exists_fundus = $objQuery->mysqlSelect("*","examination_ophthal_fundus","fundus_name='".$fundus_name."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
					if(COUNT($check_exists_fundus)==0){ //To prevent double entry
						$insert_fundus = $objQuery->mysqlInsert('examination_ophthal_fundus',$arrFileds_fundus,$arrValues_fundus);
						$fundus_id = mysql_insert_id(); //Get Id
					}
					else {
						$fundus_id = $check_exists_fundus[0]['fundus_id'];
					}
				}
				else {
					$fundus_id = $fundus_id;
				}

				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[] = 'fundus';
				$arrValues[] = $fundus_id;
									
				$arrFileds[] = 'patient_id';
				$arrValues[] = $patient_id;
				
				$arrFileds[] = 'doc_id';
				$arrValues[] = $admin_id;
				
				$arrFileds[] = 'doc_type';
				$arrValues[] = "1";
				
				$arrFileds[] = 'status';
				$arrValues[] = "0";
				$arrFileds[] = 'episode_id';
				$arrValues[] = $episode_id;
				
				if($fundus_rightEye == 1) {
					$arrFileds[]='eye_side';
					$arrValues[]="1";
					$eye_side = "1";
				} else if($fundus_leftEye == 2) {
					$arrFileds[]='eye_side';
					$arrValues[]="2";
					$eye_side = "2";
				}
	
				$check_active_fundus = $objQuery->mysqlSelect("fundus_id","doc_patient_fundus_active","fundus='".$fundus_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'and episode_id='".$episode_id."'","","","","");
				if(COUNT($check_active_fundus)==0){ //To prevent double entry
					$insert_fundus = $objQuery->mysqlInsert('doc_patient_fundus_active',$arrFileds,$arrValues);
				}
				else {
					$objQuery->mysqlDelete('doc_patient_fundus_active',"episode_id='".$episode_id."' and fundus='".$fundus_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$eye_side."' and patient_id='".$patient_id."'");
					$insert_fundus = $objQuery->mysqlInsert('doc_patient_fundus_active',$arrFileds,$arrValues);
				}
			}
		}
		
		
		/* Add Diagnostic ICD Codes */
		if(!empty($_POST['diagno_icdID'])) {
			while (list($key, $val) = each($_POST['diagno_icdID'])) {
				
				$diagno_icd_id = $_POST['diagno_icdID'][$key];
				$diagno_icd_name = $_POST['diagno_icdName'][$key];
				$diagno_docid = $_POST['diagno_docID'][$key];
				$diagno_doctype = $_POST['diagno_doctype'][$key];
				
				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[]='icd_id';
				$arrValues[]=$diagno_icd_id;	
				
				$arrFileds[]='patient_id';
				$arrValues[]=$patient_id;
				
				$arrFileds[]='episode_id';
				$arrValues[]=$episode_id;
				
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
				
				$arrFileds[]='doc_type';
				$arrValues[]="1";
				
				$arrFileds[]='status';
				$arrValues[]="0";
				
				$insert_temp_icd_value=$objQuery->mysqlInsert('patient_diagnosis',$arrFileds,$arrValues);
				
				$check_diagnosis = $objQuery->mysqlSelect("*","doctor_frequent_diagnosis","icd_id='".$diagno_icd_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_diagnosis[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDIAGNO = array();
					$arrValuesDIAGNO = array();
					if(count($check_diagnosis)>0){
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO,"dfd_id = '".$check_diagnosis[0]['dfd_id']."'");
					}
					else{
						$arrFieldsDIAGNO[] = 'icd_id';
						$arrValuesDIAGNO[] = $diagno_icd_id;
						$arrFieldsDIAGNO[] = 'doc_id';
						$arrValuesDIAGNO[] = $admin_id;
						$arrFieldsDIAGNO[] = 'doc_type';
						$arrValuesDIAGNO[] = "1";
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO);
					}
			}
		}
		
		/* Add Treatment */
		if(!empty($_POST['treatment_id'])) {
			
			while (list($key, $val) = each($_POST['treatment_id'])) {
				
				$treatment_id = $_POST['treatment_id'][$key];
				$treatment_name = $_POST['treatment_name'][$key];
				$treatment_docid = $_POST['treatment_docid'][$key];
				$treatment_doctype = $_POST['treatment_doctype'][$key];
				
				if($treatment_id == 0) {
					$arrFileds_treat = array();
					$arrValues_treat = array();
					
					$arrFileds_treat[]='treatment';
					$arrValues_treat[]=$treatment_name;
					$arrFileds_treat[]='doc_id';
					$arrValues_treat[]=$admin_id;
					$arrFileds_treat[]='doc_type';
					$arrValues_treat[]='1';
					
					$insert_treatment=$objQuery->mysqlInsert('doctor_frequent_treatment',$arrFileds_treat,$arrValues_treat);
					$treat_id = mysql_insert_id(); //Get Patient Id
				}
				else {
					$treat_id = $treatment_id;
				}
				
				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[]='dft_id';
				$arrValues[]=$treat_id;
									
				$arrFileds[]='patient_id';
				$arrValues[]=$patient_id;
				
				$arrFileds[]='episode_id';
				$arrValues[]=$episode_id;
				
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
				
				$arrFileds[]='doc_type';
				$arrValues[]="1";
				
				$arrFileds[]='status';
				$arrValues[]="0";
				
				$insert_symptoms=$objQuery->mysqlInsert('doc_patient_treatment_active',$arrFileds,$arrValues);
				
				$check_treat = $objQuery->mysqlSelect("*","doctor_frequent_treatment","dft_id='".$treat_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_treat[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_treat)>0){
						$arrFieldsTREATFREQ[] = 'freq_count';
						$arrValuesTREATFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_treatment',$arrFieldsTREATFREQ,$arrValuesTREATFREQ,"dft_id = '".$check_treat[0]['dft_id']."'");
					}		
			}
			
		}
		
		/* Add Prescriptions */
		if(!empty($_POST['prescription_ppID']))  {
			while (list($key, $val) = each($_POST['prescription_ppID'])) {
				
				$presc_pp_id = $_POST['prescription_ppID'][$key];
				$presc_trade_name = $_POST['prescription_tradeName'][$key];
				$presc_generic_id = $_POST['prescription_genericID'][$key];
				$presc_generic_name = $_POST['prescription_genericName'][$key];
				$presc_dosage = $_POST['prescription_dosage'][$key];
				$presc_timings = $_POST['prescription_timings'][$key];
				$presc_duration = $_POST['prescription_duration'][$key];
				$presc_morning = $_POST['prescription_morning'][$key];
				$presc_afternoon = $_POST['prescription_afternoon'][$key];
				$presc_night = $_POST['prescription_night'][$key];
				$presc_durationType = $_POST['prescription_duration_type'][$key];
				$presc_instructions = $_POST['prescription_instructions'][$key];	
				$prescription_date_time = $Cur_Date;
				
				if($presc_pp_id == 0) {
					$arrFileds_freq = array();
					$arrValues_freq = array();
					
					$arrFileds_freq[]='pp_id';
					$arrValues_freq[]=time();
					$arrFileds_freq[]='med_trade_name';
					$arrValues_freq[]=$presc_trade_name;
					$arrFileds_freq[]='med_generic_name';
					$arrValues_freq[]=$presc_generic_name;
					$arrFileds_freq[]='med_frequency';
					$arrValues_freq[]=$presc_dosage;
					$arrFileds_freq[]='med_timing';
					$arrValues_freq[]=$presc_timings;
					$arrFileds_freq[]='med_duration';
					$arrValues_freq[]=$presc_duration;
					$arrFileds_freq[]='doc_id';
					$arrValues_freq[]=$admin_id;
					$arrFileds_freq[]='doc_type';
					$arrValues_freq[]="1";
					$arrFileds_freq[]='freq_count';
					$arrValues_freq[]="1";
					$arrFileds_freq[]='med_frequency_morning';
					$arrValues_freq[]=$presc_morning;
					$arrFileds_freq[]='med_frequency_noon';
					$arrValues_freq[]=$presc_afternoon;
					$arrFileds_freq[]='med_frequency_night';
					$arrValues_freq[]=$presc_night;
					$arrFileds_freq[]='med_duration_type';
					$arrValues_freq[]=$presc_durationType;
					$arrFileds_freq[]='prescription_instruction';
					$arrValues_freq[]=$presc_instructions;
					$insert_medicine=$objQuery->mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
					$freq_id = mysql_insert_id(); //Get Frequent Medicine Id
					$get_ppid = time();
				}
				else {
					$get_ppid = $presc_pp_id;
					$chkProduct= $objQuery->mysqlSelect("*","doctor_frequent_medicine","pp_id='".$presc_pp_id."'","","","","");
					
					$arrFileds_freq = array();
					$arrValues_freq = array();
					if($chkProduct == true)
					{
						$freq_count=$chkProduct[0]['freq_count']+1;
					
						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$presc_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$presc_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$presc_dosage;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$presc_timings;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$presc_duration;
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]=$freq_count;	
						$arrFileds_freq[]='med_frequency_morning';
						$arrValues_freq[]=$presc_morning;
						$arrFileds_freq[]='med_frequency_noon';
						$arrValues_freq[]=$presc_afternoon;
						$arrFileds_freq[]='med_frequency_night';
						$arrValues_freq[]=$presc_night;
						$arrFileds_freq[]='med_duration_type';
						$arrValues_freq[]=$presc_durationType;
						$arrFileds_freq[]='prescription_instruction';
						$arrValues_freq[]=$presc_instructions;	
					$update_medicine=$objQuery->mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."'");
	
					}
					else
					{
						$arrFileds_freq[]='pp_id';
						$arrValues_freq[]=$presc_pp_id;
						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$presc_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$presc_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$presc_dosage;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$presc_timings;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$presc_duration;
						$arrFileds_freq[]='doc_id';
						$arrValues_freq[]=$admin_id;
						$arrFileds_freq[]='doc_type';
						$arrValues_freq[]="1";
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]="1";
						$arrFileds_freq[]='med_frequency_morning';
						$arrValues_freq[]=$presc_morning;
						$arrFileds_freq[]='med_frequency_noon';
						$arrValues_freq[]=$presc_afternoon;
						$arrFileds_freq[]='med_frequency_night';
						$arrValues_freq[]=$presc_night;
						$arrFileds_freq[]='med_duration_type';
						$arrValues_freq[]=$presc_durationType;
						$arrFileds_freq[]='prescription_instruction';
						$arrValues_freq[]=$presc_instructions;

						$insert_medicine=$objQuery->mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
						
					}
				}
				
						$arrFieldsPEP = array();
						$arrValuesPEP = array();
						$arrFieldsPEP[] = 'episode_id';
						$arrValuesPEP[] = $episode_id;
						$arrFieldsPEP[] = 'doc_id';
						$arrValuesPEP[] = $admin_id;
						$arrFieldsPEP[] = 'pp_id';
						$arrValuesPEP[] = $get_ppid;
						$arrFieldsPEP[] = 'prescription_trade_name';
						$arrValuesPEP[] = $presc_trade_name;
						$arrFieldsPEP[] = 'prescription_generic_name';
						$arrValuesPEP[] = $presc_generic_name;
						$arrFieldsPEP[] = 'prescription_frequency';
						$arrValuesPEP[] = $presc_dosage;
						$arrFieldsPEP[] = 'timing';
						$arrValuesPEP[] = $presc_timings;
						$arrFieldsPEP[] = 'duration';
						$arrValuesPEP[] = $presc_duration;
						$arrFieldsPEP[] = 'prescription_date_time';
						$arrValuesPEP[] = $prescription_date_time;
						
						$arrFieldsPEP[]='med_frequency_morning';
						$arrValuesPEP[]=$presc_morning;
						$arrFieldsPEP[]='med_frequency_noon';
						$arrValuesPEP[]=$presc_afternoon;
						$arrFieldsPEP[]='med_frequency_night';
						$arrValuesPEP[]=$presc_night;
						$arrFieldsPEP[]='med_duration_type';
						$arrValuesPEP[]=$presc_durationType;
						$arrFieldsPEP[]='prescription_instruction';
						$arrValuesPEP[]=$presc_instructions;
						
						$insert_patient_episode_prescriptions = $objQuery->mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);
					
			}
		}
			
			$getFrequentComplaints= $objQuery->mysqlSelect("a.dfs_id as dfs_id, a.symptoms_id as symptoms_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.symptoms as symptoms","doctor_frequent_symptoms as a inner join chief_medical_complaints as b on a.symptoms_id = b.complaint_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");		
			$getFrequentInvestigation = $objQuery->mysqlSelect("a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department ","doctor_frequent_investigations as a left join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_test_count DESC","","","0,8");
			$getFrequentDiagnosis = $objQuery->mysqlSelect("a.dfd_id as dfd_id, a.icd_id as icd_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on b.icd_id = a.icd_id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_count DESC","","","0,8");
			
			$getFrequentTreatment = $objQuery->mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","freq_count DESC","","","8");
			$getFrequentMedicine = $objQuery->mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='1'","freq_count desc","","","0,8");
			
			$prev_episode = $objQuery->mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_id."'","b.episode_id desc","","","1");
			if(COUNT($prev_episode)>0) {
				$getPreviousPrescription = $objQuery->mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration, b.med_frequency_morning as med_frequency_morning, b.med_frequency_noon as med_frequency_noon, b.med_frequency_night as med_frequency_night, b.med_duration_type as med_duration_type, b.prescription_instruction as prescription_instruction","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","b.episode_id='".$prev_episode[0]['episode_id']."'","","","","");
			}
			
			$lids_list = $objQuery->mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","lids_name ASC","","","");			
			$conjuctiva_list = $objQuery->mysqlSelect("*","examination_ophthal_conjuctiva","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","conjuctiva_name ASC","","","");			
			$sclera_list = $objQuery->mysqlSelect("*","examination_ophthal_sclera","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","scelra_name ASC","","","");			
			$cornea_anterior_list = $objQuery->mysqlSelect("*","examination_ophthal_cornea_anterior","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","cornea_ant_name ASC","","","");			
			$cornea_posterior_list = $objQuery->mysqlSelect("*","examination_ophthal_cornea_posterior","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","cornea_post_name ASC","","","");			
			$anterior_chamber_list = $objQuery->mysqlSelect("*","examination_ophthal_chamber","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","chamber_name ASC","","","");			
			$iris_list = $objQuery->mysqlSelect("*","examination_ophthal_iris","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","iris_name ASC","","","");			
			$pupil_list = $objQuery->mysqlSelect("*","examination_ophthal_pupil","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","pupil_name ASC","","","");			
			$angle_list = $objQuery->mysqlSelect("*","examination_ophthal_angle","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","angle_name ASC","","","");			
			$lens_list = $objQuery->mysqlSelect("*","examination_ophthal_lens","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","lens_name ASC","","","");			
			$viterous_list = $objQuery->mysqlSelect("*","examination_ophthal_viterous","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","viterous_name ASC","","","");			
			$fundus_list = $objQuery->mysqlSelect("*","examination_ophthal_fundus","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","fundus_name ASC","","","");			
		

			$result = array("result" => "success","frequent_medcomp_details" => $getFrequentComplaints,"lids_details" => $lids_list,"conjuctiva_details" => $conjuctiva_list,"sclera_details" => $sclera_list,"cornea_anterior_details" => $cornea_anterior_list,"cornea_posterior_details" => $cornea_posterior_list,"anterior_chamber_details" => $anterior_chamber_list,"iris_details" => $iris_list,"pupil_details" => $pupil_list,"angle_details" => $angle_list,"lens_details" => $lens_list,"viterous_details" => $viterous_list,"fundus_details" => $fundus_list,"frequent_investigation_details" => $getFrequentInvestigation,"frequent_diagnosis_details" => $getFrequentDiagnosis,"frequent_treatment_details" => $getFrequentTreatment,"frequent_medicine_details" => $getFrequentMedicine,"repeat_precription_details" => $getPreviousPrescription);
			echo json_encode($result);
	}
	else {
			$success = array('result' => "failure");
			echo json_encode($success);
	}	
		
}


?>