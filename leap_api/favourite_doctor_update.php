<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//PARTNER REGISTRATION
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['user_id'])  || isset($_POST['doc_id']) || isset($_POST['user_type']) || isset($_POST['fav_id']))
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	 $user_id = $_POST['user_id'];
	 $doc_id = $_POST['doc_id'];
	 $favourite_id = $_POST['fav_id'];
	
	// echo date("Y-m-d");
	 
	 $arrFields1 = array();
	 $arrValues1 = array();
	 
	/* $arrFields1[]= 'user_id';
	$arrValues1[]=  $_POST['user_id'];
	$arrFields1[]= 'doc_id';
	$arrValues1[]=  $_POST['doc_id']; */
	$arrFields1[]= 'user_type';
	$arrValues1[]=  $_POST['user_type'];
	
	
	// $addFavourite=$objQuery->mysqlInsert('add_favourite_doctor',$arrFields1,$arrValues1);
	// $update_Favourite=$objQuery->mysqlUpdate('add_favourite_doctor',$arrFields1,$arrValues1," user_id ='".$user_id."' and doc_id = '".$doc_id."'");
	
	 $update_Favourite=$objQuery->mysqlDelete('add_favourite_doctor',"favourite_id ='".$favourite_id."'");
			
	if($update_Favourite == true)
	{
		$success = array('status' => "true","update_Favourite" => $update_Favourite);    	//  Partner created resume
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","update_Favourite" => $update_Favourite);      // Partner create failed
		echo json_encode($success);
	}
}


?>