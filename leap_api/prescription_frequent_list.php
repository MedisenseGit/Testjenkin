<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
mysql_set_charset('utf8');
header('Content-Type: application/json; charset=utf-8');

function jsonEncodeArray( $array ){
    array_walk_recursive( $array, function(&$item) { 
      // $item = utf8_decode ( $item );
		$item = html_entity_decode(mb_convert_encoding($item,'HTML-ENTITIES','utf-8'), ENT_COMPAT, 'UTF-8') ;   
    });
   // return json_encode( $array );
  return  $array ;
}

//Prescription Frequent Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = $_POST['patient_id'];
	
	if($login_type == 1) {						// Premium LoginType
	
		$last_five_templates = $objQuery->mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$admin_id."'","template_id desc","","","5");
		$last_five_medicine = $objQuery->mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='1'","freq_count desc","","","5");
		
		$prev_episode = $objQuery->mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_id."'","b.episode_id desc","","","1");								
		if(COUNT($prev_episode)>0) {
			$prev_prescription = $objQuery->mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration, b.med_frequency_morning as med_frequency_morning, b.med_frequency_noon as med_frequency_noon, b.med_frequency_night as med_frequency_night, b.med_duration_type as med_duration_type, b.prescription_instruction as prescription_instruction","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","b.episode_id='".$prev_episode[0]['episode_id']."'","","","","");								
		}

		$patient_education = $objQuery->mysqlSelect("*","patient_education","doc_id='".$admin_id."' and doc_type ='1'","edu_id desc","","","");
				
		
		//$success = array('status' => "true","repeat_precription_details" => $prev_prescription,"template_deatils" => $last_five_templates,"frequent_medicine_details" => $last_five_medicine, "patient_education_details" => $patient_education);
		$success = array('status' => "true","repeat_precription_details" => $prev_prescription,"template_deatils" => $last_five_templates,"frequent_medicine_details" => $last_five_medicine,"patient_education_details" => jsonEncodeArray($patient_education));
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>