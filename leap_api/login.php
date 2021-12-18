<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['contact_num']) && isset($_POST['doc_password'])) {
	 
	// $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$mobile_num = $_POST['contact_num'];
	$password = md5($_POST['doc_password']);
	$invetory_report = array();	 
	$login_type = 0;				// login type 1 = hospital doctor, 2= refererring doctor, 3 = marketing person
	

	$result_referrring = $objQuery->mysqlSelect('*','our_partners as a inner join specialization as b on b.spec_id = a.specialisation',"(cont_num1='".$mobile_num."' or Email_id='".$mobile_num."') and (password='".$password."')","","","","");
		
		
		if($result_referrring == true)
		{
			$login_type = 2;  				// Referring doctor i.e Partner
			$success_referring = array('status' => "true",'usertype' => $login_type,'user_encrypt_id' => md5($result_referrring[0]['partner_id']),"doc_details" => $result_referrring);
			echo json_encode($success_referring);
		}
		else {
			
				$login_type = 0;
				$success_referring = array('status' => "true",'user_encrypt_id' => $login_type,'usertype' => $login_type);
				echo json_encode($success_referring);
		}
	
	
/*
	$result = $objQuery->mysqlSelect('*','referal',"contact_num='".$mobile_num."' and doc_password='".$password."'");

	if($result == true)
	{
		$login_type = 1;				// Hospital doctor
		$success = array('status' => "true",'usertype' => $login_type,"doc_details" => $result);
		echo json_encode($success);
	}
	else {
		$result_referrring = $objQuery->mysqlSelect('*','our_partners',"cont_num1='".$mobile_num."' and password='".$password."'","","","","");
		
		
		if($result_referrring == true)
		{
			$login_type = 2;  				// Referring doctor i.e Partner
			$success_referring = array('status' => "true",'usertype' => $login_type,"doc_details" => $result_referrring);
			echo json_encode($success_referring);
		}
		else {
			
			$result_market = $objQuery->mysqlSelect('*','hosp_marketing_person',"person_mobile='".$mobile_num."' and password='".$password."'","","","","");
		
			if($result_market == true)
			{
				$login_type = 3;  				// Marketing Person
				$success_referring = array('status' => "true",'usertype' => $login_type,"doc_details" => $result_market);
				echo json_encode($success_referring);
			}
			else {
				$login_type = 0;
				$success_referring = array('status' => "true",'usertype' => $login_type);
				echo json_encode($success_referring);
			}
		}
	}
*/
	
}


?>