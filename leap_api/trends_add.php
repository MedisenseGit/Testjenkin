<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//My Patients Add Trends
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$TransId=time();
	
	if($login_type == 1) {						// Premium LoginType
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

		$insert_trends = $objQuery->mysqlInsert('trend_analysis',$arrFields,$arrValues);
		
		$getTrends = $objQuery->mysqlSelect("*","trend_analysis","patient_id='".$patient_id."'","","","","");

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
			$success = array('result' => "failure");
			echo json_encode($success);
	}	
		
}


?>