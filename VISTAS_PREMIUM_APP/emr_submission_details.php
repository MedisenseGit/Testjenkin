<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));


$headers = apache_request_headers();
if($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata  = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

/*if(!empty($doctor_id) && !empty($finalHash)) 
{
	if($finalHash == $hashKey) 
	{*/

		//CREATE EPISODE
		$patient_id 		=  (int)$_POST['patient_id'];				
		$admin_id			=  $doctor_id;
		
		$get_patient_episode	=	mysqlSelect('*','doc_patient_episodes',"patient_id ='".$patient_id."'","","","","");
		
		
		
		
			
		$arrFieldsPE = array();
		$arrValuesPE = array();
		
		$arrFieldsPE[] = 'patient_id';
		$arrValuesPE[] = $patient_id;
		
		$arrFieldsPE[] = 'admin_id';
		$arrValuesPE[] = $admin_id;  
		
		$arrFieldsPE[] = 'treatment';
		$arrValuesPE[] = $txt_treatment;
		
		$arrFieldsPE[] = 'episode_medical_complaint';
		$arrValuesPE[] = $suffering_since; 
				
		$arrFieldsPE[] = 'prescription_template';
		$arrValuesPE[] = $_POST['prescription_template'];
		
		$arrFieldsPE[] = 'patient_education';
		$arrValuesPE[] = $patient_education;
		
		$arrFieldsPE[] = 'prescription_note';
		$arrValuesPE[] = $presc_note;

		$arrFieldsPE[] = 'patientNote';
		$arrValuesPE[] = $patient_note;
		
		$arrFieldsPE[] = 'referTo';
		$arrValuesPE[] = $refer_to;
		
		$arrFieldsPE[] = 'specialization';
		$arrValuesPE[] = $specialization;
		
		$arrFieldsPE[] = 'diagnosis_details';
		$arrValuesPE[] = $diagnosis_details;
		
		$arrFieldsPE[] = 'treatment_details';
		$arrValuesPE[] = $treatment_details;
		
		$arrFieldsPE[] = 'chkPatConsent';
		$arrValuesPE[] = $chkConsent;
		
		$arrFieldsPE[] = 'emr_type';
		$arrValuesPE[] = "1"; //1 for cardiodiabetic
				
		if(!empty($_POST['dateadded']))
		{
			$arrFieldsPE[] = 'next_followup_date';
			$arrValuesPE[] = date('Y-m-d',strtotime($_POST['dateadded']));
		}
		
		if(!empty($_POST['dateadded2']))
		{
			$arrFieldsPE[] = 'date_time';
			$arrValuesPE[] = $_POST['dateadded2'];
		}
		else
		{
			$arrFieldsPE[] = 'date_time';
			$arrValuesPE[] = $Cur_Date;
		}

		/*if($patient_note != '' || $refer_to != '' || $specialization != '')
		{
			$hid_appnt_trans_id = $_POST['appnt_trans_id'];
			$select_doc_my_pat = $objQuery->mysqlSelect("patient_name, patient_age, DOB, patient_email, patient_gen, merital_status, qualification, height_cm, height, weight, hyper_cond, smoking, alcoholic, diabetes_cond, pat_blood, drug_abuse, other_details, family_history, prev_inter, neuro_issue, kidney_issue, contact_person, profession, patient_mob, patient_loc, pat_state, pat_country, patient_addrs, tele_communication, patientEMR_consent, TImestamp, user_id, doc_id, patient_image, system_date, transaction_id, data_source, member_id, pat_bp, pat_thyroid, pat_cholestrole, pat_epilepsy, pat_asthama, doc_video_link, pat_video_link, teleconsult_status, subscriber_id","doc_my_patient","patient_id = '". $patient_id ."' ","","","","");

			//$arrFieldsDMP[] = 'patient_id';
			$arrFieldsDMP[] = 'patient_name';
			$arrFieldsDMP[] = 'patient_age';
			$arrFieldsDMP[] = 'DOB';
			$arrFieldsDMP[] = 'patient_email';
			$arrFieldsDMP[] = 'patient_gen';
			$arrFieldsDMP[] = 'merital_status';
			$arrFieldsDMP[] = 'qualification';
			$arrFieldsDMP[] = 'height_cm';
			$arrFieldsDMP[] = 'height';
			$arrFieldsDMP[] = 'weight';
			$arrFieldsDMP[] = 'hyper_cond';
			$arrFieldsDMP[] = 'smoking';
			$arrFieldsDMP[] = 'alcoholic';
			$arrFieldsDMP[] = 'diabetes_cond';
			$arrFieldsDMP[] = 'pat_blood';
			$arrFieldsDMP[] = 'drug_abuse';
			$arrFieldsDMP[] = 'other_details';
			$arrFieldsDMP[] = 'family_history';
			$arrFieldsDMP[] = 'prev_inter';
			$arrFieldsDMP[] = 'neuro_issue';
			$arrFieldsDMP[] = 'kidney_issue';
			$arrFieldsDMP[] = 'contact_person';
			$arrFieldsDMP[] = 'profession';
			$arrFieldsDMP[] = 'patient_mob';
			$arrFieldsDMP[] = 'patient_loc';
			$arrFieldsDMP[] = 'pat_state';
			$arrFieldsDMP[] = 'pat_country';
			$arrFieldsDMP[] = 'patient_addrs';
			$arrFieldsDMP[] = 'tele_communication';
			$arrFieldsDMP[] = 'patientEMR_consent';
			$arrFieldsDMP[] = 'TImestamp';
			$arrFieldsDMP[] = 'user_id';
			$arrFieldsDMP[] = 'doc_id';
			$arrFieldsDMP[] = 'patient_image';
			$arrFieldsDMP[] = 'system_date';
			$arrFieldsDMP[] = 'transaction_id';
			$arrFieldsDMP[] = 'data_source';
			$arrFieldsDMP[] = 'member_id';
			$arrFieldsDMP[] = 'pat_bp';
			$arrFieldsDMP[] = 'pat_thyroid';
			$arrFieldsDMP[] = 'pat_cholestrole';
			$arrFieldsDMP[] = 'pat_epilepsy';
			$arrFieldsDMP[] = 'pat_asthama';
			$arrFieldsDMP[] = 'doc_video_link';
			$arrFieldsDMP[] = 'pat_video_link';
			$arrFieldsDMP[] = 'teleconsult_status';
			$arrFieldsDMP[] = 'subscriber_id';

					
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_name'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_age'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['DOB'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_email'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_gen'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['merital_status'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['qualification'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['height_cm'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['height'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['weight'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['hyper_cond'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['smoking'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['alcoholic'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['diabetes_cond'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_blood'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['drug_abuse'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['other_details'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['family_history'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['prev_inter'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['neuro_issue'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['kidney_issue'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['contact_person'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['profession'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_mob'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_loc'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_state'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_country'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_addrs'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['tele_communication'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patientEMR_consent'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['TImestamp'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['user_id'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['doc_id'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['patient_image'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['system_date'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['transaction_id'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['data_source'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['member_id'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_bp'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_thyroid'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_cholestrole'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_epilepsy'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_asthama'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['doc_video_link'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['pat_video_link'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['teleconsult_status'];
			$arrValuesDMP[] = $select_doc_my_pat[0]['subscriber_id'];				

			$insert_doc_my_pat = $objQuery->mysqlInsert('doc_my_patient',$arrFieldsDMP,$arrValuesDMP);
			$strPatientID = mysql_insert_id();
			$strGetTrId = explode('_', $_POST['appnt_trans_id']);

			if($strGetTrId != '')
			{
				$select_app_tr_det = $objQuery->mysqlSelect("appoint_trans_id, Payment_id, patient_id, hosp_id, member_id, pref_doc, user_type, department, Login_User_Id, Hosp_patient_Id, Visiting_date, Visiting_time, patient_name, Mobile_no, Email_address, Amount, pay_status, visit_status, tele_communication, patientEMR_consent, Time_stamp, medisense_share, hosp_share, src_type, appointment_type, reference_id, referring_hosp, referring_doc, teleconsult_status","appointment_transaction_detail","appoint_trans_id = '". $strGetTrId['1'] ."' ","","","","");

				$arrFieldsATD[] = 'appoint_trans_id';
				$arrFieldsATD[] = 'Payment_id';
				$arrFieldsATD[] = 'patient_id';
				$arrFieldsATD[] = 'hosp_id';
				$arrFieldsATD[] = 'member_id';
				$arrFieldsATD[] = 'pref_doc';
				$arrFieldsATD[] = 'user_type';
				$arrFieldsATD[] = 'department';
				$arrFieldsATD[] = 'Login_User_Id';
				$arrFieldsATD[] = 'Hosp_patient_Id';
				$arrFieldsATD[] = 'Visiting_date';
				$arrFieldsATD[] = 'Visiting_time';
				$arrFieldsATD[] = 'patient_name';
				$arrFieldsATD[] = 'Mobile_no';
				$arrFieldsATD[] = 'Email_address';
				$arrFieldsATD[] = 'Amount';
				$arrFieldsATD[] = 'pay_status';
				$arrFieldsATD[] = 'visit_status';
				$arrFieldsATD[] = 'tele_communication';
				$arrFieldsATD[] = 'patientEMR_consent';
				$arrFieldsATD[] = 'Time_stamp';
				$arrFieldsATD[] = 'medisense_share';
				$arrFieldsATD[] = 'hosp_share';
				$arrFieldsATD[] = 'src_type';
				$arrFieldsATD[] = 'appointment_type';
				$arrFieldsATD[] = 'reference_id';
				$arrFieldsATD[] = 'referring_hosp';
				$arrFieldsATD[] = 'referring_doc';
				$arrFieldsATD[] = 'teleconsult_status';

				$arrValuesATD[] = $select_app_tr_det[0]['appoint_trans_id'];
				$arrValuesATD[] = $select_app_tr_det[0]['Payment_id'];
				$arrValuesATD[] = $strPatientID;
				$arrValuesATD[] = $select_app_tr_det[0]['hosp_id'];
				$arrValuesATD[] = $select_app_tr_det[0]['member_id'];
				$arrValuesATD[] = $select_app_tr_det[0]['pref_doc'];
				$arrValuesATD[] = $select_app_tr_det[0]['user_type'];
				$arrValuesATD[] = $select_app_tr_det[0]['department'];
				$arrValuesATD[] = $select_app_tr_det[0]['Login_User_Id'];
				$arrValuesATD[] = $select_app_tr_det[0]['Hosp_patient_Id'];
				$arrValuesATD[] = $select_app_tr_det[0]['Visiting_date'];
				$arrValuesATD[] = $select_app_tr_det[0]['Visiting_time'];
				$arrValuesATD[] = $select_app_tr_det[0]['patient_name'];
				$arrValuesATD[] = $select_app_tr_det[0]['Mobile_no'];
				$arrValuesATD[] = $select_app_tr_det[0]['Email_address'];
				$arrValuesATD[] = $select_app_tr_det[0]['Amount'];
				$arrValuesATD[] = $select_app_tr_det[0]['pay_status'];
				$arrValuesATD[] = $select_app_tr_det[0]['visit_status'];
				$arrValuesATD[] = $select_app_tr_det[0]['tele_communication'];
				$arrValuesATD[] = $select_app_tr_det[0]['patientEMR_consent'];
				$arrValuesATD[] = $select_app_tr_det[0]['Time_stamp'];
				$arrValuesATD[] = $select_app_tr_det[0]['medisense_share'];
				$arrValuesATD[] = $select_app_tr_det[0]['hosp_share'];
				$arrValuesATD[] = $select_app_tr_det[0]['src_type'];
				$arrValuesATD[] = $select_app_tr_det[0]['appointment_type'];
				$arrValuesATD[] = $select_app_tr_det[0]['reference_id'];
				$arrValuesATD[] = $select_app_tr_det[0]['referring_hosp'];
				$arrValuesATD[] = $select_app_tr_det[0]['referring_doc'];
				$arrValuesATD[] = $select_app_tr_det[0]['teleconsult_status'];

				$insert_app_tr_det = $objQuery->mysqlInsert('appointment_transaction_detail',$arrFieldsATD,$arrValuesATD);

				$select_app_tkn_sys = $objQuery->mysqlSelect("token_id, token_no, patient_id, appoint_trans_id, patient_name, doc_id, doc_type, hosp_id, status, tele_communication, patientEMR_consent, app_date, app_time, set_diation_timer, dilation_status, appointment_type, reference_id, referring_hosp, referring_doc, referal_note, created_date","appointment_token_system","appoint_trans_id = '". $strGetTrId['1'] ."' ","","","","");

				$arrFieldsATS[] = 'token_no';
				$arrFieldsATS[] = 'patient_id';
				$arrFieldsATS[] = 'appoint_trans_id';
				$arrFieldsATS[] = 'patient_name';
				$arrFieldsATS[] = 'doc_id';
				$arrFieldsATS[] = 'doc_type';
				$arrFieldsATS[] = 'hosp_id';
				$arrFieldsATS[] = 'status';
				$arrFieldsATS[] = 'tele_communication';
				$arrFieldsATS[] = 'patientEMR_consent';
				$arrFieldsATS[] = 'app_date';
				$arrFieldsATS[] = 'app_time';
				$arrFieldsATS[] = 'set_diation_timer';
				$arrFieldsATS[] = 'dilation_status';
				$arrFieldsATS[] = 'appointment_type';
				$arrFieldsATS[] = 'reference_id';
				$arrFieldsATS[] = 'referring_hosp';
				$arrFieldsATS[] = 'referring_doc';
				$arrFieldsATS[] = 'referal_note';
				$arrFieldsATS[] = 'created_date';

				$arrValuesATS[] = '1';
				$arrValuesATS[] = $strPatientID;
				$arrValuesATS[] = $select_app_tkn_sys[0]['appoint_trans_id'];
				$arrValuesATS[] = $select_app_tkn_sys[0]['patient_name'];
				$arrValuesATS[] = '3744';
				$arrValuesATS[] = $select_app_tkn_sys[0]['doc_type'];
				$arrValuesATS[] = $select_app_tkn_sys[0]['hosp_id'];
				$arrValuesATS[] = $select_app_tkn_sys[0]['status'];
				$arrValuesATS[] = $select_app_tkn_sys[0]['tele_communication'];
				$arrValuesATS[] = $select_app_tkn_sys[0]['patientEMR_consent'];
				$arrValuesATS[] = date('Y-m-d');
				$arrValuesATS[] = '';
				$arrValuesATS[] = '';
				$arrValuesATS[] = '';
				$arrValuesATS[] = '0';
				$arrValuesATS[] = '0';
				$arrValuesATS[] = '';
				$arrValuesATS[] = $admin_id;
				$arrValuesATS[] = $patient_note;
				$arrValuesATS[] = date('Y-m-d h:i:s');						
				
				$insert_app_tkn_sys = $objQuery->mysqlInsert('appointment_token_system',   $arrFieldsATS,$arrValuesATS);

			}
		}
		else
		{
			//echo "No Referral";
		}*/

		
		
		if($chkConsent=="1")
		{
			$getDocDetails = $objQuery->mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");

			$arrFieldsChat = array();
			$arrValuesChat = array();
			$arrFieldsChat[] = 'patient_id';
			$arrValuesChat[] = $patient_id;
			$arrFieldsChat[] = 'episode_id';
			$arrValuesChat[] = $episode_id;
			$arrFieldsChat[] = 'doc_id';
			$arrValuesChat[] = $admin_id;
			$arrFieldsChat[] = 'company_id';
			$arrValuesChat[] = $getDocDetails[0]['company_id'];
			$arrFieldsChat[] = 'chat_note';
			$arrValuesChat[] = "EMR is referred to Institution successfully";
			$arrFieldsChat[] = 'status';
			$arrValuesChat[] = "1";
			$arrFieldsChat[] = 'created_date';
			$arrValuesChat[] = $Cur_Date;
				
			$insert_chat_notification=$objQuery->mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);
		}
				
		if(!empty($refer_to))
		{
			$arrFileds_outref[]='patient_id';
			$arrValues_outref[]=$patient_id;
			$arrFileds_outref[]='episode_id';
			$arrValues_outref[]=$episode_id;
			$arrFileds_outref[]='doc_id';
			$arrValues_outref[]=$admin_id;
			$arrFileds_outref[]='doc_type';
			$arrValues_outref[]="1";
			$arrFileds_outref[]='referral_id';
			$arrValues_outref[]=$refer_to;
			$arrFileds_outref[]='type';
			$arrValues_outref[]="4";
			$arrFileds_outref[]='timestamp';
			$arrValues_outref[]=$Cur_Date;
			$insert_outgoing_referrals=$objQuery->mysqlInsert('doctor_outgoing_referrals',$arrFileds_outref,$arrValues_outref);	
		}
		$arrFieldsSYMPTOMS[] = 'episode_id';
		$arrValuesSYMPTOMS[] = $episode_id;
		$arrFieldsSYMPTOMS[] = 'status';
		$arrValuesSYMPTOMS[] = "0";
		$update_icd=$objQuery->mysqlUpdate('doc_patient_symptoms_active',$arrFieldsSYMPTOMS,$arrValuesSYMPTOMS,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");
		
		//Update Diagnosis -' patient_diagnosis' table
		$arrFieldsExam[] = 'episode_id';
		$arrValuesExam[] = $episode_id;
		$arrFieldsExam[] = 'status';
		$arrValuesExam[] = "0";
		$update_icd=$objQuery->mysqlUpdate('patient_diagnosis',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");
				
		//Update Treatment -'doc_patient_treatment_active' table
		$arrFieldsTreat[] = 'episode_id';
		$arrValuesTreat[] = $episode_id;
		$arrFieldsTreat[] = 'status';
		$arrValuesTreat[] = "0";
		$update_icd=$objQuery->mysqlUpdate('doc_patient_treatment_active',$arrFieldsTreat,$arrValuesTreat,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");	
				
		$getTreatmentDetails = $objQuery->mysqlSelect("b.treatment as surgery_name","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.patient_id = '".$patient_id."' and a.doc_id='".$admin_id."' and a.doc_type='1' and a.episode_id='".$episode_id."'","","","","");

		$arrField_Scheduler[]="patient_id";
		$arrVal_Scheduler[]=$patient_id;
		
		$arrField_Scheduler[]="doc_id";
		$arrVal_Scheduler[]=$admin_id;
		
		$arrField_Scheduler[]="doc_type";
		$arrVal_Scheduler[]="1";
		
		$arrField_Scheduler[]="title";
		$arrVal_Scheduler[]=$getTreatmentDetails[0]['surgery_name'];
		
		$arrField_Scheduler[]="status";
		$arrVal_Scheduler[]="Scheduled";
		
		$arrField_Scheduler[]="date";
		$arrVal_Scheduler[]=date('Y-m-d',strtotime($_POST['dateadded5']));
		
		$arrField_Scheduler[]="time";
		$arrVal_Scheduler[]=date('H:i:s',strtotime($_POST['dateadded5']));
				
		$arrField_Scheduler[]="created";
		$arrVal_Scheduler[]=$Cur_Date;
		
		$arrField_Scheduler[]="modified";
		$arrVal_Scheduler[]=$Cur_Date;
		
		if(!empty($_POST['dateadded5']))
		{
			$insert_treatment=$objQuery->mysqlInsert('ot_scheduler',$arrField_Scheduler,$arrVal_Scheduler);
		}
		/* save for patient_episode_prescriptions starts here */
		$getChosenProduct= $objQuery->mysqlSelect("*","doctor_temp_frequent_medicine","doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
		while(list($key_prod, $val_prod) = each($getChosenProduct))
		{
			$prescription_product_id = $val_prod['pp_id'];
			$prescription_trade_name = $val_prod['med_trade_name'];
			$prescription_generic_name = $val_prod['med_generic_name'];
			$prescription_frequency = $val_prod['med_frequency'];
			$prescription_timing = $val_prod['med_timing'];
			$prescription_duration = $val_prod['med_duration'];
			
			$prescription_frequency_morning = $val_prod['med_frequency_morning'];
			$prescription_frequency_noon = $val_prod['med_frequency_noon'];
			$prescription_frequency_night = $val_prod['med_frequency_night'];
			$prescription_duration_type = $val_prod['med_duration_type'];
			$prescription_other_instruction = $val_prod['other_instruction'];
			$prescription_date_time = $_POST['dateadded2'];

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
			
			$arrFieldsPEP[] = 'med_frequency_morning';
			$arrValuesPEP[] = $prescription_frequency_morning;
			$arrFieldsPEP[] = 'med_frequency_noon';
			$arrValuesPEP[] = $prescription_frequency_noon;
			$arrFieldsPEP[] = 'med_frequency_night';
			$arrValuesPEP[] = $prescription_frequency_night;
			$arrFieldsPEP[] = 'med_duration_type';
			$arrValuesPEP[] = $prescription_duration_type;
			$arrFieldsPEP[] = 'prescription_instruction';
			$arrValuesPEP[] = $prescription_other_instruction;
			$arrFieldsPEP[] = 'prescription_template';
			$arrValuesPEP[] = $_POST['prescription_template'];
			$arrFieldsPEP[] = 'prescription_date_time';
			$arrValuesPEP[] = $prescription_date_time;
			$insert_patient_episode_prescriptions = $objQuery->mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);
		
			$chkProduct= $objQuery->mysqlSelect("pp_id,freq_count","doctor_frequent_medicine","pp_id='".$prescription_product_id."'","","","","");
		
			$arrFileds_freq = array();
			$arrValues_freq = array();
					
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
			
			$arrFileds_freq[]='med_frequency_morning';
			$arrValues_freq[]=$prescription_frequency_morning;
			$arrFileds_freq[]='med_frequency_noon';
			$arrValues_freq[]=$prescription_frequency_noon;
			$arrFileds_freq[]='med_frequency_night';
			$arrValues_freq[]=$prescription_frequency_night;
			$arrFileds_freq[]='med_duration_type';
			$arrValues_freq[]=$prescription_duration_type;
			$arrFileds_freq[]='prescription_instruction';
			$arrValues_freq[]=$prescription_other_instruction;
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
			$arrFileds_freq[]='doc_type';
			$arrValues_freq[]="1";
			if($chkProduct == true)
			{
				$freq_count=$chkProduct[0]['freq_count']+1;
				$arrFileds_freq[]='freq_count';
				$arrValues_freq[]=$freq_count;	
				$update_medicine=$objQuery->mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."'");
			}
			else
			{
				$arrFileds_freq[]='pp_id';
				$arrValues_freq[]=$prescription_product_id;
				
				$arrFileds_freq[]='freq_count';
				$arrValues_freq[]="1";

				$insert_medicine=$objQuery->mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
				
			}
		}  
		//end while loop
		/* save for patient_episode_prescriptions Ends here */
		
		/* Save Examination template details Starts here */
				
		$chkExamSaveTemplate = $_POST['chkExamSaveTemplate'];
		
		if ($chkExamSaveTemplate == 1)
		{
			$exam_template_name = $_POST['exam_template_name'];
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
			
			$getChosenExam= $objQuery->mysqlSelect("*","doc_patient_examination_active","doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
					
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
		/* Save Examination template details ends here */
		//Update Examination -'doc_patient_examination_active' table
		$arrFieldsExam[] = 'episode_id';
		$arrValuesExam[] = $episode_id;
		$arrFieldsExam[] = 'status';
		$arrValuesExam[] = "0";
		$update_icd=$objQuery->mysqlUpdate('doc_patient_examination_active',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");	
		/* Save Invetigation template details Starts here */
		$chkInvestSaveTemplate = $_POST['chkInvestSaveTemplate'];
		if ($chkInvestSaveTemplate == 1)
		{
			$invest_template_name = $_POST['invest_template_name'];
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
			$invets_template_id = mysql_insert_id(); //Get episode_id
			
			$getChosenInvset= $objQuery->mysqlSelect("*","patient_temp_investigation","doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
					
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
				
		//Update Investigation -' patient_temp_investigation' table
		$arrFieldsINVEST=array();
		$arrValuesINVEST=array();
		$arrFieldsINVEST[] = 'episode_id';
		$arrValuesINVEST[] = $episode_id;
		$arrFieldsINVEST[] = 'status';
		$arrValuesINVEST[] = "0";
		$update_icd=$objQuery->mysqlUpdate('patient_temp_investigation',$arrFieldsINVEST,$arrValuesINVEST,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");
				
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

			$insert_patient_episode_prescription_template = $objQuery->mysqlInsert('doc_patient_episode_prescription_templates',$arrFieldsPEPT,$arrValuesPEPT);
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
				
				$med_frequency_morning = $val_temp['med_frequency_morning'];
				$med_frequency_noon = $val_temp['med_frequency_noon'];
				$med_frequency_night = $val_temp['med_frequency_night'];
				$med_duration_type = $val_temp['med_duration_type'];
				$other_instruction = $val_temp['other_instruction'];

				$arrFieldsPEPTD = array();
				$arrValuesPEPTD = array();
				$arrFieldsPEPTD[] = 'template_id';
				$arrValuesPEPTD[] = $template_id;
				$arrFieldsPEPTD[] = 'pp_id';
				$arrValuesPEPTD[] = $prescription_product_id;
				$arrFieldsPEPTD[] = 'doc_id';
				$arrValuesPEPTD[] = $admin_id;
				$arrFieldsPEPTD[] = 'doc_type';
				$arrValuesPEPTD[] = "1";						
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
					
				$arrFieldsPEPTD[] = 'med_frequency_morning';
				$arrValuesPEPTD[] = $med_frequency_morning;
				$arrFieldsPEPTD[] = 'med_frequency_noon';
				$arrValuesPEPTD[] = $med_frequency_noon;
				$arrFieldsPEPTD[] = 'med_frequency_night';
				$arrValuesPEPTD[] = $med_frequency_night;
				$arrFieldsPEPTD[] = 'med_duration_type';
				$arrValuesPEPTD[] = $med_duration_type;
				$arrFieldsPEPTD[] = 'other_instruction';
				$arrValuesPEPTD[] = $other_instruction;
						
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
			$arrFieldsPayment[]='patient_id';
			$arrValuesPayment[]=$patient_id;
			$arrFieldsPayment[]='trans_date';
			$arrValuesPayment[]=$Cur_Date;
			$arrFieldsPayment[]='narration';
			$arrValuesPayment[]="Consultation Charge";
			$arrFieldsPayment[]='amount';
			$arrValuesPayment[]=$_POST['consult_charge'];
			$arrFieldsPayment[]='user_id';
			$arrValuesPayment[]=$admin_id;
			$arrFieldsPayment[]='user_type';
			$arrValuesPayment[]="1";
			$arrFieldsPayment[]='hosp_id';
			$arrValuesPayment[]=$Hosp_Id;
			$arrFieldsPayment[]='payment_status';
			$arrValuesPayment[]="PENDING";
			$arrFieldsPayment[]='pay_method';
			$arrValuesPayment[]="Cash";
			$insert_pay_transaction= $objQuery->mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
		}
		//Save for Appointment Payment Transaction ends here
					
		$chkPatientAppTab = $objQuery->mysqlSelect("*","appointment_token_system","patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date='".date('Y-m-d')."'","","","","");
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
						
		}
		
		//Insert to 'trend_analysis'
		$arrFieldTrend=array();
		$arrValueTrend=array();
		
		$arrFieldTrend[]='date_added';
		$arrValueTrend[]=date('Y-m-d',strtotime($_POST['dateadded2']));
		$arrFieldTrend[]='patient_id';
		$arrValueTrend[]=$patient_id;
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="1";
		$checkTrend= $objQuery->mysqlSelect("*","trend_analysis","date_added='".date('Y-m-d',strtotime($_POST['dateadded2']))."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
		if(count($checkTrend)==0)
		{
			$insert_trend_analysis= $objQuery->mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
	
		$result = array("result" => "success");
		echo json_encode($result);
		
	/*}
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
}*/

?>

