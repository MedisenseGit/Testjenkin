<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

 if( (API_KEY == $_POST['API_KEY']) || isset($_POST['login_type']) || isset($_POST['userid']) || isset($_POST['event_type']) || isset($_POST['listing_type']) || isset($_POST['eventid'])) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	
	$login_type = $_POST['login_type'];
	$user_id = $_POST['userid'];
	//$event_type = $_POST['event_type'];
	$event_id = $_POST['eventid'];
	$listing_type = $_POST['listing_type'];

	//echo $login_type; 
	//echo $user_id; 
	//echo $event_type; 
	//echo $event_id; 
	//echo $listing_type;
	
	 $arrFields1 = array();
	 $arrValues1 = array();
	 
	$current_date = date('Y-m-d h:i:s');

	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		
    		 $userType = "2"; //For Hospital Doctors
   	
		$arrFields = array();
		$arrValues = array();

		$arrFields[]= 'category_id';
		$arrValues[]= $event_id;
		$arrFields[]= 'category_type';
		$arrValues[]= $listing_type;
		$arrFields[]= 'likes';
		$arrValues[]= $user_id;
		$arrFields[]= 'user_type';
		$arrValues[]= $userType;
		$arrFields[]= 'like_date';
		$arrValues[]= time();
	
		$addLike=$objQuery->mysqlInsert('home_post_like',$arrFields,$arrValues);
		
	}
	else if($login_type == 2)		// Type-2 Standard User
	{
		
    		 $userType = "1"; //For Standard User
   	
		$arrFields = array();
		$arrValues = array();

		$arrFields[]= 'category_id';
		$arrValues[]= $event_id;
		$arrFields[]= 'category_type';
		$arrValues[]= $listing_type;
		$arrFields[]= 'likes';
		$arrValues[]= $user_id;
		$arrFields[]= 'user_type';
		$arrValues[]= $userType;
		$arrFields[]= 'like_date';
		$arrValues[]= time();
	
		$addLike=$objQuery->mysqlInsert('home_post_like',$arrFields,$arrValues);
		
	}
	
	$success = array('status' => "true","Event_Details" => $addLike);     
	echo json_encode($success);




	
	
}


?>