<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');


// App Security Section Starts
function hmac($string, $secret) {
	return hash_hmac('sha256', $string, $secret);
}
// App Security Section Ends

//DOCTOR LOGIN
if(API_KEY == $_POST['API_KEY']){

	$mobile_num = $_POST['contact_num'];
	$password = md5($_POST['doc_password']); 
	$device_id = $_POST['device_id'];
	
	$result = mysqlSelect("*","referal","(contact_num='".$mobile_num."' or ref_mail='".$mobile_num."') and (doc_password='".$password."')","","","");
	$get_qualification = mysqlSelect("id,doc_id,qualification_type,country,city,start_date,end_date,created_date","doctor_academics","doc_id='".$result[0]['ref_id']."'","","","");
	
	
	
	$doc_specialization = mysqlSelect('*','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result[0]['ref_id']."'");
	$doc_hospital = mysqlSelect('*','doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id',"a.doc_id='".$result[0]['ref_id']."'","a.doc_hosp_id ASC","","","");
	
	if($result!="")// == true)
	{	

		//Logic for creating accessToken
		$accessToken = hmac($result[0]['ref_id'], $Cur_Date);
		
		$arrFields = array();
		$arrValues = array();
		$arrFields[] = 'accessToken';
		$arrValues[] = $accessToken;
		$updateAccessToken = mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$result[0]['ref_id']."'");
		
		// Update access Token in multiple sessions
		$check_session = mysqlSelect("*","referal_sessions","doc_id='".$result[0]['ref_id']."' and device_id='".$device_id."' and created_date='".$Cur_Date."'","","","","");
		$arrFields2 = array();
		$arrValues2 = array();
		$arrFields2[] = 'doc_id';
		$arrValues2[] = $result[0]['ref_id'];
		$arrFields2[] = 'device_id';
		$arrValues2[] = $device_id;
		$arrFields2[] = 'accessToken';
		$arrValues2[] = $accessToken;
		$arrFields2[] = 'created_date';
		$arrValues2[] = $Cur_Date;
		if(COUNT($check_session)>0){	
			 $updateAccess=mysqlUpdate('referal_sessions',$arrFields2,$arrValues2,"doc_id='".$result[0]['ref_id']."' and device_id='".$device_id."'");
		}
		else{
			$updateAccess=mysqlInsert('referal_sessions',$arrFields2,$arrValues2);
		}
			

		$success = array('status' => "true",'accessToken' => $accessToken,'user_encrypt_id' => md5($result[0]['ref_id']), "doc_details" => $result,"doc_specialization"=> $doc_specialization,"doc_hospital"=> $doc_hospital,"doc_qualification"=>$get_qualification,'err_msg' => '');
		echo json_encode($success);	
	}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Username or Password');
		echo json_encode($failure);						
	} 
}
else 
{
	$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
	echo json_encode($failure);
}


?>