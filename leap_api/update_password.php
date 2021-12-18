<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Update Login Account Password

 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['update_password']) && isset($_POST['login_type'])) {
	 
	// $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	//$mobile_num = $_POST['mobile_num'];
	$password = md5($_POST['update_password']);
	$login_type = $_POST['login_type'];
	
	$arrField1 = array();
	$arrValues1 = array();
	
	
	/* if($login_type == 1)				// Hospital Doctor Login
	{
		$arrField1[] = 'doc_password';
		$arrValues1[] = $password;
		$updatepassword=$objQuery->mysqlUpdate('referal',$arrField1,$arrValues1,"contact_num='".$mobile_num."'");
	}
	else if($login_type == 2)			// Partner Login
	{
		$arrField1[] = 'password';
		$arrValues1[] = $password;
		$updatepassword=$objQuery->mysqlUpdate('our_partners',$arrField1,$arrValues1,"cont_num1='".$mobile_num."'");
	}
	if($login_type == 3)				// Marketing Person Login
	{
		$arrField1[] = 'password';
		$arrValues1[] = $password;
		$updatepassword=$objQuery->mysqlUpdate('hosp_marketing_person',$arrField1,$arrValues1,"person_mobile='".$mobile_num."'");
	} */

	if($login_type == 1)				// Hospital Doctor Login
	{
		$arrField1[] = 'doc_password';
		$arrValues1[] = $password;
		$updatepassword=$objQuery->mysqlUpdate('referal',$arrField1,$arrValues1,"ref_id='".$_POST['userid']."'");
	}
	else if($login_type == 2)			// Partner Login
	{
		$arrField1[] = 'password';
		$arrValues1[] = $password;
		$updatepassword=$objQuery->mysqlUpdate('our_partners',$arrField1,$arrValues1,"partner_id='".$_POST['userid']."'");
	}
	if($login_type == 3)				// Marketing Person Login
	{
		$arrField1[] = 'password';
		$arrValues1[] = $password;
		$updatepassword=$objQuery->mysqlUpdate('hosp_marketing_person',$arrField1,$arrValues1,"person_id='".$_POST['userid']."'");
	}
		
		if($updatepassword == true)
		{
			$result = array('status' => "true",'update_status' => "Password updated successfully");
			echo json_encode($result);
		}
		else {	
			$result = array('status' => "true",'update_status' => "Failed to update password");
			echo json_encode($result);
		}
	
}


?>