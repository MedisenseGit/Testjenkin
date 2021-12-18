
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




if(HEALTH_API_KEY == $data ->api_key  && isset($data ->member_id))	
{	
	$member_id 	 = $data ->member_id;
	
	$get_member_details = mysqlSelect("member_id,member_name","user_family_member","member_id ='".$member_id."'","","","","");
	
	
	
	$medicine_list = mysqlSelect("prescription_trade_name,prescription_generic_name,episode_prescription_id","doc_patient_episode_prescriptions","","","pp_id","","");
	
	
	$get_patient = mysqlSelect("patient_id","doc_my_patient","member_id ='".$member_id."' and teleconsult_status=1","patient_id DESC","","","1");
	
	// Get Latest Consultation Deatils
	
	$get_Episodes =mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"(a.patient_id) ='".$get_patient[0]['patient_id']."'","a.episode_id DESC","","","0,1");
	
	$episode_details= array();
	$prescription_details	= array();
	
	$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"(episode_id)='".$get_Episodes[0]['episode_id']."'","","","","");
	
	
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
	$getEpiList['prescription_result']=$prescription_details;
	array_push($episode_details, $getEpiList);
	
	
	
	//$response = array("get_member_details"=>$get_member_details);
	//echo json_encode($response);
	
	
	$response = array('status' => "true","episode_id"=>$get_Episodes[0]['episode_id'],"date_time"=>$get_Episodes[0]['date_time'],"doc_name"=>$get_Episodes[0]['ref_name'],"episode_details"=>$episode_details,"list_medicine"=>$medicine_list,"get_member_details"=>$get_member_details);
	
	echo json_encode($response);
}
else if(HEALTH_API_KEY == $data ->api_key  && isset($data ->admin_id))	
{	
	
	$admin_id 	 = $data ->admin_id;
	$medicine_list = mysqlSelect("prescription_trade_name,prescription_generic_name,episode_prescription_id","doc_patient_episode_prescriptions","","","pp_id","","");
	
	$get_member = mysqlSelect("member_id,member_name","user_family_member","user_id ='".$admin_id."'","","","","");
	
	$get_member_details = mysqlSelect("member_id,member_name","user_family_member","user_id ='".$admin_id."' and member_type='primary'","","","","");
	
	$episode_details		= array();
	$prescription_details	= array();
	foreach($get_member as $member)
	{
		
		$get_patient = mysqlSelect("patient_id","doc_my_patient","member_id ='".$member['member_id']."' and teleconsult_status=1","patient_id DESC ","","","5");
		$getPrescList['patient_id']	= $get_patient[0]['patient_id'];
		$getPrescList['member_id']	= $member['member_id'];
		$getPrescList['member_name']	= $get_member[0]['member_name'];
		
		
		$get_Episodes =mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"(a.patient_id) ='".$get_patient[0]['patient_id']."'","a.episode_id DESC","","","0,1");
		
		
		$getPrescList['episode_id']	= $get_Episodes[0]['episode_id'];
		$getPrescList['date_time']	= $get_Episodes[0]['date_time'];
		$getPrescList['ref_name']	= $get_Episodes[0]['ref_name'];
		array_push($prescription_details, $getPrescList);
	}
	
	$getPrescList['newmember_id']	= $get_member[0]['member_id'];
	$getPrescList['newmember_name']	= $get_member[0]['member_name'];
	$getEpiList['episode_val']=$prescription_details;
	array_push($episode_details, $getEpiList);
	
	$response = array('status' => "true","get_member"=>$get_member,"episode_details"=>$episode_details,"newmember_id"=>$get_member[0]['member_id'],"newmember"=> $get_member[0]['member_name'],"get_member_details"=>$get_member_details,"list_medicine"=>$medicine_list);
	
	echo json_encode($response);
}
else if(HEALTH_API_KEY == $data ->api_key  &&  isset($data ->episode_id))
{
	$episode_id = $data ->episode_id;
	$memberid   = $data ->memberid;
	$episode_details		= array();
	$prescription_details	= array(); 
	
	$get_Episodes =	mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"md5(a.episode_id) ='".$episode_id."'","a.episode_id DESC","","","");
		
	$get_patient = mysqlSelect("member_id","doc_my_patient","patient_id ='".$get_Episodes[0]['patient_id']."' and teleconsult_status=1","patient_id DESC","","","");	
		
	$getEpiList['episode_id']	= $get_Episodes[0]['episode_id'];
	$getEpiList['date_time']	= $get_Episodes[0]['date_time'];
	$getEpiList['ref_name']		= $get_Episodes[0]['ref_name'];
	$getEpiList['patient_id']	= $get_Episodes[0]['patient_id'];
	
	if(!empty($memberid))
	{
		$get_member_details = mysqlSelect("member_name,member_id","user_family_member","member_id='".$memberid."' ","","","","");
		$getEpiList['member_name']		= $family_names_array[0]['member_name'];
		$getEpiList['member_id']		= $family_names_array[0]['member_id'];
	}
	else
	{
		$get_member_details = mysqlSelect("member_name,member_id","user_family_member","member_id='".$get_patient[0]['member_id']."' ","","","","");
		$getEpiList['member_name']		= $family_names_array[0]['member_name'];
		$getEpiList['member_id']		= $get_patient[0]['member_id'];
	}
	
	
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
	
	$getEpiList['prescription_result']	=	$prescription_details;
	array_push($episode_details, $getEpiList);
	
	$response1 = array('status' => "true","episode_details"=>$episode_details,"get_member_details"=>$get_member_details);
	
	echo json_encode($response1 );
}
else if(HEALTH_API_KEY == $data ->api_key  &&  isset($data ->pid))
{
	$pid = $data ->pid;
	$episode_details 		= array();
	$prescription_details 	= array();
	
	$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"(episode_prescription_id)='".$pid."'","","","","");
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
	
	$getEpiList['prescription_result']	=	$prescription_details;
	array_push($episode_details, $getEpiList);
	
	$response = array('status' => "true","episode_details"=>$episode_details);
	echo json_encode($response);
	
}
else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}



?>


