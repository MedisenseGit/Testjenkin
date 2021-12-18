<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id   = $_SESSION['user_id'];
$patient_id = $_SESSION['patient_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();


$treatid	=	$_GET['treatid'];
$patientid  =	$_GET['patientid'];

if(isset($_GET['treatid']) && !empty($_GET['treatid'])){
	
	$params     = explode("-", $_GET['treatid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_treat = array();
		$arrValues_treat = array();
		
		$arrFileds_treat[]='treatment';
		$arrValues_treat[]=$params[0];
		$arrFileds_treat[]='doc_id';
		$arrValues_treat[]=$admin_id;
		$arrFileds_treat[]='doc_type';
		$arrValues_treat[]='1';
		
		$insert_treatment	=	mysqlInsert('doctor_frequent_treatment',$arrFileds_treat,$arrValues_treat);
		$treat_id 			= $insert_treatment;
	} 
	else
	{
		$treat_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='dft_id';
		$arrValues[]=$treat_id;
							
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="1";
		$checkTreat= mysqlSelect("dft_id","doc_patient_treatment_active","dft_id='".$treat_id."' and patient_id = '".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
		if(COUNT($checkTreat)==0){
		$insert_symptoms=mysqlInsert('doc_patient_treatment_active',$arrFileds,$arrValues);
		}
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

if(isset($_GET['edittreatid']) && !empty($_GET['edittreatid'])){
	
	$params     = explode("-", $_GET['edittreatid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_treat = array();
		$arrValues_treat = array();
		
		$arrFileds_treat[]='treatment';
		$arrValues_treat[]=$params[0];
		$arrFileds_treat[]='doc_id';
		$arrValues_treat[]=$admin_id;
		$arrFileds_treat[]='doc_type';
		$arrValues_treat[]='1';
		
		$insert_treatment=mysqlInsert('doctor_frequent_treatment',$arrFileds_treat,$arrValues_treat);
		$treat_id = $insert_treatment;
	} else
	{
		$treat_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='dft_id';
		$arrValues[]=$treat_id;
							
		$arrFileds[]='patient_id';
		$arrValues[]=$patient_id;
		
		$arrFileds[]='episode_id';
		$arrValues[]=$_GET['episodeid'];
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="0";
		$checkTreat= mysqlSelect("dft_id","doc_patient_treatment_active","dft_id='".$treat_id."' and patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and episode_id='".$_GET['episodeid']."'","","","","");
		if(COUNT($checkTreat)==0){
		$insert_symptoms=mysqlInsert('doc_patient_treatment_active',$arrFileds,$arrValues);
		}
		$check_treat = mysqlSelect("*","doctor_frequent_treatment","dft_id='".$treat_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_treat[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_treat)>0){
						$arrFieldsTREATFREQ[] = 'freq_count';
						$arrValuesTREATFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_treatment',$arrFieldsTREATFREQ,$arrFieldsTREATFREQ,"dft_id = '".$check_treat[0]['dft_id']."'");
					}
					
					

}

if(isset($_GET['deltreament'])){
	
	mysqlDelete('doc_patient_treatment_active',"treatment_id='".$_GET['deltreament']."'");
}

if(isset($_GET['treatid'])){
$getTreatment= mysqlSelect("b.treatment as treatment,a.treatment_id as treatment_id","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.doc_id='".$admin_id."' and a.patient_id='".$_GET['patientid']."' and a.doc_type='1' and a.status='1'","a.treatment_id asc","","","");

								while(list($key, $value) = each($getTreatment)){ 
									echo '<div class="input-group m-b"><span class="tag label label-primary m-r">' . $value['treatment'] . '<a data-role="remove" class="text-white del_treatment m-l" data-treatment-id="'.$value['treatment_id'].'">x</a></span></div>';
								}
}

if(isset($_GET['edittreatid'])){
$getTreatment= mysqlSelect("b.treatment as treatment,a.treatment_id as treatment_id","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.episode_id='".$_GET['episodeid']."'","a.treatment_id asc","","","");

								while(list($key, $value) = each($getTreatment)){ 
									echo '<div class="input-group m-b"><span class="tag label label-primary m-r">' . $value['treatment'] . '<a data-role="remove" class="text-white edit_del_treatment m-l" data-treatment-id="'.$value['treatment_id'].'">x</a></span></div>';
								}
}
								?>		

