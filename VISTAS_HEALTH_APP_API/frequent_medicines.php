<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

//$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

// Frequently Ordered Medicine
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey)
	{
			$admin_id = $user_id;
			$last_five_medicine = mysqlSelect("a.freq_medicine_id as freq_medicine_id, a.pp_id as pp_id, a.med_trade_name as med_trade_name, a.med_generic_name as med_generic_name, a.med_frequency as med_frequency, a.med_frequency_morning as med_frequency_morning, a.med_frequency_noon as med_frequency_noon, a.med_frequency_night as med_frequency_night, b.english as med_timing, a.med_duration as med_duration, a.med_duration_type as med_duration_type, a.prescription_instruction as prescription_instruction, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count","doctor_frequent_medicine as a inner join doc_medicine_timing_language as b on b.language_id = a.med_timing","a.doc_type ='1'","a.freq_count desc","","","0,10");
			
			$get_member = mysqlSelect("member_id","user_family_member","user_id='".$admin_id."'","","","","");
			foreach($get_member as $member)
			{
				
				$get_patient = mysqlSelect("patient_id","doc_my_patient","member_id='".$member['member_id']."'","","","","");
			
				$prev_episode = mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$get_patient[0]['patient_id']."'","b.episode_id desc","","","1");

				
				if(COUNT($prev_episode)>0)
				{
					$prev_prescription = mysqlSelect("b.episode_id as episode_id, b.episode_prescription_id as episode_prescription_id, b.doc_id as doc_id, b.pp_id as pp_id, b.prescription_trade_name as prescription_trade_name, b.prescription_generic_name as prescription_generic_name, b.prescription_dosage_name as prescription_dosage_name, b.timing as timing, b.duration as duration, b.med_frequency_morning as med_frequency_morning, b.med_frequency_noon as med_frequency_noon, b.med_frequency_night as med_frequency_night, b.med_duration_type as med_duration_type, b.prescription_instruction as prescription_instruction","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","b.episode_id='".$prev_episode[0]['episode_id']."'","","","","");								
				}
			
			}
			
			
			
		$admin_id 	 = $user_id;
		$get_member = mysqlSelect("member_id,member_name","user_family_member","user_id ='".$admin_id."'","","","","");
	
		$episode_details		= array();
		$prescription_details	= array();	
		foreach($get_member as $member)
		{
			
			$get_patient = mysqlSelect("patient_id","doc_my_patient","member_id ='".$member['member_id']."' and teleconsult_status=1","patient_id DESC ","","","");
			$getPrescList['patient_id']		= $get_patient[0]['patient_id'];
			$getPrescList['member_id']		= $member['member_id'];
			$getPrescList['member_name']	= $get_member[0]['member_name'];
			
			
			$get_Episodes 	=	mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"(a.patient_id) ='".$get_patient[0]['patient_id']."'","a.episode_id DESC","","","0,1");
			
			
			$getPrescList['episode_id']	= $get_Episodes[0]['episode_id'];
			$getPrescList['date_time']	= $get_Episodes[0]['date_time'];
			$getPrescList['ref_name']	= $get_Episodes[0]['ref_name'];
			
			array_push($prescription_details, $getPrescList);
		
			$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"md5(episode_id)='".$episode_id."'","","","","");
		
		
			foreach($prescription_result as $listPrescriptionList)
			{
				$getPrescList['episode_prescription_id']	= $listPrescriptionList['episode_prescription_id'];
				$getPrescList['episode_id']					= $listPrescriptionList['episode_id'];
				$getPrescList['prescription_trade_name']	= $listPrescriptionList['prescription_trade_name'];
				$getPrescList['prescription_generic_name']	= $listPrescriptionList['prescription_generic_name'];
				$getPrescList['prescription_frequency']		= $listPrescriptionList['prescription_frequency'];
				$getPrescList['duration']					= $listPrescriptionList['duration'];
				$getPrescList['med_duration_type']			= $listPrescriptionList['med_duration_type'];
				
				$getPrescList['doc_id']						= $listPrescriptionList['doc_id'];
				$getPrescList['pp_id']						= $listPrescriptionList['pp_id'];
				$getPrescList['patient_id']					= $get_Episodes[0]['patient_id'];
				
				$prescription_timings = mysqlSelect('*','doc_medicine_timing_language',"language_id='".$listPrescriptionList['timing']."'","","","","");
				
				$getPrescList['timing']	=	$prescription_timings[0]['english'];
				
				array_push($prescription_details, $getPrescList);
			}
			
		}
		
	$getEpiList['prescription_result']	=	$prescription_details;
	array_push($episode_details, $getEpiList);
	
	$success = array('status' => "true", "frequent_medicine_details" => $last_five_medicine,"repeat_precription_details" => $prev_prescription, 'err_msg' => '',"episode_details"=>$episode_details );
	echo json_encode($success);
		
	}
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
}

?>
