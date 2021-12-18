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
	
	
	$result = $objQuery->mysqlSelect('*','referal',"(contact_num='".$mobile_num."' or ref_mail='".$mobile_num."') and (doc_password='".$password."')");
	$doc_specialization = $objQuery->mysqlSelect('*','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result[0]['ref_id']."'");
	$doc_hospital = $objQuery->mysqlSelect('*','doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id',"a.doc_id='".$result[0]['ref_id']."'","a.doc_hosp_id ASC","","","");

	if($result == true)
	{
		$login_type = 1;				// Hospital doctor
		$success = array('status' => "true",'user_encrypt_id' => md5($result[0]['ref_id']),'usertype' => $login_type,"doc_details" => $result,"doc_specialization"=> $doc_specialization,"doc_hospital"=> $doc_hospital);
		echo json_encode($success);
	}
	else {
		$result_market = $objQuery->mysqlSelect('*','hosp_marketing_person',"(person_mobile='".$mobile_num."' or person_email='".$mobile_num."') and (password='".$password."')");
		
			if($result_market == true)
			{
				$login_type = 3;  				// Marketing Person
				$success_referring = array('status' => "true",'user_encrypt_id' => md5($result_market[0]['person_id']),'usertype' => $login_type,"doc_details" => $result_market);
				echo json_encode($success_referring);
			}
			else {
				$login_type = 0;
				$success_referring = array('status' => "true",'user_encrypt_id' => $login_type,'usertype' => $login_type);
				echo json_encode($success_referring);
			}
		//$login_type = 0;
		//$success_referring = array('status' => "true",'usertype' => $login_type);
		//echo json_encode($success_referring);
	}
	
	

	
}


?>