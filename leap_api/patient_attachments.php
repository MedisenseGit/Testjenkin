<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Attachment
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['patient_id']) ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$patient_id = $_POST['patient_id'];	 
	
	// echo $patient_id;

	$count_res = $objQuery->mysqlSelect('count(attach_id) as count','patient_attachment',"patient_id='".$patient_id."'","","","","");
	// echo $count_res;
	
	$attachment_res = $objQuery->mysqlSelect('b.attach_id as attch_id, b.attachments as attachments, b.patient_id as patient_id','patient_tab as a inner join patient_attachment as b on a.patient_id=b.patient_id',"b.patient_id='".$patient_id."'","","","","");
	
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