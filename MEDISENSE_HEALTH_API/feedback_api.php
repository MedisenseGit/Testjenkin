<?php 
ob_start();
error_reporting(0);
session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

if(HEALTH_API_KEY == $data ->api_key  && isset($data ->insert))	
{
	$doc_fdbckFields = $data ->doc_fdbckFields;
	$doc_fdbckValues = $data ->doc_fdbckValues;
	
	$Platform_Fields = $data ->Platform_Fields;
	$Platform_Values = $data ->Platform_Values;
	
	$feedbackcreate = mysqlInsert('doctor_feedback',$doc_fdbckFields,$doc_fdbckValues);
	
	$platfeedbackcreate = mysqlInsert('platform_feedback',$Platform_Fields,$Platform_Values);
	
	$response = array('status' => "true");
	
	echo json_encode($response );
}
else
{
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}	


?>
