<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$time_id = $_POST['time_id'];
	$day_id = $_POST['day_id'];
	$status = $_POST['status'];
	$admin_id = $_POST['user_id'];
	
	 if($status == 1)  {
		 
			$arrFields_time = array();
			$arrValues_time = array();

			$arrFields_time[] = 'doc_id';
			$arrValues_time[] = $admin_id;
			
			$arrFields_time[] = 'time_id';
			$arrValues_time[] = $time_id;
			
			$arrFields_time[] = 'day_id';
			$arrValues_time[] = $day_id;
			
			$arrFields_time[] = 'time_set';
			$arrValues_time[] = $status;
		 
		 $objQuery->mysqlDelete('ref_doc_time_set',"doc_id='".$admin_id."' and time_id='".$time_id."' and day_id='".$day_id."'");
		 
		 $doctimecreate=$objQuery->mysqlInsert('ref_doc_time_set',$arrFields_time,$arrValues_time);
			$result = array("result" => "update success");
			echo json_encode($result);
	 }
	 else if($status == 0)  {
		  $objQuery->mysqlDelete('ref_doc_time_set',"doc_id='".$admin_id."' and time_id='".$time_id."' and day_id='".$day_id."'");
		  $result = array("result" => "delete success");
		  echo json_encode($result);
	 }
	 else {
			$result = array("result" => "failed");
			echo json_encode($result);
	 }
	
 }
?>
