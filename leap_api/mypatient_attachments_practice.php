<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Attachment
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['patient_id']) ) {
	  
	$patient_id = $_POST['patient_id'];	 
	$episode_id = $_POST['episode_id'];
	$count_res = $objQuery->mysqlSelect('count(attach_id) as count','my_patient_attachments',"my_patient_id='".$patient_id."'","","","","");
	// echo $count_res;
	
	$attachment_res = $objQuery->mysqlSelect('attach_id as attch_id, attachments as attachments, my_patient_id as patient_id','my_patient_attachments',"my_patient_id='".$patient_id."' and episode_id='".$episode_id."'","","","","");
	
		if($attachment_res == true)
		{
			$success = array('status' => "true","attachment_count" => $count_res,"attachment_details" => $attachment_res);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","attachment_count" => $count_res,"attachment_details" => $attachment_res);
			echo json_encode($success);
		}
	
		
}


?>