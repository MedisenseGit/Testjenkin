<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
include('functions.php');

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(isset($_GET['patid'])){
$attach = mysqlSelect("*","doc_my_patient_reports","patient_id='".$_GET['patid']."'","","","","");
	
	/*foreach($attach as $attachList){
		unlink('episodeAttach/'.$attachList['attach_id'].'/'.$attachList['attachments']);
		$delEPIDIR = "episodeAttach/".$attachList['attach_id'];
		deleteDir($delEPIDIR);
	}*/
$delDIR ="patientAttachments/".$_GET['patid'];	
deleteDir($delDIR);	
	
/*	
	
$doc_patient_reports = mysqlSelect("DISTINCT(report_folder) as report_folder","doc_my_patient_reports","patient_id = '".$_GET['patid']."'","report_folder desc","","","");
while(list($key_list, $value_list) = each($doc_patient_reports)) 
{
	$get_reports = mysqlSelect("*","doc_my_patient_reports","report_folder = '".$value_list['report_folder']."'","","","","");
	foreach($get_reports as $attachList){
		
		unlink('patientAttachments/'.$_GET['patid'].'/'.$attachList['report_folder'].'/'.$attachList['attachments']);
		
	}
	rmdir($attachList['report_folder']);
}	*/
$get_episode = mysqlSelect("*","doc_patient_episodes","patient_id='".$_GET['patid']."'","","","","");
	
mysqlDelete('doc_patient_episode_prescriptions',"episode_id='".$get_episode[0]['episode_id']."'");

mysqlDelete('doc_my_patient_reports',"patient_id='".$_GET['patid']."'");
//mysqlDelete('patients_appointment',"a.patient_id='".$_GET['patid']."' and doc_id='".$admin_id."'");
mysqlDelete('patients_appointment',"a.patient_id='".$_GET['patid']."'");
//mysqlDelete('doc_patient_attachments',"my_patient_id='".$_GET['patid']."'");

mysqlDelete('doc_patient_episodes',"patient_id='".$_GET['patid']."' and admin_id='".$admin_id."'");
mysqlDelete('trend_analysis',"patient_id='".$_GET['patid']."' and patient_type='1'");

mysqlDelete('doc_patient_angle_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_anterior_chamber_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_conjuctiva_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_cornea_ant_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_cornea_post_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_drug_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_drug_allergy_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_examination_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_family_history_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_iris_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('oc_patient_lens_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");

mysqlDelete('doc_patient_lids_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");

mysqlDelete('doc_patient_pupil_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");

mysqlDelete('doc_patient_sclera_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");

mysqlDelete('doc_patient_symptoms_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_treatment_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");
mysqlDelete('doc_patient_viterous_active',"patient_id='".$_GET['patid']."' and doc_id='".$admin_id."' and doc_type='1'");

}

if(isset($_GET['episodeid']))
{
mysqlDelete('doc_patient_episode_prescriptions',"md5(episode_id)='".$_GET['episodeid']."'");

mysqlDelete('doc_patient_episodes',"md5(episode_id)='".$_GET['episodeid']."' and admin_id='".$admin_id."'");

mysqlDelete('doc_patient_angle_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_anterior_chamber_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_conjuctiva_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_cornea_ant_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_cornea_post_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_examination_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_iris_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('oc_patient_lens_active',"md5(episode_id)='".$_GET['episodeid']."'");

mysqlDelete('doc_patient_lids_active',"md5(episode_id)='".$_GET['episodeid']."'");

mysqlDelete('doc_patient_pupil_active',"md5(episode_id)='".$_GET['episodeid']."'");

mysqlDelete('doc_patient_sclera_active',"md5(episode_id)='".$_GET['episodeid']."'");

mysqlDelete('doc_patient_symptoms_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_treatment_active',"md5(episode_id)='".$_GET['episodeid']."'");
mysqlDelete('doc_patient_viterous_active',"md5(episode_id)='".$_GET['episodeid']."'");

}	
?>
                     
                
					