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
		
		$getFrequentDrugAbuse= $objQuery->mysqlSelect("a.fda_id as fda_id, a.drug_abuse_id as drug_abuse_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.drug_abuse as drug_abuse","doctor_frequent_drug_abuse as a inner join drug_abuse_auto as b on a.drug_abuse_id = b.drug_abuse_id","a.doc_id='".$admin_id."' and a.doc_type ='1'","a.freq_count desc","","","0,8");

		$selectDrugAbuse= $objQuery->mysqlSelect("*","drug_abuse_auto"," (doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","drug_abuse asc","","","");

		$success = array('status' => "true","frequent_drug_abuse_details" => $getFrequentDrugAbuse,"drug_abuse_details" => $selectDrugAbuse);
		echo json_encode($success);
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>