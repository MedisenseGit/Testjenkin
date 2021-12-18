<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// My Patients View Reports Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = $_POST['patient_id'];
	
	if($login_type == 1) {  // Premium Login
		$reports_details= array();
		$response["rep_details"] = array();
	
		$doc_patient_reports = $objQuery->mysqlSelect("*","doc_my_patient_reports","patient_id = '".$patient_id."' ","report_folder desc","","","");
	
			foreach($doc_patient_reports as $doc_patient_reports){	
			
				$getReportList['report_id']=$doc_patient_reports['report_id'];
				$getReportList['patient_id']=$doc_patient_reports['patient_id'];
				$getReportList['report_folder']=$doc_patient_reports['report_folder'];
				$getReportList['user_id']=$doc_patient_reports['user_id'];
				$getReportList['user_type']=$doc_patient_reports['user_type'];
				$getReportList['date_added']=$doc_patient_reports['date_added'];
				
				$get_reports = $objQuery->mysqlSelect("*","doc_my_patient_reports","report_folder = '".$doc_patient_reports['report_folder']."'","","","","");
				if($get_reports[0]['user_type']=='1'){
					$patient_tab = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$get_reports[0]['user_id']."'","","","","");
					$username=$patient_tab[0]['patient_name'];
					$getReportList['username']=$username;
				}
				if($get_reports[0]['user_type']=='2'){
					$get_doc_details = $objQuery->mysqlSelect("*","referal","ref_id='".$get_reports[0]['user_id']."'","","","","");
	
						$username=$get_doc_details[0]['ref_name'];
						$getReportList['username']=$username;
				}
				if($get_reports[0]['user_type']=='3'){
					$get_daignosis = $objQuery->mysqlSelect("diagnosis_name","Diagnostic_center","diagnostic_id = '".$get_reports[0]['user_id']."'","","","","");
					$username=$get_daignosis[0]['diagnosis_name'];
					$getReportList['username']=$username;
				}
				
				$getReportList['user_type']=$doc_patient_reports['user_type'];
				$getReportList['attachments']=$doc_patient_reports['attachments'];
				
			
				array_push($reports_details, $getReportList);
		}			
		
		$success = array('result' => "true","reports_details"=>$reports_details);
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "false");
		echo json_encode($success);
	} 
		

	
}


?>