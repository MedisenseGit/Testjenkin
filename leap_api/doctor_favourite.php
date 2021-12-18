<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//PARTNER REGISTRATION
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['user_id'])  || isset($_POST['doc_id']) || isset($_POST['user_type']))
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	
	// echo date("Y-m-d");
	 
	 $arrFields1 = array();
	 $arrValues1 = array();
	 
	$arrFields1[]= 'user_id';
	$arrValues1[]=  $_POST['user_id'];
	$arrFields1[]= 'doc_id';
	$arrValues1[]=  $_POST['doc_id'];
	$arrFields1[]= 'user_type';
	$arrValues1[]=  $_POST['user_type'];
	
	$user_id = $_POST['user_id'];
	$doctor_id = $_POST['doc_id'];
	
	$getcount = $objQuery->mysqlSelect('count(user_id) AS user_id','add_favourite_doctor',"	user_id='".$user_id."' and doc_id='".$doctor_id."'","","","","");
	if($getcount == true)
	{
		if( $getcount[0]['user_id'] >= 1)
		{
			$getcount = array('status' => "false","addFavourite" => "already_exists", "count" => $getcount[0]['user_id'] );      // Add Favourite Failed
			echo json_encode($getcount);
		}
		else {
			$addFavourite=$objQuery->mysqlInsert('add_favourite_doctor',$arrFields1,$arrValues1);
			if($addFavourite == true)
			{
				$success = array('status' => "true","addFavourite" => $addFavourite);    	//   Add Favourite Inserted
				echo json_encode($success);
			}
			else {
				$success = array('status' => "false","addFavourite" => $addFavourite);      //  Add Favourite Failed
				echo json_encode($success);
			}
		}
		
	}
	else {
		$success = array('status' => "false","addFavourite" => $addFavourite);      // Add Favourite Failed
		echo json_encode($success);
	}
		
	
}


?>