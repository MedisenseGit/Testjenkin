<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("get_config.php");

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();


   //TO CHECK AUTHENTICATION OF POST VALUES
   
	if(API_KEY == $_POST['apikey'] && !empty($_POST['serachresult'])){
		$patId = $_POST['patid'];
	$serchResult = $_POST['serachresult'];
		
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'patient_id';
		$arrValues[] = $patId;
		$arrFields[] = 'search_result';
		$arrValues[] = $serchResult;
		$arrFields[] = 'timestamp';
		$arrValues[] = $Cur_Date;
		
		
		$usercraete=$objQuery->mysqlInsert('search_history',$arrFields,$arrValues);
	
	}
?>


