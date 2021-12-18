<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();	

//SEARCH Patient
if(isset($_POST['cmdSearch'])){
	echo $_POST['search'];
	$params     = split("-", $_POST['search']);
	if($params[0]!=0){
	$patientid = $params[0];
	header("Location:?p=".md5($patientid));
	}
	else
	{
	$patientid = "0";	
	header("Location:?p=".$patientid."&n=".$params[0]);
	}
	
	
}

if(isset($_POST['addTrendsOpthal']))
{
			$txt_DvSpeherRE =  $_POST['txtDvSpeherRE'];
			$txt_DvCylRE =  $_POST['txtDvCylRE'];
			$txt_DvAxisRE =  $_POST['txtDvAxisRE'];
			$txt_DvSpeherLE =  $_POST['txtDvSpeherLE'];
			$txt_DvCylLE =  $_POST['txtDvCylLE'];
			$txt_DvAxisLE =  $_POST['txtDvAxisLE'];
			$txt_NvSpeherRE =  $_POST['txtNvSpeherRE'];
			$txt_NvCylRE =  $_POST['txtNvCylRE'];
			$txt_NvAxisRE =  $_POST['txtNvAxisRE'];
			$txt_NvSpeherLE =  $_POST['txtNvSpeherLE'];
			$txt_NvCylLE =  $_POST['txtNvCylLE'];
			$txt_NvAxisLE =  $_POST['txtNvAxisLE'];
			$txt_IpdRE =  $_POST['txtIpdRE'];
			$txt_IpdLE =  $_POST['txtIpdLE'];
			
				$arrFields = array();
				$arrValues = array();
				
				$arrFields[]='patient_id';
				$arrValues[]=$_POST['patient_id'];
				
				$arrFields[]='patient_type';
				$arrValues[]="1";
				
				$arrFields[]='date_added';
				$arrValues[]=date('Y-m-d',strtotime($_POST['dateadded']));
				
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
				
				
				
				$checkTrend= mysqlSelect("*","trend_analysis_ophthal","date_added='".date('Y-m-d',strtotime($_POST['dateadded']))."' and patient_id='".$_POST['patient_id']."' and patient_type='1'","","","","");
				if(count($checkTrend)>0)
				{
					$update_medicine=mysqlUpdate('trend_analysis_ophthal',$arrFields,$arrValues,"date_added='".date('Y-m-d',strtotime($_POST['dateadded']))."' and patient_id = '".$_POST['patient_id']."' and patient_type='1'");
				}
				else
				{
				$insert_patient=mysqlInsert('trend_analysis_ophthal',$arrFields,$arrValues);
				}
				
	header("Location:".$_SESSION['EMR_URL'].md5($_POST['patient_id']));
}

?>