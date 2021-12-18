<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//My Patients create
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {						// Premium LoginType
		
		$getFrequentComplaints= $objQuery->mysqlSelect("a.dfs_id as dfs_id, a.symptoms_id as symptoms_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.symptoms as symptoms","doctor_frequent_symptoms as a inner join chief_medical_complaints as b on a.symptoms_id = b.complaint_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");

		$select= $objQuery->mysqlSelect("*","chief_medical_complaints"," (doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","symptoms asc","","","");

		$success = array('status' => "true","frequent_medcomp_details" => $getFrequentComplaints,"chief_medcomp_details" => $select);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>