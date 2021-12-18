<?php ob_start();
	error_reporting(0);
	session_start();
	$admin_id = $_SESSION['user_id'];
	if(empty($admin_id)){
		header("Location:index.php");
	}

	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	$add_days = 3;
	$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

	$TransId=time();
	//$ccmail="medical@medisense.me";

	
	
	include('send_mail_function.php');
	include('send_text_message.php');
	require_once("../classes/querymaker.class.php");
	$objQuery = new CLSQueryMaker();
	ob_start();

	function hyphenize($string) {

		return
		## strtolower(
			  preg_replace(
				array('#[\\s+]+#', '#[^A-Za-z0-9\. -]+#', '/\@^|(\.+)/'),
				array('-',''),
			##     cleanString(
				  urldecode($string)
			##     )
			)
		## )
		;
	}
	$getDocEMR = $objQuery->mysqlSelect("spec_group_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."'","","","","");
				
	if($getDocEMR[0]['spec_group_id']==1){  //If 'spec_group_id' is 1, Then it will navigate to Cardio Diabetic EMR
		$navigateLink = "https://medisensecrm.com/standard/My-Patient-Details";
	}
	else if($getDocEMR[0]['spec_group_id']==2){ //If 'spec_group_id' is 2, Then it will navigate to Ophthal EMR
		$navigateLink = "https://medisensecrm.com/standard/Ophthal-EMR/";
	}


if(isset($_GET['diagnodetail']) && !empty($_GET['diagnodetail'])){
	
	if(isset($_GET['diagnodetail']))
	{
				$arrFileds_diagno[]='diagnosis_details';
				$arrValues_diagno[]=$_GET['diagnodetail'];
	}
	
		
	$update_diagnodetail=$objQuery->mysqlUpdate('patient_episodes',$arrFileds_diagno,$arrValues_diagno,"md5(episode_id) = '".$_GET['episodeid']."'");

}
if(isset($_GET['treatmentdetail']) && !empty($_GET['treatmentdetail'])){
	
	if(isset($_GET['treatmentdetail']))
	{
				$arrFileds_diagno[]='treatment_details';
				$arrValues_diagno[]=$_GET['treatmentdetail'];
	}
	
		
	$update_treatmentdetail=$objQuery->mysqlUpdate('patient_episodes',$arrFileds_diagno,$arrValues_diagno,"md5(episode_id) = '".$_GET['episodeid']."'");

}
	
if(isset($_POST['updateDiagnoInvestigation']))
{
	while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();
		
		if(!empty($_POST['actualVal'][$key_invest])){
		$arrFiedInvest[]='test_actual_value';
		$arrValueInvest[]=$_POST['actualVal'][$key_invest];
		}
		
		$update_invest=$objQuery->mysqlUpdate('patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$_POST['investigation_id'][$key_invest]."'");
	
		//Insert to 'trend_analysis'
		$arrFieldTrend=array();
		$arrValueTrend=array();
		
		if($_POST['main_test_id'][$key_invest]=="GLU009")  //BLOOD GLUCOSE (Post Prandial)
		{
			$arrFieldTrend[]='bp_afterfood_count';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="GLU017") //BLOOD GLUCOSE (Fasting)
		{
			$arrFieldTrend[]='bp_beforefood_count';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHO001") //HDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='HDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="LDL") //LDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='LDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHOL/HDL") //VLDL
		{
			
			$arrFieldTrend[]='VLDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="TRI001")   //TRIGLYCERIDES
		{
			
			$arrFieldTrend[]='triglyceride';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="GLU006")  //Glyco Hb (HbA1c)
		{
			
			$arrFieldTrend[]='HbA1c';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHO002")  //TOTAL CHOLESTEROL
		{
			
			$arrFieldTrend[]='cholesterol';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="URI012") //URINE SUGAR
		{
			
			$arrFieldTrend[]='urine_sugar';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		
		$arrFieldTrend[]='date_added';
		$arrValueTrend[]=$cur_Date;
		$arrFieldTrend[]='patient_id';
		$arrValueTrend[]=$_POST['patient_id'][$key_invest];
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="2";
		$checkTrend= $objQuery->mysqlSelect("*","trend_analysis","date_added='".$cur_Date."' and patient_id='".$_POST['patient_id'][$key_invest]."' and patient_type='2'","","","","");
		if(count($checkTrend)>0)
		{
			$update_trend=$objQuery->mysqlUpdate('trend_analysis',$arrFieldTrend,$arrValueTrend,"date_added='".$cur_Date."' and patient_id = '".$_POST['patient_id'][$key_invest]."' and patient_type='2'");
		}
		else
		{
		$insert_trend_analysis= $objQuery->mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
	
	}
	
	
						$errors= array();
						$timestring = time();
						$patientId = $_POST['patient_id'];
						$uploaddirectory = realpath("patientAttachments");
						$uploaddir = $uploaddirectory . "/" . $patientId . "/" .$timestring;
						
							/*Checking whether folder with category id already exist or not. */
								if (file_exists($uploaddir)) {
									//echo "The file $uploaddir exists";
									} 
								else {
									$newdir = mkdir($uploaddirectory . "/" . $patientId , 0777);
									$newdir = mkdir($uploaddirectory . "/" . $patientId . "/" .$timestring , 0777);
								}
						foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name )
						{	
												
						
						$file_name = $_FILES['file-5']['name'][$key];
						$file_size =$_FILES['file-5']['size'][$key];
						$file_tmp =$_FILES['file-5']['tmp_name'][$key];
						$file_type=$_FILES['file-5']['type'][$key];
						
						if(!empty($file_name)){
							$Photo1  = $file_name;
							$arrFields_Attach = array();
							$arrValues_Attach  = array();

							$arrFields_Attach[] = 'patient_id';
							$arrValues_Attach[] = $patientId;

							$arrFields_Attach[] = 'report_folder';
							$arrValues_Attach[] = $timestring;
							
							$arrFields_Attach[] = 'attachments';
							$arrValues_Attach[] = $file_name;
							
							$arrFields_Attach[] = 'user_id';
							$arrValues_Attach[] = $_POST['diagno_id'];
							
							$arrFields_Attach[] = 'user_type';
							$arrValues_Attach[] = "3"; //Diagnosis User
							
							$arrFields_Attach[] = 'date_added';
							$arrValues_Attach[] = $Cur_Date;
							
									
							$bslist_pht=$objQuery->mysqlInsert('my_patient_reports',$arrFields_Attach,$arrValues_Attach);
							$epiid= mysql_insert_id();


							/* Uploading image file */ 
								 
								 $dotpos = strpos($fileName, '.');
								 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
								 $uploadfile = $uploaddir . "/" . $Photo1;
								
								
								/* Moving uploaded file from temporary folder to desired folder. */
								if(move_uploaded_file ($file_tmp, $uploadfile)) {
									//echo "File uploaded.";
								} else {
									//echo "File cannot be uploaded";
								}
								
							} //End file empty conditions
								
						}//End of foreach
	$response="update-investigation";
	header("Location:Diagnostic-Refer?d=".md5($_POST['patient_id'])."&e=".md5($_POST['episode_id'])."&response=".$response);
}
//SAVE PATIENT	
	if(isset($_POST['save_patient']) || isset($_POST['update_patient'])){
	$txtName = addslashes($_POST['se_pat_name']);
		$txtMail = addslashes($_POST['se_email']);
		$txtAge = $_POST['se_pat_age'];
		$txtGen = $_POST['se_gender'];
		
		$height = $_POST['height'];
		$weight = $_POST['weight'];
		
		$txtContact = $_POST['se_con_per'];
		$txtMob = $_POST['se_phone_no'];
		$txtCountry = $_POST['se_country'];
		$txtState = $_POST['se_state'];
		$txtLoc = $_POST['se_city'];
		$txtAddress = addslashes($_POST['se_address']);
	
		$hyperCond = $_POST['se_hyper'];
		$diabetesCond = $_POST['se_diabets'];
		
		$arrFields = array();
		$arrValues = array();
	
						
			$arrFields[] = 'patient_name';
			$arrValues[] = $txtName;

			$arrFields[] = 'patient_age';
			$arrValues[] = $txtAge;

			$arrFields[] = 'patient_email';
			$arrValues[] = $txtMail;

			$arrFields[] = 'patient_gen';
			$arrValues[] = $txtGen;
			
			$arrFields[] = 'weight';
			$arrValues[] = $weight;
			
			$arrFields[] = 'height';
			$arrValues[] = $height;
			
			$arrFields[] = 'hyper_cond';
			$arrValues[] = $hyperCond;
			
			$arrFields[] = 'diabetes_cond';
			$arrValues[] = $diabetesCond;
		
			$arrFields[] = 'patient_mob';
			$arrValues[] = $txtMob;

			$arrFields[] = 'patient_loc';
			$arrValues[] = $txtLoc;

			$arrFields[] = 'pat_state';
			$arrValues[] = $txtState;

			$arrFields[] = 'pat_country';
			$arrValues[] = $txtCountry;

			$arrFields[] = 'patient_addrs';
			$arrValues[] = $txtAddress;
			$arrFields[] = 'partner_id';
			$arrValues[] = $admin_id;
			$arrFields[] = 'TImestamp';
			$arrValues[] = $Cur_Date;
			$arrFields[] = 'system_date';
			$arrValues[] = $cur_Date;
		if(isset($_POST['save_patient'])){
		$insert_patient=$objQuery->mysqlInsert('my_patient',$arrFields,$arrValues);
		$patientid = mysql_insert_id();
		}
		else if(isset($_POST['update_patient'])){
			$userupdate=$objQuery->mysqlUpdate('my_patient',$arrFields,$arrValues, "patient_id = '". $_POST['patient_id'] ."' ");
		$patientid = $_POST['patient_id'];
		}
		
		$response="updated";
		header("Location:".$navigateLink."?p=".md5($patientid));
	}
	
	//UPDATE PATIENT
	
	if(isset($_POST['updatePatient'])){
		
		$se_hyper = $_POST['se_hyper'];
		$se_diabets = $_POST['se_diabets'];
		$se_smoking = $_POST['se_smoking'];
		$se_alcoholic = $_POST['se_alcoholic'];
		$drug_abuse = $_POST['drug_abuse'];
		$other_details = $_POST['other_details'];
		
		$family_history = $_POST['family_history'];
		$prev_inter = $_POST['prev_inter'];
		$neuro_issue = $_POST['neuro_issue'];
		$kidney_issue = $_POST['kidney_issue'];
		
		$arrFields = array();
		$arrValues = array();
		
		$arrFields[] = 'hyper_cond';
		$arrValues[] = $se_hyper;
		
		$arrFields[] = 'smoking';
		$arrValues[] = $se_smoking;
		
		$arrFields[] = 'alcoholic';
		$arrValues[] = $se_alcoholic;
		
		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $se_diabets;
		
		//$arrFields[] = 'drug_abuse';
		//$arrValues[] = $drug_abuse;
		
		$arrFields[] = 'other_details';
		$arrValues[] = $other_details;
		
		//$arrFields[] = 'family_history';
		//$arrValues[] = $family_history;
		
		$arrFields[] = 'prev_inter';
		$arrValues[] = $prev_inter;
		
		$arrFields[] = 'neuro_issue';
		$arrValues[] = $neuro_issue;
		
		$arrFields[] = 'kidney_issue';
		$arrValues[] = $kidney_issue;
		
	
		$userupdate=$objQuery->mysqlUpdate('my_patient',$arrFields,$arrValues, "patient_id = '". $_POST['patient_id'] ."' ");
		//$patientid = $patient_id;
		
		//Update Drug Abuse -' doc_patient_drug_active' table
				
				$arrFieldsDrugAbuse[] = 'status';
				$arrValuesDrugAbuse[] = "0";
				$update_icd=$objQuery->mysqlUpdate('doc_patient_drug_active',$arrFieldsDrugAbuse,$arrValuesDrugAbuse,"patient_id = '".$_POST['patient_id']."' and doc_id='".$admin_id."' and doc_type='2' and status='1'");
				
				//Update Family History -' doc_patient_drug_active' table
				
				$arrFieldsFamilyHistory[] = 'status';
				$arrValuesFamilyHistory[] = "0";
				$update_icd=$objQuery->mysqlUpdate('doc_patient_family_history_active',$arrFieldsFamilyHistory,$arrValuesFamilyHistory,"patient_id = '".$_POST['patient_id']."' and doc_id='".$admin_id."' and doc_type='2' and status='1'");
				
				//Update Drug Allergy -' doc_patient_drug_allergy_active' table
				
				$arrFieldsAllergy[] = 'status';
				$arrValuesAllergy[] = "0";
				$update_icd=$objQuery->mysqlUpdate('doc_patient_drug_allergy_active',$arrFieldsAllergy,$arrValuesAllergy,"patient_id = '".$_POST['patient_id']."' and doc_type='2' and status='1'");
				
		$response="medical-history-updated";
		header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}
	
	
	//CREATE EPISODE
	if(isset($_POST['save_patient_edit']) || isset($_POST['save_patient_print'])){ //TO CHECK AUTHENTICATION OF POST VALUES
		//echo "<pre>"; print_r($_POST); exit;
		

			$patient_id = (int)$_POST['patient_id'];				
			$episode_desc = $_POST['episode_desc'];
			$medical_complaint =  $_POST['medical_complaint'];
			$medical_examination =  $_POST['medical_examination'];
			$txt_treatment =  $_POST['txt_treatment'];
			
			$diagnosis_details =  $_POST['diagnosis_details'];
			$treatment_details =  $_POST['treatment_details'];
			
			
				$arrFieldsPE = array();
				$arrValuesPE = array();
				$arrFieldsPE[] = 'patient_id';
				$arrValuesPE[] = $patient_id;
				$arrFieldsPE[] = 'admin_id';
				$arrValuesPE[] = $admin_id;
				
				$arrFieldsPE[] = 'diagnosis_details';
				$arrValuesPE[] = $diagnosis_details;
				$arrFieldsPE[] = 'treatment_details';
				$arrValuesPE[] = $treatment_details;
				
				$arrFieldsPE[] = 'next_followup_date';
				$arrValuesPE[] = date('Y-m-d',strtotime($_POST['dateadded']));
				$arrFieldsPE[] = 'date_time';
				$arrValuesPE[] = $Cur_Date;

				$insert_patient_episodes=$objQuery->mysqlInsert('patient_episodes',$arrFieldsPE,$arrValuesPE);
				$episode_id = mysql_insert_id(); //Get episode_id
			
				$arrFieldsSYMPTOMS[] = 'episode_id';
				$arrValuesSYMPTOMS[] = $episode_id;
				$arrFieldsSYMPTOMS[] = 'status';
				$arrValuesSYMPTOMS[] = "0";
				$update_icd=$objQuery->mysqlUpdate('doc_patient_symptoms_active',$arrFieldsSYMPTOMS,$arrValuesSYMPTOMS,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='2' and status='1'");
				
				
				
				//Update Investigation -' patient_temp_investigation' table
				$arrFieldsINVEST=array();
				$arrValuesINVEST=array();
				$arrFieldsINVEST[] = 'episode_id';
				$arrValuesINVEST[] = $episode_id;
				$arrFieldsINVEST[] = 'status';
				$arrValuesINVEST[] = "0";
				$update_icd=$objQuery->mysqlUpdate('patient_temp_investigation',$arrFieldsINVEST,$arrValuesINVEST,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='2' and status='1'");
				
						
				//Update Examination -'doc_patient_examination_active' table
				$arrFieldsExam[] = 'episode_id';
				$arrValuesExam[] = $episode_id;
				$arrFieldsExam[] = 'status';
				$arrValuesExam[] = "0";
				$update_icd=$objQuery->mysqlUpdate('doc_patient_examination_active',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='2' and status='1'");	
				
				//Update Diagnosis -' patient_diagnosis' table
				$arrFieldsExam[] = 'episode_id';
				$arrValuesExam[] = $episode_id;
				$arrFieldsExam[] = 'status';
				$arrValuesExam[] = "0";
				$update_icd=$objQuery->mysqlUpdate('patient_diagnosis',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='2' and status='1'");
				
				//Update Treatment -'doc_patient_treatment_active' table
				$arrFieldsTreat[] = 'episode_id';
				$arrValuesTreat[] = $episode_id;
				$arrFieldsTreat[] = 'status';
				$arrValuesTreat[] = "0";
				$update_icd=$objQuery->mysqlUpdate('doc_patient_treatment_active',$arrFieldsTreat,$arrValuesTreat,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");	
				
				
				/* save for patient_episode_prescriptions starts here */
				
				$getChosenProduct= $objQuery->mysqlSelect("*","doctor_temp_frequent_medicine","doc_id='".$admin_id."' and doc_type='2' and status='1'","","","","");
				
				while(list($key_prod, $val_prod) = each($getChosenProduct))
				{
					$prescription_product_id = $val_prod['pp_id'];
					$prescription_trade_name = $val_prod['med_trade_name'];
					$prescription_generic_name = $val_prod['med_generic_name'];
					$prescription_frequency = $val_prod['med_frequency'];
					$prescription_timing = $val_prod['med_timing'];
					$prescription_duration = $val_prod['med_duration'];
				
					$prescription_date_time = $Cur_Date;

					
						$arrFieldsPEP = array();
						$arrValuesPEP = array();
						$arrFieldsPEP[] = 'episode_id';
						$arrValuesPEP[] = $episode_id;
						$arrFieldsPEP[] = 'doc_id';
						$arrValuesPEP[] = $admin_id;
						$arrFieldsPEP[] = 'pp_id';
						$arrValuesPEP[] = $prescription_product_id;
						$arrFieldsPEP[] = 'prescription_trade_name';
						$arrValuesPEP[] = $prescription_trade_name;
						$arrFieldsPEP[] = 'prescription_generic_name';
						$arrValuesPEP[] = $prescription_generic_name;
						$arrFieldsPEP[] = 'prescription_frequency';
						$arrValuesPEP[] = $prescription_frequency;
						$arrFieldsPEP[] = 'timing';
						$arrValuesPEP[] = $prescription_timing;
						$arrFieldsPEP[] = 'duration';
						$arrValuesPEP[] = $prescription_duration;
						$arrFieldsPEP[] = 'prescription_date_time';
						$arrValuesPEP[] = $prescription_date_time;
						$insert_patient_episode_prescriptions = $objQuery->mysqlInsert('patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);
					
					$chkProduct= $objQuery->mysqlSelect("pp_id,freq_count","doctor_frequent_medicine","pp_id='".$prescription_product_id."' and doc_type='2'","","","","");
					
					$arrFileds_freq = array();
					$arrValues_freq = array();
					
					if($chkProduct == true)
					{
					$freq_count=$chkProduct[0]['freq_count']+1;
					
						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$prescription_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$prescription_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$prescription_frequency;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$prescription_timing;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$prescription_duration;
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]=$freq_count;	
					$update_medicine=$objQuery->mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."' and doc_type='2'");
	
					}
					else
					{
						$arrFileds_freq[]='pp_id';
						$arrValues_freq[]=$prescription_product_id;
						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$prescription_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$prescription_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$prescription_frequency;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$prescription_timing;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$prescription_duration;
						$arrFileds_freq[]='doc_id';
						$arrValues_freq[]=$admin_id;
						$arrFileds_freq[]='doc_type';
						$arrValues_freq[]="2";
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]="1";

						$insert_medicine=$objQuery->mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
						
					}
					
					
					
					
				}  //end while loop
				/* save for patient_episode_prescriptions Ends here */
				
				$chkSaveTemplate = $_POST['chkSaveTemplate'];
				if ($chkSaveTemplate == 1)
				{
					
					$template_name = $_POST['template_name'];
					if ($template_name == '')
					{
						$template_name = 'Template';
					}

					$arrFieldsPEPT = array();
					$arrValuesPEPT = array();
					$arrFieldsPEPT[] = 'admin_id';
					$arrValuesPEPT[] = $admin_id;
					$arrFieldsPEPT[] = 'template_name';
					$arrValuesPEPT[] = $template_name;					

					$insert_patient_episode_prescription_template = $objQuery->mysqlInsert('patient_episode_prescription_templates',$arrFieldsPEPT,$arrValuesPEPT);
					$template_id = mysql_insert_id(); //Get episode_id
					
				
					
					reset($getChosenProduct);
					while(list($key_temp, $val_temp) = each($getChosenProduct))
					{			
						$prescription_product_id = $val_temp['pp_id'];
						$prescription_trade_name = $val_temp['med_trade_name'];
						$prescription_generic_name = $val_temp['med_generic_name'];
						$prescription_frequency = $val_temp['med_frequency'];
						$prescription_timing = $val_temp['med_timing'];
						$prescription_duration = $val_temp['med_duration'];

						$arrFieldsPEPTD = array();
						$arrValuesPEPTD = array();
						$arrFieldsPEPTD[] = 'template_id';
						$arrValuesPEPTD[] = $template_id;
						$arrFieldsPEPTD[] = 'pp_id';
						$arrValuesPEPTD[] = $prescription_product_id;
						$arrFieldsPEPTD[] = 'doc_id';
						$arrValuesPEPTD[] = $admin_id;
						$arrFieldsPEPTD[] = 'doc_type';
						$arrValuesPEPTD[] = "2";						
						$arrFieldsPEPTD[] = 'prescription_trade_name';
						$arrValuesPEPTD[] = $prescription_trade_name;
						$arrFieldsPEPTD[] = 'prescription_generic_name';
						$arrValuesPEPTD[] = $prescription_generic_name;
						$arrFieldsPEPTD[] = 'prescription_frequency';
						$arrValuesPEPTD[] = $prescription_frequency;
						$arrFieldsPEPTD[] = 'prescription_timing';
						$arrValuesPEPTD[] = $prescription_timing;
						$arrFieldsPEPTD[] = 'prescription_duration';
						$arrValuesPEPTD[] = $prescription_duration;
					
						
						$insert_patient_episode_prescription_template_desc = $objQuery->mysqlInsert('doc_medicine_prescription_template_details',$arrFieldsPEPTD,$arrValuesPEPTD);
						
					}
					
					
				}
			
			
			
					//Save for Appointment Payment Transaction
					if(!empty($_POST['consult_charge']))
					{
						$arrFieldsPayment=array();	
						$arrValuesPayment=array();
						
						$arrFieldsPayment[]='patient_name';
						$arrValuesPayment[]=$_POST['patient_name'];
						$arrFieldsPayment[]='trans_date';
						$arrValuesPayment[]=$Cur_Date;
						$arrFieldsPayment[]='narration';
						$arrValuesPayment[]="Consultation Charge";
						$arrFieldsPayment[]='amount';
						$arrValuesPayment[]=$_POST['consult_charge'];
						$arrFieldsPayment[]='user_id';
						$arrValuesPayment[]=$admin_id;
						$arrFieldsPayment[]='user_type';
						$arrValuesPayment[]="2";
						$arrFieldsPayment[]='payment_status';
						$arrValuesPayment[]="PENDING";
						$arrFieldsPayment[]='pay_method';
						$arrValuesPayment[]="Cash";
						$insert_pay_transaction= $objQuery->mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
					}
					//Save for Appointment Payment Transaction ends here
					
					
		
		//Insert to 'trend_analysis'
		$arrFieldTrend=array();
		$arrValueTrend=array();
		
		$arrFieldTrend[]='date_added';
		$arrValueTrend[]=$cur_Date;
		$arrFieldTrend[]='patient_id';
		$arrValueTrend[]=$patient_id;
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="2";
		$checkTrend= $objQuery->mysqlSelect("*","trend_analysis","date_added='".$cur_Date."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
		if(count($checkTrend)==0)
		{
		$insert_trend_analysis= $objQuery->mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
		
		$response="episode-created";
		//header("Location:All-Patient-Records?response=".$response);

		/**/
		//echo "redirecting"; exit;
		if(isset($_POST['save_patient_edit'])){
		header("Location:My-Patient-Details?p=".md5($patient_id)."&response=".$response);
		} else if(isset($_POST['save_patient_print']))
		{
		header("Location:print-emr?pid=".md5($patient_id)."&episode=".md5($episode_id));	
		}
		//header("Location:My-Patient-List?response=".$response);
	}
	
	

if(isset($_POST['updateInvestigation']))
{
	while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();
		
		if(!empty($_POST['actualVal'][$key_invest])){
		$arrFiedInvest[]='test_actual_value';
		$arrValueInvest[]=$_POST['actualVal'][$key_invest];
		}
	
		
		$update_invest=$objQuery->mysqlUpdate('patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$_POST['investigation_id'][$key_invest]."'");
	
		//Insert to 'trend_analysis'
		$arrFieldTrend=array();
		$arrValueTrend=array();
		
		if($_POST['main_test_id'][$key_invest]=="GLU009")  //BLOOD GLUCOSE (Post Prandial)
		{
			$arrFieldTrend[]='bp_afterfood_count';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="GLU017") //BLOOD GLUCOSE (Fasting)
		{
			$arrFieldTrend[]='bp_beforefood_count';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHO001") //HDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='HDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="LDL") //LDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='LDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHOL/HDL") //VLDL
		{
			
			$arrFieldTrend[]='VLDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="TRI001")   //TRIGLYCERIDES
		{
			
			$arrFieldTrend[]='triglyceride';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="GLU006")  //Glyco Hb (HbA1c)
		{
			
			$arrFieldTrend[]='HbA1c';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHO002")  //TOTAL CHOLESTEROL
		{
			
			$arrFieldTrend[]='cholesterol';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="URI012") //URINE SUGAR
		{
			
			$arrFieldTrend[]='urine_sugar';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		
		$arrFieldTrend[]='date_added';
		$arrValueTrend[]=$cur_Date;
		$arrFieldTrend[]='patient_id';
		$arrValueTrend[]=$_POST['patient_id'];
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="1";
		$checkTrend= $objQuery->mysqlSelect("*","trend_analysis","date_added='".$cur_Date."' and patient_id='".$_POST['patient_id']."' and patient_type='1'","","","","");
		if(count($checkTrend)>0)
		{
			$update_trend=$objQuery->mysqlUpdate('trend_analysis',$arrFieldTrend,$arrValueTrend,"date_added='".$cur_Date."' and patient_id = '".$_POST['patient_id']."' and patient_type='1'");
		}
		else
		{
		$insert_trend_analysis= $objQuery->mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
	
	}
	while(list($key_opinvest, $value_opinvest) = each($_POST['op_investigation_id']))
	{
		if(!empty($_POST['lefteye'][$key_opinvest])){
		$arrFiedOpInvest[]='left_eye';
		$arrValueOpInvest[]=$_POST['lefteye'][$key_opinvest];
		}
		
		if(!empty($_POST['righteye'][$key_opinvest])){
		$arrFiedOpInvest[]='right_eye';
		$arrValueOpInvest[]=$_POST['righteye'][$key_opinvest];
		}
		$update_opinvest=$objQuery->mysqlUpdate('patient_temp_investigation',$arrFiedOpInvest,$arrValueOpInvest, "pti_id = '".$_POST['op_investigation_id'][$key_opinvest]."'");
	
	}
	$response="update-investigation";
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
}

if(isset($_POST['addAttachments'])){
	//Save patient episode attachments
				
						$errors= array();
						$timestring = time();
						if(!empty($_POST['upload_user'])){
						$uploadUser = $_POST['upload_user'];
						$userType = "2";
						}
						else
						{
						$uploadUser = $_POST['patient_id'];	
						$userType = "1";						
						}
						$patientId = $_POST['patient_id'];
						$uploaddirectory = realpath("patientAttachments");
						$uploaddir = $uploaddirectory . "/" . $patientId . "/" .$timestring;
						
							/*Checking whether folder with category id already exist or not. */
								if (file_exists($uploaddir)) {
									//echo "The file $uploaddir exists";
									} 
								else {
									$newdir = mkdir($uploaddirectory . "/" . $patientId , 0777);
									$newdir = mkdir($uploaddirectory . "/" . $patientId . "/" .$timestring , 0777);
								}
						foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name )
						{	
												
						
						$file_name = $_FILES['file-5']['name'][$key];
						$file_size =$_FILES['file-5']['size'][$key];
						$file_tmp =$_FILES['file-5']['tmp_name'][$key];
						$file_type=$_FILES['file-5']['type'][$key];
						
						if(!empty($file_name)){
							$Photo1  = $file_name;
							$arrFields_Attach = array();
							$arrValues_Attach  = array();

							$arrFields_Attach[] = 'patient_id';
							$arrValues_Attach[] = $patientId;

							$arrFields_Attach[] = 'report_folder';
							$arrValues_Attach[] = $timestring;
							
							$arrFields_Attach[] = 'attachments';
							$arrValues_Attach[] = $file_name;
							
							$arrFields_Attach[] = 'user_id';
							$arrValues_Attach[] = $uploadUser;
							
							$arrFields_Attach[] = 'user_type';
							$arrValues_Attach[] = $userType;
							
							$arrFields_Attach[] = 'date_added';
							$arrValues_Attach[] = $Cur_Date;
							
									
							$bslist_pht=$objQuery->mysqlInsert('my_patient_reports',$arrFields_Attach,$arrValues_Attach);
							$epiid= mysql_insert_id();


							/* Uploading image file */ 
								 
								 $dotpos = strpos($fileName, '.');
								 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
								 $uploadfile = $uploaddir . "/" . $Photo1;
								
								
								/* Moving uploaded file from temporary folder to desired folder. */
								if(move_uploaded_file ($file_tmp, $uploadfile)) {
									//echo "File uploaded.";
								} else {
									//echo "File cannot be uploaded";
								}
								
							} //End file empty conditions
								
						}//End of foreach
	$response="reports-attached";
	if(!empty($_POST['upload_user'])){
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Patient-Attachments?d=".md5($_POST['patient_id'])."&response=".$response);
	}

}

?>