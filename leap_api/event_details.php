<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

 if( (API_KEY == $_POST['API_KEY']) || isset($_POST['login_type']) || isset($_POST['userid'])  || isset($_POST['blog_type']) || isset($_POST['event_id'])) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	
	$login_type = $_POST['login_type'];
	$user_id = $_POST['userid'];
	$blog_type = $_POST['blog_type'];
	$event_id = $_POST['event_id'];
	
	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		$getOffersResult =  $objQuery->mysqlSelect("*","offers_events","event_id = '".$event_id."'","","","","");
		//echo $getOffersResult[0]['conf_id'];
		$getKeyNoteSpeakers= $objQuery->mysqlSelect("a.ref_id as ref_id, a.ref_name as ref_name, a.doc_photo as doc_photo","referal as a inner join keynote_speakers as b on a.ref_id=b.doc_id  inner join conference_login as c on c.conf_login_id=b.conf_id","b.conf_id='".$getOffersResult[0]['conf_id']."'","","","","");
		
		$getOrganizers = $objQuery->mysqlSelect("ref_id, ref_name, doc_photo","referal","ref_id='".$getOffersResult[0]['oganiser_doc_id']."'","","","","");
		
		if($getKeyNoteSpeakers==true){
					
			$success = array('status' => "true","key_note_speakers" => $getKeyNoteSpeakers,"organizers" => $getOrganizers);     
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","key_note_speakers" => $getKeyNoteSpeakers,"organizers" => $getOrganizers);      
			echo json_encode($success);
		}
	}
	else if($login_type == 2)		// Type-2 Referring Partners
	{
		$getOffersResult =  $objQuery->mysqlSelect("*","offers_events","event_id = '".$event_id."'","","","","");
		//echo $getOffersResult[0]['conf_id'];
		$getKeyNoteSpeakers= $objQuery->mysqlSelect("a.ref_id as ref_id, a.ref_name as ref_name, a.doc_photo as doc_photo","referal as a inner join keynote_speakers as b on a.ref_id=b.doc_id  inner join conference_login as c on c.conf_login_id=b.conf_id","b.conf_id='".$getOffersResult[0]['conf_id']."'","","","","");
		
		$getOrganizers = $objQuery->mysqlSelect("ref_id, ref_name, doc_photo","referal","ref_id='".$getOffersResult[0]['oganiser_doc_id']."'","","","","");
		
		if($getKeyNoteSpeakers==true){
					
			$success = array('status' => "true","key_note_speakers" => $getKeyNoteSpeakers,"organizers" => $getOrganizers);     
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","key_note_speakers" => $getKeyNoteSpeakers,"organizers" => $getOrganizers);      
			echo json_encode($success);
		}
	}
	else if($login_type == 3)		// Type-3 Marketing Person
	{
		
	}  
	
}


?>