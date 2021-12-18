<?php
ob_start();
session_start();
error_reporting(0);  


require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$admin_id = 178;
//$ccmail="medical@medisense.me";

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$time = date("Y-m-")."-".time();


//ADD Home Page
if(isset($_POST['themechoice'])){
	$home_theme=$_POST['home_theme'];
	$home_url1=$_POST['home_url1'];
	$home_url2=$_POST['home_url2'];
	$home_url3=$_POST['home_url3'];
	
	/*echo $home_theme;
	echo $home_url1;
	echo $home_url2;
	echo $home_url3;*/
	
	$arrFields_home = array();
	$arrValues_home = array();
	
	$arrFields_home[] = 'doc_id';
	$arrValues_home[] = $admin_id;
	$arrFields_home[] = 'template_id';
	$arrValues_home[] = $home_theme;	// Template1
	$arrFields_home[] = 'doc_type';
	$arrValues_home[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	$arrFields_home[] = 'pref_url1';
	$arrValues_home[] = $home_url1;
	$arrFields_home[] = 'pref_url2';
	$arrValues_home[] = $home_url2;
	$arrFields_home[] = 'pref_url3';
	$arrValues_home[] = $home_url3;
    $arrFields_home[] = 'Time_stamp';
	$arrValues_home[] = $curDate;
					
	$get_home = mysqlSelect('*','doctor_webtemplates',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) {
		$homecreate=mysqlInsert('doctor_webtemplates',$arrFields_home,$arrValues_home);
		$response=0;
		header("Location:index.php?response=".$response);
	}
	else {
		$response=1;
		header("Location:index.php?response=".$response);
	}
		
	
}
?>