<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//My Patients Add Trends Ophthal
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$TransId=time();
	
	if($login_type == 1) {						// Premium LoginType
		$patient_id = $_POST['se_patient_id'];
		
		$txtEntryDate = date('Y-m-d',strtotime($_POST['trend_add_date']));
		
		$txt_DvSpeherRE =  $_POST['dvSphereRE'];
		$txt_DvCylRE =  $_POST['dvCylRE'];
		$txt_DvAxisRE =  $_POST['dvAxisRE'];
		$txt_DvSpeherLE =  $_POST['dvSpeherLE'];
		$txt_DvCylLE =  $_POST['dvCylLE'];
		$txt_DvAxisLE =  $_POST['dvAxisLE'];
		$txt_NvSpeherRE =  $_POST['nvSpeherRE'];
		$txt_NvCylRE =  $_POST['nvCylRE'];
		$txt_NvAxisRE =  $_POST['nvAxisRE'];
		$txt_NvSpeherLE =  $_POST['nvSpeherLE'];
		$txt_NvCylLE =  $_POST['nvCylLE'];
		$txt_NvAxisLE =  $_POST['nvAxisLE'];
		$txt_IpdRE =  $_POST['ipdRE'];
		$txt_IpdLE =  $_POST['ipdLE'];

		
		//Insert to 'trend_analysis Ophthal'
		$arrFields = array();
		$arrValues = array();
				
		$arrFields[]='patient_id';
		$arrValues[]=$patient_id;
				
		if(!empty($_POST['visit_entry_date'])) {
			$arrFields[] = 'date_added';
			$arrValues[] = date('Y-m-d',strtotime($txtEntryDate));
		}
		
				
		if(!empty($txt_DvSpeherRE)){
			$arrFields[]='DvSphereRE';
			$arrValues[]=$txt_DvSpeherRE;
		}
				
		if(!empty($txt_DvCylRE)){
			$arrFields[]='DvCylRE';
			$arrValues[]=$txt_DvCylRE;
		}
				
		if(!empty($txt_DvAxisRE)){
			$arrFields[]='DvAxisRE';
			$arrValues[]=$txt_DvAxisRE;
		}
				
		if(!empty($txt_DvSpeherLE)){
			$arrFields[]='DvSpeherLE';
			$arrValues[]=$txt_DvSpeherLE;
		}
				
		if(!empty($txt_DvCylLE)){
			$arrFields[]='DvCylLE';
			$arrValues[]=$txt_DvCylLE;
		}
				
		if(!empty($txt_DvAxisLE)){
			$arrFields[]='DvAxisLE';
			$arrValues[]=$txt_DvAxisLE;
		}
				
		if(!empty($txt_NvSpeherRE)){
			$arrFields[]='NvSpeherRE';
			$arrValues[]=$txt_NvSpeherRE;
		}
				
		if(!empty($txt_NvCylRE)){
			$arrFields[]='NvCylRE';
			$arrValues[]=$txt_NvCylRE;
		}
				
		if(!empty($txt_NvAxisRE)){
			$arrFields[]='NvAxisRE';
			$arrValues[]=$txt_NvAxisRE;
		}
				
		if(!empty($txt_NvSpeherLE)){
			$arrFields[]='NvSpeherLE';
			$arrValues[]=$txt_NvSpeherLE;
		}
				
		if(!empty($txt_NvCylLE)){
			$arrFields[]='NvCylLE';
			$arrValues[]=$txt_NvCylLE;
		}
				
		if(!empty($txt_NvAxisLE)){
			$arrFields[]='NvAxisLE';
			$arrValues[]=$txt_NvAxisLE;
		}
				
		if(!empty($txt_IpdRE)){
			$arrFields[]='IpdRE';
			$arrValues[]=$txt_IpdRE;
		}
				
		if(!empty($txt_IpdLE)){
			$arrFields[]='IpdLE';
			$arrValues[]=$txt_IpdLE;
		}
				
		$arrFields[]='patient_type';
		$arrValues[]="1";
				
		$insert_trends = $objQuery->mysqlInsert('trend_analysis_ophthal',$arrFields,$arrValues);
		
		$getTrends = $objQuery->mysqlSelect("*","trend_analysis_ophthal","patient_id='".$patient_id."'","","","","");
		

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