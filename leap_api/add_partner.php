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

// PARTNER CREATE
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['user_id']) || isset($_POST['parnet_type'])|| isset($_POST['partner_name'])|| 
		isset($_POST['partner_mobile'])|| isset($_POST['partner_email']) )
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	 $userid = $_POST['user_id']; 
	 $mobile = $_POST['partner_mobile'];
	 $email = $_POST['partner_email'];
	 $partner_name =  $_POST['partner_name'];
	 $partner_type = $_POST['parnet_type'];
	 
	$password = randomPassword();
	$encypassword = md5($password);
	
	$getdetail = $objQuery->mysqlSelect('*','hosp_marketing_person',"person_id='".$userid."'","","","","");
	$hosp_id = $getdetail[0]['hosp_id'];
	// echo $hosp_id;
	
	
	//Check Referrer mobile/email id exists in our partner table
	$chkPartner = $objQuery->mysqlSelect("*","our_partners","Email_id='".$email."' or Email_id1='".$email."' or cont_num1='".$mobile."'","","","","");
	$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join hosp_marketing_person as b on a.hosp_id=b.hosp_id","b.person_id='".$userid."'","","","","");
	
	//Check Referrer is already mapped in marketing person
	$chkMappedReferrer = $objQuery->mysqlSelect("*","mapping_hosp_referrer","partner_id='".$chkPartner[0]['partner_id']."' and hosp_id='".$hosp_id."'","","","","");
	if($chkPartner==true && $chkMappedReferrer==true){
					
		$success = array('status' => "false","partner_create" => "Failed");      // partner insert failed
		echo json_encode($success);
	}
	else if($chkPartner==true){
			$Partner_Status="Paired"; //If partner is already registered
			$partner_id= $chkPartner[0]['partner_id'];
			 $arrFields_map = array();
			$arrValues_map = array();
			$arrFields_map[]= 'market_person_id';
			$arrValues_map[]=  $_POST['user_id'];
			$arrFields_map[] = 'partner_id';
			$arrValues_map[] = $partner_id;
			$arrFields_map[]= 'partner_type';
			$arrValues_map[]=  $_POST['parnet_type'];
			$arrFields_map[]= 'hosp_id';
			$arrValues_map[]=  $hosp_id;
			$arrFields_map[]= 'status';
			$arrValues_map[]=  $Partner_Status;
			
			$personcreate=$objQuery->mysqlInsert('mapping_hosp_referrer',$arrFields_map,$arrValues_map);
			
			
					//Mail Notification to Referring Partner
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&marketingmail=" . urlencode($getHosp[0]['person_email']);
					$url .= "&marketingmobile=".urlencode($getHosp[0]['person_mobile']);
					$url .= "&partnermail=".urlencode($email);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Pls use link https://medisensecrm.com/Refer/ to login. Pls check ".$email." for further details. Thanks, ".$getHosp[0]['hosp_name'];
					send_msg($mobile,$responsemsg);
					// header("Location:Add-Referring-Partner?response=add");
					$success = array('status' => "true","partner_create" => $personcreate);    	//  partner created resume
					echo json_encode($success);
	}
	else{
		
	$Partner_Status="Pending"; //If partner is not registered

		$arrFields1 = array();
		$arrValues1 = array();
		$arrFields1[]= 'partner_name';
		$arrValues1[]=  $partner_name;
		$arrFields1[]= 'Email_id';
		$arrValues1[]=  $email;
		$arrFields1[]= 'cont_num1';
		$arrValues1[]=  $mobile;
		$arrFields1[]= 'reg_date';
		$arrValues1[]=  date("Y-m-d");
		$arrFields1[] = 'password';
		$arrValues1[] = $encypassword;
		
		$personcreate=$objQuery->mysqlInsert('our_partners',$arrFields1,$arrValues1);
		$partner_id= mysql_insert_id();
		
		$arrFields_map[] = 'market_person_id';
		$arrValues_map[] = $userid;		
		$arrFields_map[] = 'partner_id';
		$arrValues_map[] = $partner_id;
		$arrFields_map[] = 'hosp_id';
		$arrValues_map[] = $hosp_id;
		$arrFields_map[] = 'partner_type';
		$arrValues_map[] = $partner_type;		
		
		$arrFields_map[] = 'status';
		$arrValues_map[] = $Partner_Status;
		$personscreate=$objQuery->mysqlInsert('mapping_hosp_referrer',$arrFields_map,$arrValues_map);
			
			
					//Mail Notification to Referring Partner
					 $usercredentials="User ID :".$email." / ".$mobile."<br>Password: ".$password."<br>";
					
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&marketingmail=" . urlencode($getHosp[0]['person_email']);
					$url .= "&marketingmobile=".urlencode($getHosp[0]['person_mobile']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&partnermail=".urlencode($email);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Pls use link https://medisensecrm.com/Refer/ to login with user ID : ".$email." and password : ".$password.". Pls check ".$email." for further details. Thanks, ".$getHosp[0]['hosp_name'];
					send_msg($mobile,$responsemsg);
					
					$success = array('status' => "true","partner_create" => $personcreate);    	//  partner created resume
					echo json_encode($success);
			
			// header("Location:Add-Referring-Partner?response=add");
		
		}
/*	
	$getcount = $objQuery->mysqlSelect('count(cont_num1) AS NumberOfPartner','our_partners',"cont_num1='".$mobile."' or Email_id='".$email."'","","","","");
	if($getcount == true)
	{
		if( $getcount[0]['NumberOfPartner'] >= 1)
		{
			$getcount = array('status' => "false","partner_create" => "already_exists", "count" => $getcount[0]['NumberOfPartner'] );      // patient insert failed
			echo json_encode($getcount);
		}
		else {
			
			$parnerCreate=$objQuery->mysqlInsert('our_partners',$arrFields1,$arrValues1);
			$pid= mysql_insert_id();
			$arrFields_map[]= 'partner_id';
			$arrValues_map[]=  $pid;
		
			if($parnerCreate == true)
			{
				$parnerCreate=$objQuery->mysqlInsert('mapping_hosp_referrer',$arrFields_map,$arrValues_map);
		
				 $getPartnerDetail = $objQuery->mysqlSelect('*','patient_tab',"patient_id ='".$pid."'","","","","");
	
				$success = array('status' => "true","partner_create" => $parnerCreate,"partner_detail" => $getPartnerDetail);    	//  partner created resume
				echo json_encode($success);
			}
			else {
				$success = array('status' => "false","partner_create" => $parnerCreate);      // partner insert failed
				echo json_encode($success);
			}
		}
	}  */
	

	
}


?>