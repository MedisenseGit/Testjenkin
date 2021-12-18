<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Examination Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	if($login_type == 1) {						// Premium LoginType
	
		$response["exam_template_details"] = array();
		
		$getFrequentExam = $objQuery->mysqlSelect("a.dfe_id as dfe_id, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_count as freq_count, b.examination_id as examination_id,b.examination as examination","doctor_frequent_examination as a left join examination as b on a.examination_id=b.examination_id","(a.doc_id='".$admin_id."' and a.doc_type='1') or (a.doc_id='0' and a.doc_type='0')","a.freq_count DESC","","","8");
		$selectExamination= $objQuery->mysqlSelect("*","examination"," (doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","examination asc","","","");

		$exam_template_details= array();
		$exam_templates = $objQuery->mysqlSelect("*","doc_patient_episode_examination_templates","doc_id='".$admin_id."' and doc_type='1' ","exam_template_id desc","","","8");
		foreach($exam_templates as $exam_templatesList) {
			$stuff= array();
			
			$get_details = $objQuery->mysqlSelect('b.exam_template_id as exam_template_id, b.default_visible as default_visible, b.template_name as template_name, a.examination as examination_id, c.examination as examination, b.doc_id as doc_id, b.doc_type as doc_type, a.exam_result as exam_result, a.findings as findings','doc_patient_episode_examination_template_details as a inner join doc_patient_episode_examination_templates as b on a.exam_template_id = b.exam_template_id inner join examination as c on c.examination_id = a.examination',"a.exam_template_id = '".$exam_templatesList['exam_template_id']."'","","","","");
		
			foreach($get_details as $get_detailsList) {
				$stuff["exam_template_id"] = $get_detailsList['exam_template_id'];		
				$stuff["default_visible"] = $get_detailsList['default_visible'];			
				$stuff["template_name"] = $get_detailsList['template_name'];
				$stuff["examination_id"] = $get_detailsList['examination_id'];
				$stuff["examination"] = $get_detailsList['examination'];
				$stuff["doc_id"] = $get_detailsList['doc_id'];
				$stuff["doc_type"] = $get_detailsList['doc_type'];
				$stuff["exam_result"] = $get_detailsList['exam_result'];
				$stuff["findings"] = $get_detailsList['findings'];
				
				 array_push($response["exam_template_details"], $stuff);
			}
		}			

		$response["status"] = "true";
		$response["frequent_examination_details"] = $getFrequentExam;
		$response["examination_details"] = $selectExamination;
		echo(json_encode($response));
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>