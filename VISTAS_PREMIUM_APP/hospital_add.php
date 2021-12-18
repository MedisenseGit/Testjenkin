<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


include('send_mail_function.php');
include('send_text_message.php');


$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		$admin_id = $doctor_id;
		$hosp_name=$_POST['hosp_name'];	
		$hosp_email=$_POST['hosp_email'];
		$hosp_contact=$_POST['hosp_mobile'];
		$hosp_address=$_POST['hosp_address'];
		$hosp_city=$_POST['hosp_city'];
		$hosp_state=$_POST['hosp_state'];
		$hosp_country=$_POST['hosp_country'];
		
		$arrFields[] = 'hosp_name';
		$arrValues[] = $hosp_name;
		$arrFields[] = 'hosp_email';
		$arrValues[] = $hosp_email;
		$arrFields[] = 'hosp_contact';
		$arrValues[] = $hosp_contact;
		$arrFields[] = 'hosp_city';
		$arrValues[] = $hosp_city;
		$arrFields[] = 'hosp_state';
		$arrValues[] = $hosp_state;
		$arrFields[] = 'hosp_addrs';
		$arrValues[] = $hosp_address;
		$arrFields[] = 'hosp_country';
		$arrValues[] = $hosp_country;
		$arrFields[] = 'communication_status';
		$arrValues[] = "1";  
		
		$hospcreate=mysqlInsert('hosp_tab',$arrFields,$arrValues);
		$hosp_id = $hospcreate;	
		$arrFields_docHosp[] = 'doc_id';
		$arrValues_docHosp[] = $admin_id;
		$arrFields_docHosp[] = 'hosp_id';
		$arrValues_docHosp[] = $hosp_id;
			
		$dochospcreate=mysqlInsert('doctor_hosp',$arrFields_docHosp,$arrValues_docHosp);	
		
		$doc_hospital = mysqlSelect('*','doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id',"a.doc_id='".$admin_id."'","a.doc_hosp_id ASC");

		
		$success = array('result' => "success","doc_hospital"=> $doc_hospital);
		echo json_encode($success);
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}
?>