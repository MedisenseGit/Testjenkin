<?php 
ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("../classes/querymaker.class.php");

ob_start();


$id		 = $_POST['id'];
$type    = $_POST['type']; // table name  type 1 = doctor_academics , type 2= doc_work_exp , type 3 = doctor_registration_details

if($type=="1")
{
	$delect_val =  mysqlDelete('doctor_academics',"id='".$id."'");
}

if($type=="2")
{
	$delect_val =  mysqlDelete('doc_work_exp',"id='".$id."'");
}	

if($type=="3")
{
	$delect_val =  mysqlDelete('doctor_registration_details',"reg_det_id='".$id."'");
}	


?>