<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//PARTNER REGISTRATION
 if(API_KEY == $_POST['API_KEY']  && isset($_POST['user_id']))

 {
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$userid = $_POST['user_id'];
	
	$resultFavourite = $objQuery->mysqlSelect('a.ref_id as ref_id, a.ref_name as ref_name, a.doc_age as doc_age,  a.ref_address as ref_address, a.ref_exp as ref_exp, a.doc_photo as doc_photo, b.favourite_id as favour_id, b.user_type as fav_type,a.doc_qual as doc_qual, c.spec_id as spec_id, c.spec_name as spec_name,a.ref_address as ref_address','referal as a inner join add_favourite_doctor as b on a.ref_id = b.doc_id inner join specialization as c on c.spec_id = a.doc_spec',"b.user_id='".$userid."'");

	if($resultFavourite == true)
	{
		$success = array('status' => "true","favouriteList" => $resultFavourite);    	//  Favourite Doctors
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","favouriteList" => $resultFavourite);      // Favourite Doctors failed
		echo json_encode($success);
	}
		
}


?>