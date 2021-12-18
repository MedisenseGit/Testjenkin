<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$docid = $_POST['user_id'];
	$response["appoint_timing"] = array();
	
	$day_val=date('D', strtotime($_POST["day_val"]));
	$GetTiming= $objQuery->mysqlSelect("*","seven_days as a left join ref_doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$docid."' and a.da_name='".$day_val."'","","","","");

	foreach($GetTiming as $TimeList) {
		 $stuff= array();
		$Timing= $objQuery->mysqlSelect("*","timings","Timing_id='".$TimeList["time_id"]."'","","","","");
		
		 $stuff["aapt_time"] = $Timing[0]["Timing"];	
		 $stuff["aapt_time_id"] = $Timing[0]["Timing_id"];	
		 array_push($response["appoint_timing"], $stuff);		
	}
	
	echo json_encode($response);
	
 }
?>
