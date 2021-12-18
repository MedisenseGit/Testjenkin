<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");



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
	
		$pharma_name=$_POST['txtPharmaName'];	
		$txtemail=$_POST['txtPharmaEmail'];
		$mobile=$_POST['txtPharmaMobile'];
		$city=$_POST['txtPharmaCity'];

		$arrFields_pharma[] = 'pharma_name';
		$arrValues_pharma[] = $pharma_name;
		$arrFields_pharma[] = 'pharma_email';
		$arrValues_pharma[] = $txtemail;
		$arrFields_pharma[] = 'pharma_contact_num';
		$arrValues_pharma[] = $mobile;
		$arrFields_pharma[] = 'pharma_city';
		$arrValues_pharma[] = $city;
		
		$pharmacreate=mysqlInsert('pharma',$arrFields_pharma,$arrValues_pharma);
		$pharma_id = mysql_insert_id();
		$arrFields_refer[] = 'pharma_id';
		$arrValues_refer[] = $pharma_id;
		$arrFields_refer[] = 'doc_id';
		$arrValues_refer[] = $admin_id;
		$arrFields_refer[] = 'doc_type';
		$arrValues_refer[] = "1"; 
		
		$pharmarefer=mysqlInsert('doc_pharma',$arrFields_refer,$arrValues_refer);
	
		$get_pharma = mysqlSelect('a.pharma_id as pharma_id, a.pharma_name as pharma_name, a.pharma_city as pharma_city, a.pharma_state as pharma_state, a.pharma_country as pharma_country, a.phrama_contact_person as phrama_contact_person, a.pharma_contact_num as pharma_contact_num, a.pharma_email as pharma_email, a.pharma_password as pharma_password','pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id',"b.doc_id='".$admin_id."'","a.pharma_name ASC","","","");
		
		$success = array('status' => "true","pharma_details"=>$get_pharma);
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