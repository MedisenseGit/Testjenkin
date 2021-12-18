<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";
//$ccmail="salmabanu.h@gmail.com";
$objQuery = new CLSQueryMaker();

//Random Password Generator
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// PARTNER CREATE - LEAP
if(API_KEY == $_POST['API_KEY']) {
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	

	$admin_id = $_POST['user_id'];
	 
	$password = randomPassword();
	$encypassword = md5($password);
	
	
	$getDoc = $objQuery->mysqlSelect("*","referal as a inner join doctor_hosp as b on a.ref_id = b.doc_id","a.ref_id='".$admin_id."'","","","","");
	$hosp_id = $getDoc[0]['hosp_id'];
	// echo $hosp_id;
	
	$getCompany = $objQuery->mysqlSelect('*','hosp_tab',"hosp_id='".$hosp_id."'","","","","");
	$company_id = $getCompany[0]['company_id'];
	
	$selectHosp = $getDoc[0]['hosp_id'];
	$ref_name = $_POST['partner_name'];
	$partnertype = $_POST['partner_membertype'];
	$partnercategory = $_POST['partner_membertype'];
	$ref_mobile = $_POST['partner_mobile'];
	$ref_email = $_POST['partner_email'];
	
	$password = randomPassword();
	$encypassword = md5($password);

	//Check Referrer mobile/email id exists in our partner table
	$chkPartner = $objQuery->mysqlSelect("*","our_partners","Email_id='".$ref_email."' or Email_id1='".$ref_email."' or cont_num1='".$ref_mobile."'","","","","");
	$getHosp = $objQuery->mysqlSelect("*","hosp_tab","hosp_id='".$selectHosp."'","","","","");
	// $getDoc = $objQuery->mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	//Check Referrer is already mapped in marketing person
	$chkMappedReferrer = $objQuery->mysqlSelect("*","mapping_hosp_referrer","partner_id='".$chkPartner[0]['partner_id']."' and hosp_id='".$selectHosp."' and doc_id='".$admin_id."'","","","","");
	$get_organisation = $objQuery->mysqlSelect('company_id as Comp_Id,company_name as Comp_name,mobile as Org_Contact,email_id as Comp_Email,company_logo as Logo','compny_tab',"company_id='".$company_id."'");
	$compLogo='https://medisensecrm.com/Hospital/company_logo/'.$get_organisation[0]['Comp_Id'].'/'.$get_organisation[0]['Logo'];
	
	if($chkPartner==true && $chkMappedReferrer==true){
					
		$success = array('status' => "false","partner_create" => "Failed");      // partner insert failed
		echo json_encode($success);
	}
	else if($chkPartner==true){
			
			$partner_id= $chkPartner[0]['partner_id'];
			$arrFields1 = array();
			$arrValues1 = array();
			
			$arrFields1[] = 'partner_id';
			$arrValues1[] = $partner_id;
			$arrFields1[] = 'partner_type';
			$arrValues1[] = $partnertype;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $selectHosp;
			$arrFields1[] = 'company_id';
			$arrValues1[] = $company_id;
			$arrFields1[] = 'doc_id';
			$arrValues1[] = $admin_id;
			
			$personcreate=$objQuery->mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
					//Mail Notification to Referring Partner
					
					$usercredentials="Link :".$webLink."<br>User ID :".$chkPartner[0]['Email_id']." / ".$chkPartner[0]['cont_num1']."<br>Password: You have already registered. If you have forgotten password, then click forgot password in login page. <br><br>";
					
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&marketingmail=" . urlencode($getDoc[0]['ref_mail']);
					$url .= "&marketingmobile=".urlencode($getDoc[0]['contact_num']);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Pls use link www.medisensepractice.com to login. Pls check ".$ref_email." for further details. Thanks, ".$getDoc[0]['ref_name'];
					send_msg($mobile,$responsemsg);
					// header("Location:Add-Referring-Partner?response=add");
					$success = array('status' => "true","partner_create" => $personcreate);    	
					echo json_encode($success);
	}
	else{
		
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'partner_name';
		$arrValues[] = $ref_name;
		$arrFields[] = 'contact_person';
		$arrValues[] = $ref_name;
		$arrFields[] = 'Email_id';
		$arrValues[] = $ref_email;
		$arrFields[] = 'cont_num1';
		$arrValues[] = $ref_mobile;
		$arrFields[] = 'password';
		$arrValues[] = $encypassword;
		$arrFields[] = 'reg_date';
		$arrValues[] = date("Y-m-d");
		$arrFields[] = 'Type';
		$arrValues[] = $partnercategory;
		
		
			$personcreate=$objQuery->mysqlInsert('our_partners',$arrFields,$arrValues);
			$partner_id= mysql_insert_id();
			
			//Insert Partner Id to Source List table
			$arrFields2[] = 'source_name';
			$arrValues2[] = $ref_name;
			$arrFields2[] = 'partner_id';
			$arrValues2[] = $partner_id;
		
			$createsource=$objQuery->mysqlInsert('source_list',$arrFields2,$arrValues2);
			
			
			$arrFields1 = array();
			$arrValues1 = array();
			
			$arrFields1[] = 'partner_id';
			$arrValues1[] = $partner_id;
			$arrFields1[] = 'partner_type';
			$arrValues1[] = $partnertype;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $selectHosp;
			$arrFields1[] = 'company_id';
			$arrValues1[] = $company_id;
			$arrFields1[] = 'doc_id';
			$arrValues1[] = $admin_id;
			$personcreate=$objQuery->mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
					//Mail Notification to Referring Partner
					// $usercredentials="Link :".$webLink."<br>User ID :".$ref_email." / ".$ref_mobile."<br>Password: ".$password."<br>";
					
					 $usercredentials="User ID :".$email." / ".$mobile."<br>Password: ".$password."<br>";
					
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&marketingmail=" . urlencode($getDoc[0]['ref_mail']);
					$url .= "&marketingmobile=".urlencode($getDoc[0]['contact_num']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Please use link www.medisensepractice.com to login with user ID : ".$ref_email." and password : ".$password.". Pls check ".$ref_email." for further details. Thanks, ".$getDoc[0]['ref_name'];
					send_msg($mobile,$responsemsg);
					
					$success = array('status' => "true","partner_create" => $personcreate);    	//  partner created resume
					echo json_encode($success);
			
			// header("Location:Add-Referring-Partner?response=add");
		

		}
	}


?>