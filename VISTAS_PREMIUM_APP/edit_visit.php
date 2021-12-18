<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));


$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

/*$postdata = $data;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);*/

$data = json_decode(file_get_contents('php://input'), true);
//echo $data['patient_id'];

/*if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) 
	{*/
		$admin_id = $doctor_id;
		$hospital_id = $data['hosp_id'];
		
		$patient_id = $data['patient_id'];
		$consultation_fees =  $data['consultation_fees'];
		$episode_id =  $data['episode_id'];
		$diagnosis_details =  $data['diagnosis_details'];
		$treatment_details =  $data['treatment_details'];
		$prescription_note =  $data['prescription_note'];
		$visit_entry_date =  $data['visit_entry_date'];
		$visit_chiefMedComplaint_sufferings =  $data['chiefMedComplaint_sufferings'];
		$chkExamSaveTemplate =  $data['examination_template_save'];
		$exam_template_name =  $data['examination_template_name'];
		$chkInvestSaveTemplate =  $data['investigation_template_save'];
		$invest_template_name =  $data['investigation_template_name'];
		$patient_education =  $data['patient_education'];

		$check_episode = mysqlSelect("*","doc_patient_episodes","episode_id='".$episode_id."' and admin_id='".$admin_id."' and patient_id='".$patient_id."'","","","","");
		$arrFieldsEpisode = array();
		$arrValuesEpisode = array();
		if(count($check_episode)>0){
				if(!empty($data['diagnosis_details'])) {
					$arrFieldsEpisode[] = 'diagnosis_details';
					$arrValuesEpisode[] = $diagnosis_details;
				}
				if(!empty($data['treatment_details'])) {
					$arrFieldsEpisode[] = 'treatment_details';
					$arrValuesEpisode[] = $treatment_details;
				}
				if(!empty($data['prescription_note'])) {
					$arrFieldsEpisode[] = 'prescription_note';
					$arrValuesEpisode[] = $prescription_note;
				}

				if(!empty($data['visit_entry_date'])) {
					$arrFieldsEpisode[] = 'date_time';
					$arrValuesEpisode[] = date('Y-m-d H:i:s',strtotime($data['visit_entry_date']));
				}
				
				if(!empty($data['chiefMedComplaint_sufferings'])) {
					$arrFieldsEpisode[] = 'episode_medical_complaint';
					$arrValuesEpisode[] = $visit_chiefMedComplaint_sufferings;
				}
			
				if(!empty($patient_education)) {
					$arrFieldsEpisode[] = 'patient_education';
					$arrValuesEpisode[] = $patient_education;
				}
				
				$update_episod=mysqlUpdate('doc_patient_episodes',$arrFieldsEpisode,$arrValuesEpisode,"episode_id='".$episode_id."' and admin_id='".$admin_id."' and patient_id='".$patient_id."'");
		}
	
		//Delete all Episode deatils
		mysqlDelete('doc_patient_symptoms_active',"episode_id='".$episode_id."' and doc_type='1'");
		mysqlDelete('patient_temp_investigation',"episode_id='".$episode_id."' and doc_type='1'"); 
		mysqlDelete('doc_patient_examination_active',"episode_id='".$episode_id."' and doc_type='1'"); 
		mysqlDelete('patient_diagnosis',"episode_id='".$episode_id."' and doc_type='1'");  
		mysqlDelete('doc_patient_treatment_active',"episode_id='".$episode_id."' and doc_type='1'");  
		mysqlDelete('doc_patient_episode_prescriptions',"episode_id='".$episode_id."' and doc_id='".$admin_id."'");
			
		/* Add Chief Medical Complaints */	
		if(!empty($data['chiefcomplaint_id'])) {
			while (list($key, $val) = each($data['chiefcomplaint_id'])) {
				
				$chief_Med_compID = $data['chiefcomplaint_id'][$key];
				$chief_Med_comp_name = $data['chiefcomplaint_name'][$key];
				$chief_Med_comp_docid = $data['chiefcomplaint_docid'][$key];
				$chief_Med_comp_doctype = $data['chiefcomplaint_doctype'][$key];
				
				if($chief_Med_compID == 0) {
					$arrFileds_symp = array();
					$arrValues_symp = array();
					
					$arrFileds_symp[]='symptoms';
					$arrValues_symp[]=$chief_Med_comp_name;
					$arrFileds_symp[]='doc_id';
					$arrValues_symp[]=$admin_id;
					$arrFileds_symp[]='doc_type';
					$arrValues_symp[]='1';
					
					$insert_symptoms=mysqlInsert('chief_medical_complaints',$arrFileds_symp,$arrValues_symp);
					$symp_id = $insert_symptoms; //Get Patient Id
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
				
				$insert_symptoms=mysqlInsert('doc_patient_symptoms_active',$arrFileds,$arrValues);
				
				$check_symp = mysqlSelect("*","doctor_frequent_symptoms","symptoms_id='".$symp_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_symp[0]['freq_count']+1; //Count will increment by one
				$arrFieldsSYMPFREQ = array();
				$arrValuesSYMPFREQ = array();
				if(count($check_symp)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"dfs_id = '".$check_symp[0]['dfs_id']."'");
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
						$insert_freq_symp=mysqlInsert('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);	
				}
			} 
		}

		/* Add Investigations */
		if(!empty($data['investigation_id']))	{	 
			while (list($key, $val) = each($data['investigation_id'])) {
				$investigation_id = $data['investigation_id'][$key];
				$test_id = $data['test_id'][$key];
				$group_test_id = $data['grouptest_id'][$key];
				$test_name = $data['test_name'][$key];
				$normal_range = $data['normalRange'][$key];
				$actual_value = $data['actualRange'][$key];
				$right_eye = $data['rightEyeRange'][$key];
				$left_eye = $data['leftEyeRange'][$key];
				$department = $data['departmentRange'][$key];
				
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
					
					$insert_new_val=mysqlInsert('patient_diagnosis_tests',$arrFileds_test,$arrValues_test);
					$invest_id = $insert_new_val;
					$getDiagnosisDetails= mysqlSelect("test_id,test_name_site_name","patient_diagnosis_tests","id='".$invest_id."'","","","","");	
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
				
				$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
				
				$check_invest = mysqlSelect("*","doctor_frequent_investigations","main_test_id='".$investigation_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_invest)>0){
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfi_id = '".$check_invest[0]['dfi_id']."'");
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
						$insert_freq_symp=mysqlInsert('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
					}
			}
		}
			
		/* Add Examinations */
		if(!empty($data['examination_id'])) {
			while (list($key, $val) = each($data['examination_id'])) {
				
				$examination_id = $data['examination_id'][$key];
				$examination_name = $data['examination_name'][$key];
				$examination_results = $data['examination_results'][$key];
				$examination_findings = $data['examination_findings'][$key];
				$examination_docid = $data['examination_docid'][$key];
				$examination_doctype = $data['examination_doctype'][$key];
				
				if($examination_id == 0) {
					$arrFileds_exam = array();
					$arrValues_exam = array();
					
					$arrFileds_exam[]='examination';
					$arrValues_exam[]=$examination_name;
					$arrFileds_exam[]='doc_id';
					$arrValues_exam[]=$admin_id;
					$arrFileds_exam[]='doc_type';
					$arrValues_exam[]='1';
					
					$insert_symptoms=mysqlInsert('examination',$arrFileds_exam,$arrValues_exam);
					$exam_id = $insert_symptoms; //Get Patient Id
				}
				else {
					$exam_id = $examination_id;
				}
				
				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[]='examination';
				$arrValues[]=$exam_id;
				
				$arrFileds[]='exam_result';
				$arrValues[]=$examination_results;
				
				$arrFileds[]='findings';
				$arrValues[]=$examination_findings;
									
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
				
				$insert_symptoms=mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
				$check_exam = mysqlSelect("*","doctor_frequent_examination","dfe_id='".$exam_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_exam[0]['freq_count']+1; //Count will increment by one
					$arrFieldsEXAMFREQ = array();
					$arrValuesEXAMFREQ = array();
					if(count($check_exam)>0){
						$arrFieldsEXAMFREQ[] = 'freq_count';
						$arrValuesEXAMFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_examination',$arrFieldsEXAMFREQ,$arrValuesEXAMFREQ,"dfe_id = '".$check_exam[0]['dfe_id']."'");
					}
					else{
						$arrFieldsEXAMFREQ[] = 'examination_id';
						$arrValuesEXAMFREQ[] = $exam_id;
						$arrFieldsEXAMFREQ[] = 'doc_id';
						$arrValuesEXAMFREQ[] = $admin_id;
						$arrFieldsEXAMFREQ[] = 'doc_type';
						$arrValuesEXAMFREQ[] = "1";
						$arrFieldsEXAMFREQ[] = 'freq_count';
						$arrValuesEXAMFREQ[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_examination',$arrFieldsEXAMFREQ,$arrValuesEXAMFREQ);	
				}
			}
		}
		
		/* Add Diagnostic ICD Codes */
		if(!empty($data['diagno_icdID'])) {
			while (list($key, $val) = each($data['diagno_icdID'])) {
				
				$diagno_icd_id = $data['diagno_icdID'][$key];
				$diagno_icd_name = $data['diagno_icdName'][$key];
				$diagno_docid = $data['diagno_docID'][$key];
				$diagno_doctype = $data['diagno_doctype'][$key];
				
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
				
				$insert_temp_icd_value=mysqlInsert('patient_diagnosis',$arrFileds,$arrValues);
				
				$check_diagnosis = mysqlSelect("*","doctor_frequent_diagnosis","icd_id='".$diagno_icd_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_diagnosis[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDIAGNO = array();
					$arrValuesDIAGNO = array();
					if(count($check_diagnosis)>0){
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO,"dfd_id = '".$check_diagnosis[0]['dfd_id']."'");
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
						$insert_freq_symp=mysqlInsert('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO);
					}
			}
		}
		
		/* Add Treatment */
		if(!empty($data['treatment_id'])) {
			
			while (list($key, $val) = each($data['treatment_id'])) {
				
				$treatment_id = $data['treatment_id'][$key];
				$treatment_name = $data['treatment_name'][$key];
				$treatment_docid = $data['treatment_docid'][$key];
				$treatment_doctype = $data['treatment_doctype'][$key];
				
				if($treatment_id == 0) {
					$arrFileds_treat = array();
					$arrValues_treat = array();
					
					$arrFileds_treat[]='treatment';
					$arrValues_treat[]=$treatment_name;
					$arrFileds_treat[]='doc_id';
					$arrValues_treat[]=$admin_id;
					$arrFileds_treat[]='doc_type';
					$arrValues_treat[]='1';
					
					$insert_treatment=mysqlInsert('doctor_frequent_treatment',$arrFileds_treat,$arrValues_treat);
					$treat_id = $insert_treatment; //Get Patient Id
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
				
				$insert_symptoms=mysqlInsert('doc_patient_treatment_active',$arrFileds,$arrValues);
				
				$check_treat = mysqlSelect("*","doctor_frequent_treatment","dft_id='".$treat_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_treat[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_treat)>0){
						$arrFieldsTREATFREQ[] = 'freq_count';
						$arrValuesTREATFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_treatment',$arrFieldsTREATFREQ,$arrValuesTREATFREQ,"dft_id = '".$check_treat[0]['dft_id']."'");
					}		
			}
			
		}
		
		/* Add Prescriptions */
		if(!empty($data['prescription_ppID']))  {
			while (list($key, $val) = each($data['prescription_ppID'])) {
				
				$presc_pp_id = $data['prescription_ppID'][$key];
				$presc_trade_name = $data['prescription_tradeName'][$key];
				$presc_generic_id = $data['prescription_genericID'][$key];
				$presc_generic_name = $data['prescription_genericName'][$key];
				$presc_dosage = $data['prescription_dosage'][$key];
				$presc_timings = $data['prescription_timings'][$key];
				$presc_duration = $data['prescription_duration'][$key];
				$presc_morning = $data['prescription_morning'][$key];
				$presc_afternoon = $data['prescription_afternoon'][$key];
				$presc_night = $data['prescription_night'][$key];
				$presc_durationType = $data['prescription_duration_type'][$key];
				$presc_instructions = $data['prescription_instructions'][$key];	
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
					$insert_medicine=mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
					$freq_id =$insert_medicine; //Get Frequent Medicine Id
					$get_ppid = time();
				}
				else {
					$get_ppid = $presc_pp_id;
					$chkProduct= mysqlSelect("*","doctor_frequent_medicine","pp_id='".$presc_pp_id."'","","","","");
					
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
					$update_medicine=mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."'");
	
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

						$insert_medicine=mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
						
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
						
						$insert_patient_episode_prescriptions = mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);
					
			}
		}
			
			$getFrequentComplaints= mysqlSelect("a.dfs_id as dfs_id, a.symptoms_id as symptoms_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.symptoms as symptoms","doctor_frequent_symptoms as a inner join chief_medical_complaints as b on a.symptoms_id = b.complaint_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");
			$getFrequentInvestigation = mysqlSelect("a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department ","doctor_frequent_investigations as a left join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_test_count DESC","","","0,8");
			//$getFrequentExam = mysqlSelect("*","doctor_frequent_examination","doc_id='".$admin_id."' and doc_type='1'","freq_count DESC","","","0,8");
			$getFrequentExam = mysqlSelect("a.dfe_id as dfe_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.examination_id as examination_id,b.examination as examination","doctor_frequent_examination as a left join examination as b on a.examination_id=b.examination_id","(a.doc_id='".$admin_id."' and a.doc_type='1') or (a.doc_id='0' and a.doc_type='0')","a.freq_count DESC","","","8");
			$getFrequentDiagnosis = mysqlSelect("a.dfd_id as dfd_id, a.icd_id as icd_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on b.icd_id = a.icd_id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_count DESC","","","0,8");
			//$getFrequentTreatment = mysqlSelect("*","doctor_frequent_treatment","doc_id='".$admin_id."' and doc_type='1'","freq_count DESC","","","0,8");
			$getFrequentTreatment = mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","freq_count DESC","","","8");
	
			$getFrequentMedicine = mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='1'","freq_count desc","","","0,8");
			//$getPreviousPrescription = mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_id ."'","b.episode_id desc","","","1");								
		
			$prev_episode = mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_id."'","b.episode_id desc","","","1");								
			if(COUNT($prev_episode)>0) {
				$getPreviousPrescription = mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration, b.med_frequency_morning as med_frequency_morning, b.med_frequency_noon as med_frequency_noon, b.med_frequency_night as med_frequency_night, b.med_duration_type as med_duration_type, b.prescription_instruction as prescription_instruction","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","b.episode_id='".$prev_episode[0]['episode_id']."'","","","","");								
			}	
		
			$result = array("result" => "success","frequent_medcomp_details" => $getFrequentComplaints,"frequent_investigation_details" => $getFrequentInvestigation,"frequent_examination_details" => $getFrequentExam,"frequent_diagnosis_details" => $getFrequentDiagnosis,"frequent_treatment_details" => $getFrequentTreatment,"frequent_medicine_details" => $getFrequentMedicine,"repeat_precription_details" => $getPreviousPrescription);
			echo json_encode($result);

	/*}
	else
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}*/

?>