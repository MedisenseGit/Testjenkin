<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

// Update Investigations
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = (int)$_POST['patient_id'];
	
	
	if($login_type == 1) {						// Premium LoginType
			
			
		/* Update Investigations */
		if(!empty($_POST['investigation_id']))	{	 
			while (list($key, $val) = each($_POST['investigation_id'])) {
				$investigation_id = $_POST['investigation_id'][$key];
				$test_id = $_POST['test_id'][$key];
				$group_test_id = $_POST['grouptest_id'][$key];
				$test_name = $_POST['test_name'][$key];
				$normal_range = $_POST['normalRange'][$key];
				$actual_value = $_POST['actualRange'][$key];
				$right_eye = $_POST['rightEyeRange'][$key];
				$left_eye = $_POST['leftEyeRange'][$key];
				$department = $_POST['departmentRange'][$key];
				
				$arrFiedInvest=array();
				$arrValueInvest=array();
				
				if(!empty($actual_value)){
				$arrFiedInvest[]='test_actual_value';
				$arrValueInvest[]=$actual_value;
				}
				
				if(!empty($left_eye)){
				$arrFiedInvest[]='left_eye';
				$arrValueInvest[]=$left_eye;
				}
				
				if(!empty($right_eye)){
				$arrFiedInvest[]='right_eye';
				$arrValueInvest[]=$right_eye;
				}
		
				$update_invest=$objQuery->mysqlUpdate('patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$investigation_id."'");
	
				//Insert to 'trend_analysis'
				$arrFieldTrend=array();
				$arrValueTrend=array();
				
				if($test_id=="GLU009")  //BLOOD GLUCOSE (Post Prandial)
				{
					$arrFieldTrend[]='bp_afterfood_count';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="GLU017") //BLOOD GLUCOSE (Fasting)
				{
					$arrFieldTrend[]='bp_beforefood_count';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="CHO001") //HDL CHOLESTEROL
				{
					
					$arrFieldTrend[]='HDL';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="LDL") //LDL CHOLESTEROL
				{
					
					$arrFieldTrend[]='LDL';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="CHOL/HDL") //VLDL
				{
					
					$arrFieldTrend[]='VLDL';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="TRI001")   //TRIGLYCERIDES
				{
					
					$arrFieldTrend[]='triglyceride';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="GLU006")  //Glyco Hb (HbA1c)
				{
					
					$arrFieldTrend[]='HbA1c';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="CHO002")  //TOTAL CHOLESTEROL
				{
					
					$arrFieldTrend[]='cholesterol';
					$arrValueTrend[]=$actual_value;
				}
				else if($test_id=="URI012") //URINE SUGAR
				{
					
					$arrFieldTrend[]='urine_sugar';
					$arrValueTrend[]=$actual_value;
				}
				
				$arrFieldTrend[]='date_added';
				$arrValueTrend[]=$cur_Date;
				$arrFieldTrend[]='patient_id';
				$arrValueTrend[]=$patient_id;
				$arrFieldTrend[]='patient_type';
				$arrValueTrend[]="1";
				$checkTrend= $objQuery->mysqlSelect("*","trend_analysis","date_added='".$cur_Date."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
				if(count($checkTrend)>0)
				{
					$update_trend=$objQuery->mysqlUpdate('trend_analysis',$arrFieldTrend,$arrValueTrend,"date_added='".$cur_Date."' and patient_id = '".$patient_id."' and patient_type='1'");
				}
				else
				{
					$insert_trend_analysis= $objQuery->mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
				}
				
			}
		}
		
			$result = array("result" => "success","frequent_medcomp_details" => $getFrequentComplaints);
			echo json_encode($result);
	}
	else {
			$success = array('result' => "failure");
			echo json_encode($success);
	}	
		
}


?>