<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//My Patients create
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$TransId=time();
	
	if($login_type == 1) {						// Premium LoginType
		$txtName = $_POST['se_pat_name'];
		$txtGen = $_POST['se_gender'];
		$txtLoc = $_POST['se_city'];
		$txtCountry = $_POST['se_country'];
		$txtAge = $_POST['se_pat_age'];
		$txtMob = $_POST['se_phone_no'];
		$txtMail = $_POST['se_email'];
		$txtAddress = addslashes($_POST['se_address']);
		$txtHeight = $_POST['se_pat_height'];
		$txtWeight = $_POST['se_pat_weight'];
		
		
		$arrFields = array();
		$arrValues = array();

		
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;

		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;

		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;

		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
		
		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMob;

		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtLoc;

		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;

		$arrFields[] = 'patient_addrs';
		$arrValues[] = $txtAddress;

		$arrFields[] = 'doc_id';
		$arrValues[] = $admin_id;

		$arrFields[] = 'system_date';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'TImestamp';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'transaction_id';
		$arrValues[] = $TransId;
		
		$arrFields[] = 'height';
		$arrValues[] = $txtHeight;
		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		
		
		$check_referring = $objQuery->mysqlSelect('*','login_user',"sub_contact='".$txtMob."'","","","","");
		$login_user_id = (int)$check_referring[0]['login_id'];
		if(empty($check_referring)){
			 $patientcraete=$objQuery->mysqlInsert('doc_my_patient',$arrFields,$arrValues);
				if($patientcraete == true)
				{
					$success = array('result' => "success");
					echo json_encode($success);
				}
				else {
					$success = array('result' => "failure");
					echo json_encode($success);
				}
		}
		 else if($check_referring==true)
		{
			  $result_family = $objQuery->mysqlSelect("*","user_family_member","user_id ='".$login_user_id."'","","","","");
			  $success = array('result' => "success","family_details" => $result_family);
			  echo json_encode($success);
			  
			  /* $patientcraete=$objQuery->mysqlInsert('doc_my_patient',$arrFields,$arrValues);
				if($patientcraete == true)
				{
					$success = array('result' => "success","family_details" => $result_family);
					echo json_encode($success);
				}
				else {
					$success = array('result' => "failure","family_details" => $result_family);
					echo json_encode($success);
				} */
		}
			
	}
	else {
			$success = array('result' => "failure");
			echo json_encode($success);
	}	
		
}


?>