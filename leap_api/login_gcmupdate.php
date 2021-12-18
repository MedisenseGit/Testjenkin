<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['login_type']) && isset($_POST['user_id']) && isset($_POST['gcm_token'])) {
	 
    $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$logintype = $_POST['login_type'];
	$user_id = $_POST['user_id'];
	$gcm_tokenid = $_POST['gcm_token'];
	
	$arrField1 = array();
	$arrValues1 = array();
	
	$arrField1[] = 'gcm_tokenid';
	$arrValues1[] = $gcm_tokenid;
	
	
	if($logintype == 3)		// Type-3 Marketing Person
	{
		$update_market=$objQuery->mysqlUpdate('hosp_marketing_person',$arrField1,$arrValues1," person_id ='".$user_id."'");
		$success_market = array('status' => "true",'usertype' => $logintype,"updategcm_details" => $update_market);
		echo json_encode($success_market);
	}
	else if($logintype == 1)	// Type-1 Hospital Doctors
	{
		$update_hospdoc=$objQuery->mysqlUpdate('referal',$arrField1,$arrValues1," ref_id ='".$user_id."'");
		$success_hosp = array('status' => "true",'usertype' => $logintype,"updategcm_details" => $update_hospdoc);
		echo json_encode($success_hosp);
	}
	else if($logintype == 2)	// Type-2 Referring Partners
	{
		$update_partner=$objQuery->mysqlUpdate('our_partners',$arrField1,$arrValues1," partner_id ='".$user_id."'");
		$success_partner = array('status' => "true",'usertype' => $logintype,"updategcm_details" => $update_partner);
		echo json_encode($success_partner);
	}
	
}


?>