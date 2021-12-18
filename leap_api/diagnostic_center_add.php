<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('send_text_message.php');
include('send_mail_function.php');

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

// Diagnostic Center Add
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	$diagno_name=$_POST['txtDiagnosticName'];	
	$txtemail=$_POST['txtDiagnosticEmail'];
	$mobile=$_POST['txtDiagnosticMobile'];
	$city=$_POST['txtDiagnosticCity'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	if($login_type == 1) {	
	
		$arrFields_diagno[] = 'diagnosis_name';
		$arrValues_diagno[] = $diagno_name;
		$arrFields_diagno[] = 'diagnosis_email';
		$arrValues_diagno[] = $txtemail;
		$arrFields_diagno[] = 'diagnosis_contact_num';
		$arrValues_diagno[] = $mobile;
		$arrFields_diagno[] = 'diagnosis_city';
		$arrValues_diagno[] = $city;
		$arrFields_diagno[] = 'diagnosis_password';
		$arrValues_diagno[] = $encypassword;
		
		$chkUser = $objQuery->mysqlSelect("*","Diagnostic_center","	diagnosis_email='".$txtemail."' or diagnosis_contact_num='".$mobile."'","","","","");
		if(count($chkUser)>0){
			$get_diagnostics = $objQuery->mysqlSelect('a.diagnostic_id as diagnostic_id, a.diagnosis_name as diagnosis_name, a.diagnosis_city as diagnosis_city, a.diagnosis_state as diagnosis_state, a.diagnosis_country as diagnosis_country, a.diagnosis_contact_person as diagnosis_contact_person, a.diagnosis_contact_num as diagnosis_contact_num, a.diagnosis_email as diagnosis_email, a.diagnosis_password as diagnosis_password ','Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id',"b.doc_id='".$admin_id."'","a.diagnosis_name ASC","","","");
			$success = array('status' => "true",'reg_status' => "2","diagnostics_details"=>$get_diagnostics);
			echo json_encode($success);
		}
		else {
			$diagnocreate=$objQuery->mysqlInsert('Diagnostic_center',$arrFields_diagno,$arrValues_diagno);
			$diagno_id = mysql_insert_id();	
			$arrFields_refer[] = 'diagnostic_id';
			$arrValues_refer[] = $diagno_id;
			$arrFields_refer[] = 'doc_id';
			$arrValues_refer[] = $admin_id;
			$arrFields_refer[] = 'doc_type';
			$arrValues_refer[] = "1";
			
			$docdiagnocreate=$objQuery->mysqlInsert('doc_diagnostics',$arrFields_refer,$arrValues_refer);
			
			if(!empty($txtemail)) {
				$recoverLink="Link: https://medisensecrm.com/Diagnostic/login <br>User Name: ".$txtemail." / ".$mobile."<br>Password: ".$password;
			
				$message= stripslashes("<b>Congratulations!</b><br>You’ve been granted access to Medisense Diagnostic Software. <br><br> Please use below user name & password to login:<br><br> 

				".$recoverLink."");
						
			
						$url_page = 'diagnostic_registration.php';
						$url .= rawurlencode($url_page);
						$url .= "?usermail=".urlencode($txtemail);
						$url .= "&username=".urlencode($diagno_name);
						$url .= "&message=".urlencode($message);
						$url .= "&reclink=".urlencode($recoverLink);
						send_mail($url);

			}
		
			$get_diagnostics = $objQuery->mysqlSelect('a.diagnostic_id as diagnostic_id, a.diagnosis_name as diagnosis_name, a.diagnosis_city as diagnosis_city, a.diagnosis_state as diagnosis_state, a.diagnosis_country as diagnosis_country, a.diagnosis_contact_person as diagnosis_contact_person, a.diagnosis_contact_num as diagnosis_contact_num, a.diagnosis_email as diagnosis_email, a.diagnosis_password as diagnosis_password ','Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id',"b.doc_id='".$admin_id."'","a.diagnosis_name ASC","","","");
			
			$success = array('status' => "true",'reg_status' => "1","diagnostics_details"=>$get_diagnostics);
			echo json_encode($success);
		}

	}
	else {
		$success = array('result' => "false");
		echo json_encode($success);
	}
		

	
}


?>