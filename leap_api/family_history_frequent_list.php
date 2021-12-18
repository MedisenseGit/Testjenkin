<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Medical History Drug Abuse
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {						// Premium LoginType
		
		$getFrequentFamilyHistory= $objQuery->mysqlSelect("a.ffh_id as ffh_id, a.family_history_id as family_history_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.family_history as family_history","doctor_frequent_family_history as a inner join family_history_auto as b on a.family_history_id = b.family_history_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");

		$selectFamilyHistory= $objQuery->mysqlSelect("*","family_history_auto"," (doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","family_history asc","","","");

		$success = array('status' => "true","frequent_family_history_details" => $getFrequentFamilyHistory,"family_history_details" => $selectFamilyHistory);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>