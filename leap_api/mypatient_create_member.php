<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('send_mail_function.php');
include("send_text_message.php");


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
		$txtState = $_POST['se_state'];
		$txtCountry = $_POST['se_country'];
		$txtAge = $_POST['se_pat_age'];
		$txtMob = $_POST['se_phone_no'];
		$txtMail = $_POST['se_email'];
		$txtAddress = addslashes($_POST['se_address']);
		$txtMemberId = $_POST['se_member_id'];
		$txtPatLoginId = $_POST['se_patient_login_id'];
		$txtHeight = $_POST['se_pat_height'];
		$txtWeight = $_POST['se_pat_weight'];
		
		if($txtMemberId == 0) {
			
			$check_referring = $objQuery->mysqlSelect('*','login_user',"sub_contact='".$txtMob."'","","","","");
			 if(empty($check_referring)){

				$arrFields_user[] = 'sub_name';
				$arrValues_user[] = $txtName;
				$arrFields_user[] = 'sub_contact';
				$arrValues_user[] = $txtMob;
				$arrFields_user[] = 'sub_email';
				$arrValues_user[] = $txtMail;
				
	
				$usercreate=$objQuery->mysqlInsert('login_user',$arrFields_user,$arrValues_user);
				$login_user_id = mysql_insert_id();

				$offlineMsg="Welcome to Medisense Healthcare App. Download the patient app Now! \n Download link - https://goo.gl/u8P5us \n Thanks Medisense";
				send_msg($txtMob,$offlineMsg);
				
				$arrFields_family = array();
				$arrValues_family = array();
			
				$arrFields_family[] = 'member_name';
				$arrValues_family[] = $txtName;
				$arrFields_family[] = 'member_type';
				$arrValues_family[] = "primary";
				$arrFields_family[] = 'gender';
				$arrValues_family[] = $txtGen;
				$arrFields_family[] = 'user_id';
				$arrValues_family[] = $login_user_id;
				$patientNote=$objQuery->mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);
				$member_id = mysql_insert_id(); //Get member_id
	
				}
				else if($check_referring==true)
				{
					$login_user_id = (int)$check_referring[0]['login_id'];
					$arrFields_family = array();
					$arrValues_family = array();
			
					$arrFields_family[] = 'member_name';
					$arrValues_family[] = $txtName;
					$arrFields_family[] = 'member_type';
					$arrValues_family[] = "secondary";
					$arrFields_family[] = 'gender';
					$arrValues_family[] = $txtGen;
					$arrFields_family[] = 'user_id';
					$arrValues_family[] = $login_user_id;
					$patientNote=$objQuery->mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);
					$member_id = mysql_insert_id(); //Get member_id
					
					$offlineMsg="Welcome to Medisense Healthcare App. Download the app from link - https://goo.gl/u8P5us \n Thanks Medisense";
					send_msg($txtMob,$offlineMsg);
				}
			
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
		
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;

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
		
		$arrFields[] = 'member_id';
		$arrValues[] = $member_id;
		
		$arrFields[] = 'height';
		$arrValues[] = $txtHeight;
		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		
				$patientcraete=$objQuery->mysqlInsert('doc_my_patient',$arrFields,$arrValues);
				if($patientcraete == true)
				{
					$success = array('result' => "success","family_details" => $result_family);
					echo json_encode($success);
				}
				else {
					$success = array('result' => "failure","family_details" => $result_family);
					echo json_encode($success);
				} 
		
		}
		else {
		
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
		
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;

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
		
		$arrFields[] = 'member_id';
		$arrValues[] = $txtMemberId;
		
		$arrFields[] = 'height';
		$arrValues[] = $txtHeight;
		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		
				$patientcraete=$objQuery->mysqlInsert('doc_my_patient',$arrFields,$arrValues);
				if($patientcraete == true)
				{
					$success = array('result' => "success","family_details" => $result_family);
					echo json_encode($success);
				}
				else {
					$success = array('result' => "failure","family_details" => $result_family);
					echo json_encode($success);
				} 
		
		}
			
	}
	else {
			$success = array('result' => "failure");
			echo json_encode($success);
	}	
		
}


?>