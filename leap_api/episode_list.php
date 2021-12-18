<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Episode List
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = (int)$_POST['patient_id'];
	
	if($login_type == 1) {	
	
		$episode_details= array();
		$get_attachments= array();
	
		$get_Episodes = $objQuery->mysqlSelect('*','doc_patient_episodes',"admin_id='".$admin_id."' and patient_id ='".$patient_id."'","episode_id desc","","","");
		$episode_id=$get_Episodes[0]['episode_id'];
		
		foreach($get_Episodes as $listEpisode){
			$getEpiList['episode_id']=$listEpisode['episode_id'];
			$getEpiList['admin_id']=$listEpisode['admin_id'];
			$getEpiList['patient_id']=$listEpisode['patient_id'];
			$getEpiList['episode_medical_complaint']=$listEpisode['episode_medical_complaint'];
			$getEpiList['examination']=$listEpisode['examination'];
			$getEpiList['treatment']=$listEpisode['treatment'];
			$getEpiList['next_followup_date']=$listEpisode['next_followup_date'];
			$getEpiList['date_time']=$listEpisode['date_time'];
			
			$investigation_result = $objQuery->mysqlSelect('*','patient_investigation',"episode_id='".$listEpisode['episode_id']."' and patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['investigation_result']=$investigation_result;
			
			$diagnosis_result = $objQuery->mysqlSelect('*','patient_diagnosis',"episode_id='".$listEpisode['episode_id']."' and patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['diagnosis_result']=$diagnosis_result;
			
			$prescription_result = $objQuery->mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id='".$listEpisode['episode_id']."'","","","","");
			$getEpiList['prescription_result']=$prescription_result;
			
			$attachments_result = $objQuery->mysqlSelect('*','doc_patient_attachments',"episode_id='".$listEpisode['episode_id']."'  and my_patient_id='".$listEpisode['patient_id']."'","","","","");
			$getEpiList['attachments_result']=$attachments_result;
			
			array_push($episode_details, $getEpiList);
		}
		
		$success = array('status' => "success","episode_details"=>$episode_details);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "failure");
		echo json_encode($success);
	}
		

	
}


?>