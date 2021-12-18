<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");

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

$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		
		$last_five_templates = mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$doctor_id."'","template_id desc","","","0,10");
		$last_five_medicine = mysqlSelect("a.freq_medicine_id as freq_medicine_id, a.pp_id as pp_id, a.med_trade_name as med_trade_name, a.med_generic_name as med_generic_name, a.med_frequency as med_frequency, a.med_frequency_morning as med_frequency_morning, a.med_frequency_noon as med_frequency_noon, a.med_frequency_night as med_frequency_night, b.english as med_timing, a.med_duration as med_duration, a.med_duration_type as med_duration_type, a.prescription_instruction as prescription_instruction, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count","doctor_frequent_medicine as a inner join doc_medicine_timing_language as b on b.language_id = a.med_timing","a.doc_id='".$doctor_id."' and a.doc_type ='1'","a.freq_count desc","","","0,10");
		
		$prev_episode = mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$doctor_id."' and a.patient_id='".$patient_id."'","b.episode_id desc","","","1");								
		if(COUNT($prev_episode)>0) {
			$prev_prescription = mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration, b.med_frequency_morning as med_frequency_morning, b.med_frequency_noon as med_frequency_noon, b.med_frequency_night as med_frequency_night, b.med_duration_type as med_duration_type, b.prescription_instruction as prescription_instruction","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","b.episode_id='".$prev_episode[0]['episode_id']."'","","","","");								
		}

		$patient_education = mysqlSelect("*","patient_education","doc_id='".$doctor_id."' and doc_type ='1'","edu_id desc","","","");
				
		
		//$success = array('status' => "true","repeat_precription_details" => $prev_prescription,"template_deatils" => $last_five_templates,"frequent_medicine_details" => $last_five_medicine, "patient_education_details" => $patient_education);
		$success = array('status' => "true","repeat_precription_details" => $prev_prescription,"template_deatils" => $last_five_templates,"frequent_medicine_details" => $last_five_medicine,"patient_education_details" => jsonEncodeArray($patient_education));
		echo json_encode($success);
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}

?>