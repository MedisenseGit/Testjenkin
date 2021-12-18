<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];

if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");


if(isset($_GET['updatecon']) && !empty($_GET['updatecon']))
{
	
	if(isset($_GET['pathyper']))
	{
				$arrFileds_medical[]='hyper_cond';
				$arrValues_medical[]=$_GET['pathyper'];
	}
	if(isset($_GET['patdiabetes']))
	{
				$arrFileds_medical[]='diabetes_cond';
				$arrValues_medical[]=$_GET['patdiabetes'];
	}
	if(isset($_GET['patsmoke']))
	{
				$arrFileds_medical[]='smoking';
				$arrValues_medical[]=$_GET['patsmoke'];
	}
	if(isset($_GET['patalcohol']))
	{
				$arrFileds_medical[]='alcoholic';
				$arrValues_medical[]=$_GET['patalcohol'];
	}
	
	if(isset($_GET['previntervent']))
	{
				$arrFileds_medical[]='prev_inter';
				$arrValues_medical[]=$_GET['previntervent'];
	}
	if(isset($_GET['neuroissue']))
	{
				$arrFileds_medical[]='neuro_issue';
				$arrValues_medical[]=$_GET['neuroissue'];
	}
	if(isset($_GET['kedneyissue']))
	{
				$arrFileds_medical[]='kidney_issue';
				$arrValues_medical[]=$_GET['kedneyissue'];
	}
		if(isset($_GET['otherdetail']))
	{
				$arrFileds_medical[]='other_details';
				$arrValues_medical[]=$_GET['otherdetail'];
	}
	
		
	$update_medical=mysqlUpdate('doc_my_patient',$arrFileds_medical,$arrValues_medical,"md5(patient_id) = '".$_GET['patientid']."'");

}

?>