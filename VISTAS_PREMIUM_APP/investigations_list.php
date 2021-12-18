<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		
		$response["invest_template_details"] = array();
		
		$getFrequentTests = mysqlSelect("a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department ","doctor_frequent_investigations as a inner join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_id='".$doctor_id."' and a.doc_type='1'","a.freq_test_count DESC","","","8");
		$selectInvest= mysqlSelect("*","patient_diagnosis_tests","(doc_id='0' and doc_type='0') or (doc_id='".$doctor_id."' and doc_type='1')","test_name_site_name asc","","","");
		$selectGroupTest= mysqlSelect("*","patient_diagnosis_group_tests","","group_test_id asc","","","");

		$invest_template_details= array();
		$invest_templates = mysqlSelect("*","doc_patient_episode_investigations_templates","doc_id='".$doctor_id."' and doc_type='1' ","invest_template_id desc","","","8");
		foreach($invest_templates as $invest_templatesList) {
			$stuff= array();
			
			$get_details = mysqlSelect('b.invest_template_id as invest_template_id, b.doc_id as doc_id, b.doc_type as doc_type, b.default_visible as default_visible, b.template_name as template_name, a.main_test_id as main_test_id, d.test_id as group_test_id, a.test_name as test_name_site_name, c.department as department, c.normal_range as normal_range, a.test_actual_value as test_actual_value','doc_patient_episode_investigation_template_details as a inner join doc_patient_episode_investigations_templates as b on a.invest_template_id = b.invest_template_id inner join patient_diagnosis_tests as c on c.test_id = a.invest_template_id inner join patient_diagnosis_group_tests as d on d.sub_test_id = c.test_id',"a.invest_template_id = '".$invest_templatesList['invest_template_id']."'","","","","");
		
			foreach($get_details as $get_detailsList) {
				$stuff["invest_template_id"] = $get_detailsList['invest_template_id'];		
				$stuff["doc_id"] = $get_detailsList['doc_id'];			
				$stuff["doc_type"] = $get_detailsList['doc_type'];
				$stuff["default_visible"] = $get_detailsList['default_visible'];
				$stuff["template_name"] = $get_detailsList['template_name'];
				$stuff["main_test_id"] = $get_detailsList['main_test_id'];
				$stuff["group_test_id"] = $get_detailsList['group_test_id'];
				$stuff["test_name_site_name"] = $get_detailsList['test_name_site_name'];
				$stuff["department"] = $get_detailsList['department'];
				$stuff["normal_range"] = $get_detailsList['normal_range'];
				$stuff["test_actual_value"] = $get_detailsList['test_actual_value'];
		
				array_push($response["invest_template_details"], $stuff);
			}
		}		
		
		$response["status"] = "true";
		$response["frequent_investigation_details"] = $getFrequentTests;
		$response["investigation_details"] = $selectInvest;
		$response["invest_grouptest_details"] = $selectGroupTest;
		echo(json_encode($response));	
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