<?php 
ob_start();
error_reporting(0);
session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$arrFields_newAddress = $data ->arrFields_newAddress;
if(HEALTH_API_KEY == $data ->api_key  && isset($data ->patient_id))	
{	
	$patient_id=$data ->patient_id;
	$login_id = $data ->userid;
	
	$patient_array = mysqlSelect("*","appointment_transaction_detail","md5(patient_id) ='".$patient_id."' ","","","","");
	
	
	$user_family_member = mysqlSelect("*","user_family_member","member_id ='".$patient_array[0]['member_id']."' ","","","","");
	
	
		// Get Latest Consultation Deatils
		$get_Episodes = mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"md5(a.patient_id) ='".$patient_id."'","a.episode_id DESC","","","0,1");
		
		$episode_id=$get_Episodes[0]['episode_id'];
		$patient_id=$get_Episodes[0]['patient_id'];
		
		$episode_details= array();
		$getEpiList['episode_id']=$get_Episodes[0]['episode_id'];
		$getEpiList['emr_type']=$get_Episodes[0]['emr_type'];
		$getEpiList['admin_id']=$get_Episodes[0]['admin_id'];
		$getEpiList['patient_id']=$get_Episodes[0]['patient_id'];
		$getEpiList['episode_medical_complaint']=$get_Episodes[0]['episode_medical_complaint'];
		$getEpiList['examination']=$get_Episodes[0]['examination'];
		$getEpiList['treatment']=$get_Episodes[0]['treatment'];
		$getEpiList['next_followup_date']=$get_Episodes[0]['next_followup_date'];
		$getEpiList['date_time']=$get_Episodes[0]['date_time'];
		$getEpiList['ref_name']=$get_Episodes[0]['ref_name'];
		$getEpiList['ref_id']=$get_Episodes[0]['ref_id'];
		$getEpiList['prescription_note']=$get_Episodes[0]['prescription_note'];
		$getEpiList['diagnosis_details']=$get_Episodes[0]['diagnosis_details'];
		$getEpiList['treatment_details']=$get_Episodes[0]['treatment_details'];
		
		$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"episode_id='".$get_Episodes[0]['episode_id']."'","","","","");
		//$getEpiList['prescription_result']=$prescription_result;
		
		
		$prescription_details= array();
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
			
			$prescription_timings	 = mysqlSelect('*','doc_medicine_timing_language',"language_id='".$listPrescriptionList['timing']."'","","","","");
			$getPrescList['timing']	=$prescription_timings[0]['english'];
			
			array_push($prescription_details, $getPrescList);
		}
		
		$getEpiList['prescription_result']=$prescription_details;
		
		
		$investigation_result = mysqlSelect('*','patient_temp_investigation',"episode_id='".$get_Episodes[0]['episode_id']."' and patient_id='".$get_Episodes[0]['patient_id']."'","","","","");
		$getEpiList['investigation_result']=$investigation_result;
		
		$specialization_details= array();
		
		$appointment_result = mysqlSelect('*','doc_patient_episodes',"episode_id='".$get_Episodes[0]['episode_id']."' and patient_id='".$get_Episodes[0]['patient_id']."'","","","","");
		
		foreach($appointment_result as $listspecilization)
		{
			$getAppionList['referTo']				= $listspecilization['referTo'];
			$getAppionList['episode_id']			= $listspecilization['episode_id'];
			$getAppionList['spec_id']				= $listspecilization['specialization'];
			$getAppionList['patientNote']			= $listspecilization['patientNote'];
			$getAppionList['patient_id']			= $listspecilization['patient_id'];
			
			$specialization_result = mysqlSelect('*','specialization',"spec_id='".$listspecilization['specialization']."'","","","","");
			
			
			$getAppionList['specialization']			= $specialization_result[0]['spec_name'];
			array_push($specialization_details, $getAppionList);
			
		}
		
		$getEpiList['appointment_result']=$specialization_details;
		//$getEpiList['specialization_result']=$specialization_result;
		
		
		$chief_medical_complaint_result = mysqlSelect('a.symptoms_id as symptoms_autoid, a.symptoms as symptoms_id, b.symptoms as symptoms_name, a.patient_id as patient_id, a.episode_id as episode_id, a.doc_id as doc_id, a.doc_type as doc_type','doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id',"a.episode_id='".$get_Episodes[0]['episode_id']."' and a.patient_id='".$get_Episodes[0]['patient_id']."'","","","","");
		$getEpiList['chief_medical_complaint_result']=$chief_medical_complaint_result;
		
		array_push($episode_details, $getEpiList);
	
	
		$response = array('status' => "true","patient_details" => $patient_array,"user_family_member"=>$user_family_member, "episode_details"=>$episode_details);
	
	echo json_encode($response );
}
else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}



?>


