<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

// Update Medical History
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	$patient_id = (int)$_POST['patient_id'];
	$patient_name =  $_POST['patient_name'];
	
	$se_weight = $_POST['patient_weight'];
	$se_height = $_POST['patient_height'];
	$se_bmi = $_POST['patient_bmi'];
	$se_hypertension = $_POST['patient_hypertension'];
	$se_diabetes = $_POST['patient_diabetes'];
	$se_smoking = $_POST['patient_smoking'];
	$se_alcohol = $_POST['patient_alcohol'];
	$se_prev_intervention = $_POST['prev_intervention'];
	$se_stroke = $_POST['stroke'];
	$se_kidney_issue = $_POST['kidney_issue'];
	$se_other_details = $_POST['other_details'];
	
	if($login_type == 1) {						// Premium LoginType
	
		$arrFields = array();
		$arrValues = array();
		
		$arrFields[] = 'weight';
		$arrValues[] = $se_weight;
		
		$arrFields[] = 'height_cm';
		$arrValues[] = $se_height;
	
		$arrFields[] = 'hyper_cond';
		$arrValues[] = $se_hypertension;
		
		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $se_diabetes;
		
		$arrFields[] = 'smoking';
		$arrValues[] = $se_smoking;
		
		$arrFields[] = 'alcoholic';
		$arrValues[] = $se_alcohol;
		
		$arrFields[] = 'prev_inter';
		$arrValues[] = $se_prev_intervention;
		
		$arrFields[] = 'neuro_issue';
		$arrValues[] = $se_stroke;
		
		$arrFields[] = 'kidney_issue';
		$arrValues[] = $se_kidney_issue;
		
		$arrFields[] = 'other_details';
		$arrValues[] = $se_other_details;
		
	
		$userupdate=$objQuery->mysqlUpdate('doc_my_patient',$arrFields,$arrValues, "patient_id = '".$patient_id."' ");
	
		/* Add Drug Allergy */	
		if(!empty($_POST['allergy_id'])) {
			$objQuery->mysqlDelete('doc_patient_drug_allergy_active',"doc_id='".$admin_id."' and doc_type ='1' and patient_id='".$patient_id."'");
	
			while (list($key, $val) = each($_POST['allergy_id'])) {
				
				$allergy_ID = $_POST['allergy_id'][$key];
				$allergy_generic_ID = $_POST['allergy_generic_id'][$key];
				$allergy_generic_name = $_POST['allergy_generic_name'][$key];
				$allergy_docID = $_POST['allergy_docid'][$key];
				$allergy_doc_type = $_POST['allergy_doctype'][$key];
				
				$check_allergy = $objQuery->mysqlSelect("*","doc_patient_drug_allergy_active","allergy_id='".$allergy_ID."' and doc_id='".$admin_id."' and doc_type='1' and patient_id='".$patient_id."'","","","","");
				if(count($check_allergy)>0){
					$objQuery->mysqlDelete('doc_patient_drug_allergy_active',"allergy_id='".$allergy_ID."'");
				}
				else {
					$arrFileds = array();
					$arrValues = array();
												
					$arrFileds[]='patient_id';
					$arrValues[]=$patient_id;
					
					$arrFileds[]='generic_id';
					$arrValues[]=$allergy_generic_ID;
					
					$arrFileds[]='generic_name';
					$arrValues[]=$allergy_generic_name;
					
					$arrFileds[]='doc_id';
					$arrValues[]=$admin_id;
					
					$arrFileds[]='doc_type';
					$arrValues[]="1";
						
					$arrFileds[]='status';
					$arrValues[]="0";
					if($allergy_generic_ID!=0){
						$insert_allergy=$objQuery->mysqlInsert('doc_patient_drug_allergy_active',$arrFileds,$arrValues);
					}
				
				} 
			}
		}
		else {
			$objQuery->mysqlDelete('doc_patient_drug_allergy_active',"doc_id='".$admin_id."' and doc_type ='1' and patient_id='".$patient_id."'");
		}

		/* Add Drug Abuse */		
		if(!empty($_POST['abuse_id']))	{
			$objQuery->mysqlDelete('doc_patient_drug_active',"doc_id='".$admin_id."' and doc_type ='1' and patient_id='".$patient_id."'");
		
			while (list($key, $val) = each($_POST['abuse_id'])) {
				$abuse_ID = $_POST['abuse_id'][$key];
				$abuse_name = $_POST['abuse_name'][$key];
				$abuse_docid = $_POST['abuse_docid'][$key];
				$abuse_doctype = $_POST['abuse_doctype'][$key];
				
				if($abuse_ID == 0) {
					$arrFileds_drug = array();
					$arrValues_drug = array();
					
					$arrFileds_drug[]='drug_abuse';
					$arrValues_drug[]=$abuse_name;
					$arrFileds_drug[]='doc_id';
					$arrValues_drug[]=$admin_id;
					$arrFileds_drug[]='doc_type';
					$arrValues_drug[]='1';
					
					$insert_drugs=$objQuery->mysqlInsert('drug_abuse_auto',$arrFileds_drug,$arrValues_drug);
					$drug_id = mysql_insert_id(); //Get Patient Id
				}
				else {
					$drug_id = $abuse_ID;
				}
				
				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[]='drug_abuse_id';
				$arrValues[]=$drug_id;
									
				$arrFileds[]='patient_id';
				$arrValues[]=$patient_id;
				
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
				
				$arrFileds[]='doc_type';
				$arrValues[]="1";
				
				$arrFileds[]='status';
				$arrValues[]="0";
				
				$insert_drug=$objQuery->mysqlInsert('doc_patient_drug_active',$arrFileds,$arrValues);
				
				$check_drug = $objQuery->mysqlSelect("*","doctor_frequent_drug_abuse","drug_abuse_id='".$drug_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_drug[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDrugAbuseFreq = array();
					$arrValuesDrugAbuseFreq = array();
					if(count($check_drug)>0){
						$arrFieldsDrugAbuseFreq[] = 'freq_count';
						$arrValuesDrugAbuseFreq[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_drug_abuse',$arrFieldsDrugAbuseFreq,$arrValuesDrugAbuseFreq,"fda_id = '".$check_drug[0]['fda_id']."'");
					}
					else{
						$arrFieldsDrugAbuseFreq[] = 'drug_abuse_id';
						$arrValuesDrugAbuseFreq[] = $drug_id;
						$arrFieldsDrugAbuseFreq[] = 'doc_id';
						$arrValuesDrugAbuseFreq[] = $admin_id;
						$arrFieldsDrugAbuseFreq[] = 'doc_type';
						$arrValuesDrugAbuseFreq[] = "1";
						$arrFieldsDrugAbuseFreq[] = 'freq_count';
						$arrValuesDrugAbuseFreq[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_drug_abuse',$arrFieldsDrugAbuseFreq,$arrValuesDrugAbuseFreq);
						
					}
			}
		}
		else {
			$objQuery->mysqlDelete('doc_patient_drug_active',"doc_id='".$admin_id."' and doc_type ='1' and patient_id='".$patient_id."'");
		}
		
		/* Add Family History */		
		if(!empty($_POST['family_id'])) {
				$objQuery->mysqlDelete('doc_patient_family_history_active',"doc_id='".$admin_id."' and doc_type ='1' and patient_id='".$patient_id."'");	
	
				while (list($key, $val) = each($_POST['family_id'])) {
					$family_ID = $_POST['family_id'][$key];
					$family_name = $_POST['family_name'][$key];
					$family_docid = $_POST['family_docid'][$key];
					$family_doctype = $_POST['family_doctype'][$key];
					
					if($family_ID == 0) {
					$arrFileds_family = array();
					$arrValues_family = array();
					
					$arrFileds_family[]='family_history';
					$arrValues_family[]=$family_name;
					$arrFileds_family[]='doc_id';
					$arrValues_family[]=$admin_id;
					$arrFileds_family[]='doc_type';
					$arrValues_family[]='1';
					
					$insert_family=$objQuery->mysqlInsert('family_history_auto',$arrFileds_family,$arrValues_family);
					$family_id = mysql_insert_id(); //Get Family Id
				}
				else {
					$family_id = $family_ID;
				}
				
				$arrFileds = array();
				$arrValues = array();
				
				$arrFileds[]='family_history_id';
				$arrValues[]=$family_id;
									
				$arrFileds[]='patient_id';
				$arrValues[]=$patient_id;
				
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
				
				$arrFileds[]='doc_type';
				$arrValues[]="1";
				
				$arrFileds[]='status';
				$arrValues[]="0";
				
				$insert_familyhistory =$objQuery->mysqlInsert('doc_patient_family_history_active',$arrFileds,$arrValues);
				
				$check_family_history = $objQuery->mysqlSelect("*","doctor_frequent_family_history","family_history_id='".$family_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_family_history[0]['freq_count']+1; //Count will increment by one
					$arrFieldsFamilyFreq = array();
					$arrValuesFamilyFreq = array();
					if(count($check_family_history)>0){
						$arrFieldsFamilyFreq[] = 'freq_count';
						$arrValuesFamilyFreq[] = $freq_count;
						$update_family=$objQuery->mysqlUpdate('doctor_frequent_family_history',$arrFieldsFamilyFreq,$arrValuesFamilyFreq,"ffh_id = '".$check_family_history[0]['ffh_id']."'");
					}
					else{
						$arrFieldsFamilyFreq[] = 'family_history_id';
						$arrValuesFamilyFreq[] = $family_id;
						$arrFieldsFamilyFreq[] = 'doc_id';
						$arrValuesFamilyFreq[] = $admin_id;
						$arrFieldsFamilyFreq[] = 'doc_type';
						$arrValuesFamilyFreq[] = "1";
						$arrFieldsFamilyFreq[] = 'freq_count';
						$arrValuesFamilyFreq[] = "1";
						$insert_freq_family=$objQuery->mysqlInsert('doctor_frequent_family_history',$arrFieldsFamilyFreq,$arrValuesFamilyFreq);
						
					}
			}
		}
		else {
			$objQuery->mysqlDelete('doc_patient_family_history_active',"doc_id='".$admin_id."' and doc_type ='1' and patient_id='".$patient_id."'");	
		}
		
		
		$getFrequentDrugAllery = $objQuery->mysqlSelect("*","doc_patient_drug_allergy_active","doc_id='".$admin_id."' and doc_type='1' and patient_id='".$patient_id."'","allergy_id DESC","","","");
		
		$getFrequentDrugAbuse= $objQuery->mysqlSelect("a.fda_id as fda_id, a.drug_abuse_id as drug_abuse_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.drug_abuse as drug_abuse","doctor_frequent_drug_abuse as a inner join drug_abuse_auto as b on a.drug_abuse_id = b.drug_abuse_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");
		$selectDrugAbuse= $objQuery->mysqlSelect("*","drug_abuse_auto"," (doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","drug_abuse asc","","","");

		$getFrequentFamilyHistory= $objQuery->mysqlSelect("a.ffh_id as ffh_id, a.family_history_id as family_history_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.family_history as family_history","doctor_frequent_family_history as a inner join family_history_auto as b on a.family_history_id = b.family_history_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");
		$selectFamilyHistory= $objQuery->mysqlSelect("*","family_history_auto"," (doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","family_history asc","","","");

		
		$result = array("result" => "success","frequent_allergy_details" => $getFrequentDrugAllery,"frequent_drug_abuse_details" => $getFrequentDrugAbuse,"drug_abuse_details" => $selectDrugAbuse,"frequent_family_history_details" => $getFrequentFamilyHistory,"family_history_details" => $selectFamilyHistory);
		echo json_encode($result);
	}
	else {
		$success = array('result' => "failure");
		echo json_encode($success);
	}	
		
}


?>