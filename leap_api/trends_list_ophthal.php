<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//TREND LIST OPHTHAL
 if(API_KEY == $_POST['API_KEY']) {
	 
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	$patient_id = $_POST['patient_id'];
	
	if($login_type == 1)		// Type-1 Premium Login
	{
		$getTrends = $objQuery->mysqlSelect("*","trend_analysis_ophthal","patient_id='".$patient_id."'","","","","");
		$getSpectaclePrescriptions = $objQuery->mysqlSelect("a.spectacle_id as spectacle_id, a.episode_id as episode_id, a.doc_id as doc_id, a.distacnce_vision_right as distacnce_vision_right, a.distance_vision_left as distance_vision_left, a.near_vision_right as near_vision_right, a.near_vision_left as near_vision_left, a.dvSphereRE as dvSphereRE, a.DvCylRE as DvCylRE, a.DvAxisRE as DvAxisRE, a.DvSpeherLE as DvSpeherLE, a.DvCylLE as DvCylLE, a.DvAxisLE as DvAxisLE, a.NvSpeherRE as NvSpeherRE, a.NvCylRE as NvCylRE, a.NvAxisRE as NvAxisRE, a.NvSpeherLE as NvSpeherLE, a.NvCylLE as NvCylLE, a.NvAxisLE as NvAxisLE, a.IpdRE as IpdRE, a.IpdLE as IpdLE","examination_opthal_spectacle_prescription as a inner join doc_patient_episodes as b on b.episode_id=a.episode_id","b.patient_id='".$patient_id."'","","","","");
		
		$success = array('status' => "true","trends_details" => $getTrends,"prescription_details" => $getSpectaclePrescriptions);     
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","trends_details" => $getTrends,"prescription_details" => $getSpectaclePrescriptions);     
		echo json_encode($success);
	}
	
}


?>