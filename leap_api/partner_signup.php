<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//PARTNER REGISTRATION
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['partner_type'])  || isset($_POST['partner_name']) || isset($_POST['partner_contactperson'])|| 
	isset($_POST['partner_contactpersonposition'])|| isset($_POST['partner_email'])|| isset($_POST['partner_password'])|| 
	isset($_POST['partner_landline'])|| isset($_POST['partner_mobile'])|| isset($_POST['partner_alternatemobile']) || 
	isset($_POST['partner_website']) || isset($_POST['partner_address']) || isset($_POST['partner_location']) || 
	isset($_POST['partner_state']) || isset($_POST['partner_country']) || isset($_POST['gcm_tokenid']) || isset($_POST['partner_profilepic']))
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	
	// echo date("Y-m-d");
	 
	 $arrFields1 = array();
	 $arrValues1 = array();
	 
	$arrFields1[]= 'Type';
	$arrValues1[]=  $_POST['partner_type'];
	$arrFields1[]= 'partner_name';
	$arrValues1[]=  $_POST['partner_name'];
	$arrFields1[]= 'contact_person';
	$arrValues1[]=  $_POST['partner_contactperson'];
	$arrFields1[]= 'person_position';
	$arrValues1[]=  $_POST['partner_contactpersonposition'];
	$arrFields1[]= 'Email_id';
	$arrValues1[]=  $_POST['partner_email'];
	$arrFields1[]= 'password';
	$arrValues1[]=  $_POST['partner_password'];
	$arrFields1[]= 'landline_num';
	$arrValues1[]=  $_POST['partner_landline'];
	$arrFields1[]= 'cont_num1';
	$arrValues1[]=  $_POST['partner_mobile'];
	$arrFields1[]= 'cont_num2';
	$arrValues1[]=  $_POST['partner_alternatemobile'];
	$arrFields1[]= 'website';
	$arrValues1[]=  $_POST['partner_website'];
	$arrFields1[]= 'Address';
	$arrValues1[]=  $_POST['partner_address'];
	$arrFields1[]= 'location';
	$arrValues1[]=  $_POST['partner_location'];
	$arrFields1[]= 'state';
	$arrValues1[]=  $_POST['partner_state'];
	$arrFields1[]= 'country';
	$arrValues1[]=  $_POST['partner_country'];
	$arrFields1[]= 'gcm_tokenid';
	$arrValues1[]=  $_POST['gcm_tokenid'];
	$arrFields1[]= 'partner_logo';
	$arrValues1[]=  $_POST['partner_profilepic'];

	$mobile_num = $_POST['partner_mobile'];
	$email_address = $_POST['partner_email'];
	
	$getcount = $objQuery->mysqlSelect('count(cont_num1) AS NumberOfPartners','our_partners',"cont_num1='".$mobile_num."' or Email_id='".$email_address."'","","","","");
	if($getcount == true)
	{
		if( $getcount[0]['NumberOfPartners'] >= 1)
		{
			$getcount = array('status' => "false","partner_create" => "already_exists", "count" => $getcount[0]['NumberOfPartners'] );      // Partner already exists
			echo json_encode($getcount);
		}
		else {
			$partnerCreate=$objQuery->mysqlInsert('our_partners',$arrFields1,$arrValues1);
			$partnerid= mysql_insert_id();
			if($partnerCreate == true)
			{
				$getPartnerDetail = $objQuery->mysqlSelect('*','our_partners',"partner_id ='".$partnerid."'","","","","");
	
				$success = array('status' => "true","partner_create" => $partnerCreate,"partner_detail" => $getPartnerDetail);    	//  Partner created resume
				echo json_encode($success);
			}
			else {
				$success = array('status' => "false","partner_create" => $partnerCreate);      // Partner create failed
				echo json_encode($success);
			}
		}
		
	}
	else {
		$success = array('status' => "false","doctor_create" => $getcount);      // doctor create failed
		echo json_encode($success);
	}
	
}


?>