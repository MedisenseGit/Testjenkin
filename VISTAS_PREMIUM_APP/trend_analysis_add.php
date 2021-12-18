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

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {

	if($finalHash == $hashKey) {
			
		$admin_id =  $doctor_id;
		
		date_default_timezone_set('Asia/Kolkata');
		$Cur_Date=date('Y-m-d H:i:s');
		$TransId=time();

		$patient_id = $_POST['se_patient_id'];
		$txtWeight = $_POST['se_weight'];
		$txtHyperDate = date('Y-m-d',strtotime($_POST['se_hyper_date']));
		$txtDiabetesDate = $_POST['se_diabetes_date'];
		$txtCholestrolDate = $_POST['se_cholestroldate'];
		$txtSystolic = $_POST['se_systolic'];
		$txtDiastolic = $_POST['se_diastolic'];
		$txtPrePrandial = $_POST['se_preprandial'];
		$txtPostPrandial = $_POST['se_postprandial'];
		$txtHba1c = $_POST['se_hba1c'];
		$txtTriglycerides = $_POST['se_trigycerides'];
		$txtTotalCholestrol = $_POST['se_total_cholestrol'];
		$txtHdl = $_POST['se_hdl'];
		$txtLdl = $_POST['se_ldl'];
		$txtVldl = $_POST['se_vldl'];
		
		$arrFields = array();
		$arrValues = array();

		
		$arrFields[] = 'date_added';
		$arrValues[] = $txtHyperDate;

		$arrFields[] = 'systolic';
		$arrValues[] = $txtSystolic;

		$arrFields[] = 'diastolic';
		$arrValues[] = $txtDiastolic;

		$arrFields[] = 'bp_beforefood_count';
		$arrValues[] = $txtPrePrandial;
		
		$arrFields[] = 'bp_afterfood_count';
		$arrValues[] = $txtPostPrandial;

		$arrFields[] = 'HbA1c';
		$arrValues[] = $txtHba1c;
		
		$arrFields[] = 'triglyceride';
		$arrValues[] = $txtTriglycerides;

		$arrFields[] = 'cholesterol';
		$arrValues[] = $txtTotalCholestrol;

		$arrFields[] = 'HDL';
		$arrValues[] = $txtHdl;
		
		$arrFields[] = 'LDL';
		$arrValues[] = $txtLdl;
		
		$arrFields[] = 'VLDL';
		$arrValues[] = $txtVldl;

		$arrFields[] = 'patient_id';
		$arrValues[] = $patient_id;

		$arrFields[] = 'patient_type';
		$arrValues[] = "1";

		$insert_trends = mysqlInsert('trend_analysis',$arrFields,$arrValues);
		
		$getTrends = mysqlSelect("*","trend_analysis","patient_id='".$patient_id."'","","","","");

		if($insert_trends == true)
		{
			$success = array('result' => "success", "trends_result"=>$getTrends);
			echo json_encode($success);
		}
		else {
			$success = array('result' => "failure", "trends_result"=>$getTrends);
			echo json_encode($success);
		}
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