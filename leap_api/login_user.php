<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//HOSPITAL LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['contact_num']) && isset($_POST['doc_password'])) {
	 
	// $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$mobile_num = $_POST['contact_num'];
	$password = md5($_POST['doc_password']);
	$invetory_report = array();	 
	$login_type = 0;				// login type 1 = hospital doctor, 2 = refererring doctor, 3 = marketing team
	
	
	$result_hosp_doc = $objQuery->mysqlSelect('*','referal',"contact_num='".$mobile_num."' and doc_password='".$password."'");

	if($result_hosp_doc == true)
	{
		$login_type = 1;				// Hospital doctor
		$success = array('status' => "true",'usertype' => $login_type,"doc_details" => $result_hosp_doc);
		echo json_encode($success);
	}
	else {
		$result_referrring = $objQuery->mysqlSelect('*','our_partners',"cont_num1='".$mobile_num."' and password='".$password."'","","","","");
		
		if($result_referrring == true)
		{
			$login_type = 2;  				// Referring doctor
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
	
}


?>