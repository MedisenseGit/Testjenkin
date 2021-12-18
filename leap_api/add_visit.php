<?php
ob_start();
session_start();
error_reporting(0);

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

// Add Visit
 if(API_KEY == $_POST['API_KEY']) {

	$login_type = $_POST['login_type'];
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];

	$patient_id = (int)$_POST['patient_id'];
	$consultation_fees =  $_POST['consultation_fees'];
	$patient_name =  $_POST['patient_name'];
	$diagnosis_details =  $_POST['diagnosis_details'];
	$treatment_details =  $_POST['treatment_details'];
	$prescription_note =  $_POST['prescription_note'];
	$visit_entry_date =  $_POST['visit_entry_date'];
	$visit_chiefMedComplaint_sufferings =  $_POST['chiefMedComplaint_sufferings'];
	$chkExamSaveTemplate =  $_POST['examination_template_save'];
	$exam_template_name =  $_POST['examination_template_name'];
	$chkInvestSaveTemplate =  $_POST['investigation_template_save'];
	$invest_template_name =  $_POST['investigation_template_name'];
	$patient_education =  $_POST['patient_education'];


	if($login_type == 1) {						// Premium LoginType

			/* Add or Save Episode */
			$arrFieldsPE = array();
			$arrValuesPE = array();
			$arrFieldsPE[] = 'patient_id';
			$arrValuesPE[] = $patient_id;
			$arrFieldsPE[] = 'admin_id';
			$arrValuesPE[] = $admin_id;
			if(!empty($_POST['followup_dates'])) {
				$arrFieldsPE[] = 'next_followup_date';
				$arrValuesPE[] = date('Y-m-d',strtotime($_POST['followup_dates']));
			}

			if(!empty($_POST['diagnosis_details'])) {
				$arrFieldsPE[] = 'diagnosis_details';
				$arrValuesPE[] = $diagnosis_details;
			}

			if(!empty($_POST['treatment_details'])) {
				$arrFieldsPE[] = 'treatment_details';
				$arrValuesPE[] = $treatment_details;
			}

			if(!empty($_POST['prescription_note'])) {
				$arrFieldsPE[] = 'prescription_note';
				$arrValuesPE[] = $prescription_note;
			}
			
			if(!empty($_POST['visit_entry_date'])) {
				$arrFieldsPE[] = 'date_time';
				$arrValuesPE[] = date('Y-m-d H:i:s',strtotime($_POST['visit_entry_date']));
			}
			else {
				$arrFieldsPE[] = 'date_time';
				$arrValuesPE[] = $Cur_Date;
			}
			
			if(!empty($_POST['chiefMedComplaint_sufferings'])) {
				$arrFieldsPE[] = 'episode_medical_complaint';
				$arrValuesPE[] = $visit_chiefMedComplaint_sufferings;
			}
			
			if(!empty($patient_education)) {
				$arrFieldsPE[] = 'patient_education';
				$arrValuesPE[] = $patient_education;
			}
			
			$arrFieldsPE[] = 'emr_type';
			$arrValuesPE[] = '1';						// EMR Type 1-Cardio and Genearl, 2- Ophthamology

			$insert_patient_episodes=$objQuery->mysqlInsert('doc_patient_episodes',$arrFieldsPE,$arrValuesPE);
			$episode_id = mysql_insert_id(); //Get episode_id

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
		
		/* Save Invetigation template details Starts here */
		$chkInvestSaveTemplate = $_POST['investigation_template_save'];
		if ($chkInvestSaveTemplate == 1)
		{
					
			$invest_template_name = $_POST['investigation_template_name'];
			if ($invest_template_name == '')
			{
				$invest_template_name = 'Template';
			}

			$arrFieldsINVESTTEMP = array();
			$arrValuesINVESTTEMP = array();
			$arrFieldsINVESTTEMP[] = 'doc_id';
			$arrValuesINVESTTEMP[] = $admin_id;
			$arrFieldsINVESTTEMP[] = 'doc_type';
			$arrValuesINVESTTEMP[] = "1";
			$arrFieldsINVESTTEMP[] = 'template_name';
			$arrValuesINVESTTEMP[] = $invest_template_name;					

			$insert_patient_episode_prescription_template = $objQuery->mysqlInsert('doc_patient_episode_investigations_templates',$arrFieldsINVESTTEMP,$arrValuesINVESTTEMP);
			$invets_template_id = mysql_insert_id(); //Get invets_template_id
					
			$getChosenInvset= $objQuery->mysqlSelect("*","patient_temp_investigation","doc_id='".$admin_id."' and doc_type='1' and episode_id='".$episode_id."' and patient_id='".$patient_id."'","","","","");
					
			while(list($key_invtemp, $val_invtemp) = each($getChosenInvset))
			{	
				$arrFieldsINVESTTD = array();
				$arrValuesINVESTTD = array();
				$arrFieldsINVESTTD[] = 'invest_template_id';
				$arrValuesINVESTTD[] = $invets_template_id;
				$arrFieldsINVESTTD[] = 'main_test_id';
				$arrValuesINVESTTD[] = $val_invtemp['main_test_id'];
				$arrFieldsINVESTTD[] = 'test_name';
				$arrValuesINVESTTD[] = $val_invtemp['test_name'];
				$arrFieldsINVESTTD[] = 'test_actual_value';
				$arrValuesINVESTTD[] = $val_invtemp['test_actual_value'];
								
				$insert_patient_episode_invest_template_desc = $objQuery->mysqlInsert('doc_patient_episode_investigation_template_details',$arrFieldsINVESTTD,$arrValuesINVESTTD);
			}
		}
		/* Save Investigation template details ends here */
		

		/* Add Examinations */
		if(!empty($_POST['examination_id'])) {
			while (list($key, $val) = each($_POST['examination_id'])) {

				$examination_id = $_POST['examination_id'][$key];
				$examination_name = $_POST['examination_name'][$key];
				$examination_results = $_POST['examination_results'][$key];
				$examination_findings = $_POST['examination_findings'][$key];
				$examination_docid = $_POST['examination_docid'][$key];
				$examination_doctype = $_POST['examination_doctype'][$key];

				if($examination_id == 0) {
					$arrFileds_exam = array();
					$arrValues_exam = array();

					$arrFileds_exam[]='examination';
					$arrValues_exam[]=$examination_name;
					$arrFileds_exam[]='doc_id';
					$arrValues_exam[]=$admin_id;
					$arrFileds_exam[]='doc_type';
					$arrValues_exam[]='1';

					$insert_symptoms=$objQuery->mysqlInsert('examination',$arrFileds_exam,$arrValues_exam);
					$exam_id = mysql_insert_id(); //Get Patient Id
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

				$insert_symptoms=$objQuery->mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
				$check_exam = $objQuery->mysqlSelect("*","doctor_frequent_examination","dfe_id='".$exam_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
				$freq_count = $check_exam[0]['freq_count']+1; //Count will increment by one
					$arrFieldsEXAMFREQ = array();
					$arrValuesEXAMFREQ = array();
					if(count($check_exam)>0){
						$arrFieldsEXAMFREQ[] = 'freq_count';
						$arrValuesEXAMFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_examination',$arrFieldsEXAMFREQ,$arrValuesEXAMFREQ,"dfe_id = '".$check_exam[0]['dfe_id']."'");
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
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_examination',$arrFieldsEXAMFREQ,$arrValuesEXAMFREQ);
				}
			}
		}
		
		// Check Examination Template Save
		if($chkExamSaveTemplate == 1) {
				$exam_template_name = $_POST['examination_template_name'];
				if ($exam_template_name == '')
				{
					$exam_template_name = 'Template';
				}
				
				$arrFieldsEXAMTEMP = array();
				$arrValuesEXAMTEMP = array();
				$arrFieldsEXAMTEMP[] = 'doc_id';
				$arrValuesEXAMTEMP[] = $admin_id;
				$arrFieldsEXAMTEMP[] = 'doc_type';
				$arrValuesEXAMTEMP[] = "1";
				$arrFieldsEXAMTEMP[] = 'template_name';
				$arrValuesEXAMTEMP[] = $exam_template_name;					

				$insert_patient_episode_exam_template = $objQuery->mysqlInsert('doc_patient_episode_examination_templates',$arrFieldsEXAMTEMP,$arrValuesEXAMTEMP);
				$exam_template_id = mysql_insert_id(); //Get episode_id
				
				$getChosenExam= $objQuery->mysqlSelect("*","doc_patient_examination_active","doc_id='".$admin_id."' and doc_type='1' and episode_id='".$episode_id."' and patient_id='".$patient_id."'","","","","");
				while(list($key_examtemp, $val_examtemp) = each($getChosenExam))
					{	
						$arrFieldsEXAMTD = array();
						$arrValuesEXAMTD = array();
						$arrFieldsEXAMTD[] = 'exam_template_id';
						$arrValuesEXAMTD[] = $exam_template_id;
						$arrFieldsEXAMTD[] = 'examination';
						$arrValuesEXAMTD[] = $val_examtemp['examination'];
						$arrFieldsEXAMTD[] = 'exam_result';
						$arrValuesEXAMTD[] = $val_examtemp['exam_result'];
						$arrFieldsEXAMTD[] = 'findings';
						$arrValuesEXAMTD[] = $val_examtemp['findings'];
						
						
						$insert_patient_episode_exam_template_desc = $objQuery->mysqlInsert('doc_patient_episode_examination_template_details',$arrFieldsEXAMTD,$arrValuesEXAMTD);
						
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

					/*$chkProduct= $objQuery->mysqlSelect("pp_id,freq_count","doctor_frequent_medicine","pp_id='".$get_ppid."'","","","","");
					$arrFileds_freq = array();
					$arrValues_freq = array();
					if($chkProduct == true)
					{
					$freq_count=$chkProduct[0]['freq_count']+1;

						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]=$freq_count;
						$update_medicine=$objQuery->mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."'");
					}*/

			}
		}

			//Insert to 'trend_analysis'
			$arrFieldTrend=array();
			$arrValueTrend=array();

			$arrFieldTrend[]='date_added';
			$arrValueTrend[]=$Cur_Date;
			$arrFieldTrend[]='patient_id';
			$arrValueTrend[]=$patient_id;
			$arrFieldTrend[]='patient_type';
			$arrValueTrend[]="1";
			$checkTrend= $objQuery->mysqlSelect("*","trend_analysis","date_added='".$cur_Date."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
			if(count($checkTrend)==0)
			{
			$insert_trend_analysis= $objQuery->mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
			}


			//Save for Appointment Payment Transaction
			if(!empty($consultation_fees))
			{
				$arrFieldsPayment=array();	
				$arrValuesPayment=array();
						
				$arrFieldsPayment[]='patient_name';
				$arrValuesPayment[]=$patient_name;
				$arrFieldsPayment[]='patient_id';
				$arrValuesPayment[]=$patient_id;
				$arrFieldsPayment[]='trans_date';
				$arrValuesPayment[]=$Cur_Date;
				$arrFieldsPayment[]='narration';
				$arrValuesPayment[]="Consultation Charge";
				$arrFieldsPayment[]='amount';
				$arrValuesPayment[]=$consultation_fees;
				$arrFieldsPayment[]='user_id';
				$arrValuesPayment[]=$admin_id;
				$arrFieldsPayment[]='user_type';
				$arrValuesPayment[]="1";
				$arrFieldsPayment[]='hosp_id';
				$arrValuesPayment[]=$hospital_id;
				$arrFieldsPayment[]='payment_status';
				$arrValuesPayment[]="PENDING";
				$arrFieldsPayment[]='pay_method';
				$arrValuesPayment[]="Cash";
				$insert_pay_transaction= $objQuery->mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
			}
					
			/*//Save for Appointment Payment Transaction
			if(!empty($consultation_fees))
			{
				$arrFieldsPayment=array();
				$arrValuesPayment=array();

				$arrFieldsPayment[]='patient_name';
				$arrValuesPayment[]=$patient_name;
				$arrFieldsPayment[]='trans_date';
				$arrValuesPayment[]=$Cur_Date;
				$arrFieldsPayment[]='narration';
				$arrValuesPayment[]="Consultation Charge";
				$arrFieldsPayment[]='amount';
				$arrValuesPayment[]=$consultation_fees;
				$arrFieldsPayment[]='user_id';
				$arrValuesPayment[]=$admin_id;
				$arrFieldsPayment[]='user_type';
				$arrValuesPayment[]="1";
				$arrFieldsPayment[]='payment_status';
				$arrValuesPayment[]="PENDING";
				$arrFieldsPayment[]='pay_method';
				$arrValuesPayment[]="Cash";
				$insert_pay_transaction= $objQuery->mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
			}
			//Save for Appointment Payment Transaction ends here */

    /*      $chkPatientAppTab = $objQuery->mysqlSelect("*","appointment_token_system","patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date='".date('Y-m-d')."'","","","","");
					if(count($chkPatientAppTab)>0)
					{
						//Update Appointment Status as  -'Consulted'
						$arrFieldsAppStatus[] = 'status';
						$arrValuesAppStatus[] = "Consulted";

						$update_appointment=$objQuery->mysqlUpdate('appointment_token_system',$arrFieldsAppStatus,$arrValuesAppStatus,"token_id = '".$chkPatientAppTab[0]['token_id']."'");

						$arrFieldsAppTransStatus[] = 'pay_status';
						$arrValuesAppTransStatus[] = "Consulted";
						$arrFieldsAppTransStatus[] = 'visit_status';
						$arrValuesAppTransStatus[] = "new_visit";

						$update_appoint_trans=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFieldsAppTransStatus,$arrValuesAppTransStatus,"Visiting_date = '".date('Y-m-d')."' and patient_id='".$patient_id."'and pref_doc='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'");

					} */

          $chkPatientAppTab = $objQuery->mysqlSelect("*","appointment_token_system","patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."' and app_date='".date('Y-m-d')."'","","","","");
					if(count($chkPatientAppTab)>0)
					{
						//Update Appointment Status as  -'Consulted'
						$arrFieldsAppStatus[] = 'status';
						$arrValuesAppStatus[] = "Consulted";

						$update_appointment=$objQuery->mysqlUpdate('appointment_token_system',$arrFieldsAppStatus,$arrValuesAppStatus,"token_id = '".$chkPatientAppTab[0]['token_id']."'");

						$arrFieldsAppTransStatus[] = 'pay_status';
						$arrValuesAppTransStatus[] = "Consulted";
						$arrFieldsAppTransStatus[] = 'visit_status';
						$arrValuesAppTransStatus[] = "new_visit";

						$update_appoint_trans=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFieldsAppTransStatus,$arrValuesAppTransStatus,"Visiting_date = '".date('Y-m-d')."' and patient_id='".$patient_id."'and pref_doc='".$admin_id."' and hosp_id='".$hospital_id."'");

					}


			$getFrequentComplaints= $objQuery->mysqlSelect("a.dfs_id as dfs_id, a.symptoms_id as symptoms_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.symptoms as symptoms","doctor_frequent_symptoms as a inner join chief_medical_complaints as b on a.symptoms_id = b.complaint_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");
			$getFrequentInvestigation = $objQuery->mysqlSelect("a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department ","doctor_frequent_investigations as a left join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_test_count DESC","","","0,8");
			//$getFrequentExam = $objQuery->mysqlSelect("*","doctor_frequent_examination","doc_id='".$admin_id."' and doc_type='1'","freq_count DESC","","","0,8");
			$getFrequentExam = $objQuery->mysqlSelect("a.dfe_id as dfe_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.examination_id as examination_id,b.examination as examination","doctor_frequent_examination as a left join examination as b on a.examination_id=b.examination_id","(a.doc_id='".$admin_id."' and a.doc_type='1') or (a.doc_id='0' and a.doc_type='0')","a.freq_count DESC","","","8");
			$getFrequentDiagnosis = $objQuery->mysqlSelect("a.dfd_id as dfd_id, a.icd_id as icd_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on b.icd_id = a.icd_id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_count DESC","","","0,8");
			//$getFrequentTreatment = $objQuery->mysqlSelect("*","doctor_frequent_treatment","doc_id='".$admin_id."' and doc_type='1'","freq_count DESC","","","0,8");
			$getFrequentTreatment = $objQuery->mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","freq_count DESC","","","8");

			$getFrequentMedicine = $objQuery->mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='1'","freq_count desc","","","0,8");
			//$getPreviousPrescription = $objQuery->mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_id ."'","b.episode_id desc","","","1");

			$prev_episode = $objQuery->mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_id."'","b.episode_id desc","","","1");
			if(COUNT($prev_episode)>0) {
				$getPreviousPrescription = $objQuery->mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration, b.med_frequency_morning as med_frequency_morning, b.med_frequency_noon as med_frequency_noon, b.med_frequency_night as med_frequency_night, b.med_duration_type as med_duration_type, b.prescription_instruction as prescription_instruction","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","b.episode_id='".$prev_episode[0]['episode_id']."'","","","","");
			}

			$result = array("result" => "success","frequent_medcomp_details" => $getFrequentComplaints,"frequent_investigation_details" => $getFrequentInvestigation,"frequent_examination_details" => $getFrequentExam,"frequent_diagnosis_details" => $getFrequentDiagnosis,"frequent_treatment_details" => $getFrequentTreatment,"frequent_medicine_details" => $getFrequentMedicine,"repeat_precription_details" => $getPreviousPrescription);
			echo json_encode($result);
	}
	else {
			$success = array('result' => "failure");
			echo json_encode($success);
	}

}


?>
