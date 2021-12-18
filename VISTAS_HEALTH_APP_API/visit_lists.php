<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//My Visit Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$login_id = $user_id;
		$patient_id = $_POST['patientID'];
		
		$episode_details= array();
		
		$get_Episodes = mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"a.patient_id ='".$patient_id."'","a.episode_id DESC","","","");
		foreach($get_Episodes as $listEpisode){
			$getEpiList['episode_id']=$listEpisode['episode_id'];
			$getEpiList['emr_type']=$listEpisode['emr_type'];
			$getEpiList['admin_id']=$listEpisode['admin_id'];
			$getEpiList['patient_id']=$listEpisode['patient_id'];
			$getEpiList['episode_medical_complaint']=$listEpisode['episode_medical_complaint'];
			$getEpiList['examination']=$listEpisode['examination'];
			$getEpiList['treatment']=$listEpisode['treatment'];
			$getEpiList['next_followup_date']=$listEpisode['next_followup_date'];
			$getEpiList['date_time']=$listEpisode['date_time'];
			$getEpiList['ref_name']=$listEpisode['ref_name'];
			$getEpiList['ref_id']=$listEpisode['ref_id'];
			$getEpiList['prescription_note']=$listEpisode['prescription_note'];
			$getEpiList['diagnosis_details']=$listEpisode['diagnosis_details'];
			$getEpiList['treatment_details']=$listEpisode['treatment_details'];
			
			array_push($episode_details, $getEpiList);
		}
	
					
		$success_wallet = array('result' => "success", "episode_details"=>$episode_details, 'message' => "Your Consulatations !!!", 'err_msg' => '');
		echo json_encode($success_wallet);
		
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
